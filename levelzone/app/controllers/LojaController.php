<?php
class LojaController extends Controlador {
    function index() {
        $cat = (int)($_GET['cat'] ?? 0); $busca = trim($_GET['busca'] ?? '');
        $jogos = (new Produto)->vitrine($cat, $busca);
        $this->renderizar('loja_index', [
            'cats' => (new Categoria)->listar(), 'jogos' => $jogos,
            'vars' => (new Variacao)->agrupadas(array_column($jogos, 'id')), 'cat' => $cat, 'busca' => $busca,
        ]);
    }
    function contato() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? ''); $email = trim($_POST['email'] ?? ''); $assunto = trim($_POST['assunto'] ?? ''); $texto = trim($_POST['texto'] ?? '');
            if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $assunto === '' || $texto === '') {
                $this->aviso('erro', 'Preencha nome, e-mail válido, assunto e mensagem.'); $this->ir('loja/contato');
            }
            (new Mensagem)->criar($nome, $email, $assunto, $texto, $_SESSION['usuario']['id'] ?? null);
            $this->aviso('ok', 'Mensagem enviada! Nossa equipe responderá por e-mail.');
            $this->ir('loja/contato');
        }
        $this->renderizar('loja_contato');
    }
}
