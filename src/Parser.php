<?php
namespace GenZ;

use GenZ\Nodes\*;

class Parser
{
    private int $pos = 0;

    /** @param Token[] $tokens */
    public function __construct(private array $tokens) {}

    public function parse(): Nodes\Program
    {
        $statements = [];
        while (!$this->is('EOF')) {
            $statements[] = $this->statement();
        }
        return new Nodes\Program($statements);
    }

    private function statement(): Nodes\Node
    {
        return match (true) {
            $this->is('GAS')      => $this->assignment(),
            $this->is('CETAK')    => $this->printStatement(),
            $this->is('CEK')      => $this->ifStatement(),
            $this->is('PUTAR')    => $this->whileStatement(),
            $this->is('GASPOL')   => $this->forStatement(),
            $this->is('MANTAP')   => $this->functionDecl(),
            $this->is('BALIKIN')  => $this->returnStatement(),
            $this->is('{')        => $this->block(),
            default => throw new \RuntimeException(
                "Statement tidak dikenal: {$this->current()->type} di baris {$this->current()->line}"
            ),
        };
    }

    private function assignment(): Nodes\Assignment
    {
        $this->consume('GAS');
        $name = $this->consume('IDENTIFIER')->value;
        $this->consume('=');
        $value = $this->expression();
        $this->consume(';');
        return new Nodes\Assignment($name, $value);
    }

    private function printStatement(): Nodes\PrintStatement
    {
        $this->consume('CETAK');
        $expr = $this->expression();
        $this->consume(';');
        return new Nodes\PrintStatement($expr);
    }

    private function ifStatement(): Nodes\IfStatement
    {
        $this->consume('CEK');
        $this->consume('(');
        $condition = $this->expression();
        $this->consume(')');
        $then = $this->block();

        $else = null;

        // cekLagi atau kalauNggak
        if ($this->is('CEKLAGI')) {
            $this->advance();
            // else-if: bungkus sebagai nested IfStatement dalam Block
            $else = new Nodes\Block([$this->ifStatement()]);
        } elseif ($this->is('KALAU_NGGAK')) {
            $this->advance();
            $else = $this->block();
        }

        return new Nodes\IfStatement($condition, $then, $else);
    }

    private function whileStatement(): Nodes\WhileStatement
    {
        $this->consume('PUTAR');
        $this->consume('(');
        $condition = $this->expression();
        $this->consume(')');
        $body = $this->block();
        return new Nodes\WhileStatement($condition, $body);
    }

    private function forStatement(): Nodes\ForStatement
    {
        $this->consume('GASPOL');
        $this->consume('(');
        $varName = $this->consume('IDENTIFIER')->value;
        $this->consume('=');
        $start = $this->expression();
        $this->consume(';');
        $end = $this->expression();
        $this->consume(')');
        $body = $this->block();
        return new Nodes\ForStatement($varName, $start, $end, $body);
    }

    private function functionDecl(): Nodes\FunctionDecl
    {
        $this->consume('MANTAP');
        $name = $this->consume('IDENTIFIER')->value;
        $this->consume('(');

        $params = [];
        while (!$this->is(')')) {
            $params[] = $this->consume('IDENTIFIER')->value;
            if ($this->is(',')) $this->advance();
        }
        $this->consume(')');

        $body = $this->block();
        return new Nodes\FunctionDecl($name, $params, $body);
    }

    private function returnStatement(): Nodes\ReturnStatement
    {
        $this->consume('BALIKIN');
        $value = null;
        if (!$this->is(';')) {
            $value = $this->expression();
        }
        $this->consume(';');
        return new Nodes\ReturnStatement($value);
    }

    private function block(): Nodes\Block
    {
        $this->consume('{');
        $statements = [];
        while (!$this->is('}') && !$this->is('EOF')) {
            $statements[] = $this->statement();
        }
        $this->consume('}');
        return new Nodes\Block($statements);
    }

