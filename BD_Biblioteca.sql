CREATE DATABASE IF NOT EXISTS biblioteca_estoquev2;
USE biblioteca_estoquev2;

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
('Superior', 'super@biblioteca.com', '$2y$10$exemplohashseguro', 2, 1),
('Funcionario', 'func@biblioteca.com', '$2y$10$exemplohashseguro', 3, 1),
('Cliente', 'cliente@biblioteca.com', '$2y$10$exemplohashseguro', 4, 1);

-- Criar tabela cliente SEM FK inicialmente
CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` INT NOT NULL AUTO_INCREMENT,
  `nome_completo` VARCHAR(150) NOT NULL,
  `cpf` VARCHAR(14) UNIQUE,
  `telefone` VARCHAR(20),
  `data_nascimento` DATE,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Criar os usuários clientes primeiro
INSERT INTO `usuario` (`nome`, `email`, `senha`, `id_perfil`, `senha_temporaria`) VALUES
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

-- Agora inserir os clientes com os MESMOS IDs dos usuários
INSERT INTO `cliente` (`id_cliente`, `nome_completo`, `cpf`, `telefone`, `data_nascimento`) VALUES
(5, 'Maria Silva Santos', '123.456.789-09', '(11) 99999-0001', '1990-05-15'),
(6, 'João Oliveira Costa', '234.567.890-10', '(11) 99999-0002', '1985-08-22'),
(7, 'Ana Carolina Pereira', '345.678.901-21', '(11) 99999-0003', '1992-12-03'),
(8, 'Pedro Henrique Almeida', '456.789.012-32', '(11) 99999-0004', '1988-03-10'),
(9, 'Carla Maria Rodrigues', '567.890.123-43', '(11) 99999-0005', '1995-07-18'),
(10, 'Roberto Carlos Mendes', '678.901.234-54', '(11) 99999-0006', '1982-11-25'),
(11, 'Juliana Ferreira Lima', '789.012.345-65', '(11) 99999-0007', '1993-09-12'),
(12, 'Fernando Souza Silva', '890.123.456-76', '(11) 99999-0008', '1987-04-30'),
(13, 'Camila Oliveira Santos', '901.234.567-87', '(11) 99999-0009', '1991-06-08'),
(14, 'Ricardo Almeida Costa', '012.345.678-98', '(11) 99999-0010', '1989-02-14'),
(15, 'Patrícia Gomes Mendes', '111.222.333-44', '(11) 99999-0011', '1994-10-20'),
(16, 'Maurício Santos Pereira', '222.333.444-55', '(11) 99999-0012', '1986-01-28'),
(17, 'Vanessa Costa Rodrigues', '333.444.555-66', '(11) 99999-0013', '1996-05-16'),
(18, 'Thiago Alves Oliveira', '444.555.666-77', '(11) 99999-0014', '1984-12-09'),
(19, 'Débora Lima Santos', '555.666.777-88', '(11) 99999-0015', '1997-03-22'),
(20, 'Eduardo Santos Almeida', '666.777.888-99', '(11) 99999-0016', '1983-07-11'),
(21, 'Carla Mendonça Ferreira', '777.888.999-00', '(11) 99999-0017', '1998-08-05'),
(22, 'Lucas Oliveira Costa', '888.999.000-11', '(11) 99999-0018', '1990-11-30'),
(23, 'Amanda Costa Silva', '999.000.111-22', '(11) 99999-0019', '1988-04-17'),
(24, 'Bruno Almeida Pereira', '000.111.222-33', '(11) 99999-0020', '1995-09-23');

-- AGORA ADICIONAR A FK (após os dados estarem consistentes)
ALTER TABLE cliente ADD CONSTRAINT fk_cliente_usuario FOREIGN KEY (id_cliente) REFERENCES usuario(id_usuario) ON DELETE CASCADE;

-- Continuar com as outras tabelas
CREATE TABLE IF NOT EXISTS `funcionario` (
  `id_funcionario` INT NOT NULL AUTO_INCREMENT,
  `nome_completo` VARCHAR(150) NOT NULL,
  `cpf` VARCHAR(14) UNIQUE,
  `cargo` VARCHAR(100),
  `telefone` VARCHAR(20),
  `data_admissao` DATE,
  PRIMARY KEY (`id_funcionario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

INSERT INTO `funcionario` (`nome_completo`, `cpf`, `cargo`, `telefone`, `data_admissao`) VALUES
('Patrícia Oliveira', '123.456.789-00', 'Bibliotecária', '(11) 99999-1111', '2023-01-15'),
('Ricardo Souza', '987.654.321-00', 'Assistente de Estoque', '(11) 99999-2222', '2023-03-10'),
('Lúcia Santos', '456.789.123-00', 'Atendente', '(11) 99999-3333', '2023-02-20'),
('Felipe Costa', '789.123.456-00', 'Gerente', '(11) 99999-4444', '2022-11-05');

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

