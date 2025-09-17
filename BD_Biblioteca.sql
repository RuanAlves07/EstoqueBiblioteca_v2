-- Remover tabelas na ordem correta
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

-- Criar tabela perfil primeiro (dependência)
CREATE TABLE IF NOT EXISTS `perfil` (
  `id_perfil` INT NOT NULL AUTO_INCREMENT,
  `nome_perfil` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_perfil`),
  UNIQUE KEY `uk_perfil_nome` (`nome_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir perfis
INSERT INTO `perfil` (`id_perfil`, `nome_perfil`) VALUES
(1, 'Administrador'),
(2, 'Superior'),
(3, 'Funcionario'),
(4, 'Cliente');

-- Criar tabela usuario antes de cliente (dependência)
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

-- Inserir usuários básicos
INSERT INTO `usuario` (`nome`, `email`, `senha`, `id_perfil`, `senha_temporaria`) VALUES
('Admin', 'admin@biblioteca.com', '$2y$10$exemplohashseguro', 1, 0),
('Superior', 'super@biblioteca.com', '$2y$10$exemplohashseguro', 2, 1);

-- Criar usuários FUNCIONÁRIOS primeiro (perfil 3)
INSERT INTO `usuario` (`nome`, `email`, `senha`, `id_perfil`, `senha_temporaria`) VALUES
('Patrícia Oliveira', 'patricia.oliveira@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
('Ricardo Souza', 'ricardo.souza@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
('Lúcia Santos', 'lucia.santos@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
('Felipe Costa', 'felipe.costa@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
('Ana Maria Silva', 'ana.silva@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
('Carlos Eduardo Santos', 'carlos.santos@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
('Fernanda Costa Oliveira', 'fernanda.oliveira@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
('Roberto Almeida Pereira', 'roberto.pereira@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
('Juliana Rodrigues Mendes', 'juliana.mendes@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
('Pedro Henrique Lima', 'pedro.lima@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0);

-- Criar os usuários clientes (perfil 4) depois
INSERT INTO `usuario` (`nome`, `email`, `senha`, `id_perfil`, `senha_temporaria`) VALUES
('Cliente', 'cliente@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Maria Silva Santos', 'maria.silva@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('João Oliveira Costa', 'joao.oliveira@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Ana Carolina Pereira', 'ana.pereira@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Pedro Henrique Almeida', 'pedro.almeida@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Carla Maria Rodrigues', 'carla.rodrigues@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Roberto Carlos Mendes', 'roberto.mendes@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Juliana Ferreira Lima', 'juliana.lima@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Fernando Souza Silva', 'fernando.silva@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Camila Oliveira Santos', 'camila.santos@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Ricardo Almeida Costa', 'ricardo.costa@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Patrícia Gomes Mendes', 'patricia.mendes@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Maurício Santos Pereira', 'mauricio.pereira@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Vanessa Costa Rodrigues', 'vanessa.rodrigues@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Thiago Alves Oliveira', 'thiago.oliveira@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Débora Lima Santos', 'debora.santos@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Eduardo Santos Almeida', 'eduardo.almeida@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Carla Mendonça Ferreira', 'carla.ferreira@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Lucas Oliveira Costa', 'lucas.costa@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Amanda Costa Silva', 'amanda.costa@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
('Bruno Almeida Pereira', 'bruno.pereira@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1);

-- Criar tabela cliente SEM FK inicialmente
CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` INT NOT NULL AUTO_INCREMENT,
  `nome_completo` VARCHAR(150) NOT NULL,
  `cpf` VARCHAR(14) UNIQUE,
  `telefone` VARCHAR(20),
  `data_nascimento` DATE,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir clientes com os MESMOS IDs dos usuários (perfil 4)
INSERT INTO `cliente` (`id_cliente`, `nome_completo`, `cpf`, `telefone`, `data_nascimento`) VALUES
(3, 'Cliente', '000.000.000-00', '(11) 99999-9999', '1990-01-01'),
(4, 'Maria Silva Santos', '123.456.789-09', '(11) 99999-0001', '1990-05-15'),
(5, 'João Oliveira Costa', '234.567.890-10', '(11) 99999-0002', '1985-08-22'),
(6, 'Ana Carolina Pereira', '345.678.901-21', '(11) 99999-0003', '1992-12-03'),
(7, 'Pedro Henrique Almeida', '456.789.012-32', '(11) 99999-0004', '1988-03-10'),
(8, 'Carla Maria Rodrigues', '567.890.123-43', '(11) 99999-0005', '1995-07-18'),
(9, 'Roberto Carlos Mendes', '678.901.234-54', '(11) 99999-0006', '1982-11-25'),
(10, 'Juliana Ferreira Lima', '789.012.345-65', '(11) 99999-0007', '1993-09-12'),
(11, 'Fernando Souza Silva', '890.123.456-76', '(11) 99999-0008', '1987-04-30'),
(12, 'Camila Oliveira Santos', '901.234.567-87', '(11) 99999-0009', '1991-06-08'),
(13, 'Ricardo Almeida Costa', '012.345.678-98', '(11) 99999-0010', '1989-02-14'),
(14, 'Patrícia Gomes Mendes', '111.222.333-44', '(11) 99999-0011', '1994-10-20'),
(15, 'Maurício Santos Pereira', '222.333.444-55', '(11) 99999-0012', '1986-01-28'),
(16, 'Vanessa Costa Rodrigues', '333.444.555-66', '(11) 99999-0013', '1996-05-16'),
(17, 'Thiago Alves Oliveira', '444.555.666-77', '(11) 99999-0014', '1984-12-09'),
(18, 'Débora Lima Santos', '555.666.777-88', '(11) 99999-0015', '1997-03-22'),
(19, 'Eduardo Santos Almeida', '666.777.888-99', '(11) 99999-0016', '1983-07-11'),
(20, 'Carla Mendonça Ferreira', '777.888.999-00', '(11) 99999-0017', '1998-08-05'),
(21, 'Lucas Oliveira Costa', '888.999.000-11', '(11) 99999-0018', '1990-11-30'),
(22, 'Amanda Costa Silva', '999.000.111-22', '(11) 99999-0019', '1988-04-17'),
(23, 'Bruno Almeida Pereira', '000.111.222-33', '(11) 99999-0020', '1995-09-23');

-- AGORA ADICIONAR A FK (após os dados estarem consistentes)
ALTER TABLE cliente ADD CONSTRAINT fk_cliente_usuario FOREIGN KEY (id_cliente) REFERENCES usuario(id_usuario) ON DELETE CASCADE;

-- Continuar com as outras tabelas
CREATE TABLE IF NOT EXISTS `funcionario` (
  `id_funcionario` INT NOT NULL,
  `nome_completo` VARCHAR(150) NOT NULL,
  `cpf` VARCHAR(14) UNIQUE,
  `cargo` VARCHAR(100),
  `telefone` VARCHAR(20),
  `data_admissao` DATE,
  PRIMARY KEY (`id_funcionario`),
  CONSTRAINT `fk_funcionario_usuario` FOREIGN KEY (`id_funcionario`) REFERENCES usuario(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir funcionários com os MESMOS IDs dos usuários (perfil 3)
INSERT INTO `funcionario` (`id_funcionario`, `nome_completo`, `cpf`, `cargo`, `telefone`, `data_admissao`) VALUES
(2, 'Patrícia Oliveira', '123.456.789-00', 'Bibliotecária', '(11) 99999-1111', '2023-01-15'),
(3, 'Ricardo Souza', '987.654.321-00', 'Assistente de Estoque', '(11) 99999-2222', '2023-03-10'),
(4, 'Lúcia Santos', '456.789.123-00', 'Atendente', '(11) 99999-3333', '2023-02-20'),
(5, 'Felipe Costa', '789.123.456-00', 'Gerente', '(11) 99999-4444', '2022-11-05'),
(6, 'Ana Maria Silva', '123.456.789-09', 'Bibliotecária', '(11) 99999-5555', '2023-04-10'),
(7, 'Carlos Eduardo Santos', '234.567.890-10', 'Assistente de Estoque', '(11) 99999-6666', '2023-05-15'),
(8, 'Fernanda Costa Oliveira', '345.678.901-21', 'Atendente', '(11) 99999-7777', '2023-06-20'),
(9, 'Roberto Almeida Pereira', '456.789.012-32', 'Gerente', '(11) 99999-8888', '2022-12-01'),
(10, 'Juliana Rodrigues Mendes', '567.890.123-43', 'Bibliotecária', '(11) 99999-9999', '2023-07-05'),
(11, 'Pedro Henrique Lima', '678.901.234-54', 'Assistente de Estoque', '(11) 98888-1111', '2023-08-12');

-- Continuar com as outras tabelas...
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `id_fornecedor` INT NOT NULL AUTO_INCREMENT,
  `nome_empresa` VARCHAR(150) NOT NULL,
  `nome_fantasia` VARCHAR(150) NOT NULL,
  `cnpj` VARCHAR(18) UNIQUE,
  `contato` VARCHAR(100),
  `telefone` VARCHAR(20),
  `email` VARCHAR(100),
  `endereco` TEXT,
  PRIMARY KEY (`id_fornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `categoria` (
  `id_categoria` INT NOT NULL AUTO_INCREMENT,
  `nome_categoria` VARCHAR(100) NOT NULL,
  `descricao` TEXT,
  PRIMARY KEY (`id_categoria`),
  UNIQUE KEY `uk_categoria_nome` (`nome_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categoria` (`id_categoria`, `nome_categoria`, `descricao`) VALUES
(1, 'ficção', 'livros sobre ficção'),
(2, 'Romance', 'livros sobre romance'),
(3, 'conto', 'livros sobre conto'),
(4, 'fantasia', 'livros sobre fantasia'),
(5, 'Terror', 'livros sobre terror'),
(6, 'Horror', 'livros sobre horror'),
(7, 'Biografia', 'livros sobre biografia'),
(8, 'História', 'livros sobre história'),
(9, 'AutoAjuda', 'livros sobre AutoAjuda'),
(10, 'Outros', 'outros livros');

CREATE TABLE IF NOT EXISTS `autor` (
  `id_autor` INT NOT NULL AUTO_INCREMENT,
  `nome_autor` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_autor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `editora` (
  `id_editora` INT NOT NULL AUTO_INCREMENT,
  `nome_editora` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_editora`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

ALTER TABLE produto ADD COLUMN id_fornecedor INT NULL DEFAULT NULL AFTER quantidade_estoque;
ALTER TABLE produto ADD CONSTRAINT fk_produto_fornecedor FOREIGN KEY (id_fornecedor) REFERENCES fornecedor (id_fornecedor);

CREATE TABLE IF NOT EXISTS `emprestimo` (
  `id_emprestimo` INT NOT NULL AUTO_INCREMENT,
  `id_usuario` INT NOT NULL,
  `data_emprestimo` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `data_devolucao_prevista` DATE NOT NULL,
  `data_devolucao_real` DATETIME NULL,
  `status` ENUM('emprestado', 'devolvido', 'atrasado', 'renovado') DEFAULT 'emprestado',
  `multa` DECIMAL(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id_emprestimo`),
  KEY `fk_emprestimo_usuario` (`id_usuario`),
  CONSTRAINT `fk_emprestimo_usuario` 
    FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- Dados restantes
INSERT INTO `fornecedor` (`nome_empresa`, `nome_fantasia`, `cnpj`, `contato`, `telefone`, `email`, `endereco`) VALUES
('Editora Saraiva', 'Saraiva', '33.297.185/0001-22', 'Ana Silva', '(11) 98765-4321', 'contato@saraiva.com.br', 'Av. Paulista, 1000 - São Paulo, SP'),
('Companhia das Letras', 'Companhia das Letras', '33.456.789/0001-10', 'Carlos Mendes', '(11) 99876-5432', 'atendimento@companhiadasletras.com.br', 'R. Padre João Manuel, 123 - São Paulo, SP'),
('Abril Educação', 'Abril', '44.123.456/0001-89', 'Mariana Costa', '(11) 97654-3210', 'sac@abril.com.br', 'Av. Brigadeiro Faria Lima, 2000 - São Paulo, SP'),
('Objetiva', 'Objetiva', '55.987.654/0001-33', 'Roberto Almeida', '(21) 98765-4321', 'contato@objetiva.com.br', 'Rua do Ouvidor, 45 - Rio de Janeiro, RJ'),
('Novo Conceito', 'Novo Conceito', '66.543.210/0001-77', 'Juliana Ferreira', '(11) 91234-5678', 'ouvidoria@novoconceito.com.br', 'Av. Rebouças, 890 - São Paulo, SP');

INSERT INTO `autor` (`nome_autor`) VALUES
('J.K. Rowling'),
('Machado de Assis'),
('George Orwell'),
('Clarice Lispector'),
('Yuval Noah Harari'),
('Carl Sagan');

INSERT INTO `editora` (`nome_editora`) VALUES
('Editora Saraiva'),
('Companhia das Letras'),
('Editora Abril'),
('Objetiva'),
('Novo Conceito');

INSERT INTO `produto` (`titulo`, `isbn`, `id_categoria`, `id_autor`, `id_editora`, `ano_publicacao`, `edicao`, `quantidade_estoque`, `id_fornecedor`) VALUES
('Harry Potter e a Pedra Filosofal', '978-85-7827-041-1', 1, 1, 1, 2012, '1ª edição', 5, 1),
('Dom Casmurro', '978-85-359-0277-0', 2, 2, 2, 2005, '10ª edição', 8, 2),
('1984', '978-85-7902-102-8', 1, 3, 3, 2015, 'Revisada', 6, 3),
('A Hora da Estrela', '978-85-359-0779-9', 1, 4, 2, 2018, '20ª edição', 7, 2),
('Sapiens: Uma Breve História da Humanidade', '978-85-8086-268-1', 8, 5, 4, 2015, '1ª edição', 10, 4),
('Cosmos', '978-85-8086-402-9', 8, 6, 5, 2016, '2ª edição', 4, 5),
('A Menina que Roubava Livros', '978-85-7827-385-6', 1, 1, 1, 2011, '1ª edição', 5, 1),
('O Alienista', '978-85-359-0821-5', 1, 2, 2, 2017, '15ª edição', 9, 2),
('Breves Respostas para Grandes Questões', '978-85-8086-411-1', 10, 5, 4, 2018, '1ª edição', 6, 4),
('O Pequeno Príncipe', '978-85-8275-023-5', 1, 4, 5, 2019, '12ª edição', 12, 5);

-- Adiciona coluna id_funcionario
ALTER TABLE emprestimo 
ADD COLUMN id_funcionario INT NULL DEFAULT NULL AFTER id_usuario;

-- Adiciona chave estrangeira
ALTER TABLE emprestimo 
ADD CONSTRAINT fk_emprestimo_funcionario 
FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario) 
ON DELETE SET NULL;