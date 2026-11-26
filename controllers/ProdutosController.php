<?php
class ProdutosController extends Controlador {
    function index() {
        $this->exigir();
        $busca = trim($_GET['busca'] ?? '');
        $this->renderizar('produtos_index', ['lista' => (new Produto)->listarPainel($busca), 'busca' => $busca]);
    }
    function novo() { $this->exigir(); $this->formulario(null, [], $this->linhasVazias()); }
    function editar($id = 0) {
        $this->exigir();
        $p = (new Produto)->achar((int)$id);
        if (!$p) { $this->aviso('erro', 'Jogo não encontrado.'); $this->ir('produtos'); }
        $this->formulario($p, (new Variacao)->doProduto($p['id']), []);
    }
    private function linhasVazias() { return array_fill(0, 3, ['plataforma' => '', 'edicao' => '', 'sku' => '', 'preco' => '', 'estoque' => '0', 'minimo' => '3']); }
    private function formulario($jogo, $vars, $novas) {
        $this->renderizar('produtos_form', ['jogo' => $jogo, 'vars' => $vars, 'novas' => $novas, 'cats' => (new Categoria)->listar()]);
    }
    private function enviarImagem() {
        if (empty($_FILES['imagem']) || $_FILES['imagem']['error'] === UPLOAD_ERR_NO_FILE) return null;
        $f = $_FILES['imagem'];
        if ($f['error'] !== UPLOAD_ERR_OK || $f['size'] > 2 * 1024 * 1024) throw new Exception('A imagem deve ter até 2 MB.');
        $info = @getimagesize($f['tmp_name']);
        $tipos = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
        if (!$info || !isset($tipos[$info[2]])) throw new Exception('Use uma imagem JPG, PNG ou WEBP.');
        $dir = __DIR__ . '/../../img/produtos';
        if (!is_dir($dir)) mkdir($dir, 0775, true);
        $nome = bin2hex(random_bytes(8)) . '.' . $tipos[$info[2]];
        if (!move_uploaded_file($f['tmp_name'], "$dir/$nome")) throw new Exception('Não foi possível salvar a imagem.');
        return $nome;
    }
    function salvar() {
        $this->exigir();
        $idOriginal = (int)($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? ''); $desc = trim($_POST['descricao'] ?? ''); $cat = (int)($_POST['categoria'] ?? 0) ?: null;
        $novas = [];
        if (!$idOriginal) {
            foreach (($_POST['var'] ?? []) as $l) {
                $novas[] = ['plataforma' => trim($l['plataforma'] ?? ''), 'edicao' => trim($l['edicao'] ?? ''), 'sku' => strtoupper(trim($l['sku'] ?? '')),
                            'preco' => trim($l['preco'] ?? ''), 'estoque' => trim($l['estoque'] ?? '0'), 'minimo' => trim($l['minimo'] ?? '3')];
            }
        }
        $erro = null; $validas = []; $skus = [];
        if ($nome === '') $erro = 'Informe o nome do jogo.';
        foreach ($novas as $l) {
            if ($erro) break;
            if ($l['sku'] === '' && $l['plataforma'] === '' && $l['preco'] === '') continue;
            $preco = (float)str_replace(',', '.', $l['preco']);
            if ($l['sku'] === '' || $l['plataforma'] === '' || $preco <= 0) { $erro = 'Em cada variação, preencha plataforma, SKU e um preço maior que zero.'; break; }
            if (in_array($l['sku'], $skus, true) || (new Variacao)->skuExiste($l['sku'])) { $erro = "O SKU {$l['sku']} já existe."; break; }
            $skus[] = $l['sku'];
            $validas[] = [$l['plataforma'], $l['edicao'] ?: 'Padrão', $l['sku'], $preco, max(0, (int)$l['estoque']), max(0, (int)$l['minimo'])];
        }
        if (!$erro && !$idOriginal && !$validas) $erro = 'Cadastre ao menos uma variação (plataforma, SKU e preço) para o jogo.';
        if ($erro) {
            $this->aviso('erro', $erro);
            $atual = $idOriginal ? (new Produto)->achar($idOriginal) : null;
            $jogo = ['id' => $idOriginal, 'nome' => $nome, 'descricao' => $desc, 'categoria_id' => $cat, 'imagem' => $atual['imagem'] ?? null];
            $this->formulario($jogo, $idOriginal ? (new Variacao)->doProduto($idOriginal) : [], $novas ?: $this->linhasVazias());
            return;
        }
        $bd = Banco::pdo(); $arq = null; $id = $idOriginal;
        $bd->beginTransaction();
        try {
            $pm = new Produto; $vm = new Variacao;
            $antiga = $idOriginal ? ($pm->achar($idOriginal)['imagem'] ?? null) : null;
            $arq = $this->enviarImagem();
            $id = $pm->salvar($idOriginal, [$nome, $desc, $cat]);
            if ($arq) { $pm->imagem($id, $arq); if ($antiga) @unlink(__DIR__ . '/../../img/produtos/' . basename($antiga)); }
            foreach ($validas as $d) $vm->criar($id, $d);
            $bd->commit();
        } catch (Throwable $t) {
            $bd->rollBack();
            if ($arq) @unlink(__DIR__ . '/../../img/produtos/' . $arq);
            $this->aviso('erro', $t->getMessage());
            $this->ir($idOriginal ? 'produtos/editar/' . $idOriginal : 'produtos/novo');
        }
        $this->aviso('ok', $idOriginal ? 'Jogo atualizado.' : 'Jogo cadastrado. Se precisar, adicione mais variações abaixo.');
        $this->ir('produtos/editar/' . $id);
    }
    function alternar() {
        $this->exigir();
        (new Produto)->alternar((int)($_POST['id'] ?? 0));
        $this->aviso('ok', 'Status do jogo atualizado.');
        $this->ir((($_POST['voltar'] ?? '') === 'editar') ? 'produtos/editar/' . (int)($_POST['id'] ?? 0) : 'produtos');
    }
    private function lerVariacao($ignorar = 0) {
        $pl = trim($_POST['plataforma'] ?? ''); $ed = trim($_POST['edicao'] ?? '') ?: 'Padrão';
        $sku = strtoupper(trim($_POST['sku'] ?? '')); $preco = (float)str_replace(',', '.', $_POST['preco'] ?? 0);
        if ($pl === '' || $sku === '' || $preco <= 0) return [null, 'Preencha plataforma, SKU e um preço maior que zero.'];
        if ((new Variacao)->skuExiste($sku, $ignorar)) return [null, "O SKU $sku já existe."];
        return [[$pl, $ed, $sku, $preco, max(0, (int)($_POST['estoque'] ?? 0)), max(0, (int)($_POST['minimo'] ?? 3))], null];
    }
    function variacaoNova() {
        $this->exigir(); $pid = (int)($_POST['produto_id'] ?? 0);
        if (!(new Produto)->achar($pid)) { $this->aviso('erro', 'Jogo não encontrado.'); $this->ir('produtos'); }
        [$d, $erro] = $this->lerVariacao();
        if ($erro) $this->aviso('erro', $erro);
        else { (new Variacao)->criar($pid, $d); $this->aviso('ok', 'Variação adicionada.'); }
        $this->ir('produtos/editar/' . $pid);
    }
    function variacao() {
        $this->exigir(); $id = (int)($_POST['id'] ?? 0); $v = (new Variacao)->achar($id);
        if (!$v) { $this->aviso('erro', 'Variação não encontrada.'); $this->ir('produtos'); }
        [$d, $erro] = $this->lerVariacao($id);
        if ($erro) $this->aviso('erro', $erro);
        else { (new Variacao)->atualizar($id, $d); $this->aviso('ok', 'Variação salva.'); }
        $this->ir('produtos/editar/' . $v['produto_id']);
    }
    function variacaoAlternar() {
        $this->exigir(); $id = (int)($_POST['id'] ?? 0); $v = (new Variacao)->achar($id);
        if (!$v) $this->ir('produtos');
        (new Variacao)->alternar($id);
        $this->aviso('ok', 'Status da variação atualizado.');
        $this->ir('produtos/editar/' . $v['produto_id']);
    }
}
