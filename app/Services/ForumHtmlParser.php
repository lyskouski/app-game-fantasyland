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

        // Split by item rows: <TR><TD width=400>
        $itemRows = preg_split('/<TR><TD width=400>/', $content);

        foreach ($itemRows as $row) {
            // Skip empty rows
            if (empty(trim($row))) {
                continue;
            }

            // Extract link and name from: <A HREF='...'>{name}</A>
            if (!preg_match('/<A\s+HREF=[\'"]([^\'"]+)[\'"]\s*>([^<]+)<\/A>/i', $row, $linkMatch)) {
                continue;
            }

            // Extract description from: <br><sub>...description...</sub>
            if (!preg_match('/<br><sub>\s*(.+?)\s*<\s*\/\s*sub>/is', $row, $descMatch)) {
                continue;
            }

            // Extract author from: Автор последнего сообщения: <b>{author}</b>
            if (!preg_match('/Автор\s+последнего\s+сообщения:\s*<b>([^<]+)<\/b>/i', $row, $authorMatch)) {
                continue;
            }

            // Extract date from: Дата: {date}
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
}
