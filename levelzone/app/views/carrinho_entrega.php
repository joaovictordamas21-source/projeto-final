<?php $dados = $dados ?? []; ?>
<div class="topo"><h1>Entrega</h1><a class="btn btn-sec" href="<?= url('carrinho') ?>">Voltar ao carrinho</a></div>
<div class="pagamento-grade">
  <form method="post" action="<?= url('carrinho/entrega') ?>" class="cartao">
    <?= csrf() ?>
    <button class="fora-da-tela" name="acao" value="continuar" tabindex="-1" aria-hidden="true">Continuar</button>
    <h2>Endereço de entrega</h2>
    <p class="mudo pequeno">Passo 1 de 2. Informe o CEP: o endereço é preenchido para você. No passo 2 você escolhe pagar com cartão ou Pix.</p>
    <label>CEP <input name="cep" required maxlength="9" inputmode="numeric" placeholder="00000-000" autocomplete="postal-code" value="<?= e($dados['cep'] ?? '') ?>"></label>
    <button class="btn btn-sec btn-sm" name="acao" value="cep" formnovalidate>Buscar endereço pelo CEP</button>
    <label style="margin-top:12px">Endereço <textarea name="endereco" rows="2" autocomplete="street-address"><?= e($dados['endereco'] ?? '') ?></textarea></label>
    <label>Número e complemento <input name="numero" required placeholder="Ex.: 123, apto 4" value="<?= e($dados['numero'] ?? '') ?>"></label>
    <button class="btn" name="acao" value="continuar">Continuar para o pagamento</button>
  </form>
  <div class="cartao">
    <h2>Resumo do pedido</h2>
    <?php foreach ($itens as $i): ?>
      <div class="resumo-linha"><img class="miniatura" src="<?= e(capa($i['imagem'], $i['produto_id'])) ?>" alt="">
        <span><?= e($i['jogo']) ?><br><small class="mudo"><?= e($i['plataforma'] . ' · ' . $i['edicao']) ?> · <?= (int)$i['qtd'] ?>x</small></span>
        <strong><?= dinheiro($i['qtd'] * $i['preco']) ?></strong></div>
    <?php endforeach; ?>
    <p class="total">Total <strong><?= dinheiro($total) ?></strong></p>
  </div>
</div>
