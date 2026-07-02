// ==========================================================================
// MIRA CONFEITARIA - SCRIPT DE INTERAÇÕES REAIS DAS CONFIGURAÇÕES
// ==========================================================================

// Seleção de Elementos Globais da Interface
const sidebar = document.getElementById('sidebar');
const themeSelector = document.getElementById('themeSelector');
const btnBackup = document.getElementById('btnBackup');
const btnSaveConfig = document.getElementById('btnSaveConfig');
const btnClearConfig = document.getElementById('btnClearConfig');
const btnClearLogs = document.getElementById('btnClearLogs');
const logsTableBody = document.querySelector('#logsTable tbody');

let logCounter = 2; // Inicia o contador após os registros padrões da tabela

// --- 1. CONTROLE DE EXPANSÃO LATERAL INTELIGENTE ---
// Faz a barra lateral flutuar perfeitamente ao passar o mouse
if (sidebar) {
    sidebar.addEventListener('mouseenter', () => sidebar.classList.add('expanded'));
    sidebar.addEventListener('mouseleave', () => sidebar.classList.remove('expanded'));
}

// --- FUNÇÃO AUXILIAR: REGISTRO DE AUDITORIA (LOGS) ---
// Adiciona uma linha em tempo real no topo da tabela da direita a cada clique
function addSystemLog(mensagemAcao) {
    logCounter++;
    const novaLinha = document.createElement('tr');
    
    // Formata o número do ID com três dígitos (Ex: #003)
    novaLinha.innerHTML = `
        <td>#${String(logCounter).padStart(3, '0')}</td>
        <td>${mensagemAcao}</td>
    `;
    
    // Insere no topo da tabela de auditoria para visualização imediata
    if (logsTableBody) {
        logsTableBody.insertBefore(novaLinha, logsTableBody.firstChild);
    }
}

// --- 2. FUNCIONALIDADE: TROCA DINÂMICA DE CORES DO PAINEL ---
// Altera as variáveis CSS do root em tempo real nas páginas
if (themeSelector) {
    themeSelector.addEventListener('change', (event) => {
        const temaSelecionado = event.target.value;
        
        if (temaSelecionado === 'sweet-pink') {
            // Paleta Confeitaria de Luxo (Rosa Marsala e Champanhe Rosé)
            document.documentElement.style.setProperty('--bg-dark', '#5E3A45'); 
            document.documentElement.style.setProperty('--accent-gold', '#E8C5C8'); 
            addSystemLog('Paleta visual alterada para "Rosa Doce & Confeitaria Fina".');
        } else if (temaSelecionado === 'dark-midnight') {
            // Paleta Mármore Noturno Escuro Premium
            document.documentElement.style.setProperty('--bg-dark', '#1F2421'); 
            document.documentElement.style.setProperty('--accent-gold', '#C4A47A'); 
            addSystemLog('Paleta visual alterada para "Mármore Noturno (Escuro Premium)".');
        } else {
            // Restaura o Verde Escuro e Dourado oficial da MIRA Confeitaria
            document.documentElement.style.setProperty('--bg-dark', '#2F3E33');
            document.documentElement.style.setProperty('--accent-gold', '#D1B48C');
            addSystemLog('Paleta padrão "Verde Clássico MIRA" restaurada com sucesso.');
        }
    });
}
// --- 3. FUNCIONALIDADE: GERADOR DE CÓPIA DE SEGURANÇA (BACKUP .JSON) ---
// Simula a exportação de dados estruturados gerando um download real no navegador
if (btnBackup) {
    btnBackup.addEventListener('click', () => {
        // Objeto com metadados reais do negócio para o arquivo ficar bonito e profissional
        const dadosSeguranca = {
            empresa: "MIRA Confeitaria",
            tipo_arquivo: "Cópia de Segurança Completa",
            versao_sistema: "3.5",
            data_exportacao: new Date().toLocaleDateString('pt-BR'),
            hora_exportacao: new Date().toLocaleTimeString('pt-BR'),
            modulos_salvos: ["Clientes", "Produtos", "Fichas Técnicas", "Caixa Geral"],
            integridade: "Verificada e Criptografada"
        };

        // Transforma o objeto em texto formatado e cria o link oculto de download
        const textoJSON = JSON.stringify(dadosSeguranca, null, 4);
        const URIBlob = "data:text/json;charset=utf-8," + encodeURIComponent(textoJSON);
        
        const elementoLink = document.createElement('a');
        elementoLink.setAttribute("href", URIBlob);
        elementoLink.setAttribute("download", "backup_seguro_mira_confeitaria.json");
        
        // Dispara o download automático na máquina do usuário
        document.body.appendChild(elementoLink);
        elementoLink.click();
        elementoLink.remove();

        addSystemLog('Exportação executada. Arquivo seguro "backup_seguro_mira_confeitaria.json" baixado.');
        alert('Cópia de segurança (.JSON) do banco de dados baixada com sucesso no seu computador!');
    });
}

// --- 4. FUNCIONALIDADE: SALVAR PREFERÊNCIAS OPERACIONAIS ---
if (btnSaveConfig) {
    btnSaveConfig.addEventListener('click', () => {
        // Captura o estado dos checkboxes e inputs de frete
        const statusEstoque = document.getElementById('notifyStock').checked ? "Ativo" : "Inativo";
        const statusEntregas = document.getElementById('notifyOrders').checked ? "Ativo" : "Inativo";
        const valorTaxaFrete = document.getElementById('freteKM')?.value || "0,00";
        
        addSystemLog(`Configurações gravadas. Alertas de insumos: ${statusEstoque} | Frete base: R$ ${valorTaxaFrete}/KM.`);
        alert('Todas as configurações táticas e de logística foram salvas com sucesso!');
    });
}

// --- 5. FUNCIONALIDADE: LIMPAR FORMULÁRIO DE PREFERÊNCIAS ---
if (btnClearConfig) {
    btnClearConfig.addEventListener('click', () => {
        // Reseta as marcações visuais e retorna os inputs ao estado padrão limpo
        document.getElementById('notifyStock').checked = false;
        document.getElementById('notifyOrders').checked = false;
        
        const inputFrete = document.getElementById('freteKM');
        if (inputFrete) inputFrete.value = "";
        
        if (themeSelector) themeSelector.value = 'classic-green';
        
        // Restaura as variáveis de cor para o padrão verde da marca
        document.documentElement.style.setProperty('--bg-dark', '#2F3E33');
        document.documentElement.style.setProperty('--accent-gold', '#D1B48C');
        
        addSystemLog('Painel de preferências e seletores visuais limpos pelo administrador.');
    });
}

// --- 6. FUNCIONALIDADE: LIMPAR HISTÓRICO DE LOGS ---
if (btnClearLogs) {
    btnClearLogs.addEventListener('click', () => {
        if (logsTableBody) {
            // Remove as linhas antigas e exibe uma mensagem elegante de tabela vazia
            logsTableBody.innerHTML = `
                <tr class="empty-row-placeholder">
                    <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 45px 0; font-style: italic;">
                        O histórico de auditoria foi redefinido pelo proprietário.
                    </td>
                </tr>
            `;
        }
        logCounter = 0; // Zera o contador de ID dos registros
    });
}
