<?php
class Venda extends Modelo {
    function recentes($n = 10) {
        return $this->todos("SELECT v.*, c.nome cliente, IF(v.canal='online','Loja online',u.nome) vendedor, n.id nota_id
            FROM vendas v JOIN clientes c ON c.id=v.cliente_id JOIN usuarios u ON u.id=v.usuario_id
            LEFT JOIN notas_fiscais n ON n.venda_id=v.id ORDER BY v.id DESC LIMIT " . (int)$n);
    }
    function achar($id) { return $this->um('SELECT v.*, c.nome cliente FROM vendas v JOIN clientes c ON c.id=v.cliente_id WHERE v.id=?', [$id]); }
    function itens($id) {
        return $this->todos('SELECT i.*, x.sku, x.plataforma, x.edicao, p.nome jogo FROM itens_venda i
            JOIN variacoes x ON x.id=i.variacao_id JOIN produtos p ON p.id=x.produto_id WHERE i.venda_id=?', [$id]);
    }
    function paraCliente($cid) {
        return $this->todos('SELECT v.*, n.id nota_id, n.numero FROM vendas v LEFT JOIN notas_fiscais n ON n.venda_id=v.id WHERE v.cliente_id=? ORDER BY v.id DESC', [$cid]);
    }
    function criar($clienteId, $usuarioId, $linhas, $sobrepor, $canal = 'balcao', $pagamento = [null, null]) {
        $this->bd->beginTransaction();
        try {
            if (!$this->um('SELECT id FROM clientes WHERE id=?', [$clienteId])) throw new Exception('Selecione o cliente.');
            $this->exec('INSERT INTO vendas(cliente_id,usuario_id,criada_em,total,canal,forma_pagamento,pagamento_detalhe) VALUES(?,?,?,0,?,?,?)', [$clienteId, $usuarioId, date('Y-m-d H:i:s'), $canal, $pagamento[0], $pagamento[1]]);
            $id = (int)$this->bd->lastInsertId(); $total = 0; $n = 0;
            foreach ($linhas as $l) {
                $vid = (int)($l['v'] ?? 0); $q = (int)($l['q'] ?? 0);
                if (!$vid || $q <= 0) continue;
                $v = $this->um('SELECT v.*, p.nome jogo FROM variacoes v JOIN produtos p ON p.id=v.produto_id WHERE v.id=? AND v.ativo=1 AND p.ativo=1 FOR UPDATE', [$vid]);
                if (!$v) throw new Exception('Produto não encontrado ou inativo.');
                if ($v['estoque'] < $q) throw new Exception("Quantidade indisponível para {$v['jogo']} ({$v['plataforma']}): restam {$v['estoque']} unidade(s).");
                $preco = (float)$v['preco'];
                if ($sobrepor) { $pr = (float)str_replace(',', '.', $l['pr'] ?? 0); if ($pr > 0) $preco = $pr; }
                $this->exec('INSERT INTO itens_venda(venda_id,variacao_id,quantidade,preco_unitario) VALUES(?,?,?,?)', [$id, $vid, $q, $preco]);
                $this->exec('UPDATE variacoes SET estoque=estoque-? WHERE id=?', [$q, $vid]);
                $total += $q * $preco; $n++;
            }
            if (!$n) throw new Exception('Adicione ao menos um produto à venda.');
            $this->exec('UPDATE vendas SET total=? WHERE id=?', [$total, $id]);
            $this->bd->commit();
            return $id;
        } catch (Throwable $t) { $this->bd->rollBack(); throw $t; }
    }
    function nota($vendaId) { return $this->um('SELECT * FROM notas_fiscais WHERE venda_id=?', [$vendaId]); }
    function emitirNota($vendaId) {
        if ($n = $this->nota($vendaId)) return $n['id'];
        $prox = (int)$this->um('SELECT COALESCE(MAX(numero),0)+1 n FROM notas_fiscais')['n'];
        $this->exec('INSERT INTO notas_fiscais(venda_id,numero,serie,modelo,emitida_em) VALUES(?,?,1,?,?)', [$vendaId, $prox, 'NFC-e', date('Y-m-d H:i:s')]);
        return (int)$this->bd->lastInsertId();
    }
}
