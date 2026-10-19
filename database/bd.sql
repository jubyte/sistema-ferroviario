CREATE DATABASE IF NOT EXISTS mockingraill;
USE mockingraill;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    senha VARCHAR(50) NOT NULL,
    tipo VARCHAR(20) NOT NULL DEFAULT 'Usuario',
    status VARCHAR(20) NOT NULL DEFAULT 'Ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE trens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    empresa VARCHAR(100) NOT NULL,
    tipo VARCHAR(20) NOT NULL,
    numero_vagoes INT NOT NULL,
    usuario_responsavel_id INT,
    status VARCHAR(20) DEFAULT 'Ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_responsavel_id) REFERENCES usuarios(id)
);

CREATE TABLE rotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    distancia DECIMAL(10,2) NOT NULL,
    tempo_estimado INT NOT NULL,
    trem_id INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trem_id) REFERENCES trens(id)
);

CREATE TABLE sensores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(20) NOT NULL,
    localizacao VARCHAR(100) NOT NULL,
    trem_id INT,
    status VARCHAR(20) DEFAULT 'Ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trem_id) REFERENCES trens(id)
);