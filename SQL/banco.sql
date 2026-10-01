CREATE DATABASE IF NOT EXISTS db_docemaria;
USE db_docemaria;


SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS itens_pedido;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS produtos;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS configuracoes_site;

SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE usuarios (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    tipo_usuario ENUM('cliente', 'admin') DEFAULT 'cliente',
    pontos_fidelidade INT DEFAULT 0,
    data_cadastro DATE NOT NULL DEFAULT (CURRENT_DATE)
) ENGINE=InnoDB;


CREATE TABLE produtos (
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
    estrelas INT NULL CHECK (estrelas BETWEEN 0 AND 5),
    eh_pacote_buffet BOOLEAN DEFAULT FALSE 
) ENGINE=InnoDB;


CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    data_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status_pedido ENUM(
        'Pendente', 'Em preparo', 'Pronto', 'Entregue', 'Cancelado'
    ) DEFAULT 'Pendente',
    metodo_coleta ENUM('Retirada', 'Entrega') NOT NULL,
    nome_coletor VARCHAR(100),
    nota_pedido TEXT,
    cep VARCHAR(10),
    endereco_entrega TEXT,
    subtotal DECIMAL(10, 2) NOT NULL,
    valor_frete DECIMAL(10, 2) DEFAULT 0.00,
    gorjeta DECIMAL(10, 2) DEFAULT 0.00,
    valor_total DECIMAL(10, 2) NOT NULL,
    metodo_pagamento ENUM('Cartão', 'Pix', 'Dinheiro') NOT NULL,
    possui_brinde BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_cliente) REFERENCES usuarios (id_user)
) ENGINE=InnoDB;


