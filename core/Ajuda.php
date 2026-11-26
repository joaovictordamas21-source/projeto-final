<?php
class Ajuda {
    // QR Code FALSO, só para demonstração: desenha um padrão parecido com um QR Code (não contém nenhum dado real).
    static function qrFalso($semente, $n = 25) {
        $x = crc32((string)$semente) & 0x7fffffff;
        $m = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) { $x = ($x * 1103515245 + 12345) & 0x7fffffff; $m[$i][$j] = (($x >> 16) & 1) ? 1 : 0; }
        }
        foreach ([[0, 0], [0, $n - 7], [$n - 7, 0]] as [$r, $c]) { // os 3 quadrados de posição dos cantos
            for ($i = -1; $i <= 7; $i++) {
                for ($j = -1; $j <= 7; $j++) {
                    $y = $r + $i; $z = $c + $j;
                    if ($y < 0 || $z < 0 || $y >= $n || $z >= $n) continue;
                    $dentro = $i >= 0 && $i <= 6 && $j >= 0 && $j <= 6;
                    $borda = $dentro && ($i == 0 || $i == 6 || $j == 0 || $j == 6);
                    $centro = $i >= 2 && $i <= 4 && $j >= 2 && $j <= 4;
                    $m[$y][$z] = ($borda || $centro) ? 1 : 0;
                }
            }
        }
        for ($k = 8; $k < $n - 8; $k++) { $m[6][$k] = $k % 2 == 0 ? 1 : 0; $m[$k][6] = $k % 2 == 0 ? 1 : 0; }
        $d = '';
        foreach ($m as $i => $linha) foreach ($linha as $j => $v) if ($v) $d .= "M$j {$i}h1v1h-1z";
        $t = $n + 4;
        return '<svg class="qr" viewBox="-2 -2 ' . $t . ' ' . $t . '" role="img" aria-label="QR Code Pix de demonstração (falso)" shape-rendering="crispEdges">'
             . '<rect x="-2" y="-2" width="' . $t . '" height="' . $t . '" fill="#fff"/><path d="' . $d . '" fill="#111"/></svg>';
    }
}