-- 30 fornecedores adicionais
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
('Livraria do Brasil', 'Do Brasil', '99.012.345/0001-22', 'Fernanda Silva', '(98) 90123-4567', 'atendimento@dobrasil.com.br', 'Av. Dom Pedro II, 2000 - São Luís, MA'),
('Editora Zahar', 'Zahar', '00.135.792/0001-33', 'Gustavo Santos', '(95) 91357-9246', 'contato@zahar.com.br', 'Av. Getúlio Vargas, 1000 - Porto Velho, RO'),
('Livraria Panorama', 'Panorama', '11.246.803/0001-44', 'Isabela Lima', '(68) 92468-0357', 'vendas@panorama.com.br', 'Av. Getúlio Vargas, 2000 - Rio Branco, AC'),
('Editora Nova Fronteira', 'Nova Fronteira', '22.357.914/0001-55', 'Henrique Costa', '(91) 93579-1468', 'atendimento@novafronteira.com.br', 'Av. João Paulo II, 1000 - Belém, PA'),
('Livraria Moderna', 'Moderna', '33.468.025/0001-66', 'Larissa Almeida', '(83) 94680-2579', 'contato@moderna.com.br', 'Av. Epitácio Pessoa, 2000 - João Pessoa, PB'),
('Editora Ática', 'Ática', '44.579.136/0001-77', 'Marcos Silva', '(86) 95791-3680', 'vendas@atica.com.br', 'Av. Frei Serafim, 1000 - Teresina, PI'),
('Livraria Scipione', 'Scipione', '55.680.247/0001-88', 'Renata Santos', '(89) 96802-4791', 'atendimento@scipione.com.br', 'Av. Frei Serafim, 2000 - São Gonçalo do Amarante, PI'),
('Editora Vozes', 'Vozes', '66.791.358/0001-99', 'Felipe Costa', '(99) 97913-5802', 'contato@vozes.com.br', 'Av. Getúlio Vargas, 1000 - Imperatriz, MA'),
('Livraria Bertrand', 'Bertrand', '77.802.469/0001-00', 'Camila Almeida', '(93) 98024-6913', 'vendas@bertrand.com.br', 'Av. Getúlio Vargas, 2000 - Santarém, PA'),
('Editora Civilização Brasileira', 'Civilização Brasileira', '88.913.570/0001-11', 'Rafael Silva', '(94) 99135-7024', 'atendimento@civilizacao.com.br', 'Av. João Paulo II, 1000 - Marabá, PA'),
('Livraria Martins', 'Martins', '99.024.681/0001-22', 'Juliana Santos', '(97) 90246-8135', 'contato@martins.com.br', 'Av. Getúlio Vargas, 1000 - Macapá, AP');

-- 10 categorias adicionais
INSERT INTO `categoria` (`nome_categoria`, `descricao`) VALUES
('Ciência', 'Livros sobre ciência e tecnologia'),
('Filosofia', 'Livros sobre filosofia e pensamento'),
('Poesia', 'Livros de poesia e literatura poética'),
('Infantil', 'Livros para crianças'),
('Juvenil', 'Livros para adolescentes'),
('Religião', 'Livros sobre religião e espiritualidade'),
('Direito', 'Livros sobre direito e legislação'),
('Medicina', 'Livros sobre medicina e saúde'),
('Engenharia', 'Livros sobre engenharia e tecnologia'),
('Arte', 'Livros sobre arte e cultura');

-- 50 produtos adicionais
INSERT INTO `produto` (`titulo`, `isbn`, `id_categoria`, `id_autor`, `id_editora`, `ano_publicacao`, `edicao`, `quantidade_estoque`, `id_fornecedor`) VALUES
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
('O Dragão do Mar', '978-85-8086-000-0', 4, 6, 5, 2019, '1ª edição', 5, 5),
('Harry Potter e a Ordem da Fênix', '978-85-8086-111-2', 4, 1, 1, 2015, '1ª edição', 6, 1),
('Dom Casmurro - Edição Comemorativa', '978-85-359-7777-7', 2, 2, 2, 2020, '15ª edição', 4, 2),
('A Lavoura Arcaica', '978-85-7902-666-6', 1, 3, 3, 2019, '2ª edição', 5, 3),
('A Cidade Sitiada', '978-85-359-8888-8', 1, 4, 2, 2021, '1ª edição', 3, 2),
('Inteligência Artificial', '978-85-8086-222-3', 8, 5, 4, 2021, '1ª edição', 6, 4),
('O Planeta dos Macacos', '978-85-8086-333-4', 4, 6, 5, 2018, '1ª edição', 4, 5),
('Harry Potter e o Enigma do Príncipe', '978-85-8086-444-4', 4, 1, 1, 2016, '1ª edição', 7, 1),
('O Alienista - Edição Especial', '978-85-359-9999-9', 2, 2, 2, 2021, '20ª edição', 5, 2),
('O Triunfo do Porco', '978-85-7902-777-7', 1, 3, 3, 2020, '1ª edição', 3, 3),
('A Hora da Estrela - Edição Ilustrada', '978-85-359-0000-0', 1, 4, 2, 2022, '25ª edição', 6, 2),
('Origens da Civilização', '978-85-8086-555-5', 8, 5, 4, 2019, '1ª edição', 4, 4),
('Viagem às Estrelas', '978-85-8086-666-7', 4, 6, 5, 2020, '1ª edição', 5, 5),
('Harry Potter e as Relíquias da Morte', '978-85-8086-777-8', 4, 1, 1, 2017, '1ª edição', 8, 1),
('Memorial de Aires', '978-85-359-1111-1', 2, 2, 2, 2022, '8ª edição', 6, 2),
('O Sistema dos Animais', '978-85-7902-888-8', 1, 3, 3, 2021, '1ª edição', 4, 3),
('A Invenção da Querência', '978-85-359-2222-2', 1, 4, 2, 2023, '1ª edição', 5, 2),
('O Futuro da Humanidade', '978-85-8086-888-9', 8, 5, 4, 2022, '1ª edição', 7, 4),
('O Homem do Castelo Alto', '978-85-8086-999-0', 4, 6, 5, 2021, '1ª edição', 3, 5);