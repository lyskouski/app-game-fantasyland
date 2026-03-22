<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

use App\Settings\Defines;
use App\Settings\Effects;

class InfoParser
{
    public function options(string $html): array
    {
        $result = [];
        // Extract user info: name, level, alignment
        $userInfo = '';
        if (preg_match(
            "/<font class='cp'[^>]*>([^<]+)<\/font>\s*<font[^>]*>\[<\/font><span class='cp'[^>]*>([^<]+)<\/span><font[^>]*>\]<\/font>:&nbsp;<font[^>]*><b>([A-Z])<\/b><\/font><font[^>]*><b>([A-Z])<\/b><\/font>/u",
            $html,
            $matches
        )) {
            // $matches[1]: name, $matches[2]: level/exp, $matches[3]: alignment1, $matches[4]: alignment2
            $userInfo = sprintf('%s [%s]: %s%s', $matches[1], $matches[2], $matches[3], $matches[4]);
        }

        preg_match_all('/<input[^>]*type=["\']image["\'][^>]*>/i', $html, $inputs);
        foreach ($inputs[0] as $input) {
            preg_match('/name=["\']?(\d+)["\']?/i', $input, $name);
            preg_match('/src=["\']?([^"\'> ]+)["\']?/i', $input, $src);
            preg_match('/title=["\']?([^"\'>]+)["\']?/i', $input, $title);
            if (isset($name[1]) && isset($src[1]) && isset($title[1])) {
                $result[] = [
                    'key' => $name[1],
                    'image' => Defines::URL . $src[1],
                    'value' => $title[1],
                ];
            }
        }
        return [
            'data' => $result,
            'user' => $userInfo,
        ];
    }

    public function getDiary(string $html): array
    {
        return [
            'calendar' => $this->getCalendar($html),
            'income' => $this->getIncomeMailbox($html),
            'outcome' => $this->getOutcomeMailbox($html),
            'notes' => $this->getNotes($html)
        ];
    }

    private function getCalendar(string $html): array
    {
        $calendar = [
            ['images/clans/hunters_small.gif', 'Неделя Охотника (+1 к охоте)', ''],
            ['images/clans/druids_small.gif', 'Неделя Собирателя (+1 к добыче)', ''],
            ['images/clans/fighters_small.gif', 'Неделя Бойца (+1 монета)', ''],
            ['images/clans/thinkers_small.gif', 'Неделя Мудреца (+1 монета)', ''],
            ['images/clans/merch_small.gif', 'Неделя Купца (+1 к торговле)', ''],
            ['images/clans/miners_capital_small.gif', 'Неделя Шахтёра (+1 к добыче)', '']
        ];
        foreach ($calendar as &$item) {
            if (strpos($html, $item[0]) !== false) {
                $item[2] = 'main--light';
            }
            $item[0] = Defines::URL . $item[0];
        }
        return $calendar;
    }

    private function getIncomeMailbox(string $html): array
    {
        $mailbox = [];
        $pattern = "/fmsg\\s*\\(\\s*'([^']+)',\\s*(\\d),\\s*'(.+?)',\\s*\\d,\\s*(\\d+)\\s*\\)/u";
        if (preg_match_all($pattern, $html, $matches)) {
            foreach (array_keys($matches[0]) as $key) {
                $date = $matches[1][$key];
                $toRead = (bool)$matches[2][$key];
                $messageText = $matches[3][$key];
                $id = (int)$matches[4][$key];
                if (preg_match('/от\\s+([^:]+):\\s*(.+)/u', $messageText, $authorMatches)) {
                    $author = trim($authorMatches[1]);
                    $content = trim($authorMatches[2]);
                } else {
                    $author = 'Аноним';
                    $content = $messageText;
                }
                $mailbox[] = [
                    'date' => $date,
                    'ndate' => $this->formatDate($date),
                    'author' => $author,
                    'content' => $content,
                    'toRead' => $toRead,
                    'id' => $id,
                ];
            }
        }
        usort($mailbox, function ($a, $b) {
            return strcmp($b['date'], $a['date']);
        });

        return $mailbox;
    }

