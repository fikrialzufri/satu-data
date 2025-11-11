<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CkanVisitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'iid',
        'url',
        'count',
        'period',
        'period_date',
    ];

    protected $casts = [
        'count' => 'integer',
        'period_date' => 'date',
    ];

    public function scopeForPeriod($query, string $period)
    {
        return $query->where('period', $period);
    }

    public static function aggregated(string $period)
    {
        return static::query()
            ->forPeriod($period)
            ->latest('period_date')
            ->get(['iid', 'url', 'count', 'period_date'])
            ->map(function (self $visitor) {
                return [
                    'iid' => $visitor->iid,
                    'url' => $visitor->url,
                    'count' => $visitor->count,
                    'period_date' => optional($visitor->period_date)->toDateString(),
                ];
            });
    }
}
