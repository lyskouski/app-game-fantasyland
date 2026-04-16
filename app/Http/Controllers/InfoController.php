<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

// use App\Models\Notification;
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
        $html = $this->get('cgi/show_info.php', []);
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
        $html = $this->post('cgi/change_info.php', [], $post);
        switch ($opt) {
            case self::TYPE_STUFF:
                $html .= $this->get('cgi/set_load.php', []);
                $html .= $this->get('cgi/scrolls_set_load.php', []);
                return view('info_stuff', $this->parser->getStuff($html));
            case self::TYPE_INFO:
                return view('info_info', $this->parser->getInfo($html));
            case self::TYPE_ARMY:
                return view('info_army', $this->parser->getArmy($html));
            case self::TYPE_DIARY:
                $html .= $this->get('cgi/e_show_letters.php', []);
                $html .= $this->get('cgi/pl_notebook.php', []);
                return view('info_diary', $this->parser->getDiary($html));
            case self::TYPE_EFFECTS:
                return view('info_effects', $this->parser->getEffects($html));
            case self::TYPE_RUNES:
                $money = $this->parser->getMoney($html);
                // Notification::addIfExists($initial);
                return view('info_runes', ['money' => $money]);
            case self::TYPE_RATINGS:
                return view('info_ratings', $this->parser->getRatings($html));
            default:
                return $this->generic('cgi/change_info.php', [], $post);
        }
    }

    public function mailIncome() {
        $html = $this->get('cgi/msgs_read.php');
        return view('info_diary_mail', $this->parser->getMessage($html));
    }

    public function mailOutcome() {
        $html = $this->get('cgi/letters_read.php');
        return view('info_diary_mail', $this->parser->getMessage($html));
    }

    public function mail() {
        $data = request()->input();
        $html = $this->post('cgi/send_letter.php', $data);
        return view('mail', ['id' => 2956, 'name' => '', ...$this->parser->getMailForm($html), ...$data]);
    }

    public function deleteMessage() {
        $this->get('cgi/msgs_del.php');
        return $this->indexPost([self::OPTION => self::TYPE_DIARY]);
    }

    public function notePost() {
        $this->post('cgi/pl_notebook.php', []);
        return $this->indexPost([self::OPTION => self::TYPE_DIARY]);
    }

    public function armySelection() {
        $this->get('cgi/army_needcombat_ref.php');
        return view('empty', ['data' => '']);
    }

    public function unwear() {
        $this->get('cgi/inv_unwear.php');
        return $this->indexPost([self::OPTION => self::TYPE_STUFF]);
    }

    public function wear() {
        $this->get('cgi/inv_wear.php');
        return $this->indexPost([self::OPTION => self::TYPE_STUFF]);
    }

    public function setWear() {
        $this->get('cgi/set_wear.php');
        return $this->indexPost([self::OPTION => self::TYPE_STUFF]);
    }

    public function setSave() {
        $this->get('cgi/set_save.php');
        return $this->indexPost([self::OPTION => self::TYPE_STUFF]);
    }

    public function scrollsSetSave() {
        $this->get('cgi/scrolls_set_save.php');
        return $this->indexPost([self::OPTION => self::TYPE_STUFF]);
    }

    public function scrollsSetWear() {
        $this->get('cgi/scrolls_set_wear.php');
        return $this->indexPost([self::OPTION => self::TYPE_STUFF]);
    }

    public function loadItems() {
        $html = $this->get('cgi/inv_load_items.php');
        return view('info_stuff_items', $this->parser->getStuffItems($html));
    }

    public function addUmEffect() {
        $data = request()->post();
        $this->get('cgi/add_um_effect.php', $data);
        return $this->indexPost([self::OPTION => self::TYPE_RUNES]);
    }
}
