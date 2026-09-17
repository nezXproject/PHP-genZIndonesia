<?php
namespace GenZ\Nodes;

class ForStatement implements Node
{
    public function __construct(
        public string $varName,
        public Node $start,
        public Node $end,
        public Block $body
    ) {}
}
