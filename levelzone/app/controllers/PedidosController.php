<?php
class PedidosController extends Controlador {
    function index() {
        $this->exigir('qualquer');
        if ($this->ehEquipe()) $this->ir('vendas');
        $c = (new Cliente)->porUsuario($_SESSION['usuario']['id']);
        $this->renderizar('pedidos_index', ['pedidos' => $c ? (new Venda)->paraCliente($c['id']) : []]);
    }
}
