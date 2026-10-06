-- 1. Criação do Banco de Dados
CREATE DATABASE IF NOT EXISTS doce_maria;

USE doce_maria;

-- --------------------------------------------------------
-- 2. Tabela: produto
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS produto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    descricao TEXT NULL
);

-- --------------------------------------------------------
-- 3. Tabela: pedidos
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status VARCHAR(50) NOT NULL DEFAULT 'pago',
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- 4. Tabela: itens_pedido
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produto(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- 5. Tabela: avaliacoes
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS avaliacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NULL,
    nome VARCHAR(100) NOT NULL,
    imagem VARCHAR(255) NOT NULL DEFAULT 'usuario.png',
    nota INT NOT NULL,
    comentario TEXT NOT NULL,
    data_avaliacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produto_id) REFERENCES produto(id) ON DELETE SET NULL
);


-- ========================================================
-- INSERÇÃO DE DADOS DE TESTE (INSERTS)
-- ========================================================

-- A) Inserir Produtos
INSERT INTO produto ( nome, preco, imagem, descricao) VALUES
('Bolo de Morango Doce Maria', 45.00, 'bolo_morango.png', 'Bolo fofinho recheado com creme e morangos frescos.'),
( 'Brigadeiro Gourmet 12 un', 25.00, 'brigadeiros.png', 'Caixa com 12 brigadeiros gourmet de chocolate belga.'),
( 'Torta de Limão Siciliano', 38.00, 'torta_limao.png', 'Torta crocante com creme de limão e merengue maçaricado.'),
( 'Cupcake Red Velvet', 12.50, 'cupcake_red.png', 'Cupcake aveludado com cobertura cremosa de cream cheese.'),
( 'Brownie com Nozes', 10.00, 'brownie.png', 'Brownie super denso, fofinho por dentro e crocante por fora.');

-- B) Inserir Pedidos
INSERT INTO pedidos (id, status, data_pedido) VALUES
(1, 'pago', '2026-09-20 10:30:00'),
(2, 'pago', '2026-09-21 14:15:00'),
(3, 'pago', '2026-09-22 16:45:00'),
(4, 'pago', '2026-09-23 11:00:00'),
(5, 'pago', '2026-09-24 18:20:00');

-- C) Inserir Itens dos Pedidos
INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES
(1, 1, 10, 45.00),
(1, 2, 5,  25.00),
(2, 1, 5,  45.00),
(2, 3, 4,  38.00),
(3, 2, 5,  25.00),
(3, 4, 5,  12.50),
(4, 3, 3,  38.00),
(5, 5, 2,  10.00);

-- D) Inserir Avaliações
INSERT INTO avaliacoes (produto_id, nome, imagem, nota, comentario, data_avaliacao) VALUES
(1, 'Ana Clara', 'perfil1.png', 5, 'O bolo de morango é simplesmente maravilhoso! Chegou super fresquinho.', '2026-09-25 12:00:00'),
(2, 'Carlos Eduardo', 'perfil2.png', 5, 'Os brigadeiros derretem na boca! Com certeza vou pedir novamente.', '2026-09-26 15:30:00'),
(3, 'Beatriz Souza', 'perfil3.png', 4, 'A torta de limão é incrível, no ponto exato entre o azedinho e o doce!', '2026-09-27 18:10:00');