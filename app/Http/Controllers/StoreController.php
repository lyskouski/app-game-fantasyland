<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\LocationParser;
use App\Services\StoreParser;

class StoreController extends MainController
{
    public function buyItem() {
        $html = $this->post('cgi/buy.php');
        Notification::addIfExists($html);
        session(['tab' => 'buy']);
        return redirect('/cgi/no_combat.php');
    }

    public function sellItem() {
        $html = $this->post('cgi/sell_good_to_shop.php');
        Notification::addIfExists($html);
        session(['tab' => 'sell']);
        return redirect('/cgi/no_combat.php');
    }

    public function tent() {
        $html = $this->get('cgi/no_combat.php', []);
        $data = (new LocationParser)->onPlace($html);
        $data['tab'] = session()->pull('tab', 'buy');
        $store = new StoreParser();
        $htmlBuy = $this->get('cgi/v_trade_load_shop.php');
        $data['buy'] = $store->parseBuyStore($htmlBuy);
        $data['title_tent'] = $store->parseTitle($htmlBuy);
        if (preg_match("/v_trade_show_goods_for_sale\.php\?id=(\d+)/", $htmlBuy, $matches)) {
            $htmlSell = $this->get('cgi/v_trade_show_goods_for_sale.php', ['id' => $matches[1]]);
            $data['sell'] = $store->parseSellStore($htmlSell);
        }
        return view('main_place', $data);
    }

    public function showTents() {
        $html = $this->get('cgi/no_combat.php', []);
        $data = (new LocationParser)->onPlace($html);
        $data['id'] = request()->input('id');
        $data['item_name'] = request()->input('name');
        $htmlShops = $this->get('cgi/v_trade_show_shops.php');
        $store = new StoreParser();
        $data['shops'] = $store->parseTents($htmlShops);
        return view('store_tents', $data);
    }

    public function priceJson() {
        $goodId = request()->input('id');
        $shopId = request()->input('shop');
        $store = new StoreParser();
        $htmlBuy = $this->get('cgi/v_trade_load_shop.php', ['id' => $shopId]);
        $dataBuy = $store->parseBuyStore($htmlBuy);
        $item = ['buy' => 0, 'sell' => 0, 'id' => null];
        foreach ($dataBuy as $i) {
            if ($i['good_id'] == $goodId) {
                $item['buy'] = $i['cost'] ?? 0;
                $item['id'] = $i['shp_id'] ?? null;
                break;
            }
        }
        if (preg_match("/v_trade_show_goods_for_sale\.php\?id=(\d+)/", $htmlBuy, $matches)) {
            $htmlSell = $this->get('cgi/v_trade_show_goods_for_sale.php', ['id' => $matches[1]]);
            $dataSell = $store->parseSellStore($htmlSell);
            foreach ($dataSell as $i) {
                if ($i['good_id'] == $goodId) {
                    $item['sell'] = $i['cost'] ?? 0;
                    break;
                }
            }
        }
        return response()->json($item);
    }
}
