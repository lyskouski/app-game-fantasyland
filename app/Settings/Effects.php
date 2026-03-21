<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Settings;

class Effects
{
    public static function getImage(string $str): string
    {
        $sNew = '';
        $sText = strtolower($str);
        $sImgBegin = '&nbsp;<img align=absmiddle width=14 height=14 src="' .
            Defines::URL . 'images/miscellaneous/';
        $nNum = explode(' ', $str)[0];

        if (strpos($sText, ',') !== false) {
            $aNew = explode(',', $sText);
            foreach ($aNew as $item) {
                $cleaned = preg_replace('/(?:(?:^|\n)\s+|\s+(?:$|\n))/u', '', $item);
                $cleaned = preg_replace('/\s+/u', ' ', $cleaned);
                $sNew .= self::getImage($cleaned) . ' ';
            }
            return $sNew;
        } elseif (strpos($sText, 'ции жизни') !== false) {
            $sNew = '../effects/circle_of_life';
        } elseif (strpos($sText, 'жизни') !== false) {
            $sNew = 'hp';
        } elseif (strpos($sText, 'эффекто') !== false) {
            $sNew = 'm_prot';
        } elseif (strpos($sText, 'силы') !== false) {
            $sNew = 'strength';
        } elseif (strpos($sText, 'удаче') !== false) {
            $sNew = 'luck';
        } elseif (strpos($sText, 'ума') !== false) {
            $sNew = 'intelligence';
        } elseif (strpos($sText, 'скорости') !== false) {
            $sNew = 'speed';
        } elseif (strpos($sText, 'концентр') !== false) {
            $sNew = 'conc';
        } elseif (strpos($sText, 'от дам и колдовства') !== false) {
            $sNew = 'defence_l.gif" title="' . $str . '" /><br />' . $nNum . $sImgBegin . 'defence_s';
        } elseif (strpos($sText, 'от рыцарей и света') !== false) {
            $sNew = 'defence_k.gif" title="' . $str . '" /><br />' . $nNum . $sImgBegin . 'defence_h';
        } elseif (strpos($sText, 'от драконов и хаоса') !== false) {
            $sNew = 'defence_d.gif" title="' . $str . '" /><br />' . $nNum . $sImgBegin . 'defence_c';
        } elseif (strpos($sText, 'от дам') !== false) {
            $sNew = 'defence_l';
        } elseif (strpos($sText, 'от рыцарей') !== false) {
            $sNew = 'defence_k';
        } elseif (strpos($sText, 'от драконов') !== false) {
            $sNew = 'defence_d';
        } elseif (strpos($sText, 'от хаоса') !== false) {
            $sNew = 'defence_c';
        } elseif (strpos($sText, 'от света') !== false) {
            $sNew = 'defence_h';
        } elseif (strpos($sText, 'от яда') !== false) {
            $sNew = 'pp';
        } elseif (strpos($sText, 'от колдовства') !== false) {
            $sNew = 'defence_s';
        } elseif (strpos($sText, 'дам') !== false) {
            $sNew = 'attack_l';
        } elseif (strpos($sText, 'рыцар') !== false) {
            $sNew = 'attack_k';
        } elseif (strpos($sText, 'дракон') !== false) {
            $sNew = 'attack_d';
        } elseif (strpos($sText, 'хаос') !== false) {
            $sNew = 'attack_c';
        } elseif (strpos($sText, 'свет') !== false) {
            $sNew = 'attack_h';
        } elseif (strpos($sText, 'колдовств') !== false) {
            $sNew = 'attack_s';
        }

        return $sNew ? $nNum . $sImgBegin . $sNew . '.gif" title="' . $str . '" />' : $str;
    }
}