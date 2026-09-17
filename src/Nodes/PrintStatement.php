<?php
namespace GenZ\Nodes;

class PrintStatement implements Node
{
    public function __construct(public Node $expression) {}
}
