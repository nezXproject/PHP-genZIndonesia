<?php
namespace GenZ\Nodes;

class Assignment implements Node
{
    public function __construct(
        public string $name,
        public Node $value
    ) {}
}
