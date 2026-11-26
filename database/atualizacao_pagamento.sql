-- Use se você JÁ importou o banco antes. Selecione o banco (levelzone ou o da hospedagem) no phpMyAdmin e importe este arquivo uma vez só.
ALTER TABLE vendas ADD COLUMN forma_pagamento VARCHAR(20) NULL, ADD COLUMN pagamento_detalhe VARCHAR(80) NULL;
