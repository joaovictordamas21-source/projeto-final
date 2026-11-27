<h1>Minhas compras</h1>
<div class="cartao"><div class="rolagem"><table class="tabela">
  <tr><th>Pedido</th><th>Data</th><th>Pagamento</th><th class="dir">Total</th><th>Nota fiscal</th></tr>
  <?php foreach ($pedidos as $p): ?>
  <tr><td>#<?= $p['id'] ?></td><td><?= data_hora($p['criada_em']) ?></td><td><?= e($p['pagamento_detalhe'] ?? '—') ?></td><td class="dir"><?= dinheiro($p['total']) ?></td>
    <td><?php if ($p['nota_id']): ?><a class="btn btn-sm" href="<?= url('notas/ver/' . $p['id']) ?>">Ver NF nº <?= (int)$p['numero'] ?></a><?php else: ?><span class="mudo">Em emissão</span><?php endif; ?></td></tr>
  <?php endforeach; if (!$pedidos): ?><tr><td colspan="5" class="mudo">Você ainda não fez compras. <a href="<?= url('loja') ?>">Ver jogos</a></td></tr><?php endif; ?>
</table></div></div>
