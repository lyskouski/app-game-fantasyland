<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Console\Commands;

use App\Jobs\ListenStream;
use App\Providers\AppProxyProvider;
use App\Settings\Defines;
use Illuminate\Console\Command;

class StreamListen extends Command {
    protected $signature = 'app:stream-listen';
    protected $description = 'Listen to the stream (chat) and process incoming data';

    public function handle(): int
    {
        $chMain = (new AppProxyProvider())->boot(Defines::URL . '/ch/chmain.php');
        ListenStream::start(ListenStream::sessionIdFrom($chMain));

        return self::SUCCESS;
    }
}
