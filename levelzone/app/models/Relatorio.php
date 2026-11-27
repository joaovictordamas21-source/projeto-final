<?php
class Relatorio extends Modelo {
    function topo() {
        return $this->todos('SELECT p.nome jogo, v.plataforma, v.edicao, SUM(i.quantidade) qtd FROM itens_venda i
            JOIN variacoes v ON v.id=i.variacao_id JOIN produtos p ON p.id=v.produto_id
            GROUP BY v.id, p.nome, v.plataforma, v.edicao ORDER BY qtd DESC LIMIT 5');
    }
    function fornecedores() {
        return $this->todos("SELECT f.nome, COUNT(e.id) qtd, COALESCE(SUM(e.total),0) total FROM fornecedores f
            LEFT JOIN entradas e ON e.fornecedor_id=f.id AND e.status='confirmada' GROUP BY f.id, f.nome ORDER BY total DESC");
    }
    function entradas() { return (new Entrada)->recentes(5); }
    function baixo() {
        return $this->todos('SELECT p.nome jogo, v.plataforma, v.edicao, v.sku, v.estoque, v.estoque_minimo FROM variacoes v
            JOIN produtos p ON p.id=v.produto_id WHERE v.ativo=1 AND p.ativo=1 AND v.estoque<=v.estoque_minimo ORDER BY v.estoque');
    }
    function hoje() { return $this->um('SELECT COUNT(*) qtd, COALESCE(SUM(total),0) total FROM vendas WHERE DATE(criada_em)=?', [date('Y-m-d')]); }
    function contagens() {
        return $this->um('SELECT (SELECT COUNT(*) FROM produtos WHERE ativo=1) jogos, (SELECT COALESCE(SUM(estoque),0) FROM variacoes) unidades,
            (SELECT COUNT(*) FROM fornecedores WHERE ativo=1) fornecedores');
    }
}
