-- 1. Criação do Banco de Dados

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


