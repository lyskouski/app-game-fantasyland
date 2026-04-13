<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Native\Mobile\Facades\Dialog;

class Notification extends Model
{
    /** @use HasFactory<\Database\Factories\NotificationFactory> */
    use HasFactory;

    protected $fillable = [
        'message'
    ];

    public static function addIfExists(string $html): void
    {
        if (preg_match("/Syst\(\s*'([^']*)'/u", $html, $matches)) {
            $message = $matches[1];
            self::create([
                'message' => $message
            ]);
            Dialog::toast(strip_tags($message), 'long');
        }
    }
}