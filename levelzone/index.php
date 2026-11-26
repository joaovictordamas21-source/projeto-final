<?php
session_start();
require __DIR__ . '/config/config.php';
spl_autoload_register(function ($c) {
    foreach (['core', 'controllers', 'models'] as $d) {
        $f = __DIR__ . "/app/$d/$c.php";
        if (is_file($f)) { require $f; return; }
    }
});
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['t'] ?? '', $_POST['_t'] ?? '')) {
    http_response_code(400);
    exit('Sessão expirada ou arquivo grande demais. Volte e tente novamente.');
}
$p = explode('/', trim($_GET['url'] ?? 'loja', '/'));
$classe = ucfirst($p[0]) . 'Controller';
$acao = $p[1] ?? 'index';
if (!preg_match('/^[a-z]+$/i', $p[0]) || !class_exists($classe) || !is_callable([$obj = new $classe, $acao])) {
    http_response_code(404);
    exit('Página não encontrada.');
}
call_user_func_array([$obj, $acao], array_slice($p, 2));
