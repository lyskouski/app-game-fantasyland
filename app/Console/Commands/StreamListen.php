<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Console\Commands;

use App\Jobs\ListenStream;
use Illuminate\Console\Command;

class StreamListen extends Command {
    protected $signature = 'app:stream-listen';
    protected $description = 'Listen to the stream (chat) and process incoming data';

    public function handle(): int
    {
        ListenStream::dispatch();

        return self::SUCCESS;
    }
}
