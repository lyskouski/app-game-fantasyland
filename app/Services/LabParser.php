<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

use App\Settings\Defines;

class LabParser
{
    public function getLocation($data) {
        $result = [
            'place' => null,
            'loc' => null,
            'login' => null,
        ];
        if (preg_match('/var\s+curPlace\s*=\s*(\d+)/', $data, $matches)) {
            $result['place'] = (int)$matches[1];
        }
        if (preg_match('/var\s+curLoc\s*=\s*(\d+)/', $data, $matches)) {
            $result['loc'] = (int)$matches[1];
        }
        if (preg_match("/var\s+plLogin\s*=\s*'([^']+)'/", $data, $matches)) {
            $result['login'] = $matches[1];
        }
        $result['location'] = Defines::LAB[$result['place']][$result['loc']] ?? [
            'title' => 'Неизвестный Лабиринт',
            'id' => 0,
            'x' => 200,
            'y' => 200,
            'z' => 12,
        ];
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

    public function getQuest($html) {
        $result = [
            'title' => '',
            'description' => '',
            'actions' => [],
            'timer' => null,
        ];
        if (preg_match('/parent\.mc\.f1\([^)]*\)\.innerHTML\s*=\s*"([^"]*)"/', $html, $matches)) {
            $result['title'] = $matches[1];
        } elseif (preg_match('/var\s+mn\s*=\s*"(.*?)"/', $html, $matches)) {
            $result['title'] = strip_tags($matches[1]);
        }
        if (preg_match('/parent\.mc\.op\s*\(\s*"([^"]*)"/', $html, $matches)) {
            $result['description'] = $matches[1];
        }
        if (preg_match('/parent\.mc\.tm\s*=\s*(\d+)/', $html, $matches)) {
            $result['timer'] = (int)$matches[1];
        }
        if (preg_match_all('/parent\.mc\.msi\s*\(\s*"[^"]*"\s*,\s*"([^"]*)"\s*,/', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (!empty($result['description'])) {
                    $result['description'] .= '<br />' . $match[1];
                } else {
                    $result['description'] = $match[1];
                }
            }
        }
        $pattern = '/parent\.mc\.re\s*\(\s*"([^"]*)"\s*,\s*(\d+)\s*,\s*"([^"]*)"\s*\)/';
        if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $result['actions'][] = [
                    'text' => $match[1],
                    'id' => (int)$match[2],
                    'extra' => $match[3],
                    'option' => '',
                ];
            }
        }
        $pattern = "/<A\s+HREF\s*=\s*'javascript:\s*([^(]*)\(\);?'[^>]*>([^<]*)<\/A>/i";
        if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $result['actions'][] = [
                    'text' => $match[2],
                    'id' => '-1',
                    'extra' => '',
                    'option' => trim($match[1]),
                ];
            }
        }
        return $result;
    }
}