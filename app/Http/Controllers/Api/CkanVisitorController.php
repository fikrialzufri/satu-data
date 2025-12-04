<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CkanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CkanVisitorController extends Controller
{
    private CkanService $ckanService;

    public function __construct(CkanService $ckanService)
    {
        $this->ckanService = $ckanService;
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeRequest($request);

        $payload = Validator::make($request->all(), [
            'records' => ['required', 'array'],
            'records.*.period' => ['required', 'string', 'in:daily,monthly,yearly'],
            'records.*.date' => ['required', 'date'],
            'records.*.count' => ['required', 'integer', 'min:0'],
            'records.*.iid' => ['nullable', 'string', 'max:255'],
            'records.*.url' => ['nullable', 'url'],
        ])->validate();

        collect($payload['records'])
            ->groupBy('period')
            ->each(function ($entries, $period) {
                $normalized = $entries->map(function ($entry) {
                    return [
                        'iid' => Arr::get($entry, 'iid'),
                        'url' => Arr::get($entry, 'url'),
                        'count' => Arr::get($entry, 'count', 0),
                        'date' => Arr::get($entry, 'date'),
                    ];
                })->all();

                $this->ckanService->syncVisitors($period, $normalized);
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan',
            'data' => $payload
        ], Response::HTTP_CREATED);
    }

    private function authorizeRequest(Request $request): void
    {
        $sharedSecret = config('services.ckan.shared_secret');

        if (blank($sharedSecret)) {
            return;
        }

        $provided = $request->header('X-CKAN-SECRET');

        abort_if(
            empty($provided) || !hash_equals($sharedSecret, $provided),
            Response::HTTP_UNAUTHORIZED,
            'Unauthorized'
        );
    }
}
