<?php
class FornecedoresController extends Controlador {
    function index($editarId = 0) {
        $this->exigir(); $m = new Fornecedor;
        $this->renderizar('fornecedores_index', ['lista' => $m->listar(), 'edit' => $editarId ? $m->achar((int)$editarId) : null]);
    }
    function salvar() {
        $this->exigir(); $m = new Fornecedor; $id = (int)($_POST['id'] ?? 0);
        $dados = ['id' => $id, 'nome' => trim($_POST['nome'] ?? ''), 'cnpj' => $_POST['cnpj'] ?? '', 'telefone' => trim($_POST['telefone'] ?? ''),
                  'email' => trim($_POST['email'] ?? ''), 'cep' => $_POST['cep'] ?? '', 'endereco' => trim($_POST['endereco'] ?? '')];
        $cnpj = preg_replace('/\D/', '', $dados['cnpj']); $cep = preg_replace('/\D/', '', $dados['cep']);
        $voltar = function ($tipo, $msg) use ($m, &$dados) {
            $this->aviso($tipo, $msg);
            $this->renderizar('fornecedores_index', ['lista' => $m->listar(), 'edit' => $dados]);
        };
        if (($_POST['acao'] ?? '') === 'cep') { // botão "Buscar endereço pelo CEP"
            if (strlen($cep) !== 8) { $voltar('erro', 'Digite um CEP com 8 números e clique em Buscar.'); return; }
            $end = Ajuda::buscarCep($cep);
            if (!$end) { $voltar('erro', 'CEP não encontrado ou sem conexão. Digite o endereço manualmente.'); return; }
            $dados['endereco'] = $end;
            $voltar('ok', 'Endereço preenchido. Complete o número e o complemento e clique em Salvar.'); return;
        }
        if ($dados['nome'] === '' || strlen($cnpj) !== 14) { $voltar('erro', 'Informe o nome e um CNPJ com 14 números.'); return; }
        if (strlen($cep) !== 8) { $voltar('erro', 'Informe o CEP do fornecedor (8 números).'); return; }
        if ($dados['endereco'] === '') $dados['endereco'] = (string)Ajuda::buscarCep($cep); // preenche sozinho se faltar
        if ($dados['endereco'] === '') { $voltar('erro', 'CEP não encontrado. Digite o endereço do fornecedor.'); return; }
        if ($m->porCnpj($cnpj, $id)) { $voltar('erro', 'Já existe um fornecedor com este CNPJ. Procure-o na lista.'); return; }
        $m->salvar($id, [$dados['nome'], $cnpj, $dados['telefone'], $dados['email'], $cep, $dados['endereco']]);
        $this->aviso('ok', 'Fornecedor salvo.'); $this->ir('fornecedores');
    }
    function alternar() {
        $this->exigir(); (new Fornecedor)->alternar((int)($_POST['id'] ?? 0));
        $this->aviso('ok', 'Status do fornecedor atualizado.'); $this->ir('fornecedores');
    }
}
