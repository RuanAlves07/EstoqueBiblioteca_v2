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

INSERT INTO `usuario` (`id_usuario`, `nome`, `email`, `senha`, `id_perfil`, `senha_temporaria`) VALUES
(1, 'Admin', 'admin@biblioteca.com', '$2y$10$exemplohashseguro', 1, 0),
(2, 'Superior', 'super@biblioteca.com', '$2y$10$exemplohashseguro', 2, 1),
(3, 'Patrícia Oliveira', 'patricia.oliveira@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(4, 'Ricardo Souza', 'ricardo.souza@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(5, 'Lúcia Santos', 'lucia.santos@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(6, 'Felipe Costa', 'felipe.costa@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(7, 'Ana Maria Silva', 'ana.silva@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(8, 'Carlos Eduardo Santos', 'carlos.santos@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(9, 'Fernanda Costa Oliveira', 'fernanda.oliveira@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(10, 'Roberto Almeida Pereira', 'roberto.pereira@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(11, 'Juliana Rodrigues Mendes', 'juliana.mendes@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(12, 'Pedro Henrique Lima', 'pedro.lima@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(13, 'Mariana Alves', 'mariana.alves@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(14, 'Paulo Roberto', 'paulo.roberto@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(15, 'Sandra Regina', 'sandra.regina@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(16, 'Fábio Silva', 'fabio.silva@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(17, 'Cristina Souza', 'cristina.souza@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(18, 'Rafaela Mendes', 'rafaela.mendes@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(19, 'Gustavo Almeida', 'gustavo.almeida@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(20, 'Tatiane Costa', 'tatiane.costa@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(21, 'Rodrigo Lima', 'rodrigo.lima@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(22, 'Aline Ferreira', 'aline.ferreira@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(23, 'Marcelo Santos', 'marcelo.santos@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(24, 'Camila Rodrigues', 'camila.rodrigues@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(25, 'Eduardo Oliveira', 'eduardo.oliveira@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(26, 'Renata Alves', 'renata.alves@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(27, 'Luciano Pereira', 'luciano.pereira@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(28, 'Simone Costa', 'simone.costa@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(29, 'Thiago Mendes', 'thiago.mendes@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(30, 'Viviane Silva', 'viviane.silva@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(31, 'Wagner Souza', 'wagner.souza@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(32, 'Yara Lima', 'yara.lima@biblioteca.com', '$2y$10$exemplohashseguro', 3, 0),
(33, 'Cliente', 'cliente@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(34, 'Maria Silva Santos', 'maria.silva@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(35, 'João Oliveira Costa', 'joao.oliveira@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(36, 'Ana Carolina Pereira', 'ana.pereira@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(37, 'Pedro Henrique Almeida', 'pedro.almeida@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(38, 'Carla Maria Rodrigues', 'carla.rodrigues@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(39, 'Roberto Carlos Mendes', 'roberto.mendes@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(40, 'Juliana Ferreira Lima', 'juliana.lima@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(41, 'Fernando Souza Silva', 'fernando.silva@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(42, 'Camila Oliveira Santos', 'camila.santos@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(43, 'Ricardo Almeida Costa', 'ricardo.costa@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(44, 'Patrícia Gomes Mendes', 'patricia.mendes@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(45, 'Maurício Santos Pereira', 'mauricio.pereira@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(46, 'Vanessa Costa Rodrigues', 'vanessa.rodrigues@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(47, 'Thiago Alves Oliveira', 'thiago.oliveira@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(48, 'Débora Lima Santos', 'debora.santos@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(49, 'Eduardo Santos Almeida', 'eduardo.almeida@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(50, 'Carla Mendonça Ferreira', 'carla.ferreira@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(51, 'Lucas Oliveira Costa', 'lucas.costa@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(52, 'Amanda Costa Silva', 'amanda.costa@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1),
(53, 'Bruno Almeida Pereira', 'bruno.pereira@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1);

-- Criar tabela cliente SEM FK inicialmente
CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` INT NOT NULL AUTO_INCREMENT,
  `nome_completo` VARCHAR(150) NOT NULL,
  `cpf` VARCHAR(14) UNIQUE,
  `telefone` VARCHAR(20),
  `endereco` VARCHAR(255),
  `data_nascimento` DATE,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir clientes com os MESMOS IDs dos usuários (perfil 4) e com endereços
INSERT INTO `cliente` (`id_cliente`, `nome_completo`, `cpf`, `telefone`, `endereco`, `data_nascimento`) VALUES
(33, 'Cliente', '000.000.000-00', '(11) 99999-9999', 'Avenida Paulista, 1000 - São Paulo, SP', '1990-01-01'),
(34, 'Maria Silva Santos', '123.456.789-09', '(11) 99999-0001', 'Travessa do Comércio, 500 - Belém, PA', '1990-05-15'),
(35, 'João Oliveira Costa', '234.567.890-10', '(11) 99999-0002', 'Praça da Sé, 200 - São Paulo, SP', '1985-08-22'),
(36, 'Ana Carolina Pereira', '345.678.901-21', '(11) 99999-0003', 'Avenida Atlântica, 2000 - Rio de Janeiro, RJ', '1992-12-03'),
(37, 'Pedro Henrique Almeida', '456.789.012-32', '(11) 99999-0004', 'Avenida Sete de Setembro, 2000 - Salvador, BA', '1988-03-10'),
(38, 'Carla Maria Rodrigues', '567.890.123-43', '(11) 99999-0005', 'Avenida Ipiranga, 1000 - Porto Alegre, RS', '1995-07-18'),
(39, 'Roberto Carlos Mendes', '678.901.234-54', '(11) 99999-0006', 'Avenida Paulista, 2000 - São Paulo, SP', '1982-11-25'),
(40, 'Juliana Ferreira Lima', '789.012.345-65', '(11) 99999-0007', 'Avenida Beira-Mar, 500 - Florianópolis, SC', '1993-09-12'),
(41, 'Fernando Souza Silva', '890.123.456-76', '(11) 99999-0008', 'Avenida Atlântica, 1000 - Rio de Janeiro, RJ', '1987-04-30'),
(42, 'Camila Oliveira Santos', '901.234.567-87', '(11) 99999-0009', 'Praça da Liberdade, 100 - Belo Horizonte, MG', '1991-06-08'),
(43, 'Ricardo Almeida Costa', '012.345.678-98', '(11) 99999-0010', 'Avenida das Nações Unidas, 12901 - São Paulo, SP', '1989-02-14'),
(44, 'Patrícia Gomes Mendes', '111.222.333-44', '(11) 99999-0011', 'Avenida Boa Viagem, 1500 - Recife, PE', '1994-10-20'),
(45, 'Maurício Santos Pereira', '222.333.444-55', '(11) 99999-0012', 'Avenida Presidente Vargas, 500 - Rio de Janeiro, RJ', '1986-01-28'),
(46, 'Vanessa Costa Rodrigues', '333.444.555-66', '(11) 99999-0013', 'Avenida Paulista, 3000 - São Paulo, SP', '1996-05-16'),
(47, 'Thiago Alves Oliveira', '444.555.666-77', '(11) 99999-0014', 'Avenida Afonso Pena, 2000 - Belo Horizonte, MG', '1984-12-09'),
(48, 'Débora Lima Santos', '555.666.777-88', '(11) 99999-0015', 'Avenida Brasil, 1000 - Rio de Janeiro, RJ', '1997-03-22'),
(49, 'Eduardo Santos Almeida', '666.777.888-99', '(11) 99999-0016', 'Avenida 7 de Setembro, 2000 - Salvador, BA', '1983-07-11'),
(50, 'Carla Mendonça Ferreira', '777.888.999-00', '(11) 99999-0017', 'Avenida da Liberdade, 500 - Lisboa, Portugal', '1998-08-05'),
(51, 'Lucas Oliveira Costa', '888.999.000-11', '(11) 99999-0018', 'Avenida Paulista, 4000 - São Paulo, SP', '1990-11-30'),
(52, 'Amanda Costa Silva', '999.000.111-22', '(11) 99999-0019', 'Avenida Atlântica, 3000 - Rio de Janeiro, RJ', '1988-04-17'),
(53, 'Bruno Almeida Pereira', '000.111.222-33', '(11) 99999-0020', 'Avenida das Américas, 5000 - Rio de Janeiro, RJ', '1995-09-23');

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
(3, 'Patrícia Oliveira', '123.456.789-00', 'Bibliotecária', '(11) 99999-1111', '2023-01-15'),
(4, 'Ricardo Souza', '987.654.321-00', 'Assistente de Estoque', '(11) 99999-2222', '2023-03-10'),
(5, 'Lúcia Santos', '456.789.123-00', 'Atendente', '(11) 99999-3333', '2023-02-20'),
(6, 'Felipe Costa', '789.123.456-00', 'Gerente', '(11) 99999-4444', '2022-11-05'),
(7, 'Ana Maria Silva', '123.456.789-09', 'Bibliotecária', '(11) 99999-5555', '2023-04-10'),
(8, 'Carlos Eduardo Santos', '234.567.890-10', 'Assistente de Estoque', '(11) 99999-6666', '2023-05-15'),
(9, 'Fernanda Costa Oliveira', '345.678.901-21', 'Atendente', '(11) 99999-7777', '2023-06-20'),
(10, 'Roberto Almeida Pereira', '456.789.012-32', 'Gerente', '(11) 99999-8888', '2022-12-01'),
(11, 'Juliana Rodrigues Mendes', '567.890.123-43', 'Bibliotecária', '(11) 99999-9999', '2023-07-05'),
(12, 'Pedro Henrique Lima', '678.901.234-54', 'Assistente de Estoque', '(11) 98888-1111', '2023-08-12'),
(13, 'Mariana Alves', '111.222.333-44', 'Bibliotecária', '(11) 99999-1212', '2023-01-15'),
(14, 'Paulo Roberto', '222.333.444-55', 'Assistente de Estoque', '(11) 99999-1313', '2023-03-10'),
(15, 'Sandra Regina', '333.444.555-66', 'Atendente', '(11) 99999-1414', '2023-02-20'),
(16, 'Fábio Silva', '444.555.666-77', 'Gerente', '(11) 99999-1515', '2022-11-05'),
(17, 'Cristina Souza', '555.666.777-88', 'Bibliotecária', '(11) 99999-1616', '2023-04-10'),
(18, 'Rafaela Mendes', '666.777.888-99', 'Assistente de Estoque', '(11) 99999-1717', '2023-05-15'),
(19, 'Gustavo Almeida', '777.888.999-00', 'Atendente', '(11) 99999-1818', '2023-06-20'),
(20, 'Tatiane Costa', '888.999.000-11', 'Gerente', '(11) 99999-1919', '2022-12-01'),
(21, 'Rodrigo Lima', '999.000.111-22', 'Bibliotecária', '(11) 99999-2020', '2023-07-05'),
(22, 'Aline Ferreira', '000.111.222-33', 'Assistente de Estoque', '(11) 99999-2121', '2023-08-12'),
(23, 'Marcelo Santos', '123.123.123-44', 'Bibliotecária', '(11) 99999-2222', '2023-09-01'),
(24, 'Camila Rodrigues', '234.234.234-55', 'Assistente de Estoque', '(11) 99999-2323', '2023-10-02'),
(25, 'Eduardo Oliveira', '345.345.345-66', 'Atendente', '(11) 99999-2424', '2023-11-03'),
(26, 'Renata Alves', '456.456.456-77', 'Gerente', '(11) 99999-2525', '2023-12-04'),
(27, 'Luciano Pereira', '567.567.567-88', 'Bibliotecária', '(11) 99999-2626', '2024-01-05'),
(28, 'Simone Costa', '678.678.678-99', 'Assistente de Estoque', '(11) 99999-2727', '2024-02-06'),
(29, 'Thiago Mendes', '789.789.789-00', 'Atendente', '(11) 99999-2828', '2024-03-07'),
(30, 'Viviane Silva', '890.890.890-11', 'Gerente', '(11) 99999-2929', '2024-04-08'),
(31, 'Wagner Souza', '901.901.901-22', 'Bibliotecária', '(11) 99999-3030', '2024-05-09'),
(32, 'Yara Lima', '012.012.012-33', 'Assistente de Estoque', '(11) 99999-3131', '2024-06-10');

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
  `status` ENUM('emprestado', 'devolvido', 'atrasado') DEFAULT 'emprestado',
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

INSERT INTO `fornecedor` (`nome_empresa`, `nome_fantasia`, `cnpj`, `contato`, `telefone`, `email`, `endereco`) VALUES
('Livraria Cultura', 'Cultura', '00.111.222/0001-33', 'Carlos Silva', '(11) 91111-1111', 'contato@cultura.com.br', 'Av. Paulista, 2000 - São Paulo, SP'),
('Livraria Nobel', 'Nobel', '11.222.333/0001-44', 'Maria Santos', '(11) 92222-2222', 'vendas@nobel.com.br', 'Rua Oscar Freire, 1000 - São Paulo, SP'),
('Livraria Martins Fontes', 'Martins Fontes', '22.333.444/0001-55', 'Roberto Almeida', '(11) 93333-3333', 'atendimento@martinsfontes.com.br', 'Rua da Consolação, 3000 - São Paulo, SP'),
('Editora Record', 'Record', '33.444.555/0001-66', 'Ana Costa', '(21) 94444-4444', 'vendas@record.com.br', 'Av. República do Chile, 500 - Rio de Janeiro, RJ'),
('Editora Globo', 'Globo Livros', '44.555.666/0001-77', 'Pedro Oliveira', '(51) 95555-5555', 'contato@globo.com.br', 'Av. Ericsson, 100 - Porto Alegre, RS'),
('Livraria da Travessa', 'Travessa', '55.666.777/0001-88', 'Juliana Mendes', '(71) 96666-6666', 'vendas@travessa.com.br', 'Av. Sete de Setembro, 2000 - Salvador, BA'),
('Editora Arqueiro', 'Arqueiro', '66.777.888/0001-99', 'Fernando Lima', '(31) 97777-7777', 'contato@arqueiro.com.br', 'Av. Afonso Pena, 3000 - Belo Horizonte, MG'),
('Livraria Leitura', 'Leitura', '77.888.999/0001-00', 'Camila Rodrigues', '(85) 98888-8888', 'atendimento@leitura.com.br', 'Av. Santos Dumont, 1000 - Fortaleza, CE'),
('Editora Intrínseca', 'Intrínseca', '88.999.000/0001-11', 'Ricardo Pereira', '(81) 99999-9999', 'vendas@intrinseca.com.br', 'Rua do Bom Jesus, 500 - Recife, PE'),
('Livraria Saraiva Filial', 'Saraiva Centro', '99.000.111/0001-22', 'Patrícia Gomes', '(61) 90000-0000', 'contato@saraivacentro.com.br', 'Av. W3 Sul, 1000 - Brasília, DF'),
('Editora Rocco', 'Rocco', '00.123.456/0001-33', 'Maurício Souza', '(41) 91234-5678', 'vendas@rocco.com.br', 'Rua Professor Pedro Viriato, 500 - Curitiba, PR'),
('Livraria Curitiba', 'Curitiba', '11.234.567/0001-44', 'Vanessa Costa', '(47) 92345-6789', 'contato@livrariacuritiba.com.br', 'Rua XV de Novembro, 1000 - Joinville, SC'),
('Editora Planeta', 'Planeta', '22.345.678/0001-55', 'Thiago Alves', '(62) 93456-7890', 'atendimento@planeta.com.br', 'Av. T-10, 2000 - Goiânia, GO'),
('Livraria Pernambucana', 'Pernambucana', '33.456.789/0001-66', 'Débora Lima', '(82) 94567-8901', 'vendas@pernambucana.com.br', 'Av. Fernandes Lima, 3000 - Maceió, AL'),
('Editora Sextante', 'Sextante', '44.567.890/0001-77', 'Eduardo Santos', '(84) 95678-9012', 'contato@sextante.com.br', 'Av. Prudente de Morais, 1000 - Natal, RN'),
('Livraria Amazonas', 'Amazonas', '55.678.901/0001-88', 'Carla Mendes', '(92) 96789-0123', 'atendimento@amazonas.com.br', 'Av. Eduardo Ribeiro, 2000 - Manaus, AM'),
('Editora HarperCollins', 'HarperCollins', '66.789.012/0001-99', 'Lucas Oliveira', '(96) 97890-1234', 'vendas@harpercollins.com.br', 'Av. Portugal, 1000 - Boa Vista, RR'),
('Livraria Pioneira', 'Pioneira', '77.890.123/0001-00', 'Amanda Costa', '(69) 98901-2345', 'contato@pioneira.com.br', 'Av. Marechal Rondon, 2000 - Porto Velho, RO'),
('Editora Companhia de Bolso', 'Companhia de Bolso', '88.901.234/0001-11', 'Bruno Almeida', '(63) 99012-3456', 'vendas@companhiadebolso.com.br', 'Av. NS 15, 1000 - Palmas, TO'),
('Livraria do Brasil', 'Do Brasil', '99.012.345/0001-22', 'Fernanda Silva', '(98) 90123-4567', 'atendimento@dobrasil.com.br', 'Av. Dom Pedro II, 2000 - São Luís, MA');

INSERT INTO `autor` (`nome_autor`) VALUES
('J.R.R. Tolkien'),
('Jane Austen'),
('Franz Kafka'),
('Clarice Lispector'),
('Yuval Noah Harari'),
('Carl Sagan'),
('George R.R. Martin'),
('Stephen King'),
('Agatha Christie'),
('Dan Brown'),
('Machado de Assis'),
('Jorge Amado'),
('Paulo Coelho'),
('Chimamanda Ngozi Adichie'),
('Haruki Murakami'),
('Gabriel García Márquez'),
('Isaac Asimov'),
('Arthur C. Clarke'),
('Jorge Luis Borges'),
('José Saramago'),
('Toni Morrison'),
('Margaret Atwood'),
('Ernest Hemingway'),
('F. Scott Fitzgerald'),
('Virginia Woolf'),
('James Joyce'),
('Leo Tolstoy'),
('Fyodor Dostoevsky'),
('Charles Dickens'),
('Mark Twain');

INSERT INTO `editora` (`nome_editora`) VALUES
('Editora Record'),
('Editora Globo'),
('Editora Rocco'),
('Editora Arqueiro'),
('Editora Intrínseca'),
('Editora Planeta'),
('Editora Sextante'),
('Editora HarperCollins'),
('Editora Companhia das Letras'),
('Editora Abril'),
('Editora Objetiva'),
('Editora Novo Conceito'),
('Editora Zahar'),
('Editora Vozes'),
('Editora Ática'),
('Editora Moderna'),
('Editora Scipione'),
('Editora Saraiva'),
('Editora Cultrix'),
('Editora Martins Fontes'),
('Editora L&PM'),
('Editora Penguin Companhia'),
('Editora Nova Fronteira'),
('Editora Civilização Brasileira'),
('Editora Bertrand Brasil'),
('Editora Aleph'),
('Editora Todavia'),
('Editora Companhia de Bolso'),
('Editora Suma'),
('Editora Companhia das Letras');

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
('O Pequeno Príncipe', '978-85-8275-023-5', 1, 4, 5, 2019, '12ª edição', 12, 5),
('O Hobbit', '978-85-8086-123-4', 4, 1, 1, 2012, '1ª edição', 7, 1),
('Orgulho e Preconceito', '978-85-359-5678-1', 2, 2, 2, 2015, '5ª edição', 6, 2),
('O Processo', '978-85-7902-456-7', 1, 3, 3, 2018, '3ª edição', 4, 3),
('A Paixão segundo G.H.', '978-85-359-8765-2', 1, 4, 2, 2019, '8ª edição', 5, 2),
('Homo Deus', '978-85-8086-789-0', 8, 5, 4, 2017, '1ª edição', 8, 4),
('Pale Blue Dot', '978-85-8086-345-6', 8, 6, 5, 2016, '2ª edição', 3, 5),
('O Código Da Vinci', '978-85-8086-234-5', 1, 1, 1, 2013, '10ª edição', 9, 1),
('Memórias Póstumas de Brás Cubas', '978-85-359-1234-5', 2, 2, 2, 2014, '12ª edição', 7, 2),
('A Revolução dos Bichos', '978-85-7902-678-9', 1, 3, 3, 2016, '4ª edição', 6, 3),
('A Hora da Estrela', '978-85-359-5432-1', 1, 4, 2, 2017, '15ª edição', 8, 2),
('21 Lições para o Século 21', '978-85-8086-567-8', 8, 5, 4, 2018, '1ª edição', 5, 4),
('O Mundo de Sofia', '978-85-8086-901-2', 4, 6, 5, 2015, '3ª edição', 7, 5),
('Harry Potter e a Câmara Secreta', '978-85-8086-345-7', 4, 1, 1, 2012, '1ª edição', 6, 1),
('Quincas Borba', '978-85-359-2345-6', 2, 2, 2, 2015, '7ª edição', 5, 2),
('O Conto da Aia', '978-85-7902-789-0', 1, 3, 3, 2019, '2ª edição', 4, 3),
('Perto do Coração Selvagem', '978-85-359-6789-0', 1, 4, 2, 2018, '10ª edição', 6, 2),
('Sapiens Ilustrado', '978-85-8086-111-1', 8, 5, 4, 2019, '1ª edição', 3, 4),
('Contact', '978-85-8086-222-2', 4, 6, 5, 2017, '1ª edição', 5, 5),
('Harry Potter e o Prisioneiro de Azkaban', '978-85-8086-333-3', 4, 1, 1, 2013, '1ª edição', 7, 1),
('Helena', '978-85-359-3333-3', 2, 2, 2, 2016, '5ª edição', 6, 2),
('1984 - Edição Especial', '978-85-7902-444-4', 1, 3, 3, 2020, '5ª edição', 4, 3),
('Laços de Família', '978-85-359-5555-5', 1, 4, 2, 2019, '12ª edição', 5, 2),
('Deuses e Humanos', '978-85-8086-666-6', 8, 5, 4, 2018, '1ª edição', 6, 4),
('Cometa', '978-85-8086-777-7', 4, 6, 5, 2016, '1ª edição', 4, 5),
('Harry Potter e o Cálice de Fogo', '978-85-8086-888-8', 4, 1, 1, 2014, '1ª edição', 8, 1),
('Iaiás Garcia', '978-85-359-4444-4', 2, 2, 2, 2017, '3ª edição', 5, 2),
('A Flor da Idade', '978-85-7902-555-5', 1, 3, 3, 2018, '1ª edição', 3, 3),
('Água Viva', '978-85-359-6666-6', 1, 4, 2, 2020, '8ª edição', 6, 2),
('Futuro Breve', '978-85-8086-999-9', 8, 5, 4, 2020, '1ª edição', 7, 4),
('O Dragão do Mar', '978-85-8086-000-0', 4, 6, 5, 2019, '1ª edição', 5, 5);

-- Adiciona coluna id_funcionario
ALTER TABLE emprestimo 
ADD COLUMN id_funcionario INT NULL DEFAULT NULL AFTER id_usuario;

-- Adiciona chave estrangeira
ALTER TABLE emprestimo 
ADD CONSTRAINT fk_emprestimo_funcionario 
FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario) 
ON DELETE SET NULL;