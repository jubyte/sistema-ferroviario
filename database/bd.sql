CREATE DATABASE IF NOT EXISTS Mockingrail;
USE Mockingrail;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    tipo VARCHAR(30) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO usuarios (nome, email, telefone, tipo, status)
VALUES
('Eduarda Silva', 'eduarda@mockingrail.com', '(47) 99999-1111', 'Administrador', 'Ativo'),
('Ana Souza', 'ana@mockingrail.com', '(47) 98888-2222', 'Usuário', 'Ativo'),
('Carlos Oliveira', 'carlos@mockingrail.com', '(47) 97777-3333', 'Usuário', 'Ativo');