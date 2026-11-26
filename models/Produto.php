<?php
// "Produto" = o jogo. As versões vendáveis (plataforma, edição, SKU, preço, estoque) ficam em Variacao.
class Produto extends Modelo {
    function achar($id) { return $this->um('SELECT p.*, c.nome categoria FROM produtos p LEFT JOIN categorias c ON c.id=p.categoria_id WHERE p.id=?', [$id]); }
    function listarPainel($busca) {
        $l = "%$busca%";
        return $this->todos("SELECT p.*, c.nome categoria,
            (SELECT MIN(v.preco) FROM variacoes v WHERE v.produto_id=p.id) preco_min,
            (SELECT COALESCE(SUM(v.estoque),0) FROM variacoes v WHERE v.produto_id=p.id) estoque_total,
            (SELECT COUNT(*) FROM variacoes v WHERE v.produto_id=p.id) qtd_var,
            (SELECT GROUP_CONCAT(v.sku ORDER BY v.id SEPARATOR ',') FROM variacoes v WHERE v.produto_id=p.id) skus
            FROM produtos p LEFT JOIN categorias c ON c.id=p.categoria_id
            WHERE (? = '' OR p.nome LIKE ? OR c.nome LIKE ?
              OR EXISTS (SELECT 1 FROM variacoes v WHERE v.produto_id=p.id AND (v.sku LIKE ? OR v.plataforma LIKE ? OR v.edicao LIKE ?)))
            ORDER BY p.nome", [$busca, $l, $l, $l, $l, $l]);
    }
    function vitrine($cat, $busca) {
        $sql = 'SELECT p.*, c.nome categoria FROM produtos p LEFT JOIN categorias c ON c.id=p.categoria_id
                WHERE p.ativo=1 AND EXISTS (SELECT 1 FROM variacoes v WHERE v.produto_id=p.id AND v.ativo=1)';
        $a = [];
        if ($cat) { $sql .= ' AND p.categoria_id=?'; $a[] = $cat; }
        if ($busca !== '') {
            $l = "%$busca%";
            $sql .= ' AND (p.nome LIKE ? OR c.nome LIKE ? OR EXISTS (SELECT 1 FROM variacoes v WHERE v.produto_id=p.id AND v.ativo=1 AND (v.plataforma LIKE ? OR v.edicao LIKE ?)))';
            array_push($a, $l, $l, $l, $l);
        }
        return $this->todos($sql . ' ORDER BY p.nome', $a);
    }
    function salvar($id, $d) { // $d = [nome, descricao, categoria_id]
        if ($id) { $this->exec('UPDATE produtos SET nome=?,descricao=?,categoria_id=? WHERE id=?', [$d[0], $d[1], $d[2], $id]); return $id; }
        $this->exec('INSERT INTO produtos(nome,descricao,categoria_id) VALUES(?,?,?)', $d);
        return (int)$this->bd->lastInsertId();
    }
    function imagem($id, $arq) { $this->exec('UPDATE produtos SET imagem=? WHERE id=?', [$arq, $id]); }
    function alternar($id) { $this->exec('UPDATE produtos SET ativo=1-ativo WHERE id=?', [$id]); }
}
