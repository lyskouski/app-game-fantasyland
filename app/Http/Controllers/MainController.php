<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\CraftParser;
use App\Services\InfoParser;
use App\Services\LocationParser;
use App\Services\PreyParser;
use App\Services\StoreParser;

class MainController extends Controller
{
    public function index() {
        $html = $this->post('cgi/no_combat.php', []);
        $loc = new LocationParser();
        $prey = new PreyParser();
        // throw new \Exception('Unknown page: ' . $html);
        Notification::addIfExists($html);
        if (strpos($html, 'work_stop.php') !== false) {
            return view('prey_start', [
                ...$loc->onPlace($html),
                ...$prey->parse($html, $this->captcha(time()))
            ]);
        } elseif (strpos($html, 'craft_favorite_ref.php') !== false) {
            return view('craft_stop', [
                ...$loc->onPlace($html),
                ...(new CraftParser)->parse($html),
                'captcha' => $this->captcha(time())
            ]);
        } elseif (strpos($html, 'work_start.php') !== false) {
            return view('prey_stop', [
                ...$loc->onPlace($html),
                ...$prey->parse($html, $this->captcha(time()))
            ]);
        } elseif (strpos($html, '/cgi/maze_move.php') !== false) {
            return redirect('/labyrinth');
        } elseif (strpos($html, 'src="mc_main.php"') !== false) {
            return redirect('/cgi/mc_main.php');
        } elseif (strpos($html, "action='v_trade_search.php'") !== false) {
            return $this->marketplace($html);
        } elseif (strpos($html, 'id="LocTable"') !== false) {
            return view('main_location', $loc->onLocation($html));
        } elseif (
            strpos($html, 'cssLocImage') !== false ||
            strpos($html, '<image height=150 width=150') !== false ||
            strpos($html, "window.open('loc_desc.php") !== false
        ) {
            return $this->place($html);
        } elseif (strpos($html, 'travel_start.php') !== false) {
            return $this->map();
        }
        return view('generic', ['data' => $html]);
    }

    protected function marketplace($html) {
        $data = (new LocationParser)->onPlace($html);
        $data['tab'] = session()->pull('tab', 'tent');
        $store = new StoreParser();
        $htmlTent = $this->get('cgi/v_trade_load_all.php', []);
        $data['tent'] = $store->parseTentStore($htmlTent);
        $htmlItem = $this->get('cgi/inv_load_items.php', ['all' => '', 'dv' => 'd1777']);
        $info = new InfoParser();
        $data['items'] = $info->getStuffItems($htmlItem)['items'] ?? [];
        return view('main_marketplace', $data);
    }

    protected function place($html = null) {
        $data = (new LocationParser)->onPlace($html);
        $data['tab'] = session()->pull('tab', 'buy');
        if (preg_match("/v_trade_load_shop\.php\?sid=(\d+)/", $html, $matches)) {
            $store = new StoreParser();
            $htmlBuy = $this->get('cgi/v_trade_load_shop.php', ['sid' => $matches[1]]);
            $data['buy'] = $store->parseBuyStore($htmlBuy);
            // $data['captcha'] = $this->captcha(time());
            if (preg_match("/v_trade_show_goods_for_sale\.php\?id=(\d+)/", $htmlBuy, $matches)) {
                $htmlSell = $this->get('cgi/v_trade_show_goods_for_sale.php', ['id' => $matches[1]]);
                $data['sell'] = $store->parseSellStore($htmlSell);
            }
        }
        return view('main_place', $data);
    }

    public function map() {
        $post = request()->post();
        if (!empty($post)) {
            $html = $this->post('cgi/travel_start.php', [], $post);
        } else {
            $html = $this->get('cgi/map.php', []);
        }
        Notification::addIfExists($html);
        return view('main_map', (new LocationParser)->onMap($html));
    }

    public function mapStop() {
        $html = $this->get('cgi/travel_stop.php', []);
        Notification::addIfExists($html);
        return $this->index();
    }

    public function wear() {
        $this->get('cgi/inv_wear.php');
        return $this->index();
    }
}
