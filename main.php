<?php

/**
 * @param $src
 * @return array
 */
function tokenizer($src): array {
    return preg_split("/\s+|([,()])/", $src, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
}

/**
 * @param $tokens
 * @return array
 */
function parse(&$tokens): array {
    $ast = [];
    while ($tokens) {
        $token = array_shift($tokens);
        if ($token === "(") {
            $ast[] = parse($tokens);
        } elseif ($token === ")") {
            break;
        } elseif ($token !== "" && $token !== ",") {
            $ast[] = $token;
        }
    }
    return $ast;
}

/**
 * @param $ast
 * @return float|int|mixed
 * @throws Exception
 */
function interpret($ast) : mixed {
    if (is_array($ast)) {
        $func = array_shift($ast);
        $args = array_map('interpret', $ast);
        switch ($func) {
            case 'print':
                echo implode(' ', $args);
                break;
            case '+':
                return array_sum($args);
            case '*':
                return array_product($args);
            default:
                throw new Exception("Unknown function: $func");
        }
    }
    return $ast;
}

$src = "print ( + 5 ( * 4 2 ) )";
$tokens = tokenizer($src);
$ast = parse($tokens);
interpret($ast);
