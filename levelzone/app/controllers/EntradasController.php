<?php
class EntradasController extends Controlador {
    function index() {
        $this->exigir();
        $_SESSION['entrada_unica'] = bin2hex(random_bytes(8));
        $this->renderizar('entradas_index', ['fornecedores' => (new Fornecedor)->listar(true), 'variacoes' => (new Variacao)->ativas(), 'recentes' => (new Entrada)->recentes()]);
    }
    function salvar() {
        $this->exigir();
        // impede confirmar a mesma entrada duas vezes (reenvio do formulário)
        if (empty($_SESSION['entrada_unica']) || !hash_equals($_SESSION['entrada_unica'], $_POST['unica'] ?? '')) {
            $this->aviso('erro', 'Esta entrada já foi confirmada ou a página expirou.'); $this->ir('entradas');
        }
        unset($_SESSION['entrada_unica']);
        try {
            $id = (new Entrada)->criar((int)($_POST['fornecedor'] ?? 0), $_SESSION['usuario']['id'], $_POST['itens'] ?? []);
            $this->aviso('ok', "Entrada #$id confirmada e estoque atualizado.");
        } catch (Exception $e) { $this->aviso('erro', $e->getMessage()); }
        $this->ir('entradas');
    }
}
