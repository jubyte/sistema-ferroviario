
CREATE DATABASE IF NOT EXISTS mockingrail;
USE mockingrail;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    cep VARCHAR(9) DEFAULT NULL,
    logradouro VARCHAR(200) DEFAULT NULL,
    bairro VARCHAR(100) DEFAULT NULL,
    cidade VARCHAR(100) DEFAULT NULL,
    estado VARCHAR(2) DEFAULT NULL,
    tipo VARCHAR(20) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS trens (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    empresa VARCHAR(100) NOT NULL,
    tipo VARCHAR(20) NOT NULL,
    numero_vagoes INT NOT NULL,
    responsavel_id INT UNSIGNED,
    status VARCHAR(20) NOT NULL DEFAULT 'Ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_trens_usuarios
        FOREIGN KEY (responsavel_id)
        REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS sensores (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(20) NOT NULL,
    localizacao VARCHAR(100) NOT NULL,
    trem_id INT UNSIGNED,
    status VARCHAR(20) NOT NULL DEFAULT 'Ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_sensores_trens
        FOREIGN KEY (trem_id)
        REFERENCES trens(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS rotas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    localizacao_inicial VARCHAR(150) NOT NULL,
    localizacao_final VARCHAR(150) NOT NULL,
    trem_id INT UNSIGNED NOT NULL,
    descricao TEXT,
    distancia DECIMAL(10,2),
    tempo_estimado INT,
    status VARCHAR(20) NOT NULL DEFAULT 'Ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_rotas_trens
        FOREIGN KEY (trem_id)
        REFERENCES trens(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);