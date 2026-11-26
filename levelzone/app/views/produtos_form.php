<?php $editando = $jogo && (int)$jogo['id'] > 0; ?>
<div class="topo"><h1><?= $editando ? 'Editar jogo' : 'Novo jogo' ?></h1><a class="btn btn-sec" href="<?= url('produtos') ?>">Voltar para a lista</a></div>

<form method="post" action="<?= url('produtos/salvar') ?>" enctype="multipart/form-data" class="cartao">
  <?= csrf() ?><input type="hidden" name="id" value="<?= $editando ? (int)$jogo['id'] : 0 ?>">
  <h2>Dados do jogo</h2>
  <div class="grade2">
    <div>
      <label>Nome do jogo <input name="nome" required value="<?= e($jogo['nome'] ?? '') ?>"></label>
      <label>Categoria <select name="categoria"><option value="">Sem categoria</option>
        <?php foreach ($cats as $c): ?><option value="<?= $c['id'] ?>" <?= (int)($jogo['categoria_id'] ?? 0) === (int)$c['id'] ? 'selected' : '' ?>><?= e($c['nome']) ?></option><?php endforeach; ?></select></label>
      <label>Descrição <textarea name="descricao" rows="3"><?= e($jogo['descricao'] ?? '') ?></textarea></label>
    </div>
    <div>
      <label>Imagem de capa <small class="mudo">(JPG, PNG ou WEBP, até 2 MB; sem imagem, o sistema cria uma capa)</small>
        <input type="file" name="imagem" accept="image/jpeg,image/png,image/webp"></label>
      <?php if ($editando): ?><img class="capa-previa" src="<?= e(capa($jogo['imagem'] ?? null, $jogo['id'])) ?>" alt="Capa atual"><?php endif; ?>
    </div>
  </div>

  <?php if (!$editando): ?>
  <h2>Variações do jogo</h2>
  <p class="mudo pequeno">Cada variação é uma versão vendida do jogo (plataforma e edição) com SKU, preço e estoque próprios. Linhas em branco são ignoradas. Você pode adicionar mais depois.</p>
  <div class="rolagem"><table class="tabela">
    <tr><th>Plataforma</th><th>Edição</th><th>SKU</th><th>Preço (R$)</th><th>Estoque inicial</th><th>Estoque mínimo</th></tr>
    <?php foreach ($novas as $i => $l): ?>
    <tr>
      <td><input name="var[<?= $i ?>][plataforma]" placeholder="PS5, Xbox, Switch, PC" value="<?= e($l['plataforma']) ?>"></td>
      <td><input name="var[<?= $i ?>][edicao]" placeholder="Padrão" value="<?= e($l['edicao']) ?>"></td>
      <td><input name="var[<?= $i ?>][sku]" value="<?= e($l['sku']) ?>"></td>
      <td><input type="number" step="0.01" min="0" name="var[<?= $i ?>][preco]" value="<?= e($l['preco']) ?>"></td>
      <td><input type="number" min="0" name="var[<?= $i ?>][estoque]" value="<?= e($l['estoque']) ?>"></td>
      <td><input type="number" min="0" name="var[<?= $i ?>][minimo]" value="<?= e($l['minimo']) ?>"></td>
    </tr>
    <?php endforeach; ?>
  </table></div>
  <?php endif; ?>
  <div class="linha"><button class="btn"><?= $editando ? 'Salvar jogo' : 'Cadastrar jogo' ?></button></div>
</form>

<?php if ($editando): ?>
<div class="cartao">
  <div class="topo"><h2>Variações</h2>
    <form method="post" action="<?= url('produtos/alternar') ?>" class="embutido"><?= csrf() ?><input type="hidden" name="id" value="<?= (int)$jogo['id'] ?>"><input type="hidden" name="voltar" value="editar">
      <?php if (!empty($jogo['ativo'])): ?><button class="btn btn-perigo">Inativar jogo</button><?php else: ?><button class="btn btn-ok">Ativar jogo</button><?php endif; ?></form>
  </div>
  <p class="mudo pequeno">O estoque só muda por <a href="<?= url('entradas') ?>">Entradas de estoque</a> e pelas vendas.</p>
  <div class="rolagem"><table class="tabela">
    <tr><th>SKU</th><th>Plataforma</th><th>Edição</th><th>Preço (R$)</th><th>Mínimo</th><th>Estoque</th><th>Status</th><th></th></tr>
    <?php foreach ($vars as $v): ?>
    <tr class="<?= $v['ativo'] ? '' : 'apagada' ?>">
      <td><input class="mono" form="v<?= $v['id'] ?>" name="sku" value="<?= e($v['sku']) ?>"></td>
      <td><input form="v<?= $v['id'] ?>" name="plataforma" value="<?= e($v['plataforma']) ?>"></td>
      <td><input form="v<?= $v['id'] ?>" name="edicao" value="<?= e($v['edicao']) ?>"></td>
      <td><input form="v<?= $v['id'] ?>" type="number" step="0.01" min="0" name="preco" value="<?= e($v['preco']) ?>"></td>
      <td><input form="v<?= $v['id'] ?>" type="number" min="0" name="minimo" value="<?= (int)$v['estoque_minimo'] ?>"></td>
      <td><span class="estoque <?= $v['estoque'] == 0 ? 'zero' : ($v['estoque'] <= $v['estoque_minimo'] ? 'baixo' : 'ok') ?>"><?= (int)$v['estoque'] ?></span></td>
      <td><span class="pilula <?= $v['ativo'] ? 'ativo' : 'inativo' ?>"><?= $v['ativo'] ? 'ativo' : 'inativo' ?></span></td>
      <td class="acoes">
        <button class="btn btn-sec btn-sm" form="v<?= $v['id'] ?>">Salvar</button>
        <?php if ($v['ativo']): ?><button class="btn btn-perigo btn-sm" form="v<?= $v['id'] ?>" formaction="<?= url('produtos/variacaoAlternar') ?>">Inativar</button>
        <?php else: ?><button class="btn btn-ok btn-sm" form="v<?= $v['id'] ?>" formaction="<?= url('produtos/variacaoAlternar') ?>">Ativar</button><?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table></div>
  <?php foreach ($vars as $v): ?>
  <form id="v<?= $v['id'] ?>" method="post" action="<?= url('produtos/variacao') ?>"><?= csrf() ?><input type="hidden" name="id" value="<?= $v['id'] ?>"></form>
  <?php endforeach; ?>
</div>

<form method="post" action="<?= url('produtos/variacaoNova') ?>" class="cartao">
  <?= csrf() ?><input type="hidden" name="produto_id" value="<?= (int)$jogo['id'] ?>">
  <h2>Adicionar variação</h2>
  <div class="rolagem"><table class="tabela">
    <tr><th>Plataforma</th><th>Edição</th><th>SKU</th><th>Preço (R$)</th><th>Estoque inicial</th><th>Estoque mínimo</th></tr>
    <tr>
      <td><input name="plataforma" required placeholder="PS5, Xbox, Switch, PC"></td>
      <td><input name="edicao" placeholder="Padrão"></td>
      <td><input name="sku" required></td>
      <td><input type="number" step="0.01" min="0" name="preco" required></td>
      <td><input type="number" min="0" name="estoque" value="0"></td>
      <td><input type="number" min="0" name="minimo" value="3"></td>
    </tr>
  </table></div>
  <button class="btn">Adicionar variação</button>
</form>
<?php endif; ?>
