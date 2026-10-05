<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AuditLogger
{
    /**
     * Record one Eloquent model mutation.
     */
    public function log(string $action, Model $model): void
    {
        if ($model instanceof AuditLog || !Schema::hasTable('audit_logs')) {
            return;
        }

        try {
            $request = app()->runningInConsole() || !app()->bound('request')
                ? null
                : app('request');

            $actor = $this->actor();
            $userAgent = $request ? $request->userAgent() : 'CLI';

            $oldValues = in_array($action, ['updated', 'deleted'], true)
                ? $model->getOriginal()
                : null;
            $newValues = in_array($action, ['created', 'updated'], true)
                ? $model->getAttributes()
                : null;

            AuditLog::query()->create([
                'action' => $action,
                'user_id' => $actor['id'],
                'user_name' => $actor['name'],
                'auditable_type' => get_class($model),
                'auditable_id' => $this->auditableId($model),
                'old_values' => $this->sanitize($oldValues),
                'new_values' => $this->sanitize($newValues),
                'device' => $this->device($userAgent),
                'ip_address' => $request ? $request->ip() : null,
                'user_agent' => $userAgent,
                'http_method' => $request ? $request->method() : 'CLI',
                'url' => $request ? $request->fullUrl() : null,
            ]);
        } catch (\Throwable $exception) {
            // Audit failure must not break the original CRUD operation.
            Log::error('Gagal menyimpan audit log.', [
                'action' => $action,
                'model' => get_class($model),
                'model_id' => $this->auditableId($model),
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function actor(): array
    {
        try {
            if (auth()->check()) {
                $user = auth()->user();

                return [
                    'id' => $user->getAuthIdentifier(),
                    'name' => $user->name ?? $user->email ?? 'Authenticated user',
                ];
            }
        } catch (\Throwable $exception) {
            // The model may be changed from a console command without auth.
        }

        return [
            'id' => null,
            'name' => app()->runningInConsole() ? 'Console' : 'Guest',
        ];
    }

    private function auditableId(Model $model): ?string
    {
        $key = $model->getKey();

        if ($key === null) {
            return null;
        }

        return is_scalar($key) ? (string) $key : json_encode($key);
    }

    private function sanitize(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        foreach ($values as $key => $value) {
            if ($this->isSensitive((string) $key)) {
                $values[$key] = '[REDACTED]';
            }
        }

        return $values;
    }

    private function isSensitive(string $key): bool
    {
        return (bool) preg_match(
            '/password|token|secret|api[_-]?key|authorization|remember/i',
            $key
        );
    }

    private function device(?string $userAgent): string
    {
        if (!$userAgent || $userAgent === 'CLI') {
            return 'CLI';
        }

        if (preg_match('/ipad/i', $userAgent)) {
            return 'iPad';
        }

        if (preg_match('/iphone/i', $userAgent)) {
            return 'iPhone';
        }

        if (preg_match('/android/i', $userAgent)) {
            return 'Android';
        }

        if (preg_match('/windows/i', $userAgent)) {
            return 'Windows';
        }

        if (preg_match('/macintosh|mac os/i', $userAgent)) {
            return 'Mac';
        }

        if (preg_match('/linux/i', $userAgent)) {
            return 'Linux';
        }

        return 'Unknown';
    }
}
