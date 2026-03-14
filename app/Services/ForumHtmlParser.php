<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

class ForumHtmlParser
{
    public function parse(string $html): array
    {
        $sections = [];
        $sectionPattern = '/<TR><TD colspan=4><CENTER><h4>([^<]+)<\/h4><\/CENTER><\/TR>/';
        $parts = preg_split($sectionPattern, $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        for ($i = 1; $i < count($parts); $i += 2) {
            $sectionName = $parts[$i] ?? null;
            $sectionContent = $parts[$i + 1] ?? null;
            if ($sectionName && $sectionContent) {
                $items = $this->parseItems($sectionContent);
                $sections[] = [
                    'name' => trim($sectionName),
                    'items' => $items,
                ];
            }
        }

        return $sections;
    }

    private function parseItems(string $content): array
    {
        $items = [];
        $itemRows = preg_split('/<TR><TD width=400>/', $content);
        foreach ($itemRows as $row) {
            if (empty(trim($row))) {
                continue;
            }
            if (!preg_match('/<A\s+HREF=[\'"]([^\'"]+)[\'"]\s*>([^<]+)<\/A>/i', $row, $linkMatch)) {
                continue;
            }
            if (!preg_match('/<br><sub>\s*(.+?)\s*<\s*\/\s*sub>/is', $row, $descMatch)) {
                continue;
            }
            if (!preg_match('/Автор\s+последнего\s+сообщения:\s*<b>([^<]+)<\/b>/i', $row, $authorMatch)) {
                continue;
            }
            if (!preg_match('/Дата:\s*(\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2})/i', $row, $dateMatch)) {
                continue;
            }
            $items[] = [
                'link' => $linkMatch[1],
                'name' => $linkMatch[2],
                'description' => $descMatch[1],
                'author' => $authorMatch[1],
                'time' => $dateMatch[1],
            ];
        }
        return $items;
    }

    public function parseForum(string $html): array
    {
        $result = [];
        $rowPattern = '/<TR><TD>(.*?)<span id=\'tn(\d+)\'><\/span><\/TD><\/TR>/si';
        preg_match_all($rowPattern, $html, $rowMatches, PREG_SET_ORDER);
        $scriptPattern = '/f\(([^;]+)\);/s';
        preg_match_all($scriptPattern, $html, $scriptMatches);
        $scriptData = $scriptMatches[1] ?? [];

        $title = '';
        $pages = null;
        $id = null;

        if (preg_match('/<TR ALIGN=CENTER HEIGHT=40 CLASS=Sub_FTableTitle><TD><B>([^<]+)<\/B><\/TD>/', $html, $titleMatch)) {
            $title = trim($titleMatch[1]);
        }
        if (preg_match('/for \s*\(.*i\s*=\s*1;\s*i\s*<=\s*(\d+)/', $html, $pagesMatch)) {
            $pages = (int)$pagesMatch[1];
        }
        if (preg_match("/forum\.php\?rid=(\d+)/", $html, $idMatch)) {
            $id = (int)$idMatch[1];
        }

        foreach ($rowMatches as $i => $row) {
            $topicHtml = str_replace('../', '/', $row[1]);
            $author = '';
            $description = '';
            if (isset($scriptData[$i])) {
                $args = explode(',', str_replace(['"', "'"], '', $scriptData[$i]));
                $args = array_map('trim', $args);
                if (count($args) < 18) {
                    continue;
                }
                $author = "<font color='white'>[Lvl:&nbsp;$args[3]]</font>&nbsp;" .
                    "<font color='#$args[5]' class='shadow'>$args[1]</font>&nbsp;" .
                    "<img src='/images/info_{$args[15]}.gif' alt='[$args[15]]' />";
                $descCount = $args[16] ?? '';
                $descAuthor = $args[17] ?? '';
                $description = "Количество ответов: $descCount. Автор последнего сообщения: $descAuthor.";
            }
            $result[] = [
                'topic' => $topicHtml,
                'author' => $author,
                'description' => $description,
            ];
        }
        return [
            'items' => $result,
            'title' => $title,
            'pages' => $pages,
            'id' => $id
        ];
    }
}
