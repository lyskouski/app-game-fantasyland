<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\CraftParser;
use App\Services\LocationParser;

class MainController extends Controller
{
    public function index() {
        $post = request()->post();
        $html = $this->curl->boot($this->url . 'cgi/no_combat.php', $post);
        $loc = new LocationParser();
        if (strpos($html, 'work_stop.php') !== false) {
            return view('prey_start', [
                ...$loc->onPlace($html),
                ...$this->onPrey($html, $this->captcha(time()))
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
                ...$this->onPrey($html, $this->captcha(time()))
            ]);
        } elseif (strpos($html, 'id="LocTable"') !== false) {
            return view('main_location', $loc->onLocation($html));
        } elseif (strpos($html, 'cssLocImage') !== false || strpos($html, '<image height=150 width=150') !== false) {
            return view('main_place', $loc->onPlace($html));
        } elseif (strpos($html, 'travel_start.php') !== false) {
            return $this->map();
        }
        return view('generic', ['data' => $html]);
    }

    public function map() {
        $post = request()->post();
        if (!empty($post)) {
            $html = $this->curl->boot($this->url . 'cgi/travel_start.php', $post);
        } else {
            $html = $this->curl->boot($this->url . 'cgi/map.php');
        }
        return view('main_map', (new LocationParser)->onMap($html));
    }

    public function mapStop() {
        $this->curl->boot($this->url . 'cgi/travel_stop.php');
        return $this->index();
    }

    protected function onPrey(string $html, string $captcha) {
        $content = '';
        if (preg_match('/<HR>(.*?)<\/TD><\/TR><\/TABLE>/is', $html, $matches)) {
            $content = $matches[1];
            $content = str_replace('action="work_start.php"', 'action="/cgi/work_start.php"', $content);
            $content = str_replace('../images', $this->url . 'images', $content);
            $content = preg_replace_callback(
                "/<IMG\s+SRC='png.php\?c=(\d+)'([^>]*)>/i",
                function ($m) use ($captcha) {
                    return "<img src='" . $captcha . "'" . $m[2] . ">";
                },
                $content
            );
        }
        $image = '';
        if (preg_match('/<image[^>]*src=(["\'])([^"\']+)\1/i', $html, $imgMatch)) {
           $image = str_replace('..', '', $imgMatch[2]);
        }
        $timer = 0;
        if (preg_match('/InsertTimer2\\s*\\(\\s*(\\d+)/', $html, $matches)) {
            $timer = (int)$matches[1];
        }
        $message = '';
        if (preg_match("/Syst\(\s*'([^']*)'/u", $html, $matches)) {
            $message = $matches[1];
        }
        return ['data' => $content, 'image' => $image, 'timer' => $timer, 'message' => $message];
    }
}