CREATE TABLE itens_pedido (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos (id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos (id_produto)
) ENGINE=InnoDB;

CREATE TABLE configuracoes_site (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(50) NOT NULL UNIQUE,
    valor TEXT NOT NULL,
    descricao VARCHAR(150)
) ENGINE=InnoDB;



INSERT INTO usuarios (nome, email, senha, telefone, tipo_usuario, pontos_fidelidade)
VALUES 
('Maria Silva', 'maria.silva@email.com', 'hash_senha_admin', '(11) 99999-9999', 'admin', 0),
('Ana Beatriz', 'ana@email.com', 'hash_senha_123', '(11) 98888-8888', 'cliente', 2350),
('Juliana Martins', 'juliana@email.com', 'hash_senha_123', '(11) 97777-7777', 'cliente', 150);


INSERT INTO produtos 
(img_produto, nome_produto, descricao, categoria, peso_tamanho, preco, estoque, status_estoque, estrelas)
VALUES 
('./uploads/donut_doce_maria.jpg', 'Donut Especial Doce Maria', 'O carro-chefe da loja! Donut macio com cobertura rosa de morango e calda de chocolate escorrendo.', 'Donuts', 'Unidade (90g)', 12.90, 45, 'Em estoque', 5),
('donut_boston_cream.png', 'Donut Boston Cream', 'Massa fofinha recheada com creme de baunilha e cobertura de ganache de chocolate.', 'Donuts', 'Unidade (100g)', 13.90, 8, 'Estoque baixo', 5),
('donut_glaceado.png', 'Donut Glaceado Tradicional', 'O clássico americano com casquinha crocante de açúcar derretido.', 'Donuts', 'Unidade (75g)', 9.90, 0, 'Esgotado', 4),
('brigadeiro_gourmet.png', 'Brigadeiro Gourmet ao Leite', 'Brigadeiro feito com chocolate nobre belga e confeitos crocantes.', 'Brigadeiro', 'Unidade (25g)', 4.50, 60, 'Em estoque', 5),
('brigadeiro_pistache.png', 'Brigadeiro de Pistache', 'Brigadeiro cremoso de pistache coberto com pistache xará triturado.', 'Brigadeiro', 'Unidade (25g)', 6.00, 15, 'Em estoque', 5),
('caixa_brigadeiros.png', 'Caixa Sortida de Brigadeiros', 'Caixa presenteável com 12 brigadeiros sortidos (Ao leite, Ninho com Nutella e Pistache).', 'Brigadeiro', 'Caixa (300g)', 48.00, 6, 'Estoque baixo', 5),
('cookie_tradicional.png', 'Cookie Tradicional gotas de chocolate', 'Massa macia por dentro e crocante por fora com gotas de chocolate meio amargo.', 'Cookies', 'Unidade (60g)', 15.90, 28, 'Em estoque', 5),
('cookie_red_velvet.png', 'Cookie Red Velvet com Ninho', 'Massa red velvet recheada com brigadeiro de leite Ninho cremoso.', 'Cookies', 'Unidade (75g)', 17.50, 4, 'Estoque baixo', 5),
('cookie_granulado.png', 'Pote de Mini Cookies Granulados', 'Pote recheado com mini cookies crocantes perfeitos para viagem.', 'Cookies', 'Pote (300g)', 23.90, 15, 'Em estoque', 5),
('cupcake_morango.png', 'Cupcake de Morango e Chantilly', 'Massa de baunilha, recheio de geleia artesanal de morango e cobertura de chantilly.', 'Cupcakes', 'Unidade (80g)', 12.50, 12, 'Em estoque', 4),
('cupcake_choc.png', 'Cupcake Duplo Chocolate', 'Massa de cacau 70% recheada e coberta com ganache intensa.', 'Cupcakes', 'Unidade (85g)', 13.50, 2, 'Estoque baixo', 5),
('brownie_choc.png', 'Brownie de Chocolate com Nozes', 'Brownie denso, bem molhadinho, com pedaços de nozes americanas.', 'Brownies', 'Unidade (70g)', 18.00, 5, 'Estoque baixo', 5),
('brownie_nutella.png', 'Marmita de Brownie com Nutella', 'Pedaços de brownie submersos em pura Nutella e leite Ninho.', 'Brownies', 'Marmita (250g)', 29.90, 10, 'Em estoque', 5),
('bolo_cenoura.png', 'Fatia de Bolo de Cenoura', 'O clássico bolo de cenoura caseiro com casca durinha de chocolate.', 'Bolos', 'Fatia (120g)', 14.90, 3, 'Estoque baixo', 4),
('bolo_red_velvet.png', 'Fatia de Bolo Red Velvet', 'Massa aveludada com camadas intercaladas de cream cheese frosting.', 'Bolos', 'Fatia (140g)', 18.90, 10, 'Em estoque', 5),
('alfajor_doce_leite.png', 'Alfajor Tradicional de Doce de Leite', 'Biscoito amanteigado recheado com bastante doce de leite e coberto com chocolate.', 'Alfajor', 'Unidade (60g)', 9.50, 20, 'Em estoque', 5),
('alfajor_branco.png', 'Alfajor de Chocolate Branco', 'Recheado com doce de leite argentino e coberto com chocolate branco.', 'Alfajor', 'Unidade (60g)', 9.50, 0, 'Esgotado', 4),
('cannoli_siciliano.png', 'Cannoli Siciliano Tradicional', 'Massa crocante recheada com creme de ricota doce, gotas de chocolate e raspas de laranja.', 'Cannoli', 'Unidade (70g)', 14.50, 8, 'Estoque baixo', 5),
('cannoli_nutella.png', 'Cannoli de Nutella', 'Massa frita super crocante recheada com creme denso de Nutella.', 'Cannoli', 'Unidade (70g)', 15.00, 14, 'Em estoque', 4),
('eclair_chocolate.png', 'Eclair de Chocolate (Bomba)', 'Massa choux leve recheada com creme patissière de chocolate e glacê brilhante.', 'Eclair', 'Unidade (80g)', 14.00, 10, 'Em estoque', 5),
('eclair_cafe.png', 'Eclair de Café e Caramelo', 'Recheada com creme suave de café espresso e cobertura de caramelo salgado.', 'Eclair', 'Unidade (80g)', 14.00, 0, 'Esgotado', 4),
('macaron_frutas.png', 'Caixa de Macarons Franceses', 'Caixa com 6 macarons sortidos (Frutas vermelhas, Pistache, Chocolate e Baunilha).', 'Macaron', 'Caixa (90g)', 34.00, 12, 'Em estoque', 5),
('macaron_unidade.png', 'Macaron de Frutas Vermelhas', 'Biscoito à base de farinha de amêndoas com recheio azedinho de frutas vermelhas.', 'Macaron', 'Unidade (15g)', 6.50, 25, 'Em estoque', 4),
('pirulito_cristal.png', 'Pirulito de Cristal com Flores Comestíveis', 'Pirulito transparente artesanal feito de isomalte com flores naturais comestíveis.', 'Pirulitos', 'Unidade (40g)', 8.50, 18, 'Em estoque', 5),
('pirulito_chocolate.png', 'Pirulito de Chocolate Decorado', 'Pirulito de chocolate ao leite moldado e decorado para festas.', 'Pirulitos', 'Unidade (50g)', 9.00, 30, 'Em estoque', 4),
('pao_de_mel.png', 'Pão de Mel com Doce de Leite', 'Massa fofinha com especiarias, recheado com doce de leite e banhado no chocolate.', 'Doces', 'Unidade (50g)', 7.50, 40, 'Em estoque', 5),
('coxinha_morango.png', 'Coxinha de Morango com Brigadeiro', 'Morango fresco inteiro envolto por uma camada generosa de brigadeiro gourmet.', 'Doces', 'Unidade (100g)', 12.00, 7, 'Estoque baixo', 5),
('pacote_buffet.png', 'Pacote Buffet de Festa', 'Combo especial com centenas de doces para festas (Acompanha Brinde Exclusivo).', 'Doces', 'Pacote Buffet', 450.00, 10, 'Em estoque', 5);


INSERT INTO configuracoes_site (chave, valor, descricao) VALUES
('instagram_url', 'https://instagram.com/docemaria.doces', 'Link do Instagram da loja'),
('whatsapp_numero', '5511999999999', 'Contato do WhatsApp para suporte'),
('cor_primaria', '#8B4513', 'Marrom'),
('cor_secundaria', '#FFC0CB', 'Rosa Donuts');

INSERT INTO pedidos 
(id_cliente, data_pedido, status_pedido, metodo_coleta, nome_coletor, nota_pedido, subtotal, valor_frete, gorjeta, valor_total, metodo_pagamento, possui_brinde)
VALUES 
(2, '2025-05-16 14:30:00', 'Em preparo', 'Retirada', 'Ana Beatriz', '', 84.90, 0.00, 5.00, 89.90, 'Cartão', FALSE),
(3, '2025-05-16 13:15:00', 'Pendente', 'Entrega', 'Juliana Martins', '', 54.90, 10.00, 0.00, 64.90, 'Pix', FALSE),
(2, CURRENT_TIMESTAMP, 'Pendente', 'Entrega', 'Ana Beatriz', 'Por favor, caprichar na embalagem para presente!', 110.80, 0.00, 5.00, 115.80, 'Pix', FALSE);

INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario)
VALUES 
(1, 7, 2, 15.90),
(1, 12, 2, 18.00),
(2, 9, 1, 23.90),
(3, 1, 2, 12.90),
(3, 6, 1, 48.00),
(3, 12, 2, 18.00);