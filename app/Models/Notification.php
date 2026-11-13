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
        'message',
        'channel',
    ];

    public static function addIfExists(string $html): void
    {
        if (preg_match_all("/Syst\(\s*'([^']*)'/u", $html, $matches)) {
            foreach ($matches[1] as $message) {
                self::addMessage($message, 0);
            }
        }
    }

    public static function addMessage(string $message, int $channel = 0): void
    {
        self::create([
            'message' => strip_tags($message, ['<b>', '<i>', '<strong>', '<em>']),
            'channel' => $channel,
        ]);
        Dialog::toast(strip_tags(str_replace('&nbsp;', ' ', $message)));
    }
}