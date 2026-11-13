<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\ChatParser;

final class ChatController extends Controller
{
    private function userList(string $html) {
        if (preg_match("/window\.top\.document\.location\.href='\/\?e/", $html)) {
            return view('empty', ['data' => 'REDIRECT_TO_MAIN']);
        }
        $chout = $this->get('ch/chout.php', []);
        $w = $this->get('cgi/w.JS', []);
        $data = (new ChatParser)->parseUserList($html . $chout, $w);
        return view('chat_user_list', $data);
    }

    public function index() {
        $html = $this->get('cgi/ch_ref.php', []);
        return $this->userList($html);
    }

    public function indexClan() {
        $html = $this->get('cgi/ch_ref_clan.php');
        return $this->userList($html);
    }

    public function messages() {
        $channel = request()->input('channel', '0');
        $html = $this->get('ch/chout.php', []);
        if (preg_match("/window\.top\.document\.location\.href='\/\?e/", $html)) {
            return redirect('/');
        }
        $data = Notification::where('channel', $channel)
            ->orderBy('created_at', 'desc')
            ->limit(250)
            ->get();
        for ($i = 0; $i < $data->count(); $i++) {
            $mssg = $data[$i]->message;
            $mssg = str_replace('<b>', '<b onclick="reply(this)">', $mssg);
            $mssg = preg_replace(
                '/priv\(([^)]+)\)/',
                '<b style="color:maroon" onclick="reply(this)">priv($1)</b>',
                $mssg
            );
            $data[$i]->message = $mssg;
        }
        $parser = new ChatParser();
        return view('chat', [
            'data' => $data,
            'me' => $parser->getOwnName($html),
            'channel' => $channel,
            'menu' => $parser->getMenu($html, $this->get('cgi/w.JS', [])),
        ]);
    }

    public function clear() {
        Notification::truncate();
        return redirect('/ch/chout.php');
    }

    public function send() {
        $channel = request()->input('chn', '0');
        $this->get('chinp');
        return redirect('/ch/chout.php?channel=' . $channel);
    }

    public function claim() {
        $id = request()->query('id');
        $notification = Notification::where('id', $id)->get();
        if ($notification->isEmpty()) {
            return response('Сообщение не найдено...');
        } else {
            // "<b>{$user}:</b> {$message}"
            $message = strip_tags($notification->first()->message);
            $user = '';
            if (str_contains($message, ':')) {
                $a = explode(':', $message, 2);
                $user = $a[0];
                $message = $a[1];
            }
            $data = [
                'msg' => $message,
                'who' => $user,
            ];
            $this->post('cgi/claim.php', [], $data);
            Notification::where('id', $id)->delete();
        }
        return response('Жалоба отправлена');
    }
}
