<?php
namespace GenZ\Nodes;

class IfStatement implements Node
{
    public function __construct(
        public Node $condition,
        public Block $thenBranch,
        public ?Block $elseBranch = null
    ) {}
}
