<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Providers;

class AppContentWrapper
{
    public function __construct(public string $data)
    {
    }

    public function get() {
        $data = $this->data;
        $data = str_replace('src="../', 'src="https://www.fantasyland.ru/', $data);
        $data = str_replace('SRC="../', 'src="https://www.fantasyland.ru/', $data);
        $data = str_replace('src=../', 'src=https://www.fantasyland.ru/', $data);
        $data = str_replace('src="/', 'src="https://www.fantasyland.ru/', $data);
        $data = str_replace('src=/', 'src=https://www.fantasyland.ru/', $data);
        $data = str_replace("BACKGROUND='../", "background='https://www.fantasyland.ru/", $data);
        $data = str_replace('BACKGROUND="../', 'background="https://www.fantasyland.ru/', $data);
        $data = str_replace('background="../', 'background="https://www.fantasyland.ru/', $data);
        return $data;
    }
}