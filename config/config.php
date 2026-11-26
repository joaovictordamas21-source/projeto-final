<?php
date_default_timezone_set('America/Sao_Paulo');
define('BD_HOST', 'localhost');
define('BD_NOME', 'levelzone');
define('BD_USUARIO', 'root');
define('BD_SENHA', '');
define('URL_BASE', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));
// Códigos que a equipe digita para se cadastrar como vendedor ou administrador. TROQUE por códigos seus.
define('CODIGO_VENDEDOR', 'VENDA2026');
define('CODIGO_ADMIN', 'ADMIN2026');
// Pix: TROQUE pela chave Pix real da loja (e-mail, telefone, CPF/CNPJ ou chave aleatória). Nome e cidade sem acentos.
define('LOJA_NOME', 'LEVELZONE LTDA');
define('LOJA_CNPJ', '00.000.000/0001-00');
define('LOJA_ENDERECO', 'Rua Principal, 123 - Centro - Nova Iguaçu/RJ');

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function dinheiro($v) { return 'R$ ' . number_format((float)$v, 2, ',', '.'); }
function url($p = '') { return URL_BASE . '/' . ltrim($p, '/'); }
function data_hora($s) { return date('d/m/Y H:i', strtotime($s)); }
function capa($imagem, $id) { return $imagem ? url('img/produtos/' . $imagem) : url('capa/ver/' . (int)$id); }
function csrf() {
    $_SESSION['t'] ??= bin2hex(random_bytes(16));
    return '<input type="hidden" name="_t" value="' . $_SESSION['t'] . '">';
}
