<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\ForumParser;

final class ForumController extends Controller
{
    protected ForumParser $parser;

    public function __construct()
    {
        parent::__construct();
        $this->parser = new ForumParser();
    }

    public function index() {
        $html = $this->get('cgi/forum_rooms.php', []);
        return view('forum_rooms', $this->parser->parse($html));
    }

    public function room() {
        $data = request()->input();
        $html = $this->post('cgi/forum.php', $data);
        $w = $this->get('cgi/w.JS', []);
        return view('forum', [...$this->parser->parseForum($html, $w), ...$data]);
    }

    public function topic() {
        $data = request()->input();
        $html = $this->get('cgi/f_show_thread.php', $data);
        $w = $this->get('cgi/w.JS', []);
        return view('forum_topic', [...$this->parser->parseTopic($html, $w), ...$data]);
    }

    public function topicPost() {
        $data = request()->input();
        $html = $this->post('cgi/f_show_thread.php', $data);
        $w = $this->get('cgi/w.JS', []);
        return view('forum', [...$this->parser->parseForum($html, $w), 'p' => 1, ...$data]);
    }
}
