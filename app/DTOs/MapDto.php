<?php

namespace App\DTOs;

final readonly class MapDto
{
    const TABLE = 'map';

    public function __construct(
        public ?string $id = null,
        public ?int $locationId = null,
        public ?int $placeId = null,
        public ?int $z = null,
        public ?int $x = null,
        public ?int $y = null,
        public ?int $type = null,
        public ?string $loc = null,
        public ?string $info = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {
        //
    }
}
