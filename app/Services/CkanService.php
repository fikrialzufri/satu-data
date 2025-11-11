<?php

namespace App\Services;

use App\Models\CkanVisitor;
use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CkanService
{
    private const PERIODS = ['daily', 'monthly', 'yearly'];

    public function getVisitorStats(): array
    {
        return [
            'daily' => CkanVisitor::aggregated('daily'),
            'monthly' => CkanVisitor::aggregated('monthly'),
            'yearly' => CkanVisitor::aggregated('yearly'),
        ];
    }

    public function syncVisitors(string $period, array $entries): void
    {
        $period = $this->normalizePeriod($period);

        if ($period === null || empty($entries)) {
            return;
        }

        $rows = collect($entries)->map(function ($entry) use ($period) {
            $periodDate = $this->normalizeDate($entry, $period);

            if ($periodDate === null) {
                return null;
            }

            return [
                'iid' => Arr::get($entry, 'iid', Arr::get($entry, 'id')),
                'url' => Arr::get($entry, 'url'),
                'count' => (int) Arr::get($entry, 'count', 0),
                'period' => $period,
                'period_date' => $periodDate->toDateString(),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        })->filter()->all();

        if (empty($rows)) {
            return;
        }

        DB::transaction(function () use ($rows) {
            CkanVisitor::upsert(
                $rows,
                ['period', 'period_date', 'iid'],
                ['url', 'count', 'updated_at']
            );
        });
    }

    private function normalizePeriod(string $period): ?string
    {
        $period = strtolower($period);

        return in_array($period, self::PERIODS, true) ? $period : null;
    }

    private function normalizeDate(array $entry, string $period): ?CarbonImmutable
    {
        $dateValue = Arr::get($entry, 'date', Arr::get($entry, 'period_date'));

        if (empty($dateValue)) {
            return null;
        }

        try {
            $date = CarbonImmutable::parse($dateValue);
        } catch (\Throwable $exception) {
            return null;
        }

        switch ($period) {
            case 'daily':
                return $date->startOfDay();
            case 'monthly':
                return $date->startOfMonth();
            case 'yearly':
                return $date->startOfYear();
            default:
                return null;
        }
    }
}
