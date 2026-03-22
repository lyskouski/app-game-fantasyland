<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\InfoParser;

class InfoController extends Controller
{
    protected InfoParser $parser;

    public const OPTION = 'option';
    public const TYPE_STUFF = '0';
    public const TYPE_INFO = '1';
    public const TYPE_ARMY = '3';
    public const TYPE_DIARY = '4';
    public const TYPE_EFFECTS = '5';
    public const TYPE_RUNES = '6';
    public const TYPE_RATINGS = '10';

    public function __construct()
    {
        parent::__construct();
        $this->parser = new InfoParser();
    }

    public function index() {
        $html = $this->curl->boot($this->url . '/cgi/show_info.php');
        return view('info', $this->parser->options($html));
    }

    public function indexPost(?array $post = null) {
        $opt = request()->post(self::OPTION);
        if (isset($post[self::OPTION])) {
            $opt = $post[self::OPTION];
        }
        $post = [
            $opt . '.x' => rand(1, 10),
            $opt . '.y' => rand(1, 10),
        ];
        $html = $this->curl->boot($this->url . 'cgi/change_info.php', $post);
        switch ($opt) {
            case self::TYPE_STUFF:
                $html .= $this->curl->boot($this->url . 'cgi/set_load.php');
                $html .= $this->curl->boot($this->url . 'cgi/scrolls_set_load.php');
                return view('info_stuff', $this->parser->getStuff($html));
            case self::TYPE_INFO:
                return view('info_info', $this->parser->getInfo($html));
            case self::TYPE_ARMY:
                return view('info_army', $this->parser->getArmy($html));
            case self::TYPE_DIARY:
                $html .= $this->curl->boot($this->url . 'cgi/e_show_letters.php');
                $html .= $this->curl->boot($this->url . 'cgi/pl_notebook.php');
                return view('info_diary', $this->parser->getDiary($html));
            case self::TYPE_EFFECTS:
                return view('info_effects', $this->parser->getEffects($html));
            case self::TYPE_RUNES:
                return view('info_runes');
            case self::TYPE_RATINGS:
                return view('info_ratings', $this->parser->getRatings($html));
            default:
                return $this->get('cgi/change_info.php', $post);
        }
    }

    public function mailIncome() {
        $data = request()->input();
        $html = $this->curl->boot($this->url . 'cgi/msgs_read.php?' . http_build_query($data));
        return view('info_diary_mail', $this->parser->getMessage($html));
    }

    public function mailOutcome() {
        $data = request()->input();
        $html = $this->curl->boot($this->url . 'cgi/letters_read.php?' . http_build_query($data));
        return view('info_diary_mail', $this->parser->getMessage($html));
    }

    public function mail() {
        $data = request()->input();
        $post = request()->post();
        $html = $this->curl->boot($this->url . 'cgi/send_letter.php?' . http_build_query($data), $post);
        return view('mail', ['id' => 2956, 'name' => '', ...$this->parser->getMailForm($html), ...$data]);
    }

    public function deleteMessage() {
        $data = request()->input();
        $this->curl->boot($this->url . 'cgi/msgs_del.php?' . http_build_query($data));
        return $this->indexPost([self::OPTION => self::TYPE_DIARY]);
    }

    public function notePost() {
        $post = request()->post();
        $this->curl->boot($this->url . 'cgi/pl_notebook.php', $post);
        return $this->indexPost([self::OPTION => self::TYPE_DIARY]);
    }

    public function armySelection() {
        $data = request()->input();
        $this->curl->boot($this->url . 'cgi/army_needcombat_ref.php?' . http_build_query($data));
        return view('empty', ['data' => '']);
    }

    public function unwear() {
        $data = request()->input();
        $this->curl->boot($this->url . 'cgi/inv_unwear.php?' . http_build_query($data));
        return $this->indexPost([self::OPTION => self::TYPE_STUFF]);
    }

    public function wear() {
        $data = request()->input();
        $this->curl->boot($this->url . 'cgi/inv_wear.php?' . http_build_query($data));
        return $this->indexPost([self::OPTION => self::TYPE_STUFF]);
    }

    public function setWear() {
        $data = request()->input();
        $this->curl->boot($this->url . 'cgi/set_wear.php?' . http_build_query($data));
        return $this->indexPost([self::OPTION => self::TYPE_STUFF]);
    }

    public function setSave() {
        $data = request()->input();
        $this->curl->boot($this->url . 'cgi/set_save.php?' . http_build_query($data));
        return $this->indexPost([self::OPTION => self::TYPE_STUFF]);
    }

    public function loadItems() {
        $data = request()->input();
        $html = $this->curl->boot($this->url . 'cgi/inv_load_items.php?' . http_build_query($data));
        return view('info_stuff_items', $this->parser->getStuffItems($html));
    }
}
