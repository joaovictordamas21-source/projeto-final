<div class="topo"><h1>Relatórios</h1>
  <div class="linha"><a class="btn" target="_blank" href="<?= url('relatorios/imprimir') ?>">Exportar PDF</a><a class="btn btn-sec" href="<?= url('relatorios/excel') ?>">Exportar Excel</a></div></div>
<p class="mudo pequeno">PDF: abra a página e pressione Ctrl+P, escolhendo "Salvar como PDF". Excel: baixa uma planilha (CSV) que o Excel abre direto.</p>
<div class="grade2">
  <div class="cartao"><h2>Jogos mais vendidos</h2><table class="tabela"><tr><th>Jogo</th><th>Variação</th><th class="dir">Qtde. vendida</th></tr>
    <?php foreach ($topo as $r): ?><tr><td><?= e($r['jogo']) ?></td><td><?= e($r['plataforma'] . ' · ' . $r['edicao']) ?></td><td class="dir"><?= (int)$r['qtd'] ?></td></tr>
    <?php endforeach; if (!$topo): ?><tr><td colspan="3" class="mudo">Sem vendas ainda.</td></tr><?php endif; ?></table></div>
  <div class="cartao"><h2>Fornecedores</h2><table class="tabela"><tr><th>Fornecedor</th><th>Entradas</th><th class="dir">Valor total</th></tr>
    <?php foreach ($fornecedores as $r): ?><tr><td><?= e($r['nome']) ?></td><td><?= (int)$r['qtd'] ?></td><td class="dir"><?= dinheiro($r['total']) ?></td></tr><?php endforeach; ?></table></div>
  <div class="cartao"><h2>Entradas recentes</h2><table class="tabela"><tr><th>ID</th><th>Fornecedor</th><th>Data</th><th class="dir">Valor</th></tr>
    <?php foreach ($entradas as $r): ?><tr><td>#<?= $r['id'] ?></td><td><?= e($r['fornecedor']) ?></td><td><?= data_hora($r['criada_em']) ?></td><td class="dir"><?= dinheiro($r['total']) ?></td></tr>
    <?php endforeach; if (!$entradas): ?><tr><td colspan="4" class="mudo">Sem entradas ainda.</td></tr><?php endif; ?></table></div>
  <div class="cartao <?= $baixo ? 'alerta-borda' : '' ?>"><h2>Estoque baixo</h2><table class="tabela"><tr><th>Jogo</th><th>SKU</th><th>Qtde.</th><th>Mínimo</th></tr>
    <?php foreach ($baixo as $r): ?><tr><td><?= e($r['jogo'] . ' · ' . $r['plataforma'] . ' · ' . $r['edicao']) ?></td><td class="mono"><?= e($r['sku']) ?></td><td><?= (int)$r['estoque'] ?></td><td><?= (int)$r['estoque_minimo'] ?></td></tr>
    <?php endforeach; if (!$baixo): ?><tr><td colspan="4" class="mudo">Nenhum item abaixo do estoque mínimo.</td></tr><?php endif; ?></table></div>
</div>
