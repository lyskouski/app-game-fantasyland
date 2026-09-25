<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

class UserParser
{
    public function parseUser(string $args, string $w): string {
        $parts = str_getcsv(trim($args), ',', '"');
        return $this->buildUsername([null, ...array_map('trim', $parts)], $w);
    }

    public function extractBalancedArgs(string $text, string $needle): array {
        $results = [];
        $offset = 0;
        $len = strlen($text);
        while (($pos = strpos($text, $needle, $offset)) !== false) {
            $start = $pos + strlen($needle);
            $depth = 1;
            $i = $start;
            $quote = null;
            while ($i < $len && $depth > 0) {
                $ch = $text[$i];
                if ($quote !== null) {
                    if ($ch === '\\') {
                        $i++;
                    } elseif ($ch === $quote) {
                        $quote = null;
                    }
                } elseif ($ch === '"' || $ch === "'") {
                    $quote = $ch;
                } elseif ($ch === '(') {
                    $depth++;
                } elseif ($ch === ')') {
                    $depth--;
                }
                $i++;
            }
            $results[] = ['args' => substr($text, $start, $i - $start - 1), 'end' => $i];
            $offset = $i;
        }
        return $results;
    }


    public function buildUsername(array $args, string $w): string
    {
        if (count($args) < 16) {
            return '?';
        }
        //z() => pt,login,id,lvl,tagss,col,clan1,zap1,clan2,zap2,clan3,zap3,clan4,zap4,mob,sex,image,buttons,reputation
        //f() => i,login,id,lvl,tagss,col,clan1,zap1,clan2,zap2,clan3,zap3,clan4,zap4,mob,sex,i2,s2, thid, rid
        //w() => login,id,lvl,tagss,col,clan1,zap1,clan2,zap2,clan3,zap3,clan4,zap4,mob,sex, fun)
        //wc() => login,id,lvl,tagss,col,clan1,zap1,clan2,zap2,clan3,zap3,clan4,zap4,mob,sex,statuss,hp,hpmax,dealer,mod

        $clanData = $this->parseClansData($w);

        return '<span style="white-space: nowrap;">' .
            "<font color='white'>[Lvl:&nbsp;{$args[3]}]</font>" .
            $this->getClanImage($args[6], $clanData) .
            $this->getClanImage($args[8], $clanData) .
            $this->getClanImage($args[10], $clanData) .
            "&nbsp;<font color='#{$args[5]}' class='shadow'>{$args[1]}</font>" .
            "&nbsp;<img align='absmiddle' src='/images/info_{$args[15]}.gif' alt='[{$args[15]}]' />" .
            "</span>";
    }

    public function parseClansData(string $w): array
    {
        $clanData = [
            'idsMap' => [],
            'cnames' => [],
            'imgs' => []
        ];

        if (preg_match('/var\s+ids\s*=\s*new\s+Array\(([^)]+)\)/', $w, $match)) {
            $ids = array_map('trim', explode(',', $match[1]));
            foreach ($ids as $index => $id) {
                $clanData['idsMap'][$id] = $index;
            }
        }
        if (preg_match("/var\s+cnames\s*=\s*new\s+Array\(([^)]+)\)/", $w, $match)) {
            $cnames = array_map(function($item) {
                return trim(trim($item), "'\"");
            }, explode(',', $match[1]));
            $clanData['cnames'] = $cnames;
        }
        if (preg_match("/var\s+imgs\s*=\s*new\s+Array\(([^)]+)\)/", $w, $match)) {
            $imgs = array_map(function($item) {
                return trim(trim($item), "'\"");
            }, explode(',', $match[1]));
            $clanData['imgs'] = $imgs;
        }

        return $clanData;
    }

    public function getClanImage($clanId, array $clanData): string
    {
        if (!$clanId || !isset($clanData['idsMap'][$clanId])) {
            return '';
        }

        $position = $clanData['idsMap'][$clanId];
        $clanName = $clanData['cnames'][$position] ?? '';
        $imgName = $clanData['imgs'][$position] ?? '';

        if (!$imgName) {
            return '';
        }

        return "&nbsp;<img align='absmiddle' src='https://www.fantasyland.ru/images/clans/{$imgName}' alt='{$clanName}' />";
    }
}
