<?php
// Gestão de usuários: pesquisa, filtro por perfil, criação, edição (nome, e-mail, perfil, nova senha) e ativar/inativar.
class UsuariosController extends Controlador {
    private const PERFIS = ['admin', 'vendedor', 'cliente'];

    function index() {
        $this->exigir();
        $busca = trim($_GET['busca'] ?? ''); $filtro = $_GET['perfil'] ?? '';
        if (!in_array($filtro, self::PERFIS, true)) $filtro = '';
        $m = new Usuario;
        $this->renderizar('usuarios_index', ['lista' => $m->buscar($busca, $filtro), 'busca' => $busca, 'filtro' => $filtro, 'cont' => $m->contagens()]);
    }
    function editar($id = 0) {
        $this->exigir();
        $alvo = (new Usuario)->achar((int)$id);
        if (!$alvo) { $this->aviso('erro', 'Usuário não encontrado.'); $this->ir('usuarios'); }
        $this->renderizar('usuarios_form', ['alvo' => $alvo]);
    }
    function salvar() { // novo usuário da equipe
        $this->exigir(); $m = new Usuario;
        $nome = trim($_POST['nome'] ?? ''); $email = strtolower(trim($_POST['email'] ?? '')); $perfil = $_POST['perfil'] ?? ''; $senha = $_POST['senha'] ?? '';
        $erro = null;
        if ($nome === '') $erro = 'Informe o nome.';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erro = 'Informe um e-mail válido.';
        elseif (!in_array($perfil, ['vendedor', 'admin'], true)) $erro = 'Escolha vendedor ou administrador.';
        elseif (strlen($senha) < 6) $erro = 'A senha precisa ter pelo menos 6 caracteres.';
        elseif ($m->porEmail($email)) $erro = 'Já existe um usuário com este e-mail.';
        if ($erro) { $this->aviso('erro', $erro); $this->ir('usuarios'); }
        $m->criar($nome, $email, $senha, $perfil);
        $this->aviso('ok', 'Usuário criado.'); $this->ir('usuarios');
    }
    function atualizar() {
        $this->exigir(); $m = new Usuario; $id = (int)($_POST['id'] ?? 0); $alvo = $m->achar($id);
        if (!$alvo) { $this->aviso('erro', 'Usuário não encontrado.'); $this->ir('usuarios'); }
        $nome = trim($_POST['nome'] ?? ''); $email = strtolower(trim($_POST['email'] ?? '')); $perfil = $_POST['perfil'] ?? ''; $senha = $_POST['senha'] ?? '';
        $eu = $id === (int)$_SESSION['usuario']['id'];
        $erro = null;
        if ($nome === '') $erro = 'Informe o nome.';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erro = 'Informe um e-mail válido.';
        elseif (!in_array($perfil, self::PERFIS, true)) $erro = 'Escolha um perfil válido.';
        elseif ($senha !== '' && strlen($senha) < 6) $erro = 'A nova senha precisa ter pelo menos 6 caracteres.';
        elseif ($m->emailEmUso($email, $id)) $erro = 'Já existe outro usuário com este e-mail.';
        elseif ($alvo['perfil'] === 'admin' && $perfil !== 'admin' && $alvo['ativo'] && $m->adminsAtivos() <= 1) $erro = 'Não dá para tirar o perfil do último administrador ativo.';
        elseif ($eu && $perfil === 'cliente') $erro = 'Você não pode mudar a própria conta para cliente.';
        if ($erro) { $this->aviso('erro', $erro); $this->ir('usuarios/editar/' . $id); }
        $m->atualizar($id, $nome, $email, $perfil);
        if ($senha !== '') $m->trocarSenha($id, $senha);
        if ($eu) { $_SESSION['usuario']['nome'] = $nome; $_SESSION['usuario']['email'] = $email; $_SESSION['usuario']['perfil'] = $perfil; }
        $this->aviso('ok', 'Usuário atualizado.'); $this->ir('usuarios');
    }
    function alternar() {
        $this->exigir(); $m = new Usuario; $id = (int)($_POST['id'] ?? 0); $alvo = $m->achar($id);
        if (!$alvo) $this->ir('usuarios');
        if ($id === (int)$_SESSION['usuario']['id']) $this->aviso('erro', 'Você não pode inativar a própria conta.');
        elseif ($alvo['perfil'] === 'admin' && $alvo['ativo'] && $m->adminsAtivos() <= 1) $this->aviso('erro', 'Não dá para inativar o último administrador ativo.');
        else { $m->alternar($id); $this->aviso('ok', 'Status do usuário atualizado.'); }
        $this->ir('usuarios');
    }
}
