<?php
class NotasController extends Controlador {
    function emitir($vendaId = 0) {
        $this->exigir();
        $m = new Venda;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$m->achar((int)$vendaId)) { $this->aviso('erro', 'Venda não encontrada.'); $this->ir('vendas'); }
        $m->emitirNota((int)$vendaId);
        $this->aviso('ok', 'Nota fiscal emitida.');
        $this->ir('vendas');
    }
    function ver($vendaId = 0) {
        $this->exigir('qualquer');
        $m = new Venda; $venda = $m->achar((int)$vendaId); $nota = $m->nota((int)$vendaId);
        if ($venda && !$this->ehEquipe()) { // cliente só vê as próprias notas
            $c = (new Cliente)->porUsuario($_SESSION['usuario']['id']);
            if (!$c || (int)$c['id'] !== (int)$venda['cliente_id']) $venda = null;
        }
        if (!$venda || !$nota) { $this->aviso('erro', 'Nota fiscal não encontrada.'); $this->ir($this->ehEquipe() ? 'vendas' : 'pedidos'); }
        $this->renderizar('notas_ver', ['venda' => $venda, 'nota' => $nota, 'itens' => $m->itens($venda['id'])]);
    }
}
