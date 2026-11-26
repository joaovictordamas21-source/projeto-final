<?php
class Variacao extends Modelo {
    function achar($id) {
        return $this->um('SELECT v.*, p.nome jogo, p.imagem, p.ativo produto_ativo FROM variacoes v JOIN produtos p ON p.id=v.produto_id WHERE v.id=?', [$id]);
    }
    function doProduto($pid) { return $this->todos('SELECT * FROM variacoes WHERE produto_id=? ORDER BY id', [$pid]); }
    function agrupadas(array $ids) { // variações ativas, agrupadas por jogo
        $ids = array_values(array_map('intval', $ids)); if (!$ids) return [];
        $r = $this->todos('SELECT * FROM variacoes WHERE ativo=1 AND produto_id IN (' . implode(',', array_fill(0, count($ids), '?')) . ') ORDER BY id', $ids);
        $g = [];
        foreach ($r as $v) $g[$v['produto_id']][] = $v;
        return $g;
    }
    function porIds(array $ids) {
        $ids = array_values(array_map('intval', $ids)); if (!$ids) return [];
        return $this->todos('SELECT v.*, p.nome jogo, p.imagem, p.ativo produto_ativo FROM variacoes v JOIN produtos p ON p.id=v.produto_id WHERE v.id IN (' . implode(',', array_fill(0, count($ids), '?')) . ')', $ids);
    }
    function ativas() {
        return $this->todos('SELECT v.*, p.nome jogo FROM variacoes v JOIN produtos p ON p.id=v.produto_id WHERE v.ativo=1 AND p.ativo=1 ORDER BY p.nome, v.plataforma, v.edicao');
    }
    function skuExiste($sku, $ignorar = 0) { return $this->um('SELECT id FROM variacoes WHERE sku=? AND id<>?', [$sku, $ignorar]); }
    function criar($pid, $d) { // $d = [plataforma, edicao, sku, preco, estoque, minimo]
        $this->exec('INSERT INTO variacoes(produto_id,plataforma,edicao,sku,preco,estoque,estoque_minimo) VALUES(?,?,?,?,?,?,?)', [$pid, $d[0], $d[1], $d[2], $d[3], $d[4], $d[5]]);
    }
    function atualizar($id, $d) {
        $this->exec('UPDATE variacoes SET plataforma=?,edicao=?,sku=?,preco=?,estoque_minimo=? WHERE id=?', [$d[0], $d[1], $d[2], $d[3], $d[5], $id]);
    }
    function alternar($id) { $this->exec('UPDATE variacoes SET ativo=1-ativo WHERE id=?', [$id]); }
}
