<?php
require_once "db.php";
$whatsapp_dono = "5531999999999"; // Substitua pelo seu WhatsApp real com o DDD
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mira Confeitaria | Catálogo Premium</title>
    <link rel="shortcut icon" href="imagens/favicon1.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Menu Superior -->
    <nav class="navbar">
        <div class="logo-container">
            <div class="mini-badge-logo">MI<br>ЯA</div>
            <div class="logo-mira">Mira Confeitaria</div>
        </div>
        <div class="nav-links"><a href="login.php">Painel Interno</a></div>
    </nav>

    <!-- Banner Principal -->
    <section class="hero-vitrine">
        <div class="hero-textos">
            <h1>Menu de Delícias</h1>
            <p>Adicione suas opções favoritas à sacola de pedidos. Ao finalizar, você enviará a lista completa com seus dados de entrega direto para o nosso WhatsApp!</p>
        </div>
    </section>

    <!-- Sessão de Vitrine de Vendas -->
    <section class="secao-produtos">
        
        <div class="container-filtros-premium">
            <button class="btn-filtro active" data-categoria="todos">Todos</button>
            <button class="btn-filtro" data-categoria="festivos">Festivos</button>
            <button class="btn-filtro" data-categoria="vitrine">Vitrine</button>
            <button class="btn-filtro" data-categoria="gourmet">Gourmet</button>
            <button class="btn-filtro" data-categoria="individual">Individual</button>
        </div>

        <div class="grid-vitrine">
            <!-- PRODUTO 1 -->
            <div class="card-produto">
                <div class="container-foto-produto">
                    <span class="tag-categoria">Festivos</span>
                    <img src="imagens/bolo_morango.jpg" alt="Bolo Supremo de Morango" class="foto-real-produto">
                </div>
                <div class="detalhes-produto">
                    <h3>Bolo Supremo de Morango</h3>
                    <p>Massa chiffon de baunilha, recheio triplo de ninho cremoso com geleia artesanal de morangos frescos.</p>
                    <div class="rodape-preco-venda">
                        <span class="preco-valor">R$ 120,00</span>
                        <button type="button" class="btn-comprar-vitrine btn-add-sacola" data-nome="Bolo Supremo de Morango" data-preco="120.00">Adicionar à Sacola</button>
                    </div>
                </div>
            </div>

            <!-- PRODUTO 2 -->
            <div class="card-produto">
                <div class="container-foto-produto">
                    <span class="tag-categoria">Vitrine</span>
                    <img src="imagens/torta_quatro_leites.jpg" alt="Torta Quatro Leites" class="foto-real-produto">
                </div>
                <div class="detalhes-produto">
                    <h3>Torta Quatro Leites</h3>
                    <p>Base crocante de biscoito amanteigado, camadas densas de mousse de quatro leites finos e raspas de chocolate.</p>
                    <div class="rodape-preco-venda">
                        <span class="preco-valor">R$ 85,00</span>
                        <button type="button" class="btn-comprar-vitrine btn-add-sacola" data-nome="Torta Quatro Leites" data-preco="85.00">Adicionar à Sacola</button>
                    </div>
                </div>
            </div>

            <!-- PRODUTO 3 -->
            <div class="card-produto">
                <div class="container-foto-produto">
                    <span class="tag-categoria">Gourmet</span>
                    <img src="imagens/brigadeiros_belgas.jpg" alt="Cento de Brigadeiros Belgas" class="foto-real-produto">
                </div>
                <div class="detalhes-produto">
                    <h3>Cento de Brigadeiros Belgas</h3>
                    <p>Brigadeiros gourmet enrolados no autêntico split de chocolate belga Callebaut. Derrete na boca.</p>
                    <div class="rodape-preco-venda">
                        <span class="preco-valor">R$ 160,00</span>
                        <button type="button" class="btn-comprar-vitrine btn-add-sacola" data-nome="Cento de Brigadeiros Belgas" data-preco="160.00">Adicionar à Sacola</button>
                    </div>
                </div>
            </div>

            <!-- PRODUTO 4 -->
            <div class="card-produto">
                <div class="container-foto-produto">
                    <span class="tag-categoria">Festivos</span>
                    <img src="imagens/bolo_chocolate.jpg" alt="Bolo Belga de Brigadeiro" class="foto-real-produto">
                </div>
                <div class="detalhes-produto">
                    <h3>Bolo Belga de Brigadeiro</h3>
                    <p>Massa intensa de cacau 100%, recheada com brigadeiro gourmet tradicional e cobertura escorrendo.</p>
                    <div class="rodape-preco-venda">
                        <span class="preco-valor">R$ 110,00</span>
                        <button type="button" class="btn-comprar-vitrine btn-add-sacola" data-nome="Bolo Belga de Brigadeiro" data-preco="110.00">Adicionar à Sacola</button>
                    </div>
                </div>
            </div>

            <!-- PRODUTO 5 -->
            <div class="card-produto">
                <div class="container-foto-produto">
                    <span class="tag-categoria">Individual</span>
                    <img src="imagens/tartalete_frutas.jpg" alt="Tartalete de Frutas" class="foto-real-produto">
                </div>
                <div class="detalhes-produto">
                    <h3>Tartalete de Frutas</h3>
                    <p>Massa sucrée crocante, creme de confeiteiro suave com baunilha natural e seleção de frutas vermelhas.</p>
                    <div class="rodape-preco-venda">
                        <span class="preco-valor">R$ 16,00</span>
                        <button type="button" class="btn-comprar-vitrine btn-add-sacola" data-nome="Tartalete de Frutas" data-preco="16.00">Adicionar à Sacola</button>
                    </div>
                </div>
            </div>

            <!-- PRODUTO 6 -->
            <div class="card-produto">
                <div class="container-foto-produto">
                    <span class="tag-categoria">Gourmet</span>
                    <img src="imagens/caixa_macarons.jpg" alt="Caixa Macarons Premium" class="foto-real-produto">
                </div>
                <div class="detalhes-produto">
                    <h3>Caixa Macarons Premium</h3>
                    <p>Caixa com 6 unidades do clássico doce francês. Sabores sortidos e refinados.</p>
                    <div class="rodape-preco-venda">
                        <span class="preco-valor">R$ 48,00</span>
                        <button type="button" class="btn-comprar-vitrine btn-add-sacola" data-nome="Caixa Macarons Premium" data-preco="48.00">Adicionar à Sacola</button>
                    </div>
                </div>
            </div>
            <!-- PRODUTO 7 -->
            <div class="card-produto">
                <div class="container-foto-produto">
                    <span class="tag-categoria">Festivos</span>
                    <img src="imagens/bolo_red_velvet.jpg" alt="Bolo Red Velvet Premium" class="foto-real-produto">
                </div>
                <div class="detalhes-produto">
                    <h3>Bolo Red Velvet Premium</h3>
                    <p>Massa aveludada de cor avermelhada sutil, recheada com camadas generosas de autêntico frosting de cream cheese.</p>
                    <div class="rodape-preco-venda">
                        <span class="preco-valor">R$ 135,00</span>
                        <button type="button" class="btn-comprar-vitrine btn-add-sacola" data-nome="Bolo Red Velvet Premium" data-preco="135.00">Adicionar à Sacola</button>
                    </div>
                </div>
            </div>

            <!-- PRODUTO 8 -->
            <div class="card-produto">
                <div class="container-foto-produto">
                    <span class="tag-categoria">Vitrine</span>
                    <img src="imagens/torta_limao.jpg" alt="Torta de Limão Siciliano" class="foto-real-produto">
                </div>
                <div class="detalhes-produto">
                    <h3>Torta de Limão Siciliano</h3>
                    <p>Creme aveludado de limão siciliano sobre massa sablé crocante, finalizada com merengue suíço maçaricado.</p>
                    <div class="rodape-preco-venda">
                        <span class="preco-valor">R$ 75,00</span>
                        <button type="button" class="btn-comprar-vitrine btn-add-sacola" data-nome="Torta de Limão Siciliano" data-preco="75.00">Adicionar à Sacola</button>
                    </div>
                </div>
            </div>

            <!-- PRODUTO 9 -->
            <div class="card-produto">
                <div class="container-foto-produto">
                    <span class="tag-categoria">Gourmet</span>
                    <img src="imagens/coxinha_morango.jpg" alt="Coxinha de Brigadeiro com Morango" class="foto-real-produto">
                </div>
                <div class="detalhes-produto">
                    <h3>Coxinha de Morango (Unidade)</h3>
                    <p>Morango fresco inteiro selecionado, envolto em uma camada espessa de brigadeiro tradicional cremoso.</p>
                    <div class="rodape-preco-venda">
                        <span class="preco-valor">R$ 12,00</span>
                        <button type="button" class="btn-comprar-vitrine btn-add-sacola" data-nome="Coxinha de Morango" data-preco="12.00">Adicionar à Sacola</button>
                    </div>
                </div>
            </div>

            <!-- PRODUTO 10 -->
            <div class="card-produto">
                <div class="container-foto-produto">
                    <span class="tag-categoria">Individual</span>
                    <img src="imagens/slice_cake_ninho.jpg" alt="Fatia Slice Cake Ninho" class="foto-real-produto">
                </div>
                <div class="detalhes-produto">
                    <h3>Slice Cake Ninho com Nutella</h3>
                    <p>Fatia de bolo estruturada em embalagem individual. Massa de chocolate com recheio de leite Ninho e Nutella pura.</p>
                    <div class="rodape-preco-venda">
                        <span class="preco-valor">R$ 18,00</span>
                        <button type="button" class="btn-comprar-vitrine btn-add-sacola" data-nome="Slice Cake Ninho com Nutella" data-preco="18.00">Adicionar à Sacola</button>
                    </div>
                </div>
            </div>

            <!-- PRODUTO 11 -->
            <div class="card-produto">
                <div class="container-foto-produto">
                    <span class="tag-categoria">Vitrine</span>
                    <img src="imagens/banoffee_premium.jpg" alt="Banoffee Premium" class="foto-real-produto">
                </div>
                <div class="detalhes-produto">
                    <h3>Banoffee Premium</h3>
                    <p>Camadas de biscoito triturado, doce de leite cremoso artesanal, bananas frescas fatiadas e chantilly.</p>
                    <div class="rodape-preco-venda">
                        <span class="preco-valor">R$ 80,00</span>
                        <button type="button" class="btn-comprar-vitrine btn-add-sacola" data-nome="Banoffee Premium" data-preco="80.00">Adicionar à Sacola</button>
                    </div>
                </div>
            </div>

            <!-- PRODUTO 12 -->
            <div class="card-produto">
                <div class="container-foto-produto">
                    <span class="tag-categoria">Individual</span>
                    <img src="imagens/pudim_lisinho.jpg" alt="Pudim de Leite Condensado" class="foto-real-produto">
                </div>
                <div class="detalhes-produto">
                    <h3>Pudim de Leite Condensado</h3>
                    <p>O clássico pudim individual extremamente cremoso, lisinho e sem furinhos, banhado em calda de caramelo.</p>
                    <div class="rodape-preco-venda">
                        <span class="preco-valor">R$ 10,00</span>
                        <button type="button" class="btn-comprar-vitrine btn-add-sacola" data-nome="Pudim de Leite Condensado" data-preco="10.00">Adicionar à Sacola</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sacola Flutuante Abaixo -->
    <div class="sacola-flutuante" id="abrir-carrinho">
        <span>🛍️ Minha Sacola</span>
        <span class="contador-sacola" id="cont-itens">0</span>
    </div>
    <!-- Alerta Flutuante Premium -->
    <div id="toast-alerta" class="toast-notificacao">🍰 Doce adicionado à sacola!</div>

    <!-- Painel Lateral do Carrinho -->
    <div class="painel-carrinho" id="menu-lateral-carrinho">
        <div class="topo-carrinho">
            <h3>Seu Pedido</h3>
            <button class="btn-fechar-carrinho" id="fechar-carrinho">&times;</button>
        </div>
        
        <div style="flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; padding-right: 5px;">
            <div class="lista-itens-carrinho" id="itens-carrinho-lista"></div>

            <!-- Inclusão do Campo de Cupom Fino -->
            <div style="border-top: 1px solid #F1F5F9; padding-top: 15px;">
                <label style="font-size: 10px; font-weight: 700; color: var(--texto-mutado); uppercase; letter-spacing: 0.5px;">POSSUI UM CUPOM?</label>
                <div class="container-cupom-desconto">
                    <input type="text" id="input-cupom-texto" class="input-cupom-mira" placeholder="Ex: BOASVINDAS">
                    <button type="button" id="btn-validar-cupom" class="btn-aplicar-cupom">Aplicar</button>
                </div>
                <p id="msg-cupom-status" style="font-size: 12px; font-weight: 600; margin-top: -5px; display: none;"></p>
            </div>

            <div style="border-top: 1px solid #F1F5F9; padding-top: 15px; display: flex; flex-direction: column; gap: 12px;">
                <h4 style="font-size: 13px; font-weight: 700; color: var(--verde-mira);">Dados de Entrega</h4>
                
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 10px; font-weight: 700; color: var(--texto-mutado);">NOME COMPLETO</label>
                    <input type="text" id="cli-nome" placeholder="Digite seu nome" style="width: 100%; padding: 10px 14px; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 13px; outline:none;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 10px; font-weight: 700; color: var(--texto-mutado);">ENDEREÇO RESIDENCIAL</label>
                    <input type="text" id="cli-endereco" placeholder="Rua, número e bairro" style="width: 100%; padding: 10px 14px; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 13px; outline:none;">
                </div>
            </div>
        </div>

        <div class="rodape-carrinho">
            <div id="bloco-desconto-visual" style="display: flex; justify-content: space-between; font-size: 14px; color: #10B981; margin-bottom: 8px; display: none;">
                <span>Desconto (10%):</span>
                <span id="valor-desconto-html">- R$ 0,00</span>
            </div>
            <div class="total-container">
                <span>Total:</span>
                <span id="valor-total-carrinho">R$ 0,00</span>
            </div>
            <button class="btn-finalizar-carrinho" id="finalizar-pedido-whats" data-whats="<?php echo $whatsapp_dono; ?>">Enviar Pedido via WhatsApp</button>
        </div>
    </div>

    <!-- Modal de Produto -->
    <div class="modal-overlay" id="modal-produto-overlay" aria-hidden="true">
        <div class="modal-produto">
            <button class="modal-close" id="fechar-modal-produto" aria-label="Fechar janela">&times;</button>
            <div class="modal-produto-imagem">
                <img id="modal-produto-img" src="" alt="Imagem do produto">
            </div>
            <div class="modal-produto-conteudo">
                <span class="modal-produto-categoria" id="modal-produto-categoria"></span>
                <h3 id="modal-produto-titulo"></h3>
                <p id="modal-produto-descricao"></p>
                <div class="modal-produto-rodape">
                    <span class="modal-produto-preco" id="modal-produto-preco"></span>
                    <button type="button" class="btn-comprar-vitrine btn-add-sacola btn-modal-add" id="btn-modal-add" data-nome="" data-preco="">Adicionar à Sacola</button>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>

