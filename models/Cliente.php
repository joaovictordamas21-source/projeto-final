<?php
class Cliente extends Modelo {
    function listar() { return $this->todos('SELECT * FROM clientes ORDER BY id'); }
    function porUsuario($uid) { return $this->um('SELECT * FROM clientes WHERE usuario_id=?', [$uid]); }
    function criarParaUsuario($uid, $nome) {
        $this->exec('INSERT INTO clientes(nome,usuario_id) VALUES(?,?)', [$nome, $uid]);
        return $this->porUsuario($uid);
    }
}
