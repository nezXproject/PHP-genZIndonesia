<?php
namespace GenZ\Nodes;

class Variable implements Node
{
    public function __construct(public string $name) {}
}
