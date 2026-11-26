<?php
class Fornecedor extends Modelo {
    function listar($so_ativos = false) { return $this->todos('SELECT * FROM fornecedores' . ($so_ativos ? ' WHERE ativo=1' : '') . ' ORDER BY id'); }
    function achar($id) { return $this->um('SELECT * FROM fornecedores WHERE id=?', [$id]); }
    function porCnpj($c, $ignorar = 0) { return $this->um('SELECT id FROM fornecedores WHERE cnpj=? AND id<>?', [$c, $ignorar]); }
    function salvar($id, $d) {
        if ($id) $this->exec('UPDATE fornecedores SET nome=?,cnpj=?,telefone=?,email=?,cep=?,endereco=? WHERE id=?', [...$d, $id]);
        else $this->exec('INSERT INTO fornecedores(nome,cnpj,telefone,email,cep,endereco) VALUES(?,?,?,?,?,?)', $d);
    }
    function alternar($id) { $this->exec('UPDATE fornecedores SET ativo=1-ativo WHERE id=?', [$id]); }
}
