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

        return ['data' => $sections];
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

    function parseTopic(string $html): array
    {
        $title = '';
        $author = '';
        if (preg_match("/<H1 class='ThreadTitle'>(.*?)<\/H1>/s", $html, $topicMatch)) {
            $title = trim($topicMatch[1]);
        }
        if (preg_match('/tn\(([^;]+)\)/', $html, $tnMatch)) {
            $args = explode(',', str_replace(['"', "'"], '', $tnMatch[1]));
            $args = array_map('trim', $args);
            if (count($args) >= 15) {
                $author = "<font color='white'>[Lvl:&nbsp;$args[2]]</font>&nbsp;" .
                    "<font color='#$args[4]' class='shadow'>$args[0]</font>&nbsp;" .
                    "<img src='/images/info_{$args[14]}.gif' alt='[$args[14]]' />";
            }
        }
        $items = [];
        $parts = preg_split('/<SCRIPT>z\(/i', $html);
        array_shift($parts);
        foreach ($parts as $part) {
            $args = explode(',', str_replace(['"', "'"], '', $part));
            $args = array_map('trim', $args);
            $postAuthor = "<font color='white'>[Lvl:&nbsp;$args[3]]</font>&nbsp;" .
                    "<font color='#$args[5]' class='shadow'>$args[1]</font>&nbsp;" .
                    "<img src='/images/info_{$args[15]}.gif' alt='[$args[15]]' />";

            $scriptEnd = strpos($part, '</SCRIPT>');
            $afterScript = substr($part, $scriptEnd + 9);
            $endMarker = strpos($afterScript, "</td></tr><tr align='right' valign='bottom'>");
            $postContent = str_replace(
                'src="../images/',
                'src="https://www.fantasyland.ru/images/',
                substr($afterScript, 0, $endMarker)
            );
            $items[] = [
                'time' => $args[0],
                'author' => $postAuthor,
                'content' => $postContent,
            ];
        }
        return [
            'items' => $items,
            'pages' => $this->parsePagingBlock($html),
            'title' => htmlspecialchars($title),
            'author' => $author
        ];
    }

    private function parsePagingBlock(string $html): array
    {
        $pages = [];
        if (!preg_match('/paging\s*\(([^)]+)\)/', $html, $match)) {
            return $pages;
        }
        $args = explode(',', $match[1]);
        $args = array_map('trim', $args);
        if (count($args) < 6) return $pages;
        list($posts_num, $per_page, $curr, $rid, $direction, $thread_id) = $args;
        $posts_num = (int)$posts_num;
        $per_page = (int)$per_page;
        $curr = (int)$curr;
        $rid = (int)$rid;
        $direction = (int)$direction;
        $thread_id = (int)$thread_id;
        $pages_num = ($posts_num % $per_page == 0) ? intval($posts_num / $per_page) : intval($posts_num / $per_page) + 1;
        $local_mod = $curr % 20;
        $min = 1;
        $max = $pages_num;
        $script_name = '';
        $celix = (int)floor(($pages_num - $curr) / 20);
        if ($direction === 0) {
            $script_name = 'forum.php?';
            if ($local_mod == 0) {
                $min = $curr - 19;
                $max = $curr;
            } else {
                $min = $curr - $local_mod + 1;
                $max = $min + 19;
                $max = ($max > $pages_num) ? $pages_num : $max;
            }
        } else {
            $script_name = 'f_show_thread.php?id=' . $thread_id . '&';
            $max = $curr + $celix * 20;
            $min = $max - 19;
            $min = ($min < 1) ? 1 : $min;
            $max = ($max > $pages_num) ? $pages_num : $max;
        }
        $navLinks = [];
        if (($direction === 0 && $curr > 20) || ($direction === 1 && $celix != intval($pages_num / 20))) {
            $navLinks[] = [
                'title' => 'Начало',
                'url' => $script_name . 'rid=' . $rid . '&p=1',
            ];
            $navLinks[] = [
                'title' => '<<',
                'url' => $script_name . 'rid=' . $rid . '&p=' . ($min - 1),
            ];
        }
        for ($i = $min; $i <= $max; $i++) {
            $url = $script_name . 'rid=' . $rid . '&p=' . $i;
            $navLinks[] = [
                'title' => (string)$i,
                'url' => $url,
            ];
        }
        if (($direction === 0 && $curr <= $pages_num - ($pages_num % 20)) || ($direction === 1 && $celix > 0)) {
            $navLinks[] = [
                'title' => '>>',
                'url' => $script_name . 'rid=' . $rid . '&p=' . ($max + 1),
            ];
            $navLinks[] = [
                'title' => 'Конец',
                'url' => $script_name . 'rid=' . $rid . '&p=' . $pages_num,
            ];
        }
        return $navLinks;
    }
}
