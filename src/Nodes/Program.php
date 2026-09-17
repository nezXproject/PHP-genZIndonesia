<?php
namespace GenZ\Nodes;

class Program implements Node
{
    /** @param Node[] $statements */
    public function __construct(public array $statements) {}
}
