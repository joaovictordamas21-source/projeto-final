<?php
class CategoriasController extends Controlador {
    function index($editarId = 0) {
        $this->exigir(); $m = new Categoria;
        $this->renderizar('categorias_index', ['lista' => $m->listar(), 'edit' => $editarId ? $m->achar((int)$editarId) : null]);
    }
    function salvar() {
        $this->exigir(); $m = new Categoria; $id = (int)($_POST['id'] ?? 0); $nome = trim($_POST['nome'] ?? '');
        if ($nome === '') { $this->aviso('erro', 'Informe o nome da categoria.'); $this->ir('categorias'); }
        if ($m->existe($nome, $id)) { $this->aviso('erro', 'Já existe uma categoria com este nome.'); $this->ir('categorias'); }
        $m->salvar($id, $nome); $this->aviso('ok', 'Categoria salva.'); $this->ir('categorias');
    }
    function excluir() {
        $this->exigir();
        if ((new Categoria)->excluir((int)($_POST['id'] ?? 0))) $this->aviso('ok', 'Categoria excluída.');
        else $this->aviso('erro', 'Não dá para excluir: há jogos nesta categoria. Mude a categoria deles antes.');
        $this->ir('categorias');
    }
}
