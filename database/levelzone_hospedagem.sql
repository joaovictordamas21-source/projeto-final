
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(80) NOT NULL, email VARCHAR(120) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL, perfil ENUM('admin','vendedor','cliente') NOT NULL DEFAULT 'cliente', ativo TINYINT(1) NOT NULL DEFAULT 1
);
CREATE TABLE categorias (id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(60) NOT NULL UNIQUE);
CREATE TABLE produtos (
  id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(120) NOT NULL, descricao VARCHAR(500) NOT NULL DEFAULT '',
  categoria_id INT NULL, imagem VARCHAR(80) NULL, ativo TINYINT(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);
CREATE TABLE variacoes (
  id INT AUTO_INCREMENT PRIMARY KEY, produto_id INT NOT NULL, plataforma VARCHAR(30) NOT NULL, edicao VARCHAR(40) NOT NULL DEFAULT 'Padrão',
  sku VARCHAR(40) NOT NULL UNIQUE, preco DECIMAL(10,2) NOT NULL, estoque INT NOT NULL DEFAULT 0, estoque_minimo INT NOT NULL DEFAULT 3,
  ativo TINYINT(1) NOT NULL DEFAULT 1, FOREIGN KEY (produto_id) REFERENCES produtos(id)
);
CREATE TABLE fornecedores (
  id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(120) NOT NULL, cnpj VARCHAR(18) NOT NULL UNIQUE,
  telefone VARCHAR(30), email VARCHAR(120), cep VARCHAR(9), endereco VARCHAR(255), ativo TINYINT(1) NOT NULL DEFAULT 1
);
CREATE TABLE clientes (id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(120) NOT NULL, usuario_id INT NULL UNIQUE);
CREATE TABLE entradas (
  id INT AUTO_INCREMENT PRIMARY KEY, fornecedor_id INT NOT NULL, usuario_id INT NOT NULL, criada_em DATETIME NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'confirmada', total DECIMAL(12,2) NOT NULL DEFAULT 0,
  FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id), FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
CREATE TABLE itens_entrada (
  id INT AUTO_INCREMENT PRIMARY KEY, entrada_id INT NOT NULL, variacao_id INT NOT NULL, quantidade INT NOT NULL, custo_unitario DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (entrada_id) REFERENCES entradas(id), FOREIGN KEY (variacao_id) REFERENCES variacoes(id)
);
CREATE TABLE vendas (
  id INT AUTO_INCREMENT PRIMARY KEY, cliente_id INT NOT NULL, usuario_id INT NOT NULL, criada_em DATETIME NOT NULL,
  total DECIMAL(12,2) NOT NULL DEFAULT 0, canal ENUM('balcao','online') NOT NULL DEFAULT 'balcao',
  forma_pagamento VARCHAR(20) NULL, pagamento_detalhe VARCHAR(80) NULL,
  FOREIGN KEY (cliente_id) REFERENCES clientes(id), FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
CREATE TABLE itens_venda (
  id INT AUTO_INCREMENT PRIMARY KEY, venda_id INT NOT NULL, variacao_id INT NOT NULL, quantidade INT NOT NULL, preco_unitario DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (venda_id) REFERENCES vendas(id), FOREIGN KEY (variacao_id) REFERENCES variacoes(id)
);
CREATE TABLE notas_fiscais (
  id INT AUTO_INCREMENT PRIMARY KEY, venda_id INT NOT NULL UNIQUE, numero INT NOT NULL, serie INT NOT NULL DEFAULT 1,
  modelo VARCHAR(10) NOT NULL DEFAULT 'NFC-e', emitida_em DATETIME NOT NULL, FOREIGN KEY (venda_id) REFERENCES vendas(id)
);
CREATE TABLE mensagens (
  id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(80) NOT NULL, email VARCHAR(120) NOT NULL, assunto VARCHAR(120) NOT NULL,
  texto TEXT NOT NULL, usuario_id INT NULL, criada_em DATETIME NOT NULL, lida TINYINT(1) NOT NULL DEFAULT 0
);

INSERT INTO clientes(nome) VALUES ('Cliente Balcão');
INSERT INTO fornecedores(nome,cnpj,telefone,email,cep,endereco) VALUES
 ('Pixel Distribuidora de Games LTDA','12345678000190','(21) 99999-1111','contato@pixeldist.com.br','26272080','Rua Paulo Roberto dos Santos, Jardim Nova Era, Nova Iguaçu/RJ');
INSERT INTO categorias(id,nome) VALUES (1,'Ação'),(2,'Aventura'),(3,'RPG'),(4,'Esportes'),(5,'Corrida'),(6,'Acessórios');
INSERT INTO produtos(id,nome,descricao,categoria_id) VALUES
 (1,'Elden Ring','RPG de mundo aberto dos criadores de Dark Souls.',3),
 (2,'EA Sports FC 26','O futebol mais popular do mundo.',4),
 (3,'The Legend of Zelda: Tears of the Kingdom','Explore Hyrule e os céus em uma nova aventura.',2),
 (4,'Forza Horizon 5','Corridas em mundo aberto no México.',5),
 (5,'Hades II','Ação roguelike na mitologia grega.',1),
 (6,'Mario Kart World','Corridas com personagens da Nintendo.',5),
 (7,'God of War Ragnarök','Kratos e Atreus enfrentam o fim dos tempos.',1),
 (8,'Minecraft','Construa, explore e sobreviva.',2),
 (9,'Controle DualSense','Controle sem fio para PlayStation 5.',6),
 (10,'Headset Gamer 7.1 Surround','Som surround 7.1 com microfone.',6);
INSERT INTO variacoes(produto_id,plataforma,edicao,sku,preco,estoque,estoque_minimo) VALUES
 (1,'PS5','Padrão','ELDEN-PS5',299.90,10,3),
 (1,'PS5','Deluxe','ELDEN-PS5-DLX',379.90,4,2),
 (1,'PC','Padrão','ELDEN-PC',229.90,10,3),
 (2,'PS5','Padrão','FC26-PS5',349.90,12,4),
 (2,'Xbox','Padrão','FC26-XBX',349.90,8,4),
 (3,'Switch','Padrão','ZELDA-TOTK-NSW',379.80,8,3),
 (4,'Xbox','Padrão','FORZA5-XBX',199.90,6,3),
 (4,'PC','Padrão','FORZA5-PC',199.90,6,3),
 (5,'PC','Padrão','HADES2-PC',89.90,15,5),
 (6,'Switch','Padrão','MKWORLD-NSW',389.90,9,3),
 (7,'PS5','Padrão','GOW-RAG-PS5',199.90,7,3),
 (8,'Switch','Padrão','MINECRAFT-NSW',169.90,10,3),
 (8,'PC','Padrão','MINECRAFT-PC',129.90,12,3),
 (9,'PS5','Branco','DUALSENSE-PS5-BRA',449.90,5,3),
 (9,'PS5','Preto','DUALSENSE-PS5-PRE',449.90,5,3),
 (10,'PC','Preto','HEADSET-71-PRE',279.90,17,5);
