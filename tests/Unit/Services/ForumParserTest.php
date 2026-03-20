<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

// php artisan test tests/Unit/Services/ForumParserTest.php

namespace Tests\Unit\Services;

use App\Services\ForumParser;
use PHPUnit\Framework\TestCase;

class ForumParserTest extends TestCase
{
    private ForumParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new ForumParser();
    }

    public static function forumDataProvider(): \Generator
    {
        yield 'First page' => [
            'html' => '<SCRIPT>paging( 36, 25, 1, 3, 1, 533630);</SCRIPT>',
            'shouldNotContain' => [],
            'shouldContain' => ['1', '2'],
            'description' => 'Check two pages topic starting from the first page'
        ];
        yield 'End page' => [
            'html' => '<SCRIPT>paging( 7803, 25, 313, 3, 1, 452972);</SCRIPT>',
            'shouldNotContain' => ['>>', 'Конец'],
            'shouldContain' => [
                'Начало', '<<', '294', '295', '296', '297', '298', '299', '300',
                '301', '302', '303', '304', '305', '306', '307', '308', '309',
                '310', '311', '312', '313'
            ],
            'description' => 'Opening last page of the topic should show all previous pages and navigation buttons'
        ];
        yield 'Middle page (293)' => [
            'html' => '<SCRIPT>paging( 7803, 25, 293, 3, 1, 452972);</SCRIPT>',
            'shouldNotContain' => [],
            'shouldContain' => [
                'Начало', '<<', '274', '275', '276', '277', '278', '279', '280',
                '281', '282', '283', '284', '285', '286', '287', '288', '289',
                '290', '291', '292', '293', '>>', 'Конец'
            ],
            'description' => 'Opening middle page of the topic should show all navigation buttons'
        ];
    }

    /**
     * @dataProvider forumDataProvider
     */
    public function testParsePagingBlock(string $html, array $shouldNotContain, array $shouldContain, string $description): void
    {
        $result = $this->parser->parseTopic($html)['pages'];
        $this->assertNotEmpty($result, $description);

        $titles = array_column($result, 'title');

        foreach ($shouldContain as $item) {
            $this->assertContains($item, $titles, $description);
        }

        foreach ($shouldNotContain as $item) {
            $this->assertNotContains($item, $titles, $description);
        }
    }
}

