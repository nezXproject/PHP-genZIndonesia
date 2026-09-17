<?php
namespace GenZ\Nodes;

class WhileStatement implements Node
{
    public function __construct(
        public Node $condition,
        public Block $body
    ) {}
}
