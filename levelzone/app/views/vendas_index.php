<div class="topo"><h1>Vendas (balcão)</h1></div>
<form method="post" action="<?= url('vendas/salvar') ?>" class="cartao">
  <?= csrf() ?>
  <h2>Nova venda</h2>
  <label>Cliente
    <select name="cliente" required><option value="">Selecione...</option>
    <?php foreach ($clientes as $c): ?><option value="<?= $c['id'] ?>" <?= $c['nome'] === 'Cliente Balcão' ? 'selected' : '' ?>><?= e($c['nome']) ?></option><?php endforeach; ?></select></label>
  <div class="rolagem"><table class="tabela">
    <tr><th>Variação (SKU)</th><th>Quantidade</th><th>Preço unitário (R$)</th></tr>
    <?php for ($i = 0; $i < 5; $i++): ?>
    <tr>
      <td><select name="itens[<?= $i ?>][v]"><option value="">-- não usar esta linha --</option>
        <?php foreach ($variacoes as $v): ?><option value="<?= $v['id'] ?>"><?= e("{$v['jogo']} · {$v['plataforma']} · {$v['edicao']} ({$v['sku']}) — " . dinheiro($v['preco']) . " — estoque {$v['estoque']}") ?></option><?php endforeach; ?></select></td>
      <td><input type="number" min="0" name="itens[<?= $i ?>][q]" value="0"></td>
      <td><input type="number" min="0" step="0.01" name="itens[<?= $i ?>][pr]" placeholder="preço cadastrado"></td>
    </tr>
    <?php endfor; ?>
  </table></div>
  <p class="mudo pequeno">Deixe o preço em branco para usar o preço cadastrado. Só altere o valor quando houver autorização.</p>
  <button class="btn">Finalizar venda</button>
</form>
<div class="cartao"><h2>Vendas recentes</h2><div class="rolagem"><table class="tabela">
  <tr><th>ID</th><th>Cliente</th><th>Vendedor</th><th>Data</th><th>Pagamento</th><th class="dir">Valor total</th><th>Nota fiscal</th></tr>
  <?php foreach ($recentes as $r): ?>
  <tr><td>#<?= $r['id'] ?></td><td><?= e($r['cliente']) ?></td><td><?= e($r['vendedor']) ?></td><td><?= data_hora($r['criada_em']) ?></td><td><?= e($r['pagamento_detalhe'] ?? 'Balcão') ?></td><td class="dir"><?= dinheiro($r['total']) ?></td>
    <td><?php if ($r['nota_id']): ?><a class="btn btn-sm" href="<?= url('notas/ver/' . $r['id']) ?>">Ver NF</a>
      <?php else: ?><form method="post" action="<?= url('notas/emitir/' . $r['id']) ?>" class="embutido"><?= csrf() ?><button class="btn btn-sec btn-sm">Emitir NF</button></form><?php endif; ?></td></tr>
  <?php endforeach; if (!$recentes): ?><tr><td colspan="7" class="mudo">Nenhuma venda registrada ainda.</td></tr><?php endif; ?>
</table></div></div>
