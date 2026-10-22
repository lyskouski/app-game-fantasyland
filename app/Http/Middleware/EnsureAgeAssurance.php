<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Middleware;

use App\Services\AgeAssurance;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Shows the neutral age screen before anything else, keeps minors
 * out of the app, and keeps social features (chat, mail, forum)
 * away from devices where a parent has locked them.
 */
class EnsureAgeAssurance
{
    private const EXEMPT = ['age', 'shell/*', 'up'];

    private const SOCIAL = [
        // Chat
        'ch/*', 'chinp', 'chat/*', 'cgi/ch_ref.php', 'cgi/claim',
        // Mail
        'cgi/msgs_read.php', 'cgi/letters_read.php', 'cgi/send_letter.php', 'cgi/msgs_del.php',
        // Forum
        'cgi/forum_rooms.php', 'cgi/forum.php', 'cgi/f_show_thread.php',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is(...self::EXEMPT)) {
            return $next($request);
        }
        if (!AgeAssurance::isDeclared()) {
            return redirect('/age');
        }
        if (!AgeAssurance::isAdult() || ($request->is(...self::SOCIAL) && AgeAssurance::isSocialLocked())) {
            return response()->view('restricted', [
                'isAdult' => AgeAssurance::isAdult(),
                'adultAge' => AgeAssurance::ADULT_AGE,
            ], 403);
        }
        return $next($request);
    }
}
