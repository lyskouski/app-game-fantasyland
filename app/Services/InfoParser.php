<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

use App\Settings\Defines;

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
            'test' => $html,
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
}
