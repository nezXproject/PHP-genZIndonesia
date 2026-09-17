<?php
namespace GenZ;

use GenZ\Nodes\*;

class ReturnSignal extends \Exception
{
    public function __construct(public mixed $value) {}
}

class Interpreter
{
    /** @var array<string, mixed> */
    private array $globals = [];

    /** @var array<string, array{params: string[], body: Nodes\Block}> */
    private array $functions = [];

    public function run(Nodes\Program $program): void
    {
        foreach ($program->statements as $stmt) {
            $this->execute($stmt, $this->globals);
        }
    }

    private function execute(Nodes\Node $node, array &$scope): mixed
    {
        return match (true) {
            $node instanceof Nodes\Assignment     => $this->handleAssignment($node, $scope),
            $node instanceof Nodes\PrintStatement => $this->handlePrint($node, $scope),
            $node instanceof Nodes\Block          => $this->handleBlock($node, $scope),
            $node instanceof Nodes\IfStatement    => $this->handleIf($node, $scope),
            $node instanceof Nodes\WhileStatement => $this->handleWhile($node, $scope),
            $node instanceof Nodes\ForStatement   => $this->handleFor($node, $scope),
            $node instanceof Nodes\FunctionDecl   => $this->handleFunctionDecl($node),
            $node instanceof Nodes\ReturnStatement=> throw new ReturnSignal(
                $node->value ? $this->evaluate($node->value, $scope) : null
            ),
            default => $this->evaluate($node, $scope),
        };
    }

    private function evaluate(Nodes\Node $node, array &$scope): mixed
    {
        return match (true) {
            $node instanceof Nodes\Literal      => $node->value,
            $node instanceof Nodes\Variable     => $this->lookup($node->name, $scope),
            $node instanceof Nodes\BinaryOp     => $this->evaluateBinaryOp($node, $scope),
            $node instanceof Nodes\UnaryOp      => $this->evaluateUnaryOp($node, $scope),
            $node instanceof Nodes\FunctionCall => $this->evaluateFunctionCall($node, $scope),
            default => throw new \RuntimeException("Node tidak bisa dievaluasi: " . get_class($node)),
        };
    }

    private function handleAssignment(Nodes\Assignment $node, array &$scope): mixed
    {
        $value = $this->evaluate($node->value, $scope);
        $scope[$node->name] = $value;
        return $value;
    }

    private function handlePrint(Nodes\PrintStatement $node, array &$scope): void
    {
        $value = $this->evaluate($node->expression, $scope);
        echo $this->stringify($value) . PHP_EOL;
    }

    private function handleBlock(Nodes\Block $node, array &$scope): void
    {
        foreach ($node->statements as $stmt) {
            $this->execute($stmt, $scope);
        }
    }

    private function handleIf(Nodes\IfStatement $node, array &$scope): void
    {
        if ($this->truthy($this->evaluate($node->condition, $scope))) {
            $this->handleBlock($node->thenBranch, $scope);
        } elseif ($node->elseBranch !== null) {
            $this->handleBlock($node->elseBranch, $scope);
        }
    }

    private function handleWhile(Nodes\WhileStatement $node, array &$scope): void
    {
        while ($this->truthy($this->evaluate($node->condition, $scope))) {
            $this->handleBlock($node->body, $scope);
        }
    }

    private function handleFor(Nodes\ForStatement $node, array &$scope): void
    {
        $start = $this->evaluate($node->start, $scope);
        $end   = $this->evaluate($node->end, $scope);

        for ($i = $start; $i <= $end; $i++) {
            $scope[$node->varName] = $i;
            $this->handleBlock($node->body, $scope);
        }
    }

    private function handleFunctionDecl(Nodes\FunctionDecl $node): void
    {
        $this->functions[$node->name] = [
            'params' => $node->params,
            'body'   => $node->body,
        ];
    }

    private function evaluateFunctionCall(Nodes\FunctionCall $node, array &$scope): mixed
    {
        if (!isset($this->functions[$node->name])) {
            throw new \RuntimeException("Fungsi '{$node->name}' tidak didefinisikan");
        }

        $fn = $this->functions[$node->name];
        $args = array_map(fn($a) => $this->evaluate($a, $scope), $node->args);

        if (count($args) !== count($fn['params'])) {
            throw new \RuntimeException(
                "Fungsi '{$node->name}' butuh " . count($fn['params']) . " argumen, dapat " . count($args)
            );
        }

        // Scope baru untuk fungsi
        $localScope = [];
        foreach ($fn['params'] as $idx => $paramName) {
            $localScope[$paramName] = $args[$idx];
        }

        try {
            $this->handleBlock($fn['body'], $localScope);
        } catch (ReturnSignal $ret) {
            return $ret->value;
        }

        return null;
    }

    private function evaluateBinaryOp(Nodes\BinaryOp $node, array &$scope): mixed
    {
        // Short-circuit untuk logika
        if ($node->operator === '&&') {
            $left = $this->evaluate($node->left, $scope);
            if (!$this->truthy($left)) return false;
            return $this->truthy($this->evaluate($node->right, $scope));
        }
        if ($node->operator === '||') {
            $left = $this->evaluate($node->left, $scope);
            if ($this->truthy($left)) return true;
            return $this->truthy($this->evaluate($node->right, $scope));
        }

        $left  = $this->evaluate($node->left, $scope);
        $right = $this->evaluate($node->right, $scope);

        return match ($node->operator) {
            '+'  => $left + $right,
            '-'  => $left - $right,
            '*'  => $left * $right,
            '/'  => $right == 0 ? throw new \RuntimeException("Bagi nol!") : $left / $right,
            '==' => $left == $right,
            '!=' => $left != $right,
            '<'  => $left < $right,
            '>'  => $left > $right,
            '<=' => $left <= $right,
            '>=' => $left >= $right,
            default => throw new \RuntimeException("Operator tidak dikenal: {$node->operator}"),
        };
    }

    private function evaluateUnaryOp(Nodes\UnaryOp $node, array &$scope): mixed
    {
        $value = $this->evaluate($node->operand, $scope);
        return match ($node->operator) {
            '!' => !$this->truthy($value),
            '-' => -$value,
            default => throw new \RuntimeException("Unary operator tidak dikenal: {$node->operator}"),
        };
    }

    private function lookup(string $name, array &$scope): mixed
    {
        if (array_key_exists($name, $scope)) {
            return $scope[$name];
        }
        throw new \RuntimeException("Variabel '{$name}' belum didefinisikan");
    }

    private function truthy(mixed $value): bool
    {
        if (is_bool($value)) return $value;
        if (is_null($value)) return false;
        if (is_numeric($value)) return $value != 0;
        if (is_string($value)) return $value !== '';
        return true;
    }

    private function stringify(mixed $value): string
    {
        if (is_bool($value)) return $value ? 'gasTerus' : 'gabisa';
        if (is_null($value)) return 'kosong';
        return (string) $value;
    }
}
