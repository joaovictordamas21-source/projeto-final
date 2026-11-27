<?php
class PainelController extends Controlador {
    function index() {
        $this->exigir();
        $r = new Relatorio;
        $this->renderizar('painel_index', [
            'hoje' => $r->hoje(), 'cont' => $r->contagens(), 'baixo' => $r->baixo(), 'topo' => $r->topo(),
            'vendas' => (new Venda)->recentes(5), 'msgs' => (new Mensagem)->contarNaoLidas(),
        ]);
    }
}
