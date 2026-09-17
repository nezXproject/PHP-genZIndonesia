<?php
namespace GenZ\Nodes;

class UnaryOp implements Node
{
    public function __construct(
        public string $operator,
        public Node $operand
    ) {}
}
