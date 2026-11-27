<div class="nf">
  <div class="nf-topo">
    <div><h2><?= e(LOJA_NOME) ?></h2><p>CNPJ: <?= e(LOJA_CNPJ) ?></p><p><?= e(LOJA_ENDERECO) ?></p></div>
    <div class="nf-caixa"><b>NOTA FISCAL</b><p>Número: <?= (int)$nota['numero'] ?></p><p>Série: <?= (int)$nota['serie'] ?></p><p>Modelo: <?= e($nota['modelo']) ?></p></div>
  </div>
  <div class="nf-meta"><div>Data de emissão<b><?= date('d/m/Y', strtotime($nota['emitida_em'])) ?></b></div><div>Hora<b><?= date('H:i:s', strtotime($nota['emitida_em'])) ?></b></div><div>Referência venda<b>#<?= $venda['id'] ?></b></div><div>Cliente<b><?= e($venda['cliente']) ?></b></div></div>
  <?php if (!empty($venda['entrega_endereco'])): ?><p>Endereço de entrega: <b><?= e($venda['entrega_endereco']) ?></b> · CEP <?= e($venda['entrega_cep']) ?></p>
  <?php elseif (!empty($venda['cliente_endereco'])): ?><p>Endereço do cliente: <b><?= e($venda['cliente_endereco']) ?></b> · CEP <?= e($venda['cliente_cep']) ?></p><?php endif; ?>
  <table><tr><th>Código</th><th>Descrição</th><th>Qtd</th><th>V. unitário</th><th>V. total</th></tr>
  <?php foreach ($itens as $i): ?><tr><td><?= e($i['sku']) ?></td><td><?= e($i['jogo'] . ' · ' . $i['plataforma'] . ' · ' . $i['edicao']) ?></td><td><?= (int)$i['quantidade'] ?></td><td><?= dinheiro($i['preco_unitario']) ?></td><td><?= dinheiro($i['quantidade'] * $i['preco_unitario']) ?></td></tr><?php endforeach; ?></table>
  <?php if (!empty($venda['pagamento_detalhe'])): ?><p>Forma de pagamento: <b><?= e($venda['pagamento_detalhe']) ?></b></p><?php endif; ?>
  <p class="nf-total">Valor total da nota <b><?= dinheiro($venda['total']) ?></b></p>
  <p class="mudo pequeno sem-impressao">Confira número, data, itens e valor total antes de entregar ao cliente. Para imprimir ou salvar em PDF, pressione <b>Ctrl+P</b>. Documento de demonstração, sem valor fiscal.</p>
  <div class="linha sem-impressao">
    <?php if ($equipe): ?><a class="btn btn-sec" href="<?= url('vendas') ?>">Voltar às vendas</a>
    <?php else: ?><a class="btn btn-sec" href="<?= url('pedidos') ?>">Minhas compras</a><a class="btn btn-sec" href="<?= url('loja') ?>">Voltar à loja</a><?php endif; ?>
  </div>
</div>
