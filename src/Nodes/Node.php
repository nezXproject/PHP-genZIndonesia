<?php
namespace GenZ\Nodes;

class Literal implements Node
{
    public function __construct(public mixed $value) {}
}
