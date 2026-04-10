<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    /** @use HasFactory<\Database\Factories\MapFactory> */
    use HasFactory;

    protected $fillable = [
        'location_id',
        'place_id',
        'z',
        'x',
        'y',
        'type',
        'loc',
        'info',
    ];

    public static function getByLocation(?string $locationId, ?string $placeId, ?string $z): string
    {
        $data = self::where('location_id', $locationId)
            ->where('place_id', $placeId)
            ->where('z', $z)
            ->get()
            ->toArray();
        $result = [];
        foreach ($data as $item) {
            if (!isset($result[$item['x']])) {
                $result[(int)$item['x']] = [];
            }
            $result[(int)$item['x']][$item['y']] = [
                'loc' => json_decode($item['loc'], true),
                'curr' => [$item['z'], $item['x'], $item['y']],
                'info' => json_decode($item['info'], true),
                'type' => $item['type'],
                'time' => strtotime($item['updated_at']),
            ];
        }
        return json_encode($result);
    }

    public static function clearLocation(?string $locationId, ?string $placeId, bool $lastHour = false): void
    {
        $query = self::where('location_id', $locationId)
            ->where('place_id', $placeId);
        if ($lastHour) {
            $query->where('updated_at', '<=', now()->subHour());
        }
        $query->delete();
    }
}
