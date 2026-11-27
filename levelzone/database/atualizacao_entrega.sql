-- Use se você JÁ importou o banco antes. Selecione o banco (levelzone ou o da hospedagem) no phpMyAdmin e importe uma vez só.
ALTER TABLE vendas ADD COLUMN entrega_cep VARCHAR(9) NULL, ADD COLUMN entrega_endereco VARCHAR(255) NULL;
