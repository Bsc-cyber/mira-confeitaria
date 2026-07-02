// ==========================================================================
// MIRA CONFEITARIA - SCRIPT DE ACESSIBILIDADE E CONFIGURAÇÕES BÁSICAS
// ==========================================================================

// 1. Seleção de Elementos da Tela
const sidebar = document.getElementById('sidebar');
const viewModeSelector = document.getElementById('viewModeSelector');
const btnFontDiminuir = document.getElementById('btnFontDiminuir');
const btnFontNormal = document.getElementById('btnFontNormal');
const btnFontAumentar = document.getElementById('btnFontAumentar');
const inputUsername = document.getElementById('inputUsername');
const inputNewPassword = document.getElementById('inputNewPassword');
const inputConfirmPassword = document.getElementById('inputConfirmPassword');
const btnSaveBasicConfig = document.getElementById('btnSaveBasicConfig');
const btnResetBasicConfig = document.getElementById('btnResetBasicConfig');
const btnClearBasicLogs = document.getElementById('btnClearBasicLogs');
const basicLogsTableBody = document.querySelector('#basicLogsTable tbody');

let logCounter = 2; // Inicia a contagem após as duas linhas de aviso padrões
let currentFontSize = 100; // Porcentagem inicial do tamanho da letra

// --- CONTROLE AUTOMÁTICO DO MENU LATERAL ---
if (sidebar) {
    sidebar.addEventListener('mouseenter', () => sidebar.classList.add('expanded'));
    sidebar.addEventListener('mouseleave', () => sidebar.classList.remove('expanded'));
}

// --- FUNÇÃO AUXILIAR: ADICIONAR REGISTRO NA TABELA (LOG) ---
function addSystemLog(mensagem) {
    logCounter++;
    const novaLinha = document.createElement('tr');
    novaLinha.innerHTML = `
        <td>#${String(logCounter).padStart(3, '0')}</td>
        <td>${mensagem}</td>
    `;
    if (basicLogsTableBody) {
        // Insere sempre no topo da tabela para ficar visível de primeira
        basicLogsTableBody.insertBefore(novaLinha, basicLogsTableBody.firstChild);
    }
}

// --- 2. FUNCIONALIDADE: MODO CLARO E MODO ESCURO ---
viewModeSelector.addEventListener('change', (event) => {
    const modoSelecionado = event.target.value;
    
    if (modoSelecionado === 'dark') {
        // Altera as cores bases do root para tons pretos/grafite de painel noturno
        document.documentElement.style.setProperty('--bg-body', '#1C1F1D');
        document.documentElement.style.setProperty('--card-bg', '#2A2E2C');
        document.documentElement.style.setProperty('--text-main', '#F4F2EE');
        addSystemLog('Visual alterado para o Modo Escuro (Noturno).');
    } else {
        // Restaura as cores originais off-white confortáveis do site
        document.documentElement.style.setProperty('--bg-body', '#FDFBF7');
        document.documentElement.style.setProperty('--card-bg', '#FFFFFF');
        document.documentElement.style.setProperty('--text-main', '#2F3E33');
        addSystemLog('Visual restaurada para o Modo Claro clássico.');
    }
});

// --- 3. FUNCIONALIDADE: AUMENTAR E DIMINUIR LETRA ---
btnFontAumentar.addEventListener('click', () => {
    currentFontSize += 10; // Sobe de 10% em 10%
    document.body.style.fontSize = currentFontSize + '%';
    addSystemLog(`Tamanho da fonte aumentado para ${currentFontSize}%.`);
});

btnFontDiminuir.addEventListener('click', () => {
    if (currentFontSize > 80) { // Trava de segurança para a letra não sumir
        currentFontSize -= 10; // Desce de 10% em 10%
        document.body.style.fontSize = currentFontSize + '%';
        addSystemLog(`Tamanho da fonte reduzido para ${currentFontSize}%.`);
    }
});

btnFontNormal.addEventListener('click', () => {
    currentFontSize = 100; // Retorna ao tamanho padrão de fábrica
    document.body.style.fontSize = '100%';
    addSystemLog('Tamanho da fonte redefinido para o padrão Normal.');
});

// --- 4. FUNCIONALIDADE: SALVAR MUDANÇAS (NOME, AVATAR E SENHA) ---
btnSaveBasicConfig.addEventListener('click', () => {
    const novoNome = inputUsername.value.trim();
    const novaSenha = inputNewPassword.value;
    const confirmaSenha = inputConfirmPassword.value;

    // Ação 1: Atualizar o Nome do Usuário no rodapé da barra lateral na hora
    if (novoNome !== "") {
        const footerNameElement = document.getElementById('footerUserName');
        const footerAvatarElement = document.getElementById('footerAvatar');
        
        if (footerNameElement) footerNameElement.innerText = novoNome;
        
        // Atualiza as duas primeiras letras do avatar do rodapé (Ex: Lucas C. = LU)
        if (footerAvatarElement && novoNome.length >= 2) {
            footerAvatarElement.innerText = novoNome.substring(0, 2).toUpperCase();
        }
        addSystemLog(`Nome do operador atualizado para: "${novoNome}".`);
    }

    // Ação 2: Validação básica e simples de troca de senha
    if (novaSenha !== "" || confirmaSenha !== "") {
        if (novaSenha === confirmaSenha) {
            addSystemLog('Código de acesso e senha alterados com sucesso.');
            alert('Senha alterada com sucesso!');
            // Limpa as caixas de senha após salvar por segurança
            inputNewPassword.value = "";
            inputConfirmPassword.value = "";
        } else {
            addSystemLog('Erro: Falha na confirmação. As senhas digitadas não batem.');
            alert('As senhas não coincidem! Redigite com atenção.');
            return; // Interrompe o salvamento se der erro
        }
    }

    alert('Configurações salvas e aplicadas no monitor!');
});

// --- 5. FUNCIONALIDADE: LIMPAR FORMULÁRIO ---
btnResetBasicConfig.addEventListener('click', () => {
    inputUsername.value = "Lucas";
    inputNewPassword.value = "";
    inputConfirmPassword.value = "";
    viewModeSelector.value = "light";
    currentFontSize = 100;
    
    // Reseta as propriedades de cores para o padrão claro
    document.documentElement.style.setProperty('--bg-body', '#FDFBF7');
    document.documentElement.style.setProperty('--card-bg', '#FFFFFF');
    document.documentElement.style.setProperty('--text-main', '#2F3E33');
    document.body.style.fontSize = '100%';

    // Restaura o rodapé lateral ao padrão original
    const footerNameElement = document.getElementById('footerUserName');
    const footerAvatarElement = document.getElementById('footerAvatar');
    if (footerNameElement) footerNameElement.innerText = "Lucas";
    if (footerAvatarElement) footerAvatarElement.innerText = "LC";

    addSystemLog('Painel de customização e caixas de texto redefinidos.');
});

// --- 6. FUNCIONALIDADE: LIMPAR HISTÓRICO DE REGISTROS ---
btnClearBasicLogs.addEventListener('click', () => {
    if (basicLogsTableBody) {
        basicLogsTableBody.innerHTML = `
            <tr class="empty-row-placeholder">
                <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 40px 0; font-style: italic;">
                    O histórico de registro de atividades foi esvaziado.
                </td>
            </tr>
        `;
    }
    logCounter = 0;
});
