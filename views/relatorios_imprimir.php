<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><title>Relatório Gerencial — LevelZone</title>
<style>body{font-family:Arial,sans-serif;margin:32px;color:#111}h1{border-bottom:2px solid #333;padding-bottom:6px}table{border-collapse:collapse;width:100%;margin-bottom:18px}th,td{border:1px solid #bbb;padding:6px 8px;text-align:left;font-size:14px}th{background:#eee}.dica{background:#eef3ff;border:1px solid #9bb3ff;padding:8px 12px;margin-bottom:16px}@media print{.dica{display:none}}</style></head>
<body><p class="dica">Para imprimir ou salvar em PDF, pressione <b>Ctrl+P</b>.</p>
<h1>Relatório Gerencial — LevelZone</h1><p>Gerado em: <?= date('d/m/Y H:i') ?></p>
<h3>Jogos mais vendidos</h3><table><tr><th>Jogo</th><th>Variação</th><th>Qtde. vendida</th></tr>
<?php foreach ($topo as $r): ?><tr><td><?= e($r['jogo']) ?></td><td><?= e($r['plataforma'] . ' · ' . $r['edicao']) ?></td><td><?= (int)$r['qtd'] ?></td></tr><?php endforeach; ?></table>
<h3>Fornecedores</h3><table><tr><th>Fornecedor</th><th>Entradas</th><th>Valor total</th></tr>
<?php foreach ($fornecedores as $r): ?><tr><td><?= e($r['nome']) ?></td><td><?= (int)$r['qtd'] ?></td><td><?= dinheiro($r['total']) ?></td></tr><?php endforeach; ?></table>
<h3>Estoque baixo</h3><table><tr><th>Jogo</th><th>SKU</th><th>Qtde.</th><th>Mínimo</th></tr>
<?php foreach ($baixo as $r): ?><tr><td><?= e($r['jogo'] . ' · ' . $r['plataforma'] . ' · ' . $r['edicao']) ?></td><td><?= e($r['sku']) ?></td><td><?= (int)$r['estoque'] ?></td><td><?= (int)$r['estoque_minimo'] ?></td></tr><?php endforeach; ?></table>
</body></html>
