<?php
namespace GenZ\Nodes;

class ReturnStatement implements Node
{
    public function __construct(public ?Node $value = null) {}
}
