<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

class QuestParser
{
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
