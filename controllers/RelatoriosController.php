<?php
class RelatoriosController extends Controlador {
    private function dados() {
        $r = new Relatorio;
        return ['topo' => $r->topo(), 'fornecedores' => $r->fornecedores(), 'entradas' => $r->entradas(), 'baixo' => $r->baixo()];
    }
    function index() { $this->exigir(); $this->renderizar('relatorios_index', $this->dados()); }
    function imprimir() { // página limpa: use Ctrl+P para imprimir ou salvar em PDF
        $this->exigir();
        extract($this->dados());
        require __DIR__ . '/../views/relatorios_imprimir.php';
    }
    function excel() {
        $this->exigir(); $d = $this->dados();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="relatorio-levelzone-' . date('Ymd-Hi') . '.csv"');
        $o = fopen('php://output', 'w'); fwrite($o, "\xEF\xBB\xBF");
        $w = fn($linha) => fputcsv($o, $linha, ';');
        $w(['Relatório Gerencial - LevelZone', 'Gerado em ' . date('d/m/Y H:i')]); $w([]);
        $w(['Jogos mais vendidos']); $w(['Jogo', 'Plataforma', 'Edição', 'Qtde. vendida']);
        foreach ($d['topo'] as $r) $w([$r['jogo'], $r['plataforma'], $r['edicao'], $r['qtd']]);
        $w([]); $w(['Fornecedores']); $w(['Fornecedor', 'Entradas', 'Valor total']);
        foreach ($d['fornecedores'] as $r) $w([$r['nome'], $r['qtd'], number_format($r['total'], 2, ',', '.')]);
        $w([]); $w(['Estoque baixo']); $w(['Jogo', 'Plataforma', 'Edição', 'SKU', 'Qtde.', 'Mínimo']);
        foreach ($d['baixo'] as $r) $w([$r['jogo'], $r['plataforma'], $r['edicao'], $r['sku'], $r['estoque'], $r['estoque_minimo']]);
        exit;
    }
}
