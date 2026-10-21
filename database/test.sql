
USE mockingrail;

-- usuarios
INSERT INTO usuarios (nome, email, telefone, tipo, senha, status) VALUES
('Eduarda', 'eduarda@mockingrail.com', '(47) 99999-9999', 'Administrador', '', 'Ativo'),
('Marcus', 'marcus@gmail.com', '(47) 11111-1111', 'Usuario', '', 'Ativo');

-- trens
INSERT INTO trens (nome, empresa, tipo, numero_vagoes, status) VALUES
('Distrito 12', 'Mocking Rail', 'Eletrico', 7, 'Ativo'),
('Carga 02', 'ColheitaLog', 'Diesel', 13, 'Ativo');

-- sensores
INSERT INTO sensores (nome, tipo, localizacao, trem_id, status) VALUES
('Sensor de Velocidade 01', 'Velocidade', 'Zona Norte, Joinville', (SELECT id FROM trens WHERE nome = 'Distrito 12' LIMIT 1), 'Ativo'),
('Sensor de Consumo de Energia 02', 'Energia', 'Distrito Industrial, Joinville', (SELECT id FROM trens WHERE nome = 'Carga 02' LIMIT 1), 'Ativo');

-- rotas
INSERT INTO rotas (nome, localizacao_inicial, localizacao_final, descricao, distancia, tempo_estimado, trem_id) VALUES
('Rota Joinville - Sao Francisco do Sul', 'Joinville', 'São Francisco do Sul', 'Trajeto simulado para transporte de cargas entre Joinville e Sao Francisco do Sul.', 45.00, 60, (SELECT id FROM trens WHERE nome = 'Carga 02' LIMIT 1)),
('Rota Distrito 12', 'Centro', 'Distrito Industrial', 'Trajeto simulado de monitoramento ferroviario na regiao de Joinville.', 20.00, 35, (SELECT id FROM trens WHERE nome = 'Distrito 12' LIMIT 1));

-- conferência
SELECT * FROM usuarios;
SELECT * FROM trens;
SELECT * FROM sensores;
SELECT * FROM rotas;
