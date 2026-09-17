<?php
namespace GenZ\Nodes;

class BinaryOp implements Node
{
    public function __construct(
        public Node $left,
        public string $operator,
        public Node $right
    ) {}
}
