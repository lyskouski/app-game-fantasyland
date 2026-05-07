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

final class MainController extends Controller
{
    private LocationParser $locationParser;
    private PreyParser $preyParser;

    public function __construct() {
        parent::__construct();
        $this->locationParser = new LocationParser();
        $this->preyParser = new PreyParser();
    }

    public function index() {
        $html = $this->post('cgi/no_combat.php', []);
        Notification::addIfExists($html);

        foreach ($this->getPageRoutes() as $pattern => $handler) {
            if (strpos($html, $pattern) !== false) {
                return $handler($html);
            }
        }

        return view('generic', ['data' => $html]);
    }

    /**
     * @return array<string, callable>
     */
    private function getPageRoutes(): array
    {
        return [
            'work_stop.php' => fn($html) => view('prey_start', [
                ...$this->locationParser->onPlace($html),
                ...$this->preyParser->parse($html, $this->captcha(time()))
            ]),
            'craft_favorite_ref.php' => fn($html) => view('craft_stop', [
                ...$this->locationParser->onPlace($html),
                ...(new CraftParser)->parse($html),
                'captcha' => $this->captcha(time())
            ]),
            'work_start.php' => fn($html) => view('prey_stop', [
                ...$this->locationParser->onPlace($html),
                ...$this->preyParser->parse($html, $this->captcha(time()))
            ]),
            '/cgi/maze_move.php' => fn($html) => redirect('/labyrinth'),
            'src="mc_main.php"' => fn($html) => redirect('/cgi/mc_main.php'),
            "action='v_trade_search.php'" => fn($html) => $this->marketplace($html),
            "id='ArenaText'" => fn($html) => $this->arena($html),
            'id="LocTable"' => fn($html) => view('main_location', $this->locationParser->onLocation($html)),
            'travel_start.php' => fn($html) => $this->map(),
            'cssLocImage' => fn($html) => $this->place($html),
            '<image height=150 width=150' => fn($html) => $this->place($html),
            "window.open('loc_desc.php" => fn($html) => $this->place($html)
        ];
    }

    protected function marketplace($html) {
        $data = $this->locationParser->onPlace($html);
        $data['tab'] = session()->pull('tab', 'tent');
        $store = new StoreParser();
        $htmlTent = $this->get('cgi/v_trade_load_all.php', []);
        $data['tent'] = $store->parseTentStore($htmlTent);
        $htmlItem = $this->get('cgi/inv_load_items.php', ['all' => '', 'dv' => 'd1777']);
        $info = new InfoParser();
        $data['items'] = $info->getStuffItems($htmlItem)['items'] ?? [];
        $post = [
            InfoController::TYPE_ARMY . '.x' => rand(1, 10),
            InfoController::TYPE_ARMY . '.y' => rand(1, 10),
        ];
        $htmlArmy = $this->post('cgi/change_info.php', [], $post);
        $data['army'] = $info->getArmy($htmlArmy)['army'] ?? [];
        return view('main_marketplace', $data);
    }

    protected function arena($html) {
        if (strpos($html, 'ReloadFrame();') !== false) {
            return redirect('/cgi/train_start.php');
        }
        $data = $this->locationParser->onArena($html);
        return view('main_arena', $data);
    }

    protected function place($html = null) {
        $data = $this->locationParser->onPlace($html);
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
        return view('main_map', $this->locationParser->onMap($html));
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
