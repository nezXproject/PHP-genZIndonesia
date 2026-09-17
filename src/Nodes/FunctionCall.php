<?php
namespace GenZ\Nodes;

class FunctionCall implements Node
{
    /** @param Node[] $args */
    public function __construct(
        public string $name,
        public array $args
    ) {}
}
