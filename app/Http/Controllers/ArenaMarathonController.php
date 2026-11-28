<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\ArenaParser;
use App\Services\LocationParser;

final class ArenaMarathonController extends ArenaController
{
    public function index() {
        $html = $this->get('/cgi/arena_marathon.php');

        $url = null;
        if (preg_match('/DoScript\("DoExt\(\'([^\']+)\'\)/', $html, $matches)) {
            $url = '/cgi/' . $matches[1];
        } elseif (preg_match('/parent\.document\.location\.href\s*=\s*\'([^\']+)\'/', $html, $matches)) {
            $url = $matches[1];
        } elseif (preg_match('/window\.top\.loc\.location\.href\s*=\s*\'([^\']+)\'/', $html, $matches)) {
            $url = '/cgi/' . $matches[1];
        }
        if ($url !== null && $url !== '/cgi/arena_marathon.php') {
            return redirect($url);
        }

        $data = $this->mainPage();
        $data['quest'] = (new LocationParser)->extractNpcs($html);
        if (!$data['current'] && preg_match('/arenaSelBtn\s*=\s*(\d+)/', $html, $matches)) {
            $data['current'] = (int)$matches[1];
        }
        $parser = new ArenaParser();
        if (str_contains($html, "id='hpLine'")) {
            $health = $parser->getHealthState($html);
            return view('arena_pause', [...$data, ...$health]);
        } elseif (str_contains($html, 'train_stop.php')) {
            return redirect('/cgi/train_continue');
        }
        switch ($data['current']) {
            case 1: // Single
                $data['captcha'] = $this->captcha(time());
                $arena = $parser->getMarathonSingle($html);
                return view('arena_marathon_ring', [...$data, ...$arena]);
            //case 2: // Group
            //    $w = $this->get('cgi/w.JS', []);
            //    $group = $parser->getGroupGroups($html, $w);
            //    return view('arena_group', [...$data, ...$group]);
            case 5: // Train
                $data['captcha'] = $this->captcha(time());
                $arena = $parser->train($html);
                return view('arena_train', [...$data, ...$arena]);
            default:
                return view('main_arena_marathon', $data);
        }
    }

    public function startSingle() {
        $html = $this->post('/cgi/marathon_do.php');
        Notification::addIfExists($html);
        if (str_contains($html, "parent.document.location.href = 'combat.php'")) {
            return redirect('/cgi/combat.php');
        }
        return $this->index();
    }
}