    private function getOutcomeMailbox(string $html): array
    {
        $mailbox = [];
        $pattern = '/<span[^>]*title=["\'](\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+Письмо\s+(\d+)\s+к\s+([^:]+):\s*([^"\']*)["\'][^>]*>/u';
        if (preg_match_all($pattern, $html, $matches)) {
            foreach (array_keys($matches[0]) as $key) {
                $date = $matches[1][$key];
                $id = $matches[2][$key];
                $author = trim($matches[3][$key]);
                $content = trim($matches[4][$key]);

                $mailbox[] = [
                    'date' => $date,
                    'ndate' => $this->formatDate($date),
                    'author' => $author,
                    'content' => $content,
                    'id' => $id,
                ];
            }
        }
        usort($mailbox, function ($a, $b) {
            return strcmp($b['date'], $a['date']);
        });

        return $mailbox;
    }

    private function getNotes(string $html): string
    {
        $pattern = '<textarea name="notes" id="notes" class="textarea" maxlength="25000" style="width:550px; height: 400px;">';
        return explode('</textarea>', explode($pattern, $html)[1])[0];
    }

    private function formatDate(string $date): string
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
        if (!$dateTime) {
            return $date;
        }
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $messageDate = clone $dateTime;
        $messageDate->setTime(0, 0, 0);
        if ($messageDate == $today) {
            return $dateTime->format('H:i');
        }
        $months = [
            1 => 'Янв', 2 => 'Фев', 3 => 'Мар', 4 => 'Апр',
            5 => 'Май', 6 => 'Июн', 7 => 'Июл', 8 => 'Авг',
            9 => 'Сен', 10 => 'Окт', 11 => 'Ноя', 12 => 'Дек'
        ];
        $day = (int)$dateTime->format('d');
        $month = (int)$dateTime->format('m');
        return $day . ' ' . $months[$month];
    }

    public function getMessage(string $html): array
    {
        $result = ['text' => null, 'url' => null, 'date' => null];
        if (preg_match("/Syst\s*\(\s*'(.*?)'\s*\);?/su", $html, $matches)) {
            $content = $matches[1];
            if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/u', $content, $dateMatches)) {
                $result['date'] = $dateMatches[1];
            }
            $content = str_replace("\\'", "'", $content);
            $content = preg_replace('/<a[^>]*>.*?<\/a>/su', '', $content);
            $content = strip_tags($content);
            $result['text'] = trim($content);
        }
        if (preg_match('/window\.open\(\"([^"]+)/u', $html, $linkMatches)) {
            $result['url'] = $linkMatches[1];
        }
        return $result;
    }

    public function getMailForm(string $html): array
    {
        $result = ['notification' => '', 'timer' => 0, 'title' => '', 'message' => ''];
        $data = explode('</SCRIPT>', explode('<script>st = InsertTimer(', $html)[0]);
        if (sizeof($data) > 1) {
            $result['notification'] = trim(strip_tags($data[sizeof($data) - 1]));
        }
        if (preg_match('/InsertTimer\(\s*(\d+)\s*,/u', $html, $matches)) {
            $result['timer'] = (int)$matches[1];
        }
        if (preg_match("/GetBlockTitle\('([^']+)'\)[^<]*?<\/SCRIPT>\s*<div[^>]*style=\"text-align: left\"/su", $html, $matches)) {
            $result['title'] = $matches[1];
        }
        if (preg_match('/<div[^>]*style="text-align: left"[^>]*>(.+?)<\/div>/su', $html, $matches)) {
            $result['message'] = trim($matches[1]);
        }
        return $result;
    }

    public function getEffects(string $html): array
    {
        $awards = [];
        $effects = [];
        if (preg_match('/<TABLE><TR><TD><b>Награды:<\/b>.*?<\/TABLE>/s', $html, $awardsSection)) {
            $awards = $this->parseAwardsOrEffects($awardsSection[0], false);
        }
        if (preg_match('/<TABLE><TR><TD><b>Эффекты:<\/b>.*?<\/TABLE>/s', $html, $effectsSection)) {
            $effects = $this->parseAwardsOrEffects($effectsSection[0], true);
        }
        return [
            'awards' => $awards,
            'effects' => $effects,
        ];
    }

    private function parseAwardsOrEffects(string $content, bool $isEffects = false): array
    {
        $result = [];
        if (preg_match_all('/<IMG\s+src="([^"]+)"\s+title="([^"]+)"/s', $content, $matches)) {
            foreach (array_keys($matches[0]) as $key) {
                $src = $matches[1][$key];
                $titleText = $matches[2][$key];
                $image = str_replace('../', '', $src);
                $lines = array_map('trim', preg_split('/\r?\n/', $titleText));
                $lines = array_filter($lines);
                $lines = array_values($lines);
                // First line is the title
                $title = isset($lines[0]) ? rtrim($lines[0], ':') : '';
                // Find the effect line (starts with + or -)
                $effect = '';
                $time = '';
                foreach ($lines as $line) {
                    if (preg_match('/^[+\-]/', $line)) {
                        $effect = $line;
                        break;
                    }
                }
                $item = [
                    'image' => Defines::URL . $image,
                    'title' => $title,
                    'effect' => Effects::getImage($effect),
                    'time' => null,
                ];
                if ($isEffects) {
                    foreach ($lines as $line) {
                        if (preg_match('/Закончится через\s+(.+)/u', $line, $timeMatches)) {
                            $time = $timeMatches[1];
                            break;
                        }
                    }
                    $item['time'] = $time;
                }
                $result[] = $item;
            }
        }
        return $result;
    }

    public function getRatings(string $html): array
    {
        $ratings = [];
        $sections = preg_split('/<i>([^<]+):<\/i>/', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        $currentTitle = '';
        for ($i = 0; $i < count($sections); $i++) {
            if ($i % 2 == 1) {
                $currentTitle = $sections[$i];
            } else {
                $content = $sections[$i];
                $pattern = "/OutpR\(\s*'([^']*)',\s*'[^']*',\s*'([^']*)',\s*'[^']*',\s*[\\d.]+,\s*'([^']*)'\s*(?:,\s*'([^']*)'\s*)?\)/u";
                if (preg_match_all($pattern, $content, $matches)) {
                    foreach (array_keys($matches[0]) as $key) {
                        $imageInitial = $matches[1][$key];
                        $imageFinal = $matches[2][$key];
                        $rating = $matches[3][$key];
                        $extension = isset($matches[4][$key]) && $matches[4][$key] ? $matches[4][$key] : '.gif';
                        if ($imageInitial === 'no_medal') {
                            $imageInitial = '';
                        }
                        $ratings[] = [
                            'title' => $currentTitle,
                            'rating' => $rating,
                            'image_initial' => $imageInitial ? Defines::URL . 'images/medals/' . $imageInitial . '.gif' : '',
                            'image_final' => Defines::URL . 'images/medals/' . $imageFinal . $extension,
                        ];
                        $currentTitle = '';
                    }
                }
            }
        }
        return ['ratings' => $ratings];
    }

    public function getInfo(string $html): array
    {
        $info = [
            [
                'image' => Defines::URL . 'images/miscellaneous/hp.gif',
                'title' => 'Жизнь',
                'value' => null,
            ]
        ];
        $hpPattern = "/<TD id=hp1><font[^>]*>\\[<\\/font>([^<]+)<font[^>]*>\\]<\\/font><\\/TD>/u";
        if (preg_match($hpPattern, $html, $hpMatches)) {
            $info[0]['value'] = trim($hpMatches[1]);
        }
        $pattern = "/<image[^>]*src='([^']+)'[^>]*title='([^']+)'[^>]*><\\/td><td[^>]*>([^<]+)<\\/td>/u";
        if (preg_match_all($pattern, $html, $matches)) {
            foreach (array_keys($matches[0]) as $key) {
                $imagePath = $matches[1][$key];
                $title = $matches[2][$key];
                $value = trim($matches[3][$key]);
                $info[] = [
                    'image' => Defines::URL . str_replace('../', '', $imagePath),
                    'title' => $title,
                    'value' => $value,
                ];
            }
        }
        return ['info' => $info];
    }

    public function getArmy(string $html): array
    {
        $army = [];
        $pattern = "/InvArmyShow\\((\\d+),\\s*'([^']+)',\\s*\"(.*?)\"\\s*,\\s*(\\d+)\\s*,\\s*(\\d+)\\s*,\\s*'',\\s*'',\\s*(\\d+)\\s*\\)/s";
        if (preg_match_all($pattern, $html, $matches)) {
            foreach (array_keys($matches[0]) as $key) {
                $image = $matches[2][$key];
                $effectsHtml = $matches[3][$key];
                $count = $matches[4][$key];
                $id = $matches[5][$key];
                $selected = $matches[6][$key];
                $name = '';
                if (preg_match('/<b>([^<]+)<\/b>/', $effectsHtml, $nameMatch)) {
                    $name = $nameMatch[1];
                }
                $lvl = '';
                if (preg_match('/<b>(Уровень: \\d+)<\/b>/', $effectsHtml, $lvlMatch)) {
                    $lvl = $lvlMatch[1];
                }
                $effectsHtml = explode('<br>', $effectsHtml);
                array_shift($effectsHtml);
                array_shift($effectsHtml);
                $army[] = [
                    'name' => $name,
                    'image' => Defines::URL . 'images/armies/' . $image,
                    'lvl' => $lvl,
                    'count' => $count,
                    'id' => $id,
                    'selected' => $selected,
                    'effects' => str_replace(
                        ['src=/', '%ba%', '%oa%', '%sa%'],
                        ['src=' . Defines::URL],
                        implode(', ', $effectsHtml)
                    ),
                ];
            }
        }
        return ['army' => $army];
    }

    public function getStuff(string $html): array
    {
        $stuff = [];
        $pattern = "/<img[^>]*?onClick='unwear\\((\\d+)\\)'[^>]*?>/s";
        if (preg_match_all($pattern, $html, $matches)) {
            foreach (array_keys($matches[0]) as $key) {
                $imgTag = $matches[0][$key];
                $id = (int)$matches[1][$key];
                $title = '';
                if (preg_match("/title='(.*?)'/s", $imgTag, $titleMatch)) {
                    $title = $titleMatch[1];
                }
                $image = '';
                if (preg_match("/src=[\"']([^\"']+)[\"']/", $imgTag, $srcMatch)) {
                    $image = Defines::URL . str_replace('../', '', $srcMatch[1]);
                }

                if ($title && $image) {
                    $stuff[$id] = [
                        'id' => $id,
                        'title' => $title,
                        'image' => $image,
                    ];
                }
            }
        }
        $playerImage = '';
        if (preg_match('/<img[^>]*src="([^"]*\/images\/players\/[^"]*)"[^>]*>/u', $html, $playerImageMatch)) {
            $playerImage = Defines::URL . $playerImageMatch[1];
        }
        $playerMoney = '';
        if (preg_match('/<span id="playerMoney">([^<]+)<\/span>/u', $html, $moneyMatch)) {
            $playerMoney = trim($moneyMatch[1]);
        }
        $playerUM = '';
        if (preg_match('/<span id="playerUM">([^<]+)<\/span>/u', $html, $umMatch)) {
            $playerUM = trim($umMatch[1]);
        }
        return [
            'stuff' => $stuff,
            'image' => $playerImage,
            'playerMoney' => $playerMoney,
            'playerUM' => $playerUM,
        ];
    }

    public function getStuffItems(string $html): array
    {
        $items = [];
        $pattern = "/InvItemShow\\('([^']+)',\\s*'(.*?(?<!\\\\))',\\s*(\\d+),\\s*(\\d+),\\s*'[^']*',\\s*'([^']*)'/s";

        if (preg_match_all($pattern, $html, $matches)) {
            foreach (array_keys($matches[0]) as $key) {
                $image = $matches[1][$key];
                $count = (int)$matches[3][$key];
                $id = (int)$matches[4][$key];
                $wearArgument = $matches[5][$key];
                $parts = preg_split('/<br>/i', $matches[2][$key]);
                $title = '';
                if (isset($parts[0]) && !empty($parts[0])) {
                    $title = strip_tags($parts[0]);
                }
                $level = '';
                if (isset($parts[1]) && !empty($parts[1])) {
                    $level = strip_tags($parts[1]);
                }
                $description = '';
                if (count($parts) > 2) {
                    $descriptionParts = array_slice($parts, 2);
                    $description = implode('<br>', $descriptionParts);
                }
                $items[] = [
                    'id' => $id,
                    'image' => Defines::URL . 'images/items/' . $image,
                    'name' => $title,
                    'lvl' => $level,
                    'effects' => str_replace('src=/', 'src=' . Defines::URL, $description),
                    'count' => $count,
                    'wearable' => $wearArgument === 'wear',
                ];
            }
        }
        return ['items' => $items];
    }
}
