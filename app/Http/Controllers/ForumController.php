<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\ForumHtmlParser;

class ForumController extends Controller
{
    protected ForumHtmlParser $parser;

    public function __construct()
    {
        parent::__construct();
        $this->parser = new ForumHtmlParser();
    }

    public function index() {
        $html = $this->curl->boot($this->url . 'cgi/forum_rooms.php');
        return view('forum_rooms', $this->parser->parse($html));
    }

    public function room() {
        $data = request()->input();
        $post = request()->post();
        $html = $this->curl->boot($this->url . 'cgi/forum.php?' . http_build_query($data), $post);
        return view('forum', $this->parser->parseForum($html));
    }

    public function topic() {
        $data = request()->input();
        $post = request()->post();
        $html = $this->curl->boot($this->url . 'cgi/f_show_thread.php?' . http_build_query($data), $post);
        return view('forum_topic', [...$this->parser->parseTopic($html), ...$data]);
    }

    public function topicPost() {
        $data = request()->input();
        $post = request()->post();
        $html = $this->curl->boot($this->url . 'cgi/f_show_thread.php?' . http_build_query($data), $post);
        return view('forum', $this->parser->parseForum($html));
    }
}
