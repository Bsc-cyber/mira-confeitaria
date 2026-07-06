<!-- COMPONENTE DA BARRA LATERAL REUTILIZÁVEL COM ROTAS ABSOLUTAS PARA A PASTA /TESTE/ -->
<aside class="menu-lateral">
    
    <!-- Seção Superior: Logotipo e Nome MIRA Confeitaria -->
    <div class="bloco-marca">
        <div class="emblema-logo">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#d4af37" stroke-width="1.5">
                <path d="M12 22C12 22 20 16 20 10C20 4 12 2 12 2C12 2 4 4 4 10C4 16 12 22 12 22Z" />
                <path d="M12 2V22" />
                <path d="M12 7C14 9 17 9 17 9" />
                <path d="M12 12C10 14 7 14 7 14" />
            </svg>
        </div>
        <div class="titulos-logo">
            <h2>MIRA</h2>
            <span>CONFEITARIA</span>
        </div>
    </div>
    
    <!-- Seção Central: Links de Navegação Blindados Contra Erro de Pasta Duplicada -->
    <nav class="links-navegacao">
        
        <!-- 1. Dashboard -->
        <a href="/mira-confeitaria/php/home.php" class="item-menu">
            <span class="marcador-selecionado"></span>
            <svg class="icone-svg" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
            <span class="texto-menu">Dashboard</span>
        </a>
        
        <!-- 2. Clientes -->
        <a href="/mira-confeitaria/php/clientes.php" class="item-menu">
            <span class="marcador-selecionado"></span>
            <svg class="icone-svg" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span class="texto-menu">Clientes</span>
        </a>
        
        <!-- 3. Produtos -->
        <a href="/mira-confeitaria/php/produtos.php" class="item-menu">
            <span class="marcador-selecionado"></span>
            <svg class="icone-svg" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            <span class="texto-menu">Produtos</span>
        </a>
        
        <!-- 4. Pedidos -->
        <a href="/mira-confeitaria/php/pedidos.php" class="item-menu">
            <span class="marcador-selecionado"></span>
            <svg class="icone-svg" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            <span class="texto-menu">Pedidos</span>
        </a>
        
        <!-- 5. Vendas -->
        <a href="/mira-confeitaria/php/vendas.php" class="item-menu">
            <span class="marcador-selecionado"></span>
            <svg class="icone-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <span class="texto-menu">Vendas</span>
        </a>
        
        <!-- 6. Receitas -->
        <a href="/mira-confeitaria/php/receitas.php" class="item-menu">
            <span class="marcador-selecionado"></span>
            <svg class="icone-svg" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            <span class="texto-menu">Receitas</span>
        </a>
        
        <!-- 7. Fornecedores (AGORA INTEGRADO E LINKADO CORRETAMENTE!) -->
        <a href="/mira-confeitaria/php/fornecedores.php" class="item-menu">
            <span class="marcador-selecionado"></span>
            <svg class="icone-svg" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polyline points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            <span class="texto-menu">Fornecedores</span>
        </a>
        
        <!-- 8. Controle Financeiro -->
        <a href="/mira-confeitaria/php/controle_financeiro.php" class="item-menu">
            <span class="marcador-selecionado"></span>
            <svg class="icone-svg" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="12" y1="11" x2="12" y2="13"/><path d="M16 9.5a3.5 3.5 0 0 0-8 0"/></svg>
            <span class="texto-menu">Controle Financeiro</span>
        </a>
        
        <!-- 9. Relatórios -->
        <a href="/mira-confeitaria/php/relatorios.php" class="item-menu">
            <span class="marcador-selecionado"></span>
            <svg class="icone-svg" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            <span class="texto-menu">Relatórios</span>
        </a>
        
        <!-- 10. Configurações -->
        <a href="/mira-confeitaria/php/configuracoes.php" class="item-menu">
            <span class="marcador-selecionado"></span>
            <svg class="icone-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            <span class="texto-menu">Configurações</span>
        </a>
    </nav>

    <!-- Seção Inferior: Perfil do Administrador -->
    <div class="bloco-perfil">
        <div class="foto-perfil">AD</div>
        <div class="identidade-usuario">
            <strong>Admin</strong>
            <span>Administrador ▾</span>
        </div>
    </div>

    <!-- Banner Ocultável do Bolo MIRA -->
    <div class="banner-decorativo">
        <div class="miniatura-bolo"></div>
        <p class="slogan-rodape">Sabores que encantam, experiências que ficam.</p>
    </div>

    <!-- Botão de Desconexão apontando para a raiz segura de /teste/ -->
    <div class="saida-sistema">
        <a href="/teste/login.php" class="link-sair">
            <svg class="icone-svg-sair" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span class="texto-menu">Sair do Painel</span>
        </a>
    </div>

</aside>
