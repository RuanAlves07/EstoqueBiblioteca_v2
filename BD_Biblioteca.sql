-- Cria Database
CREATE DATABASE IF NOT EXISTS biblioteca_estoquev2;
USE biblioteca_estoquev2;

-- Dropa tabela se já existir algo
DROP TABLE IF EXISTS `item_emprestimo`;
DROP TABLE IF EXISTS `emprestimo`;
DROP TABLE IF EXISTS `produto`;
DROP TABLE IF EXISTS `editora`;
DROP TABLE IF EXISTS `autor`;
DROP TABLE IF EXISTS `categoria`;
DROP TABLE IF EXISTS `fornecedor`;
DROP TABLE IF EXISTS `funcionario`;
DROP TABLE IF EXISTS `cliente`;
DROP TABLE IF EXISTS `usuario`;
DROP TABLE IF EXISTS `perfil`;

-- Cria tabela do perfil (acessos)
CREATE TABLE IF NOT EXISTS `perfil` (
  `id_perfil` INT NOT NULL AUTO_INCREMENT,
  `nome_perfil` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_perfil`),
  UNIQUE KEY `uk_perfil_nome` (`nome_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert de cada permissão
INSERT INTO `perfil` (`id_perfil`, `nome_perfil`) VALUES
(1, 'Administrador'),
(2, 'Superior'),
(3, 'Funcionario'),
(4, 'Cliente');

-- Criação da tabela de usuário (acesso)
CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_perfil` INT NOT NULL,
  `senha_temporaria` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `uk_usuario_email` (`email`),
  KEY `fk_usuario_perfil` (`id_perfil`),
  CONSTRAINT `fk_usuario_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Usuários de exemplo
INSERT INTO `usuario` (`nome`, `email`, `senha`, `id_perfil`, `senha_temporaria`) VALUES
('Admin Geral', 'admin@biblioteca.com', '$2y$10$exemplohashseguro', 1, 0),
('Super User', 'super@biblioteca.com', '$2y$10$exemplohashseguro', 2, 1),
('Funcionario 1', 'func@biblioteca.com', '$2y$10$exemplohashseguro', 3, 1),
('Cliente 1', 'cliente@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1);

-- Tabela de clientes
CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` INT NOT NULL AUTO_INCREMENT,
  `nome_completo` VARCHAR(150) NOT NULL,
  `cpf` VARCHAR(14) UNIQUE,
  `telefone` VARCHAR(20),
  `data_nascimento` DATE,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela dos funcionarios
CREATE TABLE IF NOT EXISTS `funcionario` (
  `id_funcionario` INT NOT NULL AUTO_INCREMENT,
  `nome_completo` VARCHAR(150) NOT NULL,
  `cpf` VARCHAR(14) UNIQUE,
  `cargo` VARCHAR(100),
  `telefone` VARCHAR(20),
  `data_admissao` DATE,
  PRIMARY KEY (`id_funcionario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de fornecedores
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `id_fornecedor` INT NOT NULL AUTO_INCREMENT,
  `nome_empresa` VARCHAR(150) NOT NULL,
  `cnpj` VARCHAR(18) UNIQUE,
  `contato` VARCHAR(100),
  `telefone` VARCHAR(20),
  `email` VARCHAR(100),
  `endereco` TEXT,
  PRIMARY KEY (`id_fornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Categoria do Produto
CREATE TABLE IF NOT EXISTS `categoria` (
  `id_categoria` INT NOT NULL AUTO_INCREMENT,
  `nome_categoria` VARCHAR(100) NOT NULL,
  `descricao` TEXT,
  PRIMARY KEY (`id_categoria`),
  UNIQUE KEY `uk_categoria_nome` (`nome_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Autor
CREATE TABLE IF NOT EXISTS `autor` (
  `id_autor` INT NOT NULL AUTO_INCREMENT,
  `nome_autor` VARCHAR(150) NOT NULL,
  `nacionalidade` VARCHAR(50),
  `data_nascimento` DATE,
  PRIMARY KEY (`id_autor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela da editora
CREATE TABLE IF NOT EXISTS `editora` (
  `id_editora` INT NOT NULL AUTO_INCREMENT,
  `nome_editora` VARCHAR(150) NOT NULL,
  `cnpj` VARCHAR(18) UNIQUE,
  `telefone` VARCHAR(20),
  `email` VARCHAR(100),
  PRIMARY KEY (`id_editora`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de produtos
CREATE TABLE IF NOT EXISTS `produto` (
  `id_produto` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NOT NULL,
  `isbn` VARCHAR(20) UNIQUE,
  `id_categoria` INT,
  `id_autor` INT,
  `id_editora` INT,
  `ano_publicacao` YEAR,
  `edicao` VARCHAR(20),
  `quantidade_estoque` INT DEFAULT 1,
  `data_cadastro` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_produto`),
  KEY `fk_produto_categoria` (`id_categoria`),
  KEY `fk_produto_autor` (`id_autor`),
  KEY `fk_produto_editora` (`id_editora`),
  CONSTRAINT `fk_produto_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`),
  CONSTRAINT `fk_produto_autor` FOREIGN KEY (`id_autor`) REFERENCES `autor` (`id_autor`),
  CONSTRAINT `fk_produto_editora` FOREIGN KEY (`id_editora`) REFERENCES `editora` (`id_editora`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de emprestimos
CREATE TABLE IF NOT EXISTS `emprestimo` (
  `id_emprestimo` INT NOT NULL AUTO_INCREMENT,
  `id_cliente` INT NOT NULL,
  `id_funcionario` INT NOT NULL,
  `data_emprestimo` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `data_devolucao_prevista` DATE NOT NULL,
  `data_devolucao_real` DATETIME NULL,
  `status` ENUM('emprestado', 'devolvido', 'atrasado', 'renovado') DEFAULT 'emprestado',
  `multa` DECIMAL(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id_emprestimo`),
  KEY `fk_emprestimo_cliente` (`id_cliente`),
  KEY `fk_emprestimo_funcionario` (`id_funcionario`),
  CONSTRAINT `fk_emprestimo_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_emprestimo_funcionario` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario` (`id_funcionario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TABELA ITEM_EMPRESTIMO (relaciona empréstimo com produtos)
CREATE TABLE IF NOT EXISTS `item_emprestimo` (
  `id_item` INT NOT NULL AUTO_INCREMENT,
  `id_emprestimo` INT NOT NULL,
  `id_produto` INT NOT NULL,
  `data_devolucao_prevista` DATE NOT NULL,
  PRIMARY KEY (`id_item`),
  UNIQUE KEY `uk_item_emprestimo_produto` (`id_emprestimo`, `id_produto`),
  KEY `fk_item_produto` (`id_produto`),
  CONSTRAINT `fk_item_emprestimo` FOREIGN KEY (`id_emprestimo`) REFERENCES `emprestimo` (`id_emprestimo`) ON DELETE CASCADE,
  CONSTRAINT `fk_item_produto` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;