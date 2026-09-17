<?php
namespace GenZ;

class Token
{
    public function __construct(
        public readonly string $type,
        public readonly mixed $value = null,
        public readonly int $line = 1
    ) {}
}
