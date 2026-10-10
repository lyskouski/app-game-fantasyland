<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

use App\Settings\Defines;

class PreyParser
{
    public function parse(string $html) {
        $content = '';
        if (preg_match('/<HR>(.*?)<\/TD>\s*<\/TR>\s*<\/TABLE>/is', $html, $matches)) {
            $content = preg_replace('/<script\b.*?<\/script>/is', '', $matches[1]);
            $content = preg_replace('/<\/?a\b[^>]*>/i', '', $content);
            $blocks = preg_split('/<\/center>/i', $content);
            foreach ($blocks as $i => $block) {
                $block = preg_replace('/<br\s*\/?>/i', $i === 0 ? "\x01" : ' ', preg_replace('/\s+/', ' ', $block));
                $block = html_entity_decode(strip_tags($block), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $blocks[$i] = $block;
            }
            $content = str_replace("\u{A0}", ' ', implode(' ', $blocks));
            $content = preg_replace('/\s+/', ' ', $content);
            $content = trim(preg_replace('/ ?\x01 ?/', "\n ", trim($content, " \x01")));
        }
        $image = '';
        if (preg_match('/<image[^>]*src=(["\'])([^"\']+)\1/i', $html, $imgMatch)) {
           $image = str_replace('..', '', $imgMatch[2]);
        }
        $timer = 0;
        if (preg_match('/InsertTimer2\\s*\\(\\s*(\\d+)/', $html, $matches)) {
            $timer = (int)$matches[1];
        }
        return ['data' => $content, 'image' => $image, 'timer' => $timer];
    }
}
