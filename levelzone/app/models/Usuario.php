<?php
class Usuario extends Modelo {
    function porEmail($e) { return $this->um('SELECT * FROM usuarios WHERE email=?', [$e]); }
    function achar($id) { return $this->um('SELECT id,nome,email,perfil,ativo FROM usuarios WHERE id=?', [$id]); }
    function emailEmUso($email, $ignorar = 0) { return $this->um('SELECT id FROM usuarios WHERE email=? AND id<>?', [$email, $ignorar]); }
    function adminsAtivos() { return (int)$this->um("SELECT COUNT(*) c FROM usuarios WHERE perfil='admin' AND ativo=1")['c']; }
    function buscar($busca, $perfil) {
        $l = "%$busca%";
        return $this->todos("SELECT u.id, u.nome, u.email, u.perfil, u.ativo,
            (SELECT COUNT(*) FROM vendas v JOIN clientes c ON c.id=v.cliente_id WHERE c.usuario_id=u.id) compras,
            (SELECT COALESCE(SUM(v.total),0) FROM vendas v JOIN clientes c ON c.id=v.cliente_id WHERE c.usuario_id=u.id) gasto,
            (SELECT COUNT(*) FROM vendas v WHERE v.usuario_id=u.id AND v.canal='balcao') vendas_balcao
            FROM usuarios u
            WHERE (? = '' OR u.nome LIKE ? OR u.email LIKE ?) AND (? = '' OR u.perfil = ?)
            ORDER BY FIELD(u.perfil,'admin','vendedor','cliente'), u.nome", [$busca, $l, $l, $perfil, $perfil]);
    }
    function contagens() {
        $r = ['admin' => 0, 'vendedor' => 0, 'cliente' => 0];
        foreach ($this->todos('SELECT perfil, COUNT(*) n FROM usuarios GROUP BY perfil') as $l) $r[$l['perfil']] = (int)$l['n'];
        $r['total'] = array_sum($r);
        return $r;
    }
    function atualizar($id, $nome, $email, $perfil) { $this->exec('UPDATE usuarios SET nome=?, email=?, perfil=? WHERE id=?', [$nome, $email, $perfil, $id]); }
    function trocarSenha($id, $senha) { $this->exec('UPDATE usuarios SET senha=? WHERE id=?', [password_hash($senha, PASSWORD_DEFAULT), $id]); }
    function alternar($id) { $this->exec('UPDATE usuarios SET ativo=1-ativo WHERE id=?', [$id]); }
    function criar($nome, $email, $senha, $perfil) {
        $this->exec('INSERT INTO usuarios(nome,email,senha,perfil) VALUES(?,?,?,?)', [$nome, $email, password_hash($senha, PASSWORD_DEFAULT), $perfil]);
        return (int)$this->bd->lastInsertId();
    }
    function semearPadroes() { // primeiro acesso: cria um administrador e um vendedor de exemplo
        if ($this->um("SELECT id FROM usuarios WHERE perfil IN ('admin','vendedor') LIMIT 1")) return;
        if (!$this->porEmail('admin@levelzone.com')) $this->criar('Administrador', 'admin@levelzone.com', 'admin123', 'admin');
        if (!$this->porEmail('vendedor@levelzone.com')) $this->criar('Vendedor', 'vendedor@levelzone.com', 'vendedor123', 'vendedor');
    }
}
