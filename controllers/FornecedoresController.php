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
        if (($_POST['acao'] ?? '') === 'cep') { // busca o endereço no ViaCEP pelo servidor (sem JavaScript)
            if (strlen($cep) !== 8) $this->aviso('erro', 'Digite um CEP com 8 números e clique em Buscar CEP.');
            else {
                $ctx = stream_context_create(['http' => ['timeout' => 6]]);
                $r = json_decode((string)@file_get_contents("https://viacep.com.br/ws/$cep/json/", false, $ctx), true);
                if (!$r || !empty($r['erro'])) $this->aviso('erro', 'CEP não encontrado ou sem conexão. Digite o endereço manualmente.');
                else {
                    $dados['endereco'] = implode(', ', array_filter([$r['logradouro'] ?? '', $r['bairro'] ?? '', ($r['localidade'] ?? '') . '/' . ($r['uf'] ?? '')]));
                    $this->aviso('ok', 'Endereço preenchido. Complete o número e o complemento e clique em Salvar.');
                }
            }
            $this->renderizar('fornecedores_index', ['lista' => $m->listar(), 'edit' => $dados]); return;
        }
        if ($dados['nome'] === '' || strlen($cnpj) !== 14) { $this->aviso('erro', 'Informe o nome e um CNPJ com 14 números.'); $this->renderizar('fornecedores_index', ['lista' => $m->listar(), 'edit' => $dados]); return; }
        if ($m->porCnpj($cnpj, $id)) { $this->aviso('erro', 'Já existe um fornecedor com este CNPJ. Procure-o na lista.'); $this->renderizar('fornecedores_index', ['lista' => $m->listar(), 'edit' => $dados]); return; }
        $m->salvar($id, [$dados['nome'], $cnpj, $dados['telefone'], $dados['email'], $cep, $dados['endereco']]);
        $this->aviso('ok', 'Fornecedor salvo.'); $this->ir('fornecedores');
    }
    function alternar() {
        $this->exigir(); (new Fornecedor)->alternar((int)($_POST['id'] ?? 0));
        $this->aviso('ok', 'Status do fornecedor atualizado.'); $this->ir('fornecedores');
    }
}
