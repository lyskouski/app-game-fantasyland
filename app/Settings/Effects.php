<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Settings;

class Effects
{
    public static function getImage(string $str): string
    {
        if (str_contains($str, ',')) {
            $items = explode(',', $str);
            $results = [];
            foreach ($items as $item) {
                $item = preg_replace('/(?:(?:^|\n)\s+|\s+(?:$|\n))/u', '', $item);
                $item = preg_replace('/\s+/u', ' ', $item);
                $results[] = self::getImage($item);
            }
            return implode(' ', $results);
        }

        $text = strtolower($str);
        $firstNumber = explode(' ', $str)[0];

        foreach (self::getEffectPatterns() as $pattern => $effect) {
            if (str_contains($text, $pattern)) {
                return self::buildImageTag($firstNumber, $effect, $str);
            }
        }

        return $str;
    }

    /**
     * @return array<string, string|null>
     */
    private static function getEffectPatterns(): array
    {
        return [
            'ции жизни' => '../effects/circle_of_life',
            'жизни' => 'hp',
            'эффекто' => 'm_prot',
            'силы' => 'strength',
            'удаче' => 'luck',
            'ума' => 'intelligence',
            'скорости' => 'speed',
            'концентр' => 'conc',
            'от дам и колдовства' => 'defence_l|defence_s',
            'от рыцарей и света' => 'defence_k|defence_h',
            'от драконов и хаоса' => 'defence_d|defence_c',
            'от дам' => 'defence_l',
            'от рыцарей' => 'defence_k',
            'от драконов' => 'defence_d',
            'от хаоса' => 'defence_c',
            'от света' => 'defence_h',
            'от яда' => 'pp',
            'от колдовства' => 'defence_s',
            'дам' => 'attack_l',
            'рыцар' => 'attack_k',
            'дракон' => 'attack_d',
            'хаос' => 'attack_c',
            'свет' => 'attack_h',
            'колдовств' => 'attack_s',
        ];
    }

    private static function buildImageTag(string $number, string $effect, string $str): string
    {
        $imgBegin = '&nbsp;<img align=absmiddle width=14 height=14 src="' . Defines::URL . 'images/miscellaneous/';

        if (str_contains($effect, '|')) {
            [$effect1, $effect2] = explode('|', $effect);
            return $number . $imgBegin . $effect1 . '.gif" title="' . $str . '" /><br />' .
                   $number . $imgBegin . $effect2 . '.gif" title="' . $str . '" />';
        }

        return $number . $imgBegin . $effect . '.gif" title="' . $str . '" />';
    }
}
