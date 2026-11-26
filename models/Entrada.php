<?php
class Entrada extends Modelo {
    function recentes($n = 10) {
        return $this->todos('SELECT e.*, f.nome fornecedor FROM entradas e JOIN fornecedores f ON f.id=e.fornecedor_id ORDER BY e.id DESC LIMIT ' . (int)$n);
    }
    function criar($fornecedorId, $usuarioId, $linhas) {
        $this->bd->beginTransaction();
        try {
            if (!$this->um('SELECT id FROM fornecedores WHERE id=? AND ativo=1', [$fornecedorId])) throw new Exception('Selecione um fornecedor ativo.');
            $this->exec('INSERT INTO entradas(fornecedor_id,usuario_id,criada_em,status,total) VALUES(?,?,?,?,0)', [$fornecedorId, $usuarioId, date('Y-m-d H:i:s'), 'confirmada']);
            $id = (int)$this->bd->lastInsertId(); $total = 0; $n = 0;
            foreach ($linhas as $l) {
                $vid = (int)($l['v'] ?? 0); $q = (int)($l['q'] ?? 0); $c = (float)str_replace(',', '.', $l['c'] ?? 0);
                if (!$vid || $q <= 0) continue;
                if ($c <= 0) throw new Exception('Informe o custo unitário de cada item com quantidade.');
                $this->exec('INSERT INTO itens_entrada(entrada_id,variacao_id,quantidade,custo_unitario) VALUES(?,?,?,?)', [$id, $vid, $q, $c]);
                $this->exec('UPDATE variacoes SET estoque=estoque+? WHERE id=?', [$q, $vid]);
                $total += $q * $c; $n++;
            }
            if (!$n) throw new Exception('Preencha ao menos um item com quantidade maior que zero.');
            $this->exec('UPDATE entradas SET total=? WHERE id=?', [$total, $id]);
            $this->bd->commit();
            return $id;
        } catch (Throwable $t) { $this->bd->rollBack(); throw $t; }
    }
}
