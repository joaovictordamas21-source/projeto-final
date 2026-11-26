# LevelZone – Loja de jogos 

## Instalação (XAMPP + VS Code)
1. Apague a pasta `levelzone` antiga de `C:\xampp\htdocs\` (se existir) e copie esta no lugar.
2. No painel do XAMPP, inicie **Apache** e **MySQL**.
3. Em `http://localhost/phpmyadmin`, aba **Importar**, importe `database/levelzone.sql`.
   (O banco antigo, com tabelas em inglês, pode ser apagado: ele não é mais usado.)
4. Confira tudo em `http://localhost/levelzone/verificar.php` e depois abra `http://localhost/levelzone/`.

Logins da equipe (criados no primeiro acesso): admin@levelzone.com / admin123 · vendedor@levelzone.com / vendedor123.
Em **Criar conta** a pessoa escolhe o perfil; vendedor e administrador exigem o código da equipe em `config/config.php`.

## Estrutura MVC
- `index.php` – controlador frontal (rotas `/controlador/ação/parâmetro`)
- `app/models` – acesso ao banco · `app/views` – telas · `app/controllers` – regras
- `database/levelzone.sql` – tabelas em português e dados de exemplo
- `img/produtos` – capas enviadas pelo painel

## Colocar online (hospedagem com PHP + MySQL)
1. Crie a hospedagem e o banco MySQL no painel do provedor (anote servidor, nome do banco, usuário e senha).
2. Importe `database/levelzone_hospedagem.sql` no phpMyAdmin da hospedagem (sem CREATE DATABASE).
3. Edite `config/config.php`: BD_HOST, BD_NOME, BD_USUARIO, BD_SENHA e troque CODIGO_VENDEDOR / CODIGO_ADMIN.
4. Envie o conteúdo da pasta para `htdocs` ou `public_html` (inclusive o arquivo oculto `.htaccess`).
5. Deixe `img/produtos` com permissão de escrita e apague `verificar.php` depois de testar.
6. Entre como admin e troque as senhas de exemplo.

## Atualizações
- **Já importou o banco antes?** Importe `database/atualizacao_pagamento.sql` uma vez só (adiciona a forma de pagamento nas vendas). Em banco novo, use apenas `levelzone.sql`.
- **Pagamento:** o cliente escolhe Cartão (validado, nada é guardado além dos 4 últimos dígitos) ou Pix (QR Code FALSO, só de demonstração). Nenhum valor é cobrado.
- **Telas:** todos os arquivos de `app/views` ficam direto na pasta, sem subpastas (ex.: `loja_index.php`, `usuarios_form.php`).
- **Usuários:** pesquisa, filtro por perfil, edição (nome, e-mail, perfil, nova senha) e ativar/inativar.
