<div class="topo"><h1>Mensagens</h1></div>
<?php foreach ($lista as $m): ?>
<details class="cartao mensagem <?= $m['lida'] ? 'lida' : 'nova' ?>" <?= $m['lida'] ? '' : 'open' ?>>
  <summary><strong><?= e($m['assunto']) ?></strong> <span class="mudo">— <?= e($m['nome']) ?> · <?= data_hora($m['criada_em']) ?></span>
    <?php if (!$m['lida']): ?><span class="pilula ativo">nova</span><?php endif; ?></summary>
  <p><?= nl2br(e($m['texto'])) ?></p>
  <div class="linha">
    <a class="btn btn-sm" href="mailto:<?= e($m['email']) ?>?subject=<?= rawurlencode('Re: ' . $m['assunto']) ?>">Responder por e-mail</a>
    <form method="post" action="<?= url('mensagens/marcar') ?>" class="embutido"><?= csrf() ?><input type="hidden" name="id" value="<?= $m['id'] ?>">
      <?php if ($m['lida']): ?><button class="btn btn-sec btn-sm">Marcar como não lida</button>
      <?php else: ?><input type="hidden" name="lida" value="1"><button class="btn btn-sec btn-sm">Marcar como lida</button><?php endif; ?></form>
    <span class="mudo pequeno"><?= e($m['email']) ?></span>
  </div>
</details>
<?php endforeach; if (!$lista): ?><div class="cartao"><p class="mudo">Nenhuma mensagem recebida ainda. Os clientes escrevem pela página Contato da loja.</p></div><?php endif; ?>
