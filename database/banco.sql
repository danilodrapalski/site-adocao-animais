-- =====================================================
-- Site de Adoção de Animais - Estrutura do banco de dados
-- =====================================================

CREATE DATABASE IF NOT EXISTS adocao_animais
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE adocao_animais;

-- -----------------------------------------------------
-- Tabela: usuarios
-- Guarda visitantes cadastrados e administradores.
-- A senha NUNCA é salva em texto puro: use password_hash() no PHP.
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    senha      VARCHAR(255) NOT NULL,
    tipo       ENUM('usuario', 'admin') NOT NULL DEFAULT 'usuario',
    criado_em  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_usuarios_email (email)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Tabela: animais
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS animais (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome       VARCHAR(100) NOT NULL,
    especie    ENUM('cachorro', 'gato', 'outro') NOT NULL,
    raca       VARCHAR(100) DEFAULT NULL,
    idade      VARCHAR(30) DEFAULT NULL,          -- ex.: '3 meses', '2 anos'
    porte      ENUM('pequeno', 'medio', 'grande') NOT NULL,
    sexo       ENUM('macho', 'femea') NOT NULL,
    descricao  TEXT DEFAULT NULL,
    foto       VARCHAR(255) DEFAULT NULL,         -- nome do arquivo salvo em uploads/
    status     ENUM('disponivel', 'em_processo', 'adotado') NOT NULL DEFAULT 'disponivel',
    criado_em  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_animais_filtros (status, especie, porte)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Tabela: visitas
-- Cada visita liga um usuário a um animal, em uma data e horário.
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS visitas (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    usuario_id   INT UNSIGNED NOT NULL,
    animal_id    INT UNSIGNED NOT NULL,
    data_visita  DATE NOT NULL,
    horario      TIME NOT NULL,
    status       ENUM('pendente', 'confirmada', 'recusada', 'cancelada') NOT NULL DEFAULT 'pendente',
    criado_em    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_visitas_horario (animal_id, data_visita, horario),
    CONSTRAINT fk_visitas_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
        ON DELETE CASCADE,
    CONSTRAINT fk_visitas_animal
        FOREIGN KEY (animal_id) REFERENCES animais (id)
        ON DELETE CASCADE
) ENGINE=InnoDB;