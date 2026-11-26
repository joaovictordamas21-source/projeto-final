<?php
class CarrinhoController extends Controlador {
    private function linhas() {
        $c = $_SESSION['carrinho'] ?? []; if (!$c) return [];
        $saida = [];
        foreach ((new Variacao)->porIds(array_keys($c)) as $v) {
            if (!$v['ativo'] || !$v['produto_ativo']) continue;
            $v['qtd'] = min((int)$c[$v['id']], (int)$v['estoque']);
            if ($v['qtd'] > 0) $saida[] = $v;
        }
        return $saida;
    }
    private function somar($itens) { $t = 0; foreach ($itens as $i) $t += $i['qtd'] * $i['preco']; return $t; }
    function index() {
        $itens = $this->linhas();
        $this->renderizar('carrinho_index', ['itens' => $itens, 'total' => $this->somar($itens)]);
    }
    function adicionar() {
        $id = (int)($_POST['variacao'] ?? 0); $v = (new Variacao)->achar($id);
        if (!$v || !$v['ativo'] || !$v['produto_ativo'] || $v['estoque'] < 1) { $this->aviso('erro', 'Este produto está esgotado ou indisponível.'); $this->ir('loja'); }
        $_SESSION['carrinho'][$id] = min((int)$v['estoque'], ($_SESSION['carrinho'][$id] ?? 0) + 1);
        $this->aviso('ok', $v['jogo'] . ' (' . $v['plataforma'] . ') foi para o carrinho.');
        $this->ir('loja');
    }
    function atualizar() {
        foreach (($_POST['qtd'] ?? []) as $id => $q) {
            $id = (int)$id; $q = (int)$q; $v = (new Variacao)->achar($id);
            if ($q <= 0 || !$v) unset($_SESSION['carrinho'][$id]);
            else $_SESSION['carrinho'][$id] = min($q, (int)$v['estoque']);
        }
        $this->aviso('ok', 'Carrinho atualizado.');
        $this->ir('carrinho');
    }
    function remover() { unset($_SESSION['carrinho'][(int)($_POST['variacao'] ?? 0)]); $this->ir('carrinho'); }

    // Só cliente logado compra. Visitante vai para o login e volta para o pagamento.
    private function conferirCompra() {
        if (empty($_SESSION['usuario'])) {
            $_SESSION['depois_login'] = 'carrinho/pagamento';
            $this->aviso('erro', 'Entre ou crie sua conta para pagar. Seu carrinho será mantido.');
            $this->ir('conta/entrar');
        }
        if ($_SESSION['usuario']['perfil'] !== 'cliente') { $this->aviso('erro', 'Contas da equipe registram vendas pelo menu Vendas (balcão).'); $this->ir('carrinho'); }
    }
    private function mostrarPagamento($dados) {
        $itens = $this->linhas();
        if (!$itens) { $this->aviso('erro', 'Seu carrinho está vazio.'); $this->ir('loja'); }
        $_SESSION['pix_ref'] ??= strtoupper(bin2hex(random_bytes(5)));
        $this->renderizar('carrinho_pagamento', ['itens' => $itens, 'total' => $this->somar($itens), 'pixRef' => $_SESSION['pix_ref'], 'dados' => $dados]);
    }
    function pagamento() { $this->conferirCompra(); $this->mostrarPagamento([]); }

    function finalizar() {
        $this->conferirCompra();
        $forma = $_POST['forma'] ?? '';
        $dados = ['forma' => $forma, 'cartao_nome' => trim($_POST['cartao_nome'] ?? ''), 'cartao_validade' => trim($_POST['cartao_validade'] ?? ''), 'parcelas' => (int)($_POST['parcelas'] ?? 1)];
        if (!in_array($forma, ['cartao', 'pix'], true)) { $this->aviso('erro', 'Escolha a forma de pagamento: cartão ou Pix.'); $this->mostrarPagamento([]); return; }
        if ($forma === 'cartao') {
            $num = preg_replace('/\D/', '', $_POST['cartao_numero'] ?? ''); $cvv = preg_replace('/\D/', '', $_POST['cartao_cvv'] ?? '');
            $parc = max(1, min(3, $dados['parcelas'])); $erro = null;
            if ($dados['cartao_nome'] === '') $erro = 'Informe o nome impresso no cartão.';
            elseif (strlen($num) < 13 || strlen($num) > 19) $erro = 'Número do cartão inválido.';
            elseif (!preg_match('/^(0[1-9]|1[0-2])\/?(\d{2})$/', $dados['cartao_validade'], $m)) $erro = 'Validade inválida. Use o formato MM/AA.';
            elseif ((2000 + (int)$m[2]) < (int)date('Y') || ((2000 + (int)$m[2]) == (int)date('Y') && (int)$m[1] < (int)date('n'))) $erro = 'Cartão vencido.';
            elseif (strlen($cvv) < 3 || strlen($cvv) > 4) $erro = 'CVV inválido (3 ou 4 números).';
            if ($erro) { $this->aviso('erro', $erro); $this->mostrarPagamento($dados); return; }
            $detalhe = 'Cartão final ' . substr($num, -4) . ' · ' . $parc . 'x'; // o número completo e o CVV nunca são guardados
        } else {
            $detalhe = 'Pix · ref. ' . ($_SESSION['pix_ref'] ?? '');
        }
        $itens = $this->linhas();
        if (!$itens) { $this->aviso('erro', 'Seu carrinho está vazio.'); $this->ir('loja'); }
        $cl = new Cliente; $uid = $_SESSION['usuario']['id'];
        $cliente = $cl->porUsuario($uid) ?: $cl->criarParaUsuario($uid, $_SESSION['usuario']['nome']);
        try {
            $linhas = array_map(fn($i) => ['v' => $i['id'], 'q' => $i['qtd']], $itens);
            $venda = new Venda; $id = $venda->criar($cliente['id'], $uid, $linhas, false, 'online', [$forma, $detalhe]);
            $venda->emitirNota($id);
            unset($_SESSION['carrinho'], $_SESSION['pix_ref']);
            $this->aviso('ok', "Pagamento aprovado! Compra #$id concluída e nota fiscal emitida.");
            $this->ir("notas/ver/$id");
        } catch (Exception $e) { $this->aviso('erro', $e->getMessage()); $this->ir('carrinho'); }
    }
}
