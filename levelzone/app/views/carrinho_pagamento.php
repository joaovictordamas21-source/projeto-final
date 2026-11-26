<?php $dados = $dados ?? []; $forma = $dados['forma'] ?? 'cartao'; $parc = (int)($dados['parcelas'] ?? 1); ?>
<div class="topo"><h1>Pagamento</h1><a class="btn btn-sec" href="<?= url('carrinho') ?>">Voltar ao carrinho</a></div>
<div class="pagamento-grade">
  <form method="post" action="<?= url('carrinho/finalizar') ?>" class="cartao" style="position:relative">
    <?= csrf() ?>
    <input class="aba-radio" type="radio" name="forma" id="pg-cartao" value="cartao" <?= $forma !== 'pix' ? 'checked' : '' ?>>
    <input class="aba-radio" type="radio" name="forma" id="pg-pix" value="pix" <?= $forma === 'pix' ? 'checked' : '' ?>>
    <h2>Escolha como pagar</h2>
    <div class="abas">
      <label class="aba aba-cartao" for="pg-cartao">Cartão de crédito</label>
      <label class="aba aba-pix" for="pg-pix">Pix</label>
    </div>

    <div class="painel painel-cartao">
      <p class="aviso-demo">Pagamento de demonstração: nenhum valor é cobrado e o número do cartão e o CVV nunca são guardados.</p>
      <label>Nome impresso no cartão <input name="cartao_nome" autocomplete="cc-name" value="<?= e($dados['cartao_nome'] ?? '') ?>"></label>
      <label>Número do cartão <input name="cartao_numero" inputmode="numeric" autocomplete="cc-number" maxlength="23" placeholder="0000 0000 0000 0000"></label>
      <div class="duas">
        <label>Validade <input name="cartao_validade" autocomplete="cc-exp" maxlength="5" placeholder="MM/AA" value="<?= e($dados['cartao_validade'] ?? '') ?>"></label>
        <label>CVV <input name="cartao_cvv" inputmode="numeric" autocomplete="cc-csc" maxlength="4" placeholder="123"></label>
      </div>
      <label>Parcelas
        <select name="parcelas">
          <?php foreach ([1, 2, 3] as $n): ?><option value="<?= $n ?>" <?= $parc === $n ? 'selected' : '' ?>><?= $n ?>x de <?= dinheiro($total / $n) ?> sem juros</option><?php endforeach; ?>
        </select></label>
    </div>

    <div class="painel painel-pix">
      <p class="aviso-demo">QR Code de demonstração: é só um desenho. Ele não é um Pix de verdade e nenhum aplicativo de banco consegue ler.</p>
      <div class="pix-caixa">
        <?= Ajuda::qrFalso($pixRef) ?>
        <div>
          <strong>Como pagar</strong>
          <ol class="pequeno mudo">
            <li>Abra o aplicativo do seu banco.</li>
            <li>Escolha pagar com Pix e leia o QR Code.</li>
            <li>Confirme e clique em <b>Confirmar pagamento</b>.</li>
          </ol>
          <label>Pix copia e cola (demonstração)
            <textarea readonly rows="3" class="mono">DEMO-LEVELZONE-<?= e($pixRef) ?>-<?= e(number_format($total, 2, '.', '')) ?></textarea></label>
        </div>
      </div>
    </div>

    <button class="btn">Confirmar pagamento de <?= dinheiro($total) ?></button>
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