    // ============ EKSPRESI (dengan prioritas) ============

    private function expression(): Nodes\Node
    {
        return $this->logicalOr();
    }

    private function logicalOr(): Nodes\Node
    {
        $node = $this->logicalAnd();
        while ($this->is('||') || $this->is('ATAU')) {
            $op = $this->advance()->type === 'ATAU' ? '||' : '||';
            $node = new Nodes\BinaryOp($node, '||', $this->logicalAnd());
        }
        return $node;
    }

    private function logicalAnd(): Nodes\Node
    {
        $node = $this->equality();
        while ($this->is('&&') || $this->is('DAN')) {
            $this->advance();
            $node = new Nodes\BinaryOp($node, '&&', $this->equality());
        }
        return $node;
    }

    private function equality(): Nodes\Node
    {
        $node = $this->comparison();
        while ($this->is('==') || $this->is('!=')) {
            $op = $this->advance()->type;
            $node = new Nodes\BinaryOp($node, $op, $this->comparison());
        }
        return $node;
    }

    private function comparison(): Nodes\Node
    {
        $node = $this->term();
        while ($this->is('<') || $this->is('>') || $this->is('<=') || $this->is('>=')) {
            $op = $this->advance()->type;
            $node = new Nodes\BinaryOp($node, $op, $this->term());
        }
        return $node;
    }

    private function term(): Nodes\Node
    {
        $node = $this->factor();
        while ($this->is('+') || $this->is('-')) {
            $op = $this->advance()->type;
            $node = new Nodes\BinaryOp($node, $op, $this->factor());
        }
        return $node;
    }

    private function factor(): Nodes\Node
    {
        $node = $this->unary();
        while ($this->is('*') || $this->is('/')) {
            $op = $this->advance()->type;
            $node = new Nodes\BinaryOp($node, $op, $this->unary());
        }
        return $node;
    }

    private function unary(): Nodes\Node
    {
        if ($this->is('!') || $this->is('BUKAN')) {
            $this->advance();
            return new Nodes\UnaryOp('!', $this->unary());
        }
        if ($this->is('-')) {
            $this->advance();
            return new Nodes\UnaryOp('-', $this->unary());
        }
        return $this->primary();
    }

    private function primary(): Nodes\Node
    {
        $token = $this->current();

        if ($token->type === 'NUMBER' || $token->type === 'STRING') {
            $this->advance();
            return new Nodes\Literal($token->value);
        }

        if ($token->type === 'TRUE') { $this->advance(); return new Nodes\Literal(true); }
        if ($token->type === 'FALSE') { $this->advance(); return new Nodes\Literal(false); }

        if ($token->type === 'IDENTIFIER') {
            $name = $token->value;
            $this->advance();

            // Cek apakah ini function call
            if ($this->is('(')) {
                $this->advance();
                $args = [];
                while (!$this->is(')')) {
                    $args[] = $this->expression();
                    if ($this->is(',')) $this->advance();
                }
                $this->consume(')');
                return new Nodes\FunctionCall($name, $args);
            }

            return new Nodes\Variable($name);
        }

        if ($token->type === '(') {
            $this->advance();
            $expr = $this->expression();
            $this->consume(')');
            return $expr;
        }

        throw new \RuntimeException("Ekspresi tidak valid: {$token->type} di baris {$token->line}");
    }

    // ============ UTILITAS ============

    private function is(string $type): bool
    {
        return $this->current()->type === $type;
    }

    private function current(): Token
    {
        return $this->tokens[$this->pos] ?? new Token('EOF', null);
    }

    private function advance(): Token
    {
        return $this->tokens[$this->pos++];
    }

    private function consume(string $type): Token
    {
        if (!$this->is($type)) {
            throw new \RuntimeException(
                "Butuh '{$type}', dapat '{$this->current()->type}' di baris {$this->current()->line}"
            );
        }
        return $this->advance();
    }
}
