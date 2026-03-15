<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

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
                ...$this->onCraft($html),
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

    protected function onCraft(string $html) {
        $message = '';
        if (preg_match("/Syst\(\s*'([^']*)'/u", $html, $matches)) {
            $message = $matches[1];
        }
        $craft = [];
        if (preg_match_all(
            '/<BUTTON[^>]+onClick=\'regimeTo\(([^)]+)\)[^>]*>([^<]+)<\/BUTTON>/ui',
            $html,
            $matches,
            PREG_SET_ORDER
        )) {
            foreach ($matches as $m) {
                $expr = $m[1];
                $title = trim($m[2]);
                $id = null;
                if (preg_match('/^\d+$/', $expr)) {
                    $id = (int)$expr;
                } else {
                    if (preg_match('/^(\d+)\s*\+\s*(\d+)$/', $expr, $em)) {
                        $id = (int)$em[1] + (int)$em[2];
                    } else {
                        $id = @eval('return ' . $expr . ';');
                    }
                }
                if ($id !== null) {
                    $craft[] = ['id' => $id, 'title' => $title];
                }
            }
        }

        $recipes = [];
        if (preg_match_all('/<TR>(.*?)<\/TR>/s', $html, $rows, PREG_SET_ORDER)) {
            foreach ($rows as $row) {
                $tr = $row[1];
                if (strpos($tr, "startWork(") === false && strpos($tr, "startWorkCount(") === false) {
                    continue;
                }
                $src = '';
                $title = '';
                // Always extract src and title from <img ...>
                if (preg_match('/<img[^>]+title=["\']?([^"\'>]+)["\']?[^>]*src=["\']([^"\'>]+)["\']/i', $tr, $imgMatch)) {
                    $title = html_entity_decode($imgMatch[1]);
                    $src = str_replace('../', '/', $imgMatch[2]);
                }
                // Get recipe id
                $id = null;
                if (preg_match('/startWork\((\d+)\)/', $tr, $idMatch)) {
                    $id = (int)$idMatch[1];
                } elseif (preg_match('/startWorkCount\((\d+),/', $tr, $idMatch)) {
                    $id = (int)$idMatch[1];
                }
                // Get count (prefer bracket in title, else button/input value)
                $count = 1;
                if (preg_match('/\[(\d+)\]/', $tr, $bracketMatch)) {
                    $count = (int)$bracketMatch[1];
                }
                // Get time
                $time = '';
                if (preg_match('/<span[^>]+id=["\']t\d+["\'][^>]*>([^<]+)<\/span>/i', $tr, $timeMatch)) {
                    $time = trim($timeMatch[1]);
                }
                // Get receipt (ingredients)
                $receipt = [];
                if (preg_match_all('/<b>([^<]+)<\/b>/i', $tr, $bMatch)) {
                    foreach ($bMatch[1] as $value) {
                        $receipt[] = str_replace('&nbsp;', ' ', $value);
                    }
                }
                $receiptStr = implode(', ', $receipt);
                if ($id !== null && $title !== '') {
                    $recipes[] = [
                        'title' => $title,
                        'count' => $count,
                        'src' => $src,
                        'id' => $id,
                        'time' => $time,
                        'receipt' => $receiptStr
                    ];
                }
            }
        }
        return ['craft' => $craft, 'recipes' => $recipes, 'message' => $message];
    }
}
