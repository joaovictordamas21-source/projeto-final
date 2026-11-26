<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>LevelZone</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= url('public/css/estilo.css') ?>">
</head>
<?php
$rota = explode('/', $_GET['url'] ?? 'loja')[0];
$perfil = $usuario['perfil'] ?? null;
$equipe = in_array($perfil, ['admin', 'vendedor'], true);
$qtdCarrinho = array_sum($_SESSION['carrinho'] ?? []);
$rotulos = ['admin' => 'Administrador', 'vendedor' => 'Vendedor', 'cliente' => 'Cliente'];

// Ícones (SVG em linha, sem JavaScript e sem arquivos extras)
$icones = [
  'painel' => '<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>',
  'vendas' => '<circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2 3h2.3l2.7 12.4a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6L22 7H5.2"/>',
  'entradas' => '<path d="M21 8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4a2 2 0 0 0 1-1.7Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>',
  'produtos' => '<path d="M6 11h4M8 9v4M15 12h.01M18 10h.01"/><path d="M17.3 5H6.7a4 4 0 0 0-4 3.6C2.6 9.4 2 14.5 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.4-1.4a2 2 0 0 1 1.4-.6h4.4a2 2 0 0 1 1.4.6L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.5-.6-6.6-.7-7.4A4 4 0 0 0 17.3 5Z"/>',
  'categorias' => '<path d="M12.6 2.6A2 2 0 0 0 11.2 2H4a2 2 0 0 0-2 2v7.2a2 2 0 0 0 .6 1.4l8.7 8.7a2.4 2.4 0 0 0 3.4 0l6.6-6.6a2.4 2.4 0 0 0 0-3.4Z"/><circle cx="7.5" cy="7.5" r="1" fill="currentColor"/>',
  'fornecedores' => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.7a1 1 0 0 0-.2-.6l-3.5-4.4A1 1 0 0 0 17.5 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
  'relatorios' => '<path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M18 17V9M13 17V5M8 17v-3"/>',
  'mensagens' => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a2 2 0 0 1-2.06 0L2 7"/>',
  'usuarios' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
  'loja' => '<path d="M15 3h6v6M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
  'sair' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5M21 12H9"/>',
];
$svg = fn($k) => '<svg viewBox="0 0 24 24" aria-hidden="true">' . $icones[$k] . '</svg>';

// Logo: quadrado arredondado com gradiente, um "Z" e um pixel de destaque
$logo = '<svg class="logo-svg" viewBox="0 0 40 40" aria-hidden="true"><defs><linearGradient id="lg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#5b8cff"/><stop offset="1" stop-color="#8b5cf6"/></linearGradient></defs>'
      . '<rect width="40" height="40" rx="11" fill="url(#lg)"/>'
      . '<path d="M12 13h16L12 27h16" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>'
      . '<rect x="29" y="7" width="4" height="4" rx="1" fill="#fff" fill-opacity=".85"/></svg>';
?>
<body class="<?= $equipe ? 'com-lateral' : '' ?>">
<?php if ($equipe):
  $secoes = [
    'OPERAÇÃO' => ['painel' => 'Painel', 'vendas' => 'Vendas (balcão)', 'entradas' => 'Entradas de estoque'],
    'CADASTROS' => ['produtos' => 'Produtos', 'categorias' => 'Categorias', 'fornecedores' => 'Fornecedores'],
    'GESTÃO' => ['relatorios' => 'Relatórios', 'mensagens' => 'Mensagens', 'usuarios' => 'Usuários'],
  ];
  $rotaAtiva = $rota === 'notas' ? 'vendas' : $rota;
  $naoLidas = (new Mensagem)->contarNaoLidas();
  $inicial = mb_strtoupper(mb_substr($usuario['nome'], 0, 1));
?>
<aside class="lateral">
  <a class="marca" href="<?= url('painel') ?>"><?= $logo ?><span class="marca-texto"><b>Level<span>Zone</span></b><small>Painel da equipe</small></span></a>
  <nav>
    <?php foreach ($secoes as $tituloSecao => $grupoSecao): ?>
      <p class="secao"><?= $tituloSecao ?></p>
      <?php foreach ($grupoSecao as $rotaItem => $rotuloItem): ?>
        <a class="item <?= $rotaAtiva === $rotaItem ? 'on' : '' ?>" href="<?= url($rotaItem) ?>"><?= $svg($rotaItem) ?><span><?= $rotuloItem ?></span>
          <?php if ($rotaItem === 'mensagens' && $naoLidas): ?><span class="contador"><?= $naoLidas ?></span><?php endif; ?></a>
      <?php endforeach; ?>
    <?php endforeach; ?>
  </nav>
  <div class="lateral-rodape">
    <div class="usuario"><span class="avatar"><?= e($inicial) ?></span>
      <span class="usuario-nome"><strong><?= e($usuario['nome']) ?></strong><span class="perfil <?= e($perfil) ?>"><?= $rotulos[$perfil] ?></span></span></div>
    <div class="rodape-botoes">
      <a class="btn btn-sec btn-sm" href="<?= url('loja') ?>"><?= $svg('loja') ?>Ver loja</a>
      <a class="btn btn-sec btn-sm" href="<?= url('conta/sair') ?>"><?= $svg('sair') ?>Sair</a>
    </div>
  </div>
</aside>
<main class="conteudo">
<?php else: ?>
<header class="barra">
  <a class="marca" href="<?= url('loja') ?>"><?= $logo ?><span class="marca-texto"><b>Level<span>Zone</span></b></span></a>
  <nav>
    <a href="<?= url('loja') ?>" class="<?= $rota === 'loja' && ($_GET['url'] ?? 'loja') !== 'loja/contato' ? 'on' : '' ?>">Loja</a>
    <a href="<?= url('loja/contato') ?>" class="<?= ($_GET['url'] ?? '') === 'loja/contato' ? 'on' : '' ?>">Contato</a>
    <?php if ($perfil === 'cliente'): ?><a href="<?= url('pedidos') ?>" class="<?= $rota === 'pedidos' ? 'on' : '' ?>">Minhas compras</a><?php endif; ?>
  </nav>
  <div class="barra-dir">
    <a class="carrinho <?= $rota === 'carrinho' ? 'on' : '' ?>" href="<?= url('carrinho') ?>">Carrinho<?php if ($qtdCarrinho): ?> <span class="contador"><?= $qtdCarrinho ?></span><?php endif; ?></a>
    <?php if ($usuario): ?>
      <span><?= e($usuario['nome']) ?> <span class="perfil cliente"><?= $rotulos[$perfil] ?></span></span>
      <a class="btn btn-sec btn-sm" href="<?= url('conta/sair') ?>">Sair</a>
    <?php else: ?>
      <a class="btn btn-sec btn-sm" href="<?= url('conta/entrar') ?>">Entrar</a><a class="btn btn-sm" href="<?= url('conta/cadastrar') ?>">Criar conta</a>
    <?php endif; ?>
  </div>
</header>
<main class="pagina">
<?php endif; ?>
<?php if (!empty($_SESSION['aviso'])): [$avTipo, $avMsg] = $_SESSION['aviso']; unset($_SESSION['aviso']); ?>
  <div class="aviso <?= $avTipo ?>" role="status"><?= e($avMsg) ?></div>
<?php endif; ?>
