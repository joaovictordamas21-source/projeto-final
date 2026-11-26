<?php
class ContaController extends Controlador {
    private function iniciar($r) {
        session_regenerate_id(true);
        $_SESSION['usuario'] = ['id' => $r['id'], 'nome' => $r['nome'], 'email' => $r['email'], 'perfil' => $r['perfil']];
        $para = $_SESSION['depois_login'] ?? null; unset($_SESSION['depois_login']);
        $this->ir($para ?: $this->inicio());
    }
    function entrar() {
        $u = new Usuario; $u->semearPadroes();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $r = $u->porEmail(strtolower(trim($_POST['email'] ?? '')));
            if ($r && $r['ativo'] && password_verify($_POST['senha'] ?? '', $r['senha'])) $this->iniciar($r);
            $this->aviso('erro', 'E-mail ou senha incorretos. Confira os dados e tente novamente.');
            $this->ir('conta/entrar');
        }
        $this->renderizar('conta_entrar');
    }
    function cadastrar() {
        $u = new Usuario; $u->semearPadroes();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? ''); $email = strtolower(trim($_POST['email'] ?? '')); $senha = $_POST['senha'] ?? '';
            $perfil = $_POST['perfil'] ?? 'cliente';
            $codigos = ['vendedor' => CODIGO_VENDEDOR, 'admin' => CODIGO_ADMIN];
            $erro = null;
            if ($nome === '') $erro = 'Informe seu nome.';
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erro = 'Informe um e-mail válido.';
            elseif (strlen($senha) < 6) $erro = 'A senha precisa ter pelo menos 6 caracteres.';
            elseif ($senha !== ($_POST['senha2'] ?? '')) $erro = 'As senhas não são iguais.';
            elseif (!in_array($perfil, ['cliente', 'vendedor', 'admin'], true)) $erro = 'Escolha um perfil válido.';
            elseif ($perfil !== 'cliente' && !hash_equals(strtoupper($codigos[$perfil]), strtoupper(trim($_POST['codigo'] ?? '')))) $erro = 'Código da equipe incorreto. Peça o código ao administrador da loja.';
            elseif ($u->porEmail($email)) $erro = 'Já existe uma conta com este e-mail. Entre ou use outro e-mail.';
            if ($erro) { // mostra o formulário de novo, mantendo o que foi digitado
                $this->aviso('erro', $erro);
                $this->renderizar('conta_cadastrar', ['dados' => ['perfil' => $perfil, 'nome' => $nome, 'email' => $email]]);
                return;
            }
            $id = $u->criar($nome, $email, $senha, $perfil);
            if ($perfil === 'cliente') (new Cliente)->criarParaUsuario($id, $nome);
            $this->aviso('ok', 'Conta criada! Bem-vindo à LevelZone.');
            $this->iniciar(['id' => $id, 'nome' => $nome, 'email' => $email, 'perfil' => $perfil]);
        }
        $this->renderizar('conta_cadastrar');
    }
    function sair() { session_destroy(); header('Location: ' . url('loja')); exit; }
}
