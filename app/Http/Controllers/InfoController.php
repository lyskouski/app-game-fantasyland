<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\InfoParser;

class InfoController extends Controller
{
    public function index() {
        $html = $this->curl->boot($this->url . '/cgi/show_info.php');
        return view('info', (new InfoParser)->options($html));
    }

    public function indexPost(?array $post = null) {
        $opt = request()->post('option');
        $post = [
            $opt . '.x' => rand(1, 10),
            $opt . '.y' => rand(1, 10),
        ];
        $html = $this->curl->boot($this->url . 'cgi/change_info.php', $post);
        $parser = new InfoParser();
        switch ($opt) {
            case '4':
                $mails = $this->curl->boot($this->url . 'cgi/e_show_letters.php');
                return view('info_diary', $parser->getDiary($html . $mails));
            default:
                return $this->get('cgi/change_info.php', $post);
        }
    }

    public function mailIncome() {
        $data = request()->input();
        $html = $this->curl->boot($this->url . 'cgi/msgs_read.php?' . http_build_query($data));
        return view('info_diary_mail', (new InfoParser)->getMessage($html));
    }

    public function mailOutcome() {
        $data = request()->input();
        $html = $this->curl->boot($this->url . 'cgi/letters_read.php?' . http_build_query($data));
        return view('info_diary_mail', (new InfoParser)->getMessage($html));
    }
}
