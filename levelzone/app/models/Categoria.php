<?php
class Categoria extends Modelo {
    function listar() { return $this->todos('SELECT c.*, (SELECT COUNT(*) FROM produtos p WHERE p.categoria_id=c.id) qtd FROM categorias c ORDER BY c.nome'); }
    function achar($id) { return $this->um('SELECT * FROM categorias WHERE id=?', [$id]); }
    function existe($nome, $ignorar = 0) { return $this->um('SELECT id FROM categorias WHERE nome=? AND id<>?', [$nome, $ignorar]); }
    function salvar($id, $nome) {
        if ($id) $this->exec('UPDATE categorias SET nome=? WHERE id=?', [$nome, $id]);
        else $this->exec('INSERT INTO categorias(nome) VALUES(?)', [$nome]);
    }
    function excluir($id) {
        if ($this->um('SELECT id FROM produtos WHERE categoria_id=? LIMIT 1', [$id])) return false;
        $this->exec('DELETE FROM categorias WHERE id=?', [$id]);
        return true;
    }
}
