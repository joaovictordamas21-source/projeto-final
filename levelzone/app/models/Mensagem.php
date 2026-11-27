<?php
class Mensagem extends Modelo {
    function criar($nome, $email, $assunto, $texto, $uid) {
        $this->exec('INSERT INTO mensagens(nome,email,assunto,texto,usuario_id,criada_em) VALUES(?,?,?,?,?,?)', [$nome, $email, $assunto, $texto, $uid, date('Y-m-d H:i:s')]);
    }
    function listar() { return $this->todos('SELECT * FROM mensagens ORDER BY lida, id DESC'); }
    function marcar($id, $lida) { $this->exec('UPDATE mensagens SET lida=? WHERE id=?', [$lida ? 1 : 0, $id]); }
    function contarNaoLidas() { return (int)$this->um('SELECT COUNT(*) c FROM mensagens WHERE lida=0')['c']; }
}
