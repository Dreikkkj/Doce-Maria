USE db_docemaria;

-- Tabela usuarios
-- (Cadastro, Login, Minha Conta, Fidelidade e Painel Admin)
CREATE TABLE IF NOT EXISTS usuarios (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    tipo_usuario ENUM('cliente', 'admin') DEFAULT 'cliente',
    pontos_fidelidade INT DEFAULT 0,
    data_cadastro DATE NOT NULL DEFAULT (CURRENT_DATE)
);

-- Tabela produtos
-- (Catálogo, Best Sellers, Controle de Estoque e Carrinho)
CREATE TABLE IF NOT EXISTS produtos (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    img_produto VARCHAR(255),
    nome_produto VARCHAR(100) NOT NULL,
    descricao TEXT,
    categoria ENUM(
        'Cookies', 'Cupcakes', 'Brownies', 'Doces', 
        'Bolos', 'Alfajor', 'Brigadeiro', 'Cannoli', 
        'Donuts', 'Eclair', 'Macaron', 'Pirulitos'
    ) NOT NULL,
    peso_tamanho VARCHAR(50),
    preco DECIMAL(10,2) NOT NULL,
    estoque INT NOT NULL DEFAULT 0,
    status_estoque ENUM('Em estoque', 'Estoque baixo', 'Esgotado') DEFAULT 'Em estoque',
    estrelas INT NULL CHECK (estrelas BETWEEN 0 AND 5)
);

-- Tabela pedidos
-- (Checkout, Meus Pedidos e Painel Admin) 
CREATE TABLE IF NOT EXISTS pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    data_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status_pedido ENUM(
        'Pendente', 'Em preparo', 'Pronto', 'Entregue', 'Cancelado'
    ) DEFAULT 'Pendente',
    metodo_coleta ENUM('Retirada', 'Entrega') NOT NULL,
    nome_coletor VARCHAR(100),
    nota_pedido TEXT,
    subtotal DECIMAL(10, 2) NOT NULL,
    gorjeta DECIMAL(10, 2) DEFAULT 0.00,
    valor_total DECIMAL(10, 2) NOT NULL,
    metodo_pagamento ENUM('Cartão', 'Pix', 'Dinheiro') NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES usuarios (id_user)
);

-- Tabela itens_pedido
-- (Carrinho e Detalhes do Pedido)
CREATE TABLE IF NOT EXISTS itens_pedido (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos (id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos (id_produto)
);

-- Ex. de pessoas da tabela usuarios
INSERT INTO usuarios (nome, email, senha, telefone, tipo_usuario, pontos_fidelidade)
VALUES 
('Maria Silva', 'maria.silva@email.com', 'hash_senha_admin', '(11) 99999-9999', 'admin', 0),
('Ana Beatriz', 'ana@email.com', 'hash_senha_123', '(11) 98888-8888', 'cliente', 2350),
('Juliana Martins', 'juliana@email.com', 'hash_senha_123', '(11) 97777-7777', 'cliente', 150);

-- Ex. de produtos da tabela produtos
INSERT INTO produtos (img_produto, nome_produto, categoria, peso_tamanho, preco, estoque, status_estoque, estrelas)
VALUES 
('cookie_trad.png', 'Cookie Tradicional', 'Cookies', 'Unidade (60g)', 15.90, 28, 'Em estoque', 5),
('cupcake_morango.png', 'Cupcake de Morango', 'Cupcakes', 'Unidade (80g)', 12.50, 12, 'Em estoque', 4),
('brownie_choc.png', 'Brownie de Chocolate', 'Brownies', 'Unidade (70g)', 18.00, 5, 'Estoque baixo', 5),
('brigadeiro_gourmet.png', 'Brigadeiro Gourmet', 'Doces', 'Unidade (25g)', 4.50, 40, 'Em estoque', 5),
('bolo_cenoura.png', 'Fatia de Bolo de Cenoura', 'Bolos', 'Fatia (120g)', 14.90, 3, 'Estoque baixo', 4),
('cookie_granulado.png', 'Cookie granulado 300g', 'Cookies', '300g', 23.90, 15, 'Em estoque', 5);

-- População da tabela pedidos
INSERT INTO pedidos (id_cliente, data_pedido, status_pedido, metodo_coleta, nome_coletor, subtotal, gorjeta, valor_total, metodo_pagamento)
VALUES 
(2, '2025-05-16 14:30:00', 'Em preparo', 'Retirada', 'Ana Beatriz', 84.90, 5.00, 89.90, 'Cartão'),
(3, '2025-05-16 13:15:00', 'Pendente', 'Entrega', 'Juliana Martins', 54.90, 0.00, 54.90, 'Pix');

-- População da tabela itens_pedido
INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario)
VALUES 
(1, 1, 2, 15.90),
(1, 3, 2, 18.00),
(2, 6, 1, 23.90);

