<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=10.0">
    <title>Mira Confeitaria - Cardápio</title>
    <!-- Link para o arquivo de estilização externa CSS -->
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="imagens/favicon.jpg" type="image/x-icon">
    
    <!-- Biblioteca Phosphor Icons para os ícones finos e minimalistas -->
    <link rel="stylesheet" href="https://unpkg.com/phosphor-icons@1.4.1/dist/phosphor.css">
    
    <!-- Importação das fontes premium Cinzel e Montserrat diretamente do Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com/css2?family=Cinzel:wght@400;600&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

    <!-- ==========================================
       BARRA SUPERIOR FIXA (HEADER)
       ========================================== -->
    <header class="main-header">
        <div class="logo">
            <div class="logo-symbol">
                <span>MI</span>
                <span>ЯA</span>
            </div>
            <div class="logo-text">
                <h1>MIRA</h1>
                <p>confeitaria</p>
            </div>
        </div>
        
        <nav class="nav-menu">
            <a href="#" class="active" id="menu-cardapio" onclick="mostrarCardapio()">CARDÁPIO</a>
            <a href="#" id="menu-sobre" onclick="abrirSobreNos()">SOBRE NÓS</a>
        </nav>
        
        <div class="header-actions">
            <a href="#" class="btn-whatsapp" onclick="enviarPedidoWhatsApp()">
                <i class="ph ph-whatsapp-logo"></i> ENVIAR PEDIDO
            </a>
            
            <div class="cart-icon" onclick="toggleCarrinho()">
                <i class="ph ph-shopping-bag"></i>
                <span class="cart-count" id="cart-count-badge">0</span>
            </div>
        </div>
    </header>

    <!-- ==========================================
       PÁGINA DO SOBRE NÓS (FICA OCULTA POR PADRÃO)
       ========================================== -->
    <section id="section-sobre-nos" class="sobre-nos-section section-fade" style="display: none;">
        <div class="sobre-container">
            <h2>Nossa História</h2>
            <div class="sobre-linha"></div>
            <p>A <strong>Mira Confeitaria</strong> nasceu do sonho de transformar receitas tradicionais de família em experiências únicas e inesquecíveis. Cada ingrediente que entra em nossa cozinha é selecionado rigorosamente, priorizando produtores locais e a máxima qualidade.</p>
            <p>Para nós, confeitar não é apenas misturar farinha e açúcar; é esculpir afeto, celebrar momentos especiais e criar memórias doces que permanecem no coração de quem amamos. Seja muito bem-vindo ao nosso cardápio artesanal!</p>
            <button class="btn-voltar-cardapio" onclick="mostrarCardapio()">🎯 VER O CARDÁPIO AGORA</button>
        </div>
    </section>

    <!-- CONTAINER QUE ENGLOBA TODO O CARDÁPIO COMPLETO -->
    <div id="area-cardapio-completo">

        <!-- BANNER PRINCIPAL (HERO) -->
        <section class="hero-section text-animation">
            <div class="hero-content">
                <p class="subtitle">CARDÁPIO</p>
                <h2>Doces feitos para transformar momentos em memórias.</h2>
                <p class="description">Confeitaria artesanal feita com ingredientes selecionados e muito amor em cada detalhe.</p>
                <div class="hero-leaf" style="margin-top: 30px;">
                    <div style="width: 100px; height: 1px; background-color: #BC8A5F; opacity: 0.6;"></div>
                </div>
            </div>
        </section>
        <!-- ==========================================
           SEÇÃO DE CATEGORIAS (CÁPSULAS FUNCIONAIS)
           ========================================== -->
        <section class="categories-container">
            <div class="categories-section">
                <div class="category-item active" onclick="filtrarPorCategoria('todos', this)">
                    <div class="cat-icon"><i class="ph ph-squares-four"></i></div>
                    <span>TODOS</span>
                </div>
                <div class="category-item" onclick="filtrarPorCategoria('bolos', this)">
                    <div class="cat-icon"><i class="ph ph-cake"></i></div>
                    <span>BOLOS</span>
                </div>
                <div class="category-item" onclick="filtrarPorCategoria('doces', this)">
                    <div class="cat-icon"><i class="ph ph-cookie"></i></div>
                    <span>DOCES</span>
                </div>
                <div class="category-item" onclick="filtrarPorCategoria('casamentos', this)">
                    <div class="cat-icon"><i class="ph ph-sketch-logo"></i></div>
                    <span>CASAMENTOS</span>
                </div>
                <div class="category-item" onclick="filtrarPorCategoria('festas', this)">
                    <div class="cat-icon"><i class="ph ph-balloon"></i></div>
                    <span>FESTAS</span>
                </div>
                <div class="category-item" onclick="filtrarPorCategoria('sobremesas', this)">
                    <div class="cat-icon"><i class="ph ph-ice-cream"></i></div>
                    <span>SOBREMESAS</span>
                </div>
            </div>
        </section>

        <!-- ==========================================
           BARRA DE PESQUISA E FILTROS INTEGRADOS
           ========================================== -->
        <div class="filter-bar">
            <div class="search-wrapper">
                <i class="ph ph-magnifying-glass search-icon"></i>
                <input type="text" id="search-input" placeholder="Buscar doce ou bolo especial..." oninput="buscarProdutos()">
            </div>

            <div class="sort-wrapper">
                <label>ORDENAR POR:</label>
                <select id="sort-select" onchange="ordenarProdutos()">
                    <option value="populares">Mais populares</option>
                    <option value="menor-preco">Menor preço</option>
                    <option value="maior-preco">Maior preço</option>
                </select>
            </div>
        </div>

        <!-- ==========================================
           GRADE DE PRODUTOS PARTE A (PRODUTOS 1 AO 6)
           ========================================== -->
        <main class="products-grid" id="main-products-grid">
            
            <!-- Produto 1 -->
            <div class="product-card card-animation" data-category="bolos" data-price="110.00" onclick="abrirModal('Bolo de Chocolate', 'imagens/bolo_chocolate.jpg', 'Massa fofinha de cacau com recheio de brigadeiro gourmet.', 110.00)">
                <div class="product-img-wrapper"><img src="imagens/bolo_chocolate.jpg" alt="Bolo de Chocolate"></div>
                <h3>Bolo de Chocolate</h3>
                <p class="product-desc">Massa fofinha de cacau com recheio de brigadeiro gourmet.</p>
                <div class="product-footer"><span class="price">A partir de R$ 110,00</span><button class="btn-ver-opcoes">Ver Opções</button></div>
            </div>

            <!-- Produto 2 -->
            <div class="product-card card-animation" data-category="bolos" data-price="125.00" onclick="abrirModal('Bolo de Morango', 'imagens/bolo_morango.jpg', 'Pó de ló leve com creme belga e morangos frescos.', 125.00)">
                <div class="product-img-wrapper"><img src="imagens/bolo_morango.jpg" alt="Bolo de Morango"></div>
                <h3>Bolo de Morango</h3>
                <p class="product-desc">Pó de ló leve com creme belga e morangos frescos.</p>
                <div class="product-footer"><span class="price">A partir de R$ 125,00</span><button class="btn-ver-opcoes">Ver Opções</button></div>
            </div>

            <!-- Produto 3 -->
            <div class="product-card card-animation" data-category="sobremesas" data-price="95.00" onclick="abrirModal('Torta de Limão', 'imagens/torta_limao.jpg', 'Massa crocante com creme de limão e merengue tostado.', 95.00)">
                <div class="product-img-wrapper"><img src="imagens/torta_limao.jpg" alt="Torta de Limão"></div>
                <h3>Torta de Limão</h3>
                <p class="product-desc">Massa crocante com creme de limão e merengue tostado.</p>
                <div class="product-footer"><span class="price">A partir de R$ 95,00</span><button class="btn-ver-opcoes">Ver Opções</button></div>
            </div>

            <!-- Produto 4 -->
            <div class="product-card card-animation" data-category="sobremesas" data-price="130.00" onclick="abrirModal('Torta Quatro Leites', 'imagens/torta_quatro_leites.jpg', 'Bolo molhadinho com calda e raspas de chocolate branco.', 130.00)">
                <div class="product-img-wrapper"><img src="imagens/torta_quatro_leites.jpg" alt="Torta Quatro Leites"></div>
                <h3>Torta Quatro Leites</h3>
                <p class="product-desc">Bolo molhadinho com calda e raspas de chocolate branco.</p>
                <div class="product-footer"><span class="price">A partir de R$ 130,00</span><button class="btn-ver-opcoes">Ver Opções</button></div>
            </div>

            <!-- Produto 5 -->
            <div class="product-card card-animation" data-category="sobremesas" data-price="85.00" onclick="abrirModal('Banoffee Premium', 'imagens/banoffee_premium.jpg', 'Base de biscoito crocante, doce de leite artesanal, bananas frescas and chantilly polvilhado com cacau.', 85.00)">
                <div class="product-img-wrapper"><img src="imagens/banoffee_premium.jpg" alt="Banoffee Premium"></div>
                <h3>Banoffee Premium</h3>
                <p class="product-desc">Base crocante, doce de leite artesanal, bananas frescas e chantilly leve.</p>
                <div class="product-footer"><span class="price">A partir de R$ 85,00</span><button class="btn-ver-opcoes">Ver Opções</button></div>
            </div>

            <!-- Produto 6 -->
            <div class="product-card card-animation" data-category="bolos" data-price="135.00" onclick="abrirModal('Bolo Red Velvet', 'imagens/bolo_red_velvet.jpg', 'Massa aveludada de cacau com recheio cremoso à base de cream cheese e toque de baunilha.', 135.00)">
                <div class="product-img-wrapper"><img src="imagens/bolo_red_velvet.jpg" alt="Bolo Red Velvet"></div>
                <h3>Bolo Red Velvet</h3>
                <p class="product-desc">Massa aveludada vermelha com recheio cremoso tradicional de cream cheese.</p>
                <div class="product-footer"><span class="price">A partir de R$ 135,00</span><button class="btn-ver-opcoes">Ver Opções</button></div>
            </div>
            <!-- Produto 7 -->
            <div class="product-card card-animation" data-category="doces" data-price="45.00" onclick="abrirModal('Brigadeiros Belgas', 'imagens/brigadeiros_belgas.jpg', 'Caixa com brigadeiros gourmet feitos com o autêntico chocolate belga ao leite.', 45.00)">
                <div class="product-img-wrapper"><img src="imagens/brigadeiros_belgas.jpg" alt="Brigadeiros Belgas"></div>
                <h3>Brigadeiros Belgas</h3>
                <p class="product-desc">Tradicionais brigadeiros gourmet enrolados no puro granulado belga.</p>
                <div class="product-footer"><span class="price">A partir de R$ 45,00</span><button class="btn-ver-opcoes">Ver Opções</button></div>
            </div>

            <!-- Produto 8 -->
            <div class="product-card card-animation" data-category="doces" data-price="65.00" onclick="abrirModal('Caixa de Macarons', 'imagens/caixa_macarons.jpg', 'Delicados macarons franceses sortidos nos sabores pistache, baunilha, framboesa e chocolate.', 65.00)">
                <div class="product-img-wrapper"><img src="imagens/caixa_macarons.jpg" alt="Caixa de Macarons"></div>
                <h3>Caixa de Macarons</h3>
                <p class="product-desc">Caixa com clássicos macarons franceses crocantes por fora e macios por dentro.</p>
                <div class="product-footer"><span class="price">A partir de R$ 65,00</span><button class="btn-ver-opcoes">Ver Opções</button></div>
            </div>

            <!-- Produto 9 -->
            <div class="product-card card-animation" data-category="festas" data-price="12.00" onclick="abrirModal('Coxinha de Morango', 'imagens/coxinha_morango.jpg', 'Morango inteiro e fresco envolvido por uma camada generosa de brigadeiro gourmet tradicional.', 12.00)">
                <div class="product-img-wrapper"><img src="imagens/coxinha_morango.jpg" alt="Coxinha de Morango"></div>
                <h3>Coxinha de Morango</h3>
                <p class="product-desc">Grande morango fresco recheado e coberto com muito brigadeiro artesanal.</p>
                <div class="product-footer"><span class="price">A partir de R$ 12,00</span><button class="btn-ver-opcoes">Ver Opções</button></div>
            </div>

            <!-- Produto 10 -->
            <div class="product-card card-animation" data-category="sobremesas" data-price="55.00" onclick="abrirModal('Pudim Lisinho', 'imagens/pudim_lisinho.jpg', 'Pudim de leite condensado super cremoso, sem furinhos, com calda de caramelo dourada.', 55.00)">
                <div class="product-img-wrapper"><img src="imagens/pudim_lisinho.jpg" alt="Pudim Lisinho"></div>
                <h3>Pudim Lisinho</h3>
                <p class="product-desc">O clássico e perfeito pudim de leite condensado, super cremoso e sem furinhos.</p>
                <div class="product-footer"><span class="price">A partir de R$ 55,00</span><button class="btn-ver-opcoes">Ver Opções</button></div>
            </div>

            <!-- Produto 11 -->
            <div class="product-card card-animation" data-category="festas" data-price="18.00" onclick="abrirModal('Fatia Cake Ninho', 'imagens/slice_cake_ninho.jpg', 'Generosa fatia de bolo com massa fofinha e muito recheio cremoso de Leite Ninho.', 18.00)">
                <div class="product-img-wrapper"><img src="imagens/slice_cake_ninho.jpg" alt="Fatia Cake Ninho"></div>
                <h3>Fatia Cake Ninho</h3>
                <p class="product-desc">Fatia de bolo (slice cake) super recheada com creme trufado de Leite Ninho.</p>
                <div class="product-footer"><span class="price">A partir de R$ 18,00</span><button class="btn-ver-opcoes">Ver Opções</button></div>
            </div>

            <!-- Produto 12 -->
            <div class="product-card card-animation" data-category="casamentos" data-price="15.00" onclick="abrirModal('Tartalete de Frutas', 'imagens/tartalete_frutas.jpg', 'Mini tortinha de massa leve recheada com creme de confeiteiro e decorada com frutas frescas.', 15.00)">
                <div class="product-img-wrapper"><img src="imagens/tartalete_frutas.jpg" alt="Tartalete de Frutas"></div>
                <h3>Tartalete de Frutas</h3>
                <p class="product-desc">Massa sablée crocante com creme belga suave e seleção de frutas frescas por cima.</p>
                <div class="product-footer"><span class="price">A partir de R$ 15,00</span><button class="btn-ver-opcoes">Ver Opções</button></div>
            </div>

        </main>
    </div> <!-- FIM DA DIV #area-cardapio-completo -->
    <!-- ==========================================
       RODAPÉ OFICIAL (VERDE OLIVA ULTRA ESCURO)
       ========================================== -->
    <footer class="main-footer">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="logo-symbol-footer">MIЯA</div>
                <h2>MIRA confeitaria</h2>
                <p>Doces feitos para transformar momentos em memórias.</p>
            </div>
            <div class="footer-info">
                <p><i class="ph ph-instagram-logo"></i> <a href="https://www.instagram.com/miraconfeitaria?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank"> @miraconfeitaria</a></p>
                <p><a href="https://maps.app.goo.gl/3ZZSES1hGavSzgGx9" target="_blank" class="link-localizacao">
                    <i class="ph ph-map-pin"></i> Rua Sulfumiro de Freitas, 68A Progresso<br> Sete Lagoas - MG</a>
                </p>
            </div>
            <div class="footer-action">
                <a href="#" class="btn-whatsapp-footer" onclick="enviarPedidoWhatsApp()"><i class="ph ph-whatsapp-logo"></i> FALAR NO WHATSAPP</a>
            </div>
        </div>
        <div class="footer-copyright">
            <p>&copy; 2026 Mira Confeitaria. Todos os direitos reservados.</p>
        </div>
    </footer>
    <!-- ==========================================
       JANELA FLUTUANTE (MODAL DE DETALHES)
       ========================================== -->
    <div id="product-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="fecharModal()">&times;</span>
            <div class="modal-body">
                <img id="modal-img" src="" alt="Produto">
                <h2 id="modal-title">Nome do Doce</h2>
                <p id="modal-desc">Descrição completa do doce aqui.</p>
                
                <div class="options-group">
                    <label class="section-label">Selecione o Tamanho:</label>
                    <div class="radio-options">
                        <label><input type="radio" name="size" value="Fatia Individual" checked onclick="atualizarPrecoModal(15)"> Fatia Individual (+ R$ 15,00)</label>
                        <label><input type="radio" name="size" value="Bolo Inteiro 1kg" onclick="atualizarPrecoModal(0)"> Bolo Inteiro 1kg (Preço Padrão)</label>
                        <label><input type="radio" name="size" value="Bolo Inteiro 2kg" onclick="atualizarPrecoModal(90)"> Bolo Inteiro 2kg (+ R$ 90,00)</label>
                    </div>
                </div>

                <div class="options-group">
                    <label class="section-label">Observações ou Alterações:</label>
                    <textarea id="modal-obs" placeholder="Ex: Escrever 'Parabéns Rafa', retirar morangos, etc." rows="2" style="width: 100%; border: 1px solid #D1C9BE; border-radius: 8px; padding: 8px; font-size: 12px; resize: none;"></textarea>
                </div>

                <div class="quantity-selector">
                    <label class="section-label">Quantidade:</label>
                    <div class="qty-counter">
                        <button type="button" onclick="alterarQtd(-1)">-</button>
                        <input type="number" id="qty-input" value="1" min="1" readonly>
                        <button type="button" onclick="alterarQtd(1)">+</button>
                    </div>
                </div>

                <div class="modal-price-display">
                    Total do item: <span id="modal-total-price">R$ 0,00</span>
                </div>

                <button id="btn-confirm-add" onclick="adicionarAoCarrinhoDoModal()">ADICIONAR AO PEDIDO</button>
            </div>
        </div>
    </div>
    <!-- ==========================================
       PAINEL LATERAL (CARRINHO DE COMPRAS)
       ========================================== -->
    <div id="sidebar-carrinho" class="sidebar">
        <div class="sidebar-header">
            <h2>Seu Pedido</h2>
            <span class="close-sidebar" onclick="toggleCarrinho()">&times;</span>
        </div>
        
        <div id="carrinho-itens" class="sidebar-body">
            <p class="empty-msg">Nenhum item adicionado ainda.</p>
        </div>

        <div class="sidebar-form" id="checkout-form-container" style="display: none; padding: 15px 20px; border-top: 1px solid #EFEBE4; background: #F5F0E6;">
            <h3 style="font-family: 'Cinzel', serif; font-size: 13px; margin-bottom: 10px;">Dados para Entrega</h3>
            
            <div class="form-field" style="margin-bottom: 8px;">
                <input type="text" id="client-name" placeholder="Seu Nome Completo *" style="width: 100%; padding: 8px 12px; border: 1px solid #D1C9BE; border-radius: 20px; font-size: 12px;">
            </div>

            <div class="form-field" style="margin-bottom: 8px;">
                <select id="delivery-method" onchange="toggleCamposEndereco()" style="width: 100%; padding: 8px 12px; border: 1px solid #D1C9BE; border-radius: 20px; font-size: 12px; background: white;">
                    <option value="entrega">Agendar Entrega em Casa</option>
                    <option value="retirada">Retirar na Confeitaria</option>
                </select>
            </div>

            <div id="address-fields">
                <div class="form-field" style="margin-bottom: 8px;">
                    <input type="text" id="client-address" placeholder="Endereço e Número *" style="width: 100%; padding: 8px 12px; border: 1px solid #D1C9BE; border-radius: 20px; font-size: 12px;">
                </div>
                <div class="form-field">
                    <input type="text" id="client-bairro" placeholder="Bairro *" style="width: 100%; padding: 8px 12px; border: 1px solid #D1C9BE; border-radius: 20px; font-size: 12px;">
                </div>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-total">Total: <span id="sidebar-total-value">R$ 0,00</span></div>
            <button class="btn-finalize" onclick="enviarPedidoWhatsApp()">CONCLUIR E MANDAR NO WHATSAPP</button>
        </div>
    </div>

    <!-- Vincula o arquivo lógico JavaScript de controle dinâmico -->
    <script src="script.js"></script>
</body>
</html>
