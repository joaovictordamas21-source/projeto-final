<?php
class MensagensController extends Controlador {
    function index() { $this->exigir(); $this->renderizar('mensagens_index', ['lista' => (new Mensagem)->listar()]); }
    function marcar() {
        $this->exigir();
        (new Mensagem)->marcar((int)($_POST['id'] ?? 0), !empty($_POST['lida']));
        $this->ir('mensagens');
    }
}
