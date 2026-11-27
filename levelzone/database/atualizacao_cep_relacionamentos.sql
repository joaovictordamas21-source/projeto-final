-- Use se você JÁ importou o banco antes. Selecione o banco (levelzone ou o da hospedagem) no phpMyAdmin e importe uma vez só.
ALTER TABLE clientes ADD COLUMN cep VARCHAR(9) NULL, ADD COLUMN endereco VARCHAR(255) NULL;

-- limpa referências soltas para a ligação poder ser criada
UPDATE mensagens SET usuario_id = NULL WHERE usuario_id IS NOT NULL AND usuario_id NOT IN (SELECT id FROM usuarios);
UPDATE clientes SET usuario_id = NULL WHERE usuario_id IS NOT NULL AND usuario_id NOT IN (SELECT id FROM usuarios);

ALTER TABLE mensagens ADD CONSTRAINT fk_mensagens_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL;
ALTER TABLE clientes  ADD CONSTRAINT fk_clientes_usuario  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL;
