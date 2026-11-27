    <?php
    class VendasController extends Controlador {
        function index() {
            $this->exigir();
            $this->renderizar('vendas_index', ['clientes' => (new Cliente)->listar(), 'variacoes' => (new Variacao)->ativas(), 'recentes' => (new Venda)->recentes()]);
        }
        function salvar() {
            $this->exigir();
            try {
                $id = (new Venda)->criar((int)($_POST['cliente'] ?? 0), $_SESSION['usuario']['id'], $_POST['itens'] ?? [], true);
                $this->aviso('ok', "Venda #$id finalizada.");
            } catch (Exception $e) { $this->aviso('erro', $e->getMessage()); }
            $this->ir('vendas');
        }
    }
