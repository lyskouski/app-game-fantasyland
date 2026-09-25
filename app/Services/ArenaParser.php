<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

class ArenaParser
{
    public function train(string $html) {
        $train = [];
        preg_match_all("/addToContent\('(.+?'\s*,\s*'.+?'\s*,\s*'.+?'.+?)\);/", $html, $matches);
        foreach ($matches[1] as $match) {
            $parts = str_getcsv(trim($match), ',', "'");
            $parts = array_map('trim', $parts);
            if (count($parts) >= 16) {
                $train[] = [
                    'name' => trim($parts[0], "'"),
                    'img' => $parts[1],
                    'uid' => $parts[2],
                    'name2' => trim($parts[3], "'"),
                    'img2' => $parts[4],
                    'w1' => (int)$parts[5],
                    'percent' => (float)$parts[6],
                    'w2' => (int)$parts[7],
                    'w3' => (int)$parts[8],
                    'chck' => (bool)$parts[9],
                    'level' => (int)$parts[10],
                    'pid' => (int)$parts[11],
                    'type' => $parts[12],
                    'level2' => (int)$parts[13],
                    'type2' => $parts[14],
                    'uid2' => $parts[15],
                ];
            }
        }
        return ['train' => $train];
    }

    public function timer(string $html) {
        preg_match("/getPreTimerString\s*\(\s*(\d+)\s*,/", $html, $matches);
        $timer = isset($matches[1]) ? (int)$matches[1] : 0;
        preg_match("/Осталось перезапусков: <font color=F9FBA8><b>(\d+)<\/b><\/font>/", $html, $matches);
        $restarts = isset($matches[1]) ? (int)$matches[1] : 0;
        return ['timer' => $timer, 'restarts' => $restarts];
    }

    public function getHealthState(string $html) {
        preg_match("/nm\s*=\s*(\d+)\s*,\s*hm\s*=\s*(\d+)/", $html, $matches);
        if (!isset($matches[1], $matches[2])) {
            return ['hp_full' => 0, 'hp_current' => 0, 'hp_description' => ''];
        }
        preg_match("/<CENTER>(.+?)<\/CENTER>/i", $html, $descMatches);
        $description = isset($descMatches[1]) ? trim(strip_tags($descMatches[1])) : '';
        return [
            'hp_full' => (int)$matches[1],
            'hp_current' => (int)$matches[2],
            'hp_description' => $description
        ];
    }

    public function getRingGroups(string $html, string $w) {
        $accept = null;
        $decline = null;
        if (preg_match('/DoAct\(\\\\?"([^"\\\\]+)\\\\?"\);\'>согласны/u', $html, $matches)) {
            $accept = $matches[1];
        }
        if (preg_match('/DoAct\(\\\\?"([^"\\\\]+)\\\\?"\);\'>откажетесь/u', $html, $matches)) {
            $decline = $matches[1];
        } elseif (preg_match('/отозвать свою заявку.*?DoAct\(\\\\?[\'"]([^\'"\\\\]+)\\\\?[\'"]\)/su', $html, $matches)) {
            $decline = $matches[1];
        }

        $groups = [];
        preg_match_all('/x\(\'([^\']*)\'\)/', $html, $timeMatches, PREG_OFFSET_CAPTURE);
        $boundary = strpos($html, "arenaContent += '</TABLE>'");
        if ($boundary === false) {
            $boundary = strlen($html);
        }
        foreach ($timeMatches[1] as $index => [$time, $timePos]) {
            $segStart = $timePos;
            $segEnd = isset($timeMatches[1][$index + 1]) ? $timeMatches[1][$index + 1][1] : $boundary;
            $segment = substr($html, $segStart, $segEnd - $segStart);

            $calls = $this->extractBalancedArgs($segment, 'w(');
            if (empty($calls)) {
                continue;
            }
            $opponent = $this->parseFighter($calls[0]['args'], $w);

            $enemy = '';
            $attack = null;
            if (isset($calls[1])) {
                $enemy = $this->parseFighter($calls[1]['args'], $w);
                $conditionsSegment = substr($segment, $calls[1]['end']);
            } else {
                $conditionsSegment = substr($segment, $calls[0]['end']);
                if (preg_match('/<TD width=220>([^<]*)<td/isu', $conditionsSegment, $labelMatch) && trim($labelMatch[1]) !== '') {
                    $enemy = trim($labelMatch[1]);
                } elseif (preg_match('/DoAct\(\\\\?"([^"\\\\]+)\\\\?"\)/u', $conditionsSegment, $attackMatch)) {
                    $attack = $attackMatch[1];
                }
            }

            preg_match('/<td[^>]*>(.*?)<\/td><\/TR>/su', $conditionsSegment, $condMatch);
            $conditionsHtml = $condMatch[1] ?? '';
            $conditions = trim(str_replace('&nbsp;', '', strip_tags($conditionsHtml)));

            $groups[] = [
                'time' => $time,
                'opponent' => $opponent,
                'enemy' => $enemy,
                'attack' => $attack,
                'conditions' => $conditions,
                'withoutArt' => str_contains($conditionsHtml, 'woart.gif'),
            ];
        }

        return [
            'create' => str_contains($html, "class='selectControl'"),
            'accept' => $accept,
            'decline' => $decline,
            'groups' => $groups,
        ];
    }

    private function parseFighter(string $args, string $w): string {
        $parser = new ForumParser();
        $parts = str_getcsv(trim($args), ',', '"');
        return $parser->parseUsername([null, ...array_map('trim', $parts)], $w);
    }

    // Locates each `$needle(` occurrence and extracts its argument text, respecting nested parens/quotes.
    private function extractBalancedArgs(string $text, string $needle): array {
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
}
