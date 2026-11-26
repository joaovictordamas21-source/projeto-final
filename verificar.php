<?php
// Página de diagnóstico: abra http://localhost/levelzone/verificar.php (apague este arquivo ao publicar o site).
require __DIR__ . '/config/config.php';
header('Content-Type: text/html; charset=utf-8');
$ok = fn($b) => $b ? '<b style="color:green">OK</b>' : '<b style="color:red">FALTA</b>';
echo '<h1>Verificação do LevelZone</h1><p>PHP ' . PHP_VERSION . '</p><ul>';
foreach (['pdo_mysql', 'mbstring', 'openssl', 'fileinfo'] as $x) echo "<li>Extensão $x: " . $ok(extension_loaded($x)) . '</li>';
echo '<li>Pasta img/produtos gravável: ' . $ok(is_writable(__DIR__ . '/img/produtos')) . '</li>';
try {
    $bd = new PDO('mysql:host=' . BD_HOST . ';dbname=' . BD_NOME . ';charset=utf8mb4', BD_USUARIO, BD_SENHA);
    echo '<li>Conexão com o banco "' . BD_NOME . '": ' . $ok(true) . '</li>';
    $tem = $bd->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    foreach (['usuarios', 'categorias', 'produtos', 'variacoes', 'fornecedores', 'clientes', 'entradas', 'itens_entrada', 'vendas', 'itens_venda', 'notas_fiscais', 'mensagens'] as $t)
        echo "<li>Tabela $t: " . $ok(in_array($t, $tem)) . '</li>';
} catch (Throwable $e) { echo '<li>Conexão com o banco: ' . $ok(false) . ' — ' . htmlspecialchars($e->getMessage()) . '</li>'; }
echo '</ul>';
