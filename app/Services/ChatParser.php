<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

class ChatParser extends UserParser
{
    public function parseUserList(string $html, string $w): array {
        $users = [];
        foreach ($this->extractBalancedArgs($html, 'wc(') as $call) {
            $login = null;
            if (preg_match('/^"([^"]+)"/u', $call['args'], $m)) {
                $login = $m[1];
            }
            $users[] = ["login" => $login, "img" => $this->parseUser($call['args'], $w)];
        }

        $me = $this->getOwnName($html);

        return ['users' => $users, 'me' => $me];
    }

    public function getOwnName(string $html): ?string {
        $me = null;
        if (preg_match('/var\s+l\s*=\s*"([^"]*)"/u', $html, $m)) {
            $me = $m[1];
        }
        return $me;
    }

    public function getMenu(string $html, string $w): array {
        $clanData = $this->parseClansData($w);
        $menu = [];
        preg_match_all('/\bchannels\s*\.\s*push\s*\(\s*(\d+)\s*\)/', $html, $matches);
        foreach ($matches[1] as $channel) {
            $id = (int) $channel;
            if ($id === 0) {
                $menu[] = ['id' => $id, 'name' => 'Общий', 'img' => null];
                continue;
            }
            $position = $clanData['idsMap'][$id] ?? null;
            $name = $position === null ? '' : ($clanData['cnames'][$position] ?? '');
            $img = $position === null ? null : ($clanData['imgs'][$position] ?? null);
            $menu[] = ['id' => $id, 'name' => $name, 'img' => $img];
        }
        return $menu;
    }
}
