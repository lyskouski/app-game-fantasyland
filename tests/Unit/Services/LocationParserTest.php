<?php

// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace Tests\Unit\Services;

use App\Services\LocationParser;
use PHPUnit\Framework\TestCase;

class LocationParserTest extends TestCase
{
    public function test_on_place_parses_additional_go_to_link(): void
    {
        $html = <<<'HTML'
<TR><TD><A HREF='javascript:document.location_select.additional.value=1; goTo(14);'><image border=0 height=20 width=20 src="/images/miscellaneous/move_forward.gif" onmouseover="this.src = '/images/miscellaneous/move_forward_s.gif';" onmouseout="this.src = '/images/miscellaneous/move_forward.gif';"></A></TD><TD>Заплатить, и пройти дальше</TD><TR>
HTML;

        $result = (new LocationParser)->onPlace($html);

        $this->assertSame([
            [
                'loc' => 'Заплатить, и пройти дальше',
                'id' => 14,
                'additional' => 1,
            ],
        ], $result['map']);
    }
}
