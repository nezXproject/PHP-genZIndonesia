<?php
namespace GenZ\Nodes;

class Block implements Node
{
    /** @param Node[] $statements */
    public function __construct(public array $statements) {}
}
