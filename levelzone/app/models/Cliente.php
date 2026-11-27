<?php
class Cliente extends Modelo {
    function listar() { return $this->todos('SELECT * FROM clientes ORDER BY id'); }
    function porUsuario($uid) { return $this->um('SELECT * FROM clientes WHERE usuario_id=?', [$uid]); }
    function salvarEnderecoSeVazio($uid, $cep, $endereco) { // guarda o 1º endereço usado, para já vir preenchido na próxima compra
        $this->exec("UPDATE clientes SET cep=?, endereco=? WHERE usuario_id=? AND (cep IS NULL OR cep='')", [$cep, $endereco, $uid]);
    }
    function criarParaUsuario($uid, $nome, $cep = null, $endereco = null) {
        $this->exec('INSERT INTO clientes(nome,usuario_id,cep,endereco) VALUES(?,?,?,?)', [$nome, $uid, $cep, $endereco]);
        return $this->porUsuario($uid);
    }
}
