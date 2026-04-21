<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Models\Notification;

class StoreController extends MainController
{
    public function buyItem() {
        $html = $this->post('cgi/buy.php');
        Notification::addIfExists($html);
        return redirect('/cgi/no_combat.php?tab=buy');
    }

    public function sellItem() {
        $html = $this->post('cgi/sell_good_to_shop.php');
        Notification::addIfExists($html);
        return redirect('/cgi/no_combat.php?tab=sell');
    }
}
