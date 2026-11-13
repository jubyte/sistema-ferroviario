USE mockingrail;

INSERT INTO usuarios(nome, email, telefone, cep, logradouro, bairro, cidade, estado, tipo, senha, status)VALUES
('Eduarda', 'eduarda@mockingrail.com', '(47) 99999-9999','89201-000', 'Rua das Palmeiras', 'Centro', 'Joinville', 'SC','Administrador', '28122008', 'Ativo'),
('Marcus', 'marcus@gmail.com', '(47) 11111-1111','89202-000', 'Rua Blumenau', 'América', 'Joinville', 'SC','Usuario', '', 'Ativo');

INSERT INTO trens(nome, empresa, tipo, numero_vagoes, responsavel_id, status)VALUES
('Distrito 12', 'Mocking Rail', 'Eletrico', 7, (SELECT id FROM usuarios WHERE email = 'eduarda@mockingrail.com' LIMIT 1), 'Ativo'),
('Carga 02', 'ColheitaLog', 'Diesel', 13,(SELECT id FROM usuarios WHERE email = 'marcus@gmail.com' LIMIT 1), 'Ativo');

INSERT INTO sensores(nome, tipo, localizacao, trem_id, status)VALUES
('Sensor de Velocidade 01', 'Velocidade','Zona Norte, Joinville',(SELECT id FROM trens WHERE nome = 'Distrito 12' LIMIT 1), 'Ativo'),
('Sensor de Consumo de Energia 02', 'Energia','Distrito Industrial, Joinville',(SELECT id FROM trens WHERE nome = 'Carga 02' LIMIT 1), 'Ativo');


INSERT INTO rotas(nome, localizacao_inicial, localizacao_final, trem_id,descricao, distancia, tempo_estimado, status)VALUES
('Rota Joinville - Sao Francisco do Sul','Joinville', 'São Francisco do Sul',(SELECT id FROM trens WHERE nome = 'Carga 02' LIMIT 1),'Trajeto simulado para transporte de cargas entre Joinville e Sao Francisco do Sul.', 45.00, 60, 'Ativo'),
('Rota Distrito 12','Centro', 'Distrito Industrial',(SELECT id FROM trens WHERE nome = 'Distrito 12' LIMIT 1),'Trajeto simulado de monitoramento ferroviario na regiao de Joinville.',20.00, 35, 'Ativo');

-- conferência
SELECT * FROM usuarios;
SELECT * FROM trens;
SELECT * FROM sensores;
SELECT * FROM rotas;
