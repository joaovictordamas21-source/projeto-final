<?php
class Controlador {
    protected function renderizar($v, $d = []) {
        extract($d);
        $usuario = $_SESSION['usuario'] ?? null;
        // todas as telas ficam direto em app/views (ex.: 'produtos/index' -> produtos_index.php)
        require __DIR__ . '/../views/cabecalho.php';
        require __DIR__ . '/../views/' . str_replace('/', '_', $v) . '.php';
        require __DIR__ . '/../views/rodape.php';
    }
    protected function ir($p) { header('Location: ' . url($p)); exit; }
    protected function aviso($tipo, $msg) { $_SESSION['aviso'] = [$tipo, $msg]; }
    protected function ehEquipe() { return in_array($_SESSION['usuario']['perfil'] ?? '', ['admin', 'vendedor'], true); }
    protected function inicio() { return $this->ehEquipe() ? 'painel' : 'loja'; }
    // $nivel: 'qualquer' (logado) ou 'equipe' (vendedor/administrador)
    protected function exigir($nivel = 'equipe') {
        if (empty($_SESSION['usuario'])) { $this->aviso('erro', 'Entre na sua conta para continuar.'); $this->ir('conta/entrar'); }
        if ($nivel === 'equipe' && !$this->ehEquipe()) { $this->aviso('erro', 'Você não tem acesso a esta área.'); $this->ir('loja'); }
    }
}
