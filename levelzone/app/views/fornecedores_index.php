<div class="topo"><h1>Fornecedores</h1></div>
<div class="divisao">
  <form method="post" action="<?= url('fornecedores/salvar') ?>" class="cartao">
    <?= csrf() ?><input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
    <button class="fora-da-tela" name="acao" value="salvar" tabindex="-1" aria-hidden="true">Salvar</button>
    <h2><?= !empty($edit['id']) ? 'Editar fornecedor' : 'Novo fornecedor' ?></h2>
    <label>Nome <input name="nome" required value="<?= e($edit['nome'] ?? '') ?>"></label>
    <label>CNPJ <input name="cnpj" required maxlength="18" placeholder="00.000.000/0000-00" value="<?= e($edit['cnpj'] ?? '') ?>"></label>
    <label>Telefone <input name="telefone" value="<?= e($edit['telefone'] ?? '') ?>"></label>
    <label>E-mail <input type="email" name="email" value="<?= e($edit['email'] ?? '') ?>"></label>
    <label>CEP <input name="cep" required maxlength="9" placeholder="00000-000" value="<?= e($edit['cep'] ?? '') ?>"></label>
    <button class="btn btn-sec btn-sm" name="acao" value="cep" formnovalidate>Buscar endereço pelo CEP</button>
    <label>Endereço <textarea name="endereco" rows="3"><?= e($edit['endereco'] ?? '') ?></textarea></label>
    <div class="linha"><button class="btn" name="acao" value="salvar">Salvar fornecedor</button><a class="btn btn-sec" href="<?= url('fornecedores') ?>">Limpar</a></div>
  </form>
  <div class="cartao"><h2>Lista de fornecedores</h2><div class="rolagem"><table class="tabela">
    <tr><th>ID</th><th>Nome</th><th>CNPJ</th><th>Telefone</th><th>Endereço</th><th>Status</th><th></th></tr>
    <?php foreach ($lista as $f): ?>
    <tr class="<?= $f['ativo'] ? '' : 'apagada' ?>"><td>#<?= $f['id'] ?></td><td><?= e($f['nome']) ?></td><td class="mono"><?= e($f['cnpj']) ?></td><td><?= e($f['telefone']) ?></td><td><?= e($f['endereco']) ?></td>
      <td><span class="pilula <?= $f['ativo'] ? 'ativo' : 'inativo' ?>"><?= $f['ativo'] ? 'ativo' : 'inativo' ?></span></td>
      <td class="acoes"><a class="btn btn-sec btn-sm" href="<?= url('fornecedores/index/' . $f['id']) ?>">Editar</a>
        <form method="post" action="<?= url('fornecedores/alternar') ?>" class="embutido"><?= csrf() ?><input type="hidden" name="id" value="<?= $f['id'] ?>">
          <?php if ($f['ativo']): ?><button class="btn btn-perigo btn-sm">Inativar</button><?php else: ?><button class="btn btn-ok btn-sm">Ativar</button><?php endif; ?></form></td></tr>
    <?php endforeach; if (!$lista): ?><tr><td colspan="7" class="mudo">Nenhum fornecedor cadastrado. Preencha o formulário ao lado.</td></tr><?php endif; ?>
  </table></div></div>
</div>
