create database doce_maria;
use doce_maria;


CREATE TABLE produtos (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    img_produto VARCHAR(255),
    nome_produto VARCHAR(100) NOT NULL,
    descricao TEXT,
    categoria ENUM(
        'Cookies', 'Cupcakes', 'Brownies',
        'Bolos','Brigadeiro', 'Donuts', 
        'Macaron', 'Pirulitos'
    ) NOT NULL,
    peso_tamanho VARCHAR(50),
    preco DECIMAL(10,2) NOT NULL,
    estoque INT NOT NULL DEFAULT 0,
    status_estoque ENUM('Em estoque', 'Estoque baixo', 'Esgotado') DEFAULT 'Em estoque',
    estrelas INT NULL CHECK (estrelas BETWEEN 0 AND 5),
    eh_pacote_buffet BOOLEAN DEFAULT FALSE 
) ENGINE=InnoDB;

INSERT INTO produtos 
(img_produto, nome_produto, descricao, categoria, peso_tamanho, preco, estoque, status_estoque, estrelas)
VALUES 
('whitepeanut.jpg', 'Donut ChocoPeanut Branco', 'Cobertura de chocolate branco com riscos de chocolate ao leite e pedaços de amendoim torrado.', 'donuts', '85g / Médio', 12.50, 25, 'Em Estoque', 4.8),
('wconfe.png', 'Donut Confeito Divertido', 'Cobertura de chocolate branco decorada com confeitos coloridos.', 'donuts', '75g / Médio', 10.00, 40, 'Em Estoque', 4.7),
('socreamy.jpg', 'Donut Creamy Berry', 'Cobertura de mirtilo/amora com calda cremosa e açúcar de confeiteiro salpicado.', 'donuts', '90g / Médio', 13.00, 15, 'Em Estoque', 4.9),
('Reve.jpg', 'Donut Red Velvet Crunch', 'Cobertura vermelha especial estilo Red Velvet com riscos brancos e esferas crocantes.', 'donuts', '85g / Médio', 14.00, 10, 'Em Estoque', 5.0),
('purwhite.png', 'Donut Purple Glaze', 'Glacê roxo vibrante finalizado com linhas delicadas de chocolate branco.', 'donuts', '70g / Médio', 11.00, 30, 'Em Estoque', 4.6),
('purpleconfetti.jpg', 'Donut Lavender Sprinkles', 'Cobertura clara tom lavanda com granulados crocantes de amoreira/frutas vermelhas.', 'donuts', '80g / Médio', 11.50, 20, 'Em Estoque', 4.5),
('ppinkconfe.jpg', 'Donut Pink Rainbow', 'Cobertura rosa clássica com granulados coloridos estilo arco-íris.', 'donuts', '80g / Médio', 10.50, 50, 'Em Estoque', 4.8),
('pinkcofe.jpg', 'Donut Pink Sugar Sprinkles', 'Cobertura rosa suave com granulados brancos e rosa em bastão.', 'donuts', '75g / Médio', 10.50, 35, 'Em Estoque', 4.7),
('peanuts.jpg', 'Donut Caramelo & Amendoim', 'Cobertura de chocolate branco, calda de caramelo e amendoim crocante.', 'donuts', '85g / Médio', 12.50, 18, 'Em Estoque', 4.9),
('oreod.jpg', 'Donut Oreo & Cream', 'Cobertura cremosa de chocolate com pedaços e biscoito Oreo inteiro.', 'donuts', '95g / Grande', 15.00, 12, 'Em Estoque', 5.0),
('mermaidd.jpg', 'Donut Mermaid Dust', 'Cobertura azul-turquesa cintilante com acabamento em brilho comestível estilo sereia.', 'donuts', '80g / Médio', 12.00, 22, 'Em Estoque', 4.8),
('velvet.jpg', 'Cupcake Red Velvet Heart', 'Massa red velvet com cobertura cremosa de cream cheese e confeitos de corações vermelhos.', 'cupcakes', '90g / Médio', 14.00, 25, 'Em Estoque', 4.9),
('raspberry.jpg', 'Cupcake Fresh Raspberry', 'Massa leve com cobertura de buttercream de framboesa e framboesas frescas no topo.', 'cupcakes', '95g / Médio', 15.50, 18, 'Em Estoque', 4.8),
('PINKCHERRY.jpg', 'Cupcake Pink Cherry Bliss', 'Massa fofinha com cobertura espiral rosa pastel, pérolas comestíveis e uma cereja no topo.', 'cupcakes', '85g / Médio', 13.50, 30, 'Em Estoque', 4.7),
('cherryred.jpg', 'Cupcake Triple Cherry Red', 'Massa red velvet com cobertura suave de chantilly e triplo topo de cerejas com calda.', 'cupcakes', '100g / Médio', 16.00, 15, 'Em Estoque', 5.0),
('oreocup.jpg', 'Cupcake Oreo Cookies & Cream', 'Massa de chocolate amargo com cobertura de buttercream de Oreo e um biscoito Oreo inteiro.', 'cupcakes', '95g / Médio', 14.50, 22, 'Em Estoque', 4.9),
('incup.jpg', 'Cupcake Mint Choco Cherry', 'Massa de chocolate com cobertura refrescante de menta, pedaços de chocolate e cereja.', 'cupcakes', '90g / Médio', 13.00, 20, 'Em Estoque', 4.6),
('classipink.jpg', 'Cupcake Pink Pearl Elegance', 'Massa de baunilha com cobertura aveludada rosa pastel e pérolas com esferas douradas.', 'cupcakes', '85g / Médio', 12.50, 28, 'Em Estoque', 4.8),
('canelacup.jpg', 'Cupcake Cinnamon Spice', 'Massa aromatizada com canela, cobertura cremosa salpicada de cacau e pau de canela decorativo.', 'cupcakes', '85g / Médio', 12.00, 24, 'Em Estoque', 4.7),
('cafe.jpg', 'Cupcake Cappuccino Crunch', 'Massa de café com chantilly mesclado, calda de chocolate e granulados crocantes.', 'cupcakes', '85g / Médio', 13.50, 26, 'Em Estoque', 4.8),
('bluee.jpg', 'Cupcake Ocean Blue Sky', 'Massa suave com cobertura espiral azul-celeste e microesferas prateadas comestíveis.', 'cupcakes', '85g / Médio', 12.50, 30, 'Em Estoque', 4.7),
('azull.png.jpg', 'Pirulito Psicodélico Azul e Branco', 'Pirulito artesanal em espiral com sabor refrescante de mirtilo e blueberry.', 'Pirulitos', '80g / Grande', 8.50, 40, 'Em Estoque', 4.8),
('bi.png.jpg', 'Pirulito Espiral Pop Bi-Color', 'Pirulito colorido com espirais em tons de rosa, azul e roxo no sabor algodão doce.', 'Pirulitos', '80g / Grande', 8.50, 35, 'Em Estoque', 4.9),
('blue&re.png.jpg', 'Pirulito Swirl Vermelho e Azul', 'Pirulito psicodélico clássico com espirais vermelhas, azuis e brancas no sabor tutti-frutti.', 'Pirulitos', '80g / Grande', 8.50, 50, 'Em Estoque', 4.7),
('r&r.png.jpg', 'Pirulito Sweet Pink Swirl', 'Pirulito espiral delicado em tons de rosa e branco com sabor de morango silvestre.', 'Pirulitos', '80g / Grande', 8.00, 45, 'Em Estoque', 4.8),
('rainbowloli.png.jpg', 'Pirulito Arco-Íris Clássico', 'Pirulito espiral multicolorido e vibrante no delicioso sabor de frutas sortidas.', 'Pirulitos', '80g / Grande', 9.00, 60, 'Em Estoque', 5.0),
('redd.png.jpg', 'Pirulito Red Swirl com Laço', 'Pirulito espiral vermelho e branco com laço rosa decorativo no palito e sabor de cereja.', 'Pirulitos', '85g / Grande', 9.50, 30, 'Em Estoque', 4.9),
('simplesazul.png.png', 'Pirulito Esférico Blueberry', 'Pirulito esférico cristalino azul-turquesa com sabor intenso de framboesa azul.', 'Pirulitos', '20g / Pequeno', 3.00, 100, 'Em Estoque', 4.6),
('simplescere.png.png', 'Pirulito Esférico Cereja', 'Pirulito esférico translúcido na cor magenta brilhante com sabor de cereja intensa.', 'Pirulitos', '20g / Pequeno', 3.00, 100, 'Em Estoque', 4.7),
('simplessere.png.png', 'Pirulito Esférico Pêssego & Laranja', 'Pirulito esférico em tom gradiente degradê amarelo e rosa com sabor suave de pêssego.', 'Pirulitos', '20g / Pequeno', 3.00, 90, 'Em Estoque', 4.8),
('bmorango.jpg', 'Fatia Bolo Strawberry Shortcake', 'Pão de ló fofinho intercalado com creme aveludado e morangos frescos no recheio e no topo.', 'bolos', '150g / Fatia', 18.00, 15, 'Em Estoque', 4.9),
('chocolate.jpg', 'Fatia Bolo Supreme Chocolate', 'Bolo de chocolate intenso com recheio cremoso e cobertura decorada com raspas de chocolate nobre.', 'bolos', '160g / Fatia', 19.50, 12, 'Em Estoque', 4.8),
('floresta_negra.jpg', 'Fatia Bolo Floresta Negra com Framboesa', 'Massa escura de cacau recheada com mousse de framboesa, pedaços de fruta e cobertura rosa pastel.', 'bolos', '155g / Fatia', 21.00, 10, 'Em Estoque', 4.9),
('kinder.jpg', 'Fatia Bolo Duyoo Chocolate Trufado', 'Camadas de bolo de chocolate com recheio duplo de creme de leite e ganache cremoso salpicado com granulado.', 'bolos', '165g / Fatia', 22.00, 8, 'Em Estoque', 5.0),
('redvelvet.jpg', 'Fatia Bolo Red Velvet Classic', 'Massa aveludada red velvet intercalada com recheio tradicional de cream cheese e migalhas decorativas.', 'bolos', '150g / Fatia', 20.00, 14, 'Em Estoque', 4.9),
('pistache.jpg', 'Fatia Bolo Pistache com Morango', 'Bolo macio de pistache na cor verde com recheio de creme claro, geleia de morango e topo com morango e hortelã.', 'bolos', '155g / Fatia', 23.50, 10, 'Em Estoque', 4.8),
('cookiechoco.jpg', 'Cookie Double Chocolate Chips', 'Cookie macio e crocante de massa de cacau intensa, recheado e coberto com gotas de chocolate.', 'cookies', '80g / Unidade', 12.00, 25, 'Em Estoque', 4.9),
('cookienorm.jpg', 'Cookie Classic Chocolate Chips', 'Cookie artesanal dourado e amanteigado, repleto de gotas de chocolate meio amargo derretidas.', 'cookies', '80g / Unidade', 11.00, 30, 'Em Estoque', 4.8),
('cookiepeda.jpg', 'Cookie Brownie Chunks', 'Cookie estilo brownie com textura fofinha por dentro e topo coberto por pedaços generosos de chocolate nobre.', 'cookies', '85g / Unidade', 13.50, 20, 'Em Estoque', 5.0),
('brownienorm.jpg', 'Brownie Tradicional Fudge', 'Brownie clássico de chocolate com casquinha crocante por fora e interior denso, húmido e aveludado.', 'brownies', '70g / Unidade', 9.00, 25, 'Em Estoque', 4.9),
('oreobrown.jpg', 'Brownie recheado com Oreo', 'Massa de brownie super macia e densa com camada generosa de recheio de biscoito Oreo e topo decorado.', 'brownies', '85g / Unidade', 12.50, 18, 'Em Estoque', 5.0),
('gotasbrown.jpg', 'Brownie com Gotas de Chocolate', 'Brownie intenso de cacau com gotas crocantes de chocolate meio amargo espalhadas na massa e no topo.', 'brow')