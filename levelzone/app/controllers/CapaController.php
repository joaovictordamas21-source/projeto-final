<?php
// Gera uma capa (SVG) para jogos sem imagem enviada. Não usa JavaScript nem arquivos.
class CapaController extends Controlador {
    function ver($id = 0) {
        $p = (new Produto)->achar((int)$id);
        $nome = $p['nome'] ?? 'Jogo';
        $h = abs(crc32($nome)) % 360; $h2 = ($h + 40) % 360;
        $palavras = preg_split('/\s+/', trim($nome));
        $ini = '';
        foreach (array_slice($palavras, 0, 2) as $w) $ini .= mb_strtoupper(mb_substr($w, 0, 1));
        $linhas = []; $atual = '';
        foreach ($palavras as $w) {
            if ($atual !== '' && mb_strlen($atual . ' ' . $w) > 16) { $linhas[] = $atual; $atual = $w; }
            else $atual = trim($atual . ' ' . $w);
        }
        if ($atual !== '') $linhas[] = $atual;
        header('Content-Type: image/svg+xml; charset=utf-8');
        header('Cache-Control: public, max-age=3600');
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 200"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">'
           . '<stop offset="0" stop-color="hsl(' . $h . ',60%,38%)"/><stop offset="1" stop-color="hsl(' . $h2 . ',55%,13%)"/></linearGradient></defs>'
           . '<rect width="300" height="200" fill="url(#g)"/>'
           . '<text x="20" y="100" font-family="Arial,sans-serif" font-size="76" font-weight="700" fill="#fff" fill-opacity=".92">' . e($ini) . '</text>';
        $y = 138;
        foreach (array_slice($linhas, 0, 3) as $l) { echo '<text x="20" y="' . $y . '" font-family="Arial,sans-serif" font-size="17" font-weight="600" fill="#fff" fill-opacity=".85">' . e($l) . '</text>'; $y += 22; }
        echo '</svg>';
    }
}
