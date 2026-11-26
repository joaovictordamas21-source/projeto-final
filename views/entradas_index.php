<div class="topo"><h1>Entradas de estoque</h1></div>
<form method="post" action="<?= url('entradas/salvar') ?>" class="cartao">
  <?= csrf() ?><input type="hidden" name="unica" value="<?= e($_SESSION['entrada_unica']) ?>">
  <h2>Registrar nova entrada</h2>
  <label>Fornecedor
    <select name="fornecedor" required><option value="">Selecione...</option>
    <?php foreach ($fornecedores as $f): ?><option value="<?= $f['id'] ?>"><?= e($f['nome']) ?></option><?php endforeach; ?></select></label>
  <p class="mudo pequeno">Preencha uma linha para cada variação (SKU) recebida. Deixe a quantidade em 0 para ignorar a linha. Confira tudo antes de confirmar.</p>
  <div class="rolagem"><table class="tabela">
    <tr><th>Variação (SKU)</th><th>Quantidade</th><th>Custo unitário (R$)</th></tr>
    <?php for ($i = 0; $i < 5; $i++): ?>
    <tr>
      <td><select name="itens[<?= $i ?>][v]"><option value="">-- não usar esta linha --</option>
        <?php foreach ($variacoes as $v): ?><option value="<?= $v['id'] ?>"><?= e("{$v['jogo']} · {$v['plataforma']} · {$v['edicao']} ({$v['sku']})") ?></option><?php endforeach; ?></select></td>
      <td><input type="number" min="0" name="itens[<?= $i ?>][q]" value="0"></td>
      <td><input type="number" min="0" step="0.01" name="itens[<?= $i ?>][c]" value="0.00"></td>
    </tr>
    <?php endfor; ?>
  </table></div>
  <button class="btn">Confirmar entrada</button>
</form>
<div class="cartao"><h2>Últimas entradas</h2><table class="tabela">
  <tr><th>ID</th><th>Fornecedor</th><th>Data</th><th>Status</th><th class="dir">Valor total</th></tr>
  <?php foreach ($recentes as $r): ?><tr><td>#<?= $r['id'] ?></td><td><?= e($r['fornecedor']) ?></td><td><?= data_hora($r['criada_em']) ?></td><td><?= e($r['status']) ?></td><td class="dir"><?= dinheiro($r['total']) ?></td></tr>
  <?php endforeach; if (!$recentes): ?><tr><td colspan="5" class="mudo">Nenhuma entrada registrada ainda.</td></tr><?php endif; ?></table></div>
