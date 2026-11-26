<h1>Carrinho</h1>
<?php if (!$itens): ?>
  <div class="cartao"><p>Seu carrinho está vazio.</p><a class="btn" href="<?= url('loja') ?>">Ver jogos</a></div>
<?php else: ?>
<form method="post" action="<?= url('carrinho/atualizar') ?>" class="cartao">
  <?= csrf() ?>
  <div class="rolagem"><table class="tabela">
    <tr><th></th><th>Jogo</th><th>Variação</th><th class="dir">Preço</th><th>Qtde.</th><th class="dir">Subtotal</th><th></th></tr>
    <?php foreach ($itens as $i): ?>
    <tr><td><img class="miniatura" src="<?= e(capa($i['imagem'], $i['produto_id'])) ?>" alt=""></td>
      <td><?= e($i['jogo']) ?></td><td><?= e($i['plataforma'] . ' · ' . $i['edicao']) ?></td><td class="dir"><?= dinheiro($i['preco']) ?></td>
      <td><input class="qtd" type="number" min="0" max="<?= (int)$i['estoque'] ?>" name="qtd[<?= $i['id'] ?>]" value="<?= $i['qtd'] ?>"></td>
      <td class="dir"><?= dinheiro($i['qtd'] * $i['preco']) ?></td>
      <td><button class="btn btn-perigo btn-sm" form="rm<?= $i['id'] ?>">Remover</button></td></tr>
    <?php endforeach; ?>
  </table></div>
  <p class="total">Total <strong><?= dinheiro($total) ?></strong></p>
  <div class="linha"><button class="btn btn-sec">Atualizar quantidades</button><a class="btn btn-sec" href="<?= url('loja') ?>">Continuar comprando</a></div>
</form>
<?php foreach ($itens as $i): ?>
<form id="rm<?= $i['id'] ?>" method="post" action="<?= url('carrinho/remover') ?>"><?= csrf() ?><input type="hidden" name="variacao" value="<?= $i['id'] ?>"></form>
<?php endforeach; ?>
<div class="cartao">
  <h2>Finalizar compra</h2>
  <p class="mudo">Na próxima etapa você escolhe pagar com cartão ou Pix. A nota fiscal é emitida logo após o pagamento.</p>
  <a class="btn" href="<?= url('carrinho/pagamento') ?>">Ir para o pagamento</a>
</div>
<?php endif; ?>
