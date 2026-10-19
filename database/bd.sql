CREATE DATABASE IF NOT EXISTS mockingraill;
USE mockingraill;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
<<<<<<< HEAD
    tipo VARCHAR(30) NOT NULL,
    senha VARCHAR(255) NOT NULL,
=======
    senha VARCHAR(50) NOT NULL,
    tipo VARCHAR(20) NOT NULL DEFAULT 'Usuario',
>>>>>>> f2883291305fcbaaa89fe593264992cb9f388302
    status VARCHAR(20) NOT NULL DEFAULT 'Ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS trens (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
=======
CREATE TABLE trens (
    id INT AUTO_INCREMENT PRIMARY KEY,
>>>>>>> f2883291305fcbaaa89fe593264992cb9f388302
    nome VARCHAR(100) NOT NULL,
    empresa VARCHAR(100) NOT NULL,
    tipo VARCHAR(20) NOT NULL,
    numero_vagoes INT NOT NULL,
    usuario_responsavel_id INT,
    status VARCHAR(20) DEFAULT 'Ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_responsavel_id) REFERENCES usuarios(id)
);

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS sensores (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
=======
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
>>>>>>> f2883291305fcbaaa89fe593264992cb9f388302
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(20) NOT NULL,
    localizacao VARCHAR(100) NOT NULL,
    trem_id INT,
    status VARCHAR(20) DEFAULT 'Ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trem_id) REFERENCES trens(id)
);