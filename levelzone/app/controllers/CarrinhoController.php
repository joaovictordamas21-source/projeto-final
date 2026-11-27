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
            $_SESSION['depois_login'] = 'carrinho/entrega';
            $this->aviso('erro', 'Entre ou crie sua conta para pagar. Seu carrinho será mantido.');
            $this->ir('conta/entrar');
        }
        if ($_SESSION['usuario']['perfil'] !== 'cliente') { $this->aviso('erro', 'Contas da equipe registram vendas pelo menu Vendas (balcão).'); $this->ir('carrinho'); }
    }
    private function mostrarPagamento($dados) {
        $itens = $this->linhas();
        if (!$itens) { $this->aviso('erro', 'Seu carrinho está vazio.'); $this->ir('loja'); }
        if (empty($_SESSION['entrega'])) $this->ir('carrinho/entrega'); // sem CEP de entrega não segue para o pagamento
        $_SESSION['pix_ref'] ??= strtoupper(bin2hex(random_bytes(5)));
        $this->renderizar('carrinho_pagamento', ['itens' => $itens, 'total' => $this->somar($itens), 'pixRef' => $_SESSION['pix_ref'], 'dados' => $dados, 'entrega' => $_SESSION['entrega']]);
    }
    // Etapa 1: endereço de entrega (CEP obrigatório). O endereço é buscado pelo servidor, sem JavaScript.
    function entrega() {
        $this->conferirCompra();
        $itens = $this->linhas();
        if (!$itens) { $this->aviso('erro', 'Seu carrinho está vazio.'); $this->ir('loja'); }
        $cl = (new Cliente)->porUsuario($_SESSION['usuario']['id']);
        $dados = $_SESSION['entrega'] ?? ['cep' => $cl['cep'] ?? '', 'endereco' => $cl['endereco'] ?? '', 'numero' => ''];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cep = preg_replace('/\D/', '', $_POST['cep'] ?? '');
            $dados = ['cep' => $cep, 'endereco' => trim($_POST['endereco'] ?? ''), 'numero' => trim($_POST['numero'] ?? '')];
            if (($_POST['acao'] ?? '') === 'cep') { // botão "Buscar endereço pelo CEP"
                $end = strlen($cep) === 8 ? Ajuda::buscarCep($cep) : null;
                if ($end) { $dados['endereco'] = $end; $this->aviso('ok', 'Endereço preenchido. Informe o número e clique em Continuar.'); }
                else $this->aviso('erro', 'CEP não encontrado. Confira os 8 números ou digite o endereço.');
            } else {
                $erro = null;
                if (strlen($cep) !== 8) $erro = 'Informe o CEP de entrega (8 números).';
                else {
                    if ($dados['endereco'] === '') $dados['endereco'] = (string)Ajuda::buscarCep($cep);
                    if ($dados['endereco'] === '') $erro = 'CEP não encontrado. Digite o endereço completo.';
                    elseif ($dados['numero'] === '') $erro = 'Informe o número e o complemento do endereço.';
                }
                if ($erro) $this->aviso('erro', $erro);
                else { $_SESSION['entrega'] = $dados; $this->ir('carrinho/pagamento'); }
            }
        }
        $this->renderizar('carrinho_entrega', ['itens' => $itens, 'total' => $this->somar($itens), 'dados' => $dados]);
    }
    function pagamento() { $this->conferirCompra(); $this->mostrarPagamento([]); }

    function finalizar() {
        $this->conferirCompra();
        $ent = $_SESSION['entrega'] ?? null;
        if (!$ent) { $this->aviso('erro', 'Informe o CEP e o endereço de entrega antes de pagar.'); $this->ir('carrinho/entrega'); }
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
            $venda = new Venda; $id = $venda->criar($cliente['id'], $uid, $linhas, false, 'online', [$forma, $detalhe], [$ent['cep'], $ent['endereco'] . ', ' . $ent['numero']]);
            $venda->emitirNota($id);
            $cl->salvarEnderecoSeVazio($uid, $ent['cep'], $ent['endereco'] . ', ' . $ent['numero']);
            unset($_SESSION['carrinho'], $_SESSION['pix_ref'], $_SESSION['entrega']);
            $this->aviso('ok', "Pagamento aprovado! Compra #$id concluída e nota fiscal emitida.");
            $this->ir("notas/ver/$id");
        } catch (Exception $e) { $this->aviso('erro', $e->getMessage()); $this->ir('carrinho'); }
    }
}
