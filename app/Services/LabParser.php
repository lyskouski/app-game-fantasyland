<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

class LabParser
{
    public function getLocation($data) {
        $result = [
            'place' => null,
            'location' => null,
        ];
        if (preg_match('/var\s+curPlace\s*=\s*(\d+)/', $data, $matches)) {
            $result['place'] = (int)$matches[1];
        }
        if (preg_match('/var\s+curLoc\s*=\s*(\d+)/', $data, $matches)) {
            $result['location'] = (int)$matches[1];
        }
        return $result;
    }

    public function getState($data) {
        $result = [
            'lvl' => null,
            'x' => null,
            'y' => null,
            'stamina' => null,
            'img' => null,
            'map' => [
                4 => [
                    'id' => 4,
                    'title' => 'Обновить',
                    'num' => 0,
                    'img' => 'refresh.gif',
                ],
            ],
        ];
        if (preg_match('/moo\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/', $data, $matches)) {
            $result['lvl'] = (int)$matches[1];
            $result['x'] = (int)$matches[2];
            $result['y'] = (int)$matches[3];
        }
        if (preg_match('/setStamina\s*\(\s*(\d+)\s*,/', $data, $matches)) {
            $result['stamina'] = round((int) $matches[1] / 10);
        }
        if (preg_match("/SetRoomImg\s*\(\s*'([^']+)'/", $data, $matches)) {
            $result['img'] = $matches[1];
        }
        $reg = "/a\s*\(\s*(\d+)\s*,\s*'([^']+)'\s*,\s*'([^']+)'\s*,\s*'([^']+)'\s*\)/";
        if (preg_match_all($reg, $data, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $id = (int)$match[1];
                $result['map'][$id] = [
                    'id' => $id,
                    'title' => $match[2],
                    'num' => $match[3],
                    'img' => $match[4],
                ];
            }
        }
        if (preg_match_all("/b\s*\(\s*(\d+)\s*\)/", $data, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $id = (int)$match[1];
                $result['map'][$id] = [
                    'id' => $id,
                    'title' => '',
                    'num' => 0,
                    'img' => 'go_default.gif',
                ];
            }
        }
        return $result;
    }
}