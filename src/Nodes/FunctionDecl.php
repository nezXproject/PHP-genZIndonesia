<?php
namespace GenZ\Nodes;

class FunctionDecl implements Node
{
    /** @param string[] $params */
    public function __construct(
        public string $name,
        public array $params,
        public Block $body
    ) {}
}
