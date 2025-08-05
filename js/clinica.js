// Arrays para armazenar dados (simulação de banco de dados local)
let patients = [];
let appointments = [];

// Funções de inicialização
document.addEventListener('DOMContentLoaded', function() {
    initializePage();
    setupEventListeners();
    setupFormValidation();
});

// Configurar todos os event listeners
function setupEventListeners() {
    // Máscaras de entrada
    setupMasks();
    
    // Formulário de paciente
    const patientForm = document.getElementById('patient-form');
    if (patientForm) {
        patientForm.addEventListener('submit', handlePatientSubmit);
    }
    
    // Busca de pacientes
    const patientSearch = document.getElementById('patient-search');
    if (patientSearch) {
        patientSearch.addEventListener('input', debounce(searchPatients, 300));
    }
    
    // Busca de consultas
    const consultationSearch = document.getElementById('consultation-search');
    if (consultationSearch) {
        consultationSearch.addEventListener('input', debounce(searchConsultations, 300));
    }
}

// Configurar máscaras de entrada
function setupMasks() {
    // Máscara CPF
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
            validateCPF(value);
        });
    }
    
    // Máscara Telefone
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    }
}

// Configurar validação de formulários
function setupFormValidation() {
    const inputs = document.querySelectorAll('input[required], select[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearFieldError(this);
        });
    });
}

// Validar campo individual
function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.name;
    
    // Limpar erros anteriores
    clearFieldError(field);
    
    // Validações específicas
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'Este campo é obrigatório');
        return false;
    }
    
    if (fieldName === 'email' && value) {
        if (!isValidEmail(value)) {
            showFieldError(field, 'Email inválido');
            return false;
        }
    }
    
    if (fieldName === 'cpf' && value) {
        if (!isValidCPF(value)) {
            showFieldError(field, 'CPF inválido');
            return false;
        }
    }
    
    // Campo válido
    field.classList.add('valid');
    return true;
}

// Mostrar erro em campo
function showFieldError(field, message) {
    field.classList.add('invalid');
    field.classList.remove('valid');
    
    const errorElement = document.getElementById(field.name + '-error');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

// Limpar erro de campo
function clearFieldError(field) {
    field.classList.remove('invalid');
    
    const errorElement = document.getElementById(field.name + '-error');
    if (errorElement) {
        errorElement.style.display = 'none';
    }
}

// Validar CPF
function validateCPF(cpf) {
    const cpfInput = document.getElementById('cpf');
    const errorElement = document.getElementById('cpf-error');
    const successElement = document.getElementById('cpf-success');
    
    if (!cpf) return;
    
    if (isValidCPF(cpf)) {
        cpfInput.classList.add('valid');
        cpfInput.classList.remove('invalid');
        if (errorElement) errorElement.style.display = 'none';
        if (successElement) {
            successElement.textContent = 'CPF válido ✓';
            successElement.style.display = 'block';
        }
    } else {
        cpfInput.classList.add('invalid');
        cpfInput.classList.remove('valid');
        if (successElement) successElement.style.display = 'none';
        if (errorElement) {
            errorElement.textContent = 'CPF inválido';
            errorElement.style.display = 'block';
        }
    }
}

// Verificar se CPF é válido
function isValidCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g, '');
    
    if (cpf.length !== 11 || !!cpf.match(/(\d)\1{10}/)) return false;
    
    let sum = 0;
    let remainder;
    
    for (let i = 1; i <= 9; i++) {
        sum += parseInt(cpf.substring(i-1, i)) * (11 - i);
    }
    
    remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) remainder = 0;
    if (remainder !== parseInt(cpf.substring(9, 10))) return false;
    
    sum = 0;
    for (let i = 1; i <= 10; i++) {
        sum += parseInt(cpf.substring(i-1, i)) * (12 - i);
    }
    
    remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) remainder = 0;
    if (remainder !== parseInt(cpf.substring(10, 11))) return false;
    
    return true;
}

// Verificar se email é válido
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Debounce para otimizar buscas
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Gerenciar tabs
function showSubTab(tabName) {
    // Esconder todas as abas
    const tabs = document.querySelectorAll('.sub-tab-content');
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remover classe active de todos os botões
    const buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(button => {
        button.classList.remove('active');
    });
    
    // Mostrar aba selecionada
    const selectedTab = document.getElementById(tabName);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }
    
    // Ativar botão correspondente
    const activeButton = document.querySelector(`[onclick="showSubTab('${tabName}')"]`);
    if (activeButton) {
        activeButton.classList.add('active');
    }
}

// Processar submissão do formulário de paciente
function handlePatientSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const button = e.target.querySelector('button[type="submit"]');
    
    // Validar todos os campos
    const requiredFields = e.target.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        showNotification('Por favor, corrija os erros no formulário', 'error');
        return;
    }
    
    // Mostrar loading
    const originalText = button.textContent;
    button.innerHTML = '<span class="loading"></span> Salvando...';
    button.disabled = true;
    
    // Simular envio (substitua pela chamada AJAX real)
    setTimeout(() => {
        // Resetar botão
        button.textContent = originalText;
        button.disabled = false;
        
        // Mostrar sucesso
        showNotification('Paciente cadastrado com sucesso!', 'success');
        
        // Limpar formulário
        clearForm();
        
        // Ir para a aba de listagem
        showSubTab('listar-pacientes');
        
        // Recarregar lista (em ambiente real, seria uma chamada AJAX)
        setTimeout(() => {
            location.reload();
        }, 1500);
    }, 2000);
}

// Limpar formulário
function clearForm() {
    const form = document.getElementById('patient-form');
    if (form) {
        form.reset();
        
        // Limpar classes de validação
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.classList.remove('valid', 'invalid');
        });
        
        // Limpar mensagens de erro e sucesso
        const errors = form.querySelectorAll('.error, .success');
        errors.forEach(error => {
            error.style.display = 'none';
        });
    }
}

// Buscar pacientes
function searchPatients() {
    const searchTerm = document.getElementById('patient-search').value.toLowerCase();
    const cards = document.querySelectorAll('#listar-pacientes .card');
    
    cards.forEach(card => {
        const content = card.textContent.toLowerCase();
        if (content.includes(searchTerm)) {
            card.style.display = 'block';
            card.classList.add('fade-in');
        } else {
            card.style.display = 'none';
        }
    });
    
    // Mostrar mensagem se nenhum resultado
    const visibleCards = document.querySelectorAll('#listar-pacientes .card[style="display: block"]');
    const noResults = document.getElementById('no-results');
    
    if (visibleCards.length === 0 && searchTerm) {
        if (!noResults) {
            const message = document.createElement('div');
            message.id = 'no-results';
            message.className = 'card';
            message.style.textAlign = 'center';
            message.style.color = '#7f8c8d';
            message.innerHTML = '<h3>🔍 Nenhum paciente encontrado</h3><p>Tente buscar por nome, CPF ou telefone</p>';
            document.querySelector('#listar-pacientes .cards-grid').appendChild(message);
        }
    } else if (noResults) {
        noResults.remove();
    }
}

// Buscar consultas
function searchConsultations() {
    const searchTerm = document.getElementById('consultation-search').value.toLowerCase();
    const cards = document.querySelectorAll('.cards-grid .card');
    
    cards.forEach(card => {
        const content = card.textContent.toLowerCase();
        if (content.includes(searchTerm)) {
            card.style.display = 'block';
            card.classList.add('fade-in');
        } else {
            card.style.display = 'none';
        }
    });
}

// Excluir paciente
function deletePatient(patientName, patientId) {
    if (!confirm(`Tem certeza que deseja excluir o paciente "${patientName}"?\n\nEsta ação não pode ser desfeita.`)) {
        return;
    }
    
    // Mostrar loading
    const button = event.target;
    const originalText = button.textContent;
    button.innerHTML = '<span class="loading"></span> Excluindo...';
    button.disabled = true;
    
    // Fazer requisição AJAX
    fetch('api/delete_patient.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `patient_id=${patientId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            // Remover card da tela
            button.closest('.card').style.transform = 'scale(0)';
            setTimeout(() => {
                button.closest('.card').remove();
            }, 300);
        } else {
            showNotification(data.message, 'error');
            // Resetar botão
            button.textContent = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao excluir paciente', 'error');
        // Resetar botão
        button.textContent = originalText;
        button.disabled = false;
    });
}

// Mostrar notificações
function showNotification(message, type = 'success') {
    // Remover notificação existente
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Criar nova notificação
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    // Adicionar ao DOM
    document.body.appendChild(notification);
    
    // Mostrar notificação
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Esconder após 5 segundos
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Inicializar página
function initializePage() {
    // Definir data mínima para agendamentos (hoje)
    const dateInputs = document.querySelectorAll('input[type="date"]');
    const today = new Date().toISOString().split('T')[0];
    
    dateInputs.forEach(input => {
        if (input.hasAttribute('min')) {
            input.setAttribute('min', today);
        }
    });
    
    // Foco automático no primeiro campo
    const firstInput = document.querySelector('input:not([type="hidden"])');
    if (firstInput) {
        setTimeout(() => {
            firstInput.focus();
        }, 500);
    }
    
    // Animar cards ao carregar
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 100);
    });
}

// Atualizar dashboard (função para ser chamada periodicamente)
function updateDashboard() {
    // Buscar estatísticas atualizadas via AJAX
    fetch('api/get_stats.php')
    .then(response => response.json())
    .then(data => {
        // Atualizar números do dashboard
        const statNumbers = document.querySelectorAll('.stat-number');
        if (statNumbers.length >= 4) {
            statNumbers[0].textContent = data.total_patients || '0';
            statNumbers[1].textContent = data.consultations_today || '0';
            statNumbers[2].textContent = data.consultations_week || '0';
            statNumbers[3].textContent = data.monthly_revenue || 'R$ 0';
        }
    })
    .catch(error => {
        console.error('Erro ao atualizar dashboard:', error);
    });
}

// Função para formatar moeda
function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}

// Função para formatar data
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
}

// Função para formatar telefone
function formatPhone(phone) {
    const cleaned = phone.replace(/\D/g, '');
    const match = cleaned.match(/^(\d{2})(\d{5})(\d{4})$/);
    if (match) {
        return `(${match[1]}) ${match[2]}-${match[3]}`;
    }
    return phone;
}

// Função para validar data de nascimento
function validateBirthDate(date) {
    const today = new Date();
    const birthDate = new Date(date);
    const age = today.getFullYear() - birthDate.getFullYear();
    
    if (age < 0 || age > 150) {
        return false;
    }
    
    return true;
}

// Função para calcular idade
function calculateAge(birthDate) {
    const today = new Date();
    const birth = new Date(birthDate);
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
        age--;
    }
    
    return age;
}

// Função para verificar conflitos de horário
function checkTimeConflict(date, time) {
    // Esta função seria implementada com uma chamada AJAX real
    return new Promise((resolve) => {
        setTimeout(() => {
            // Simular verificação de conflito
            const isConflict = Math.random() < 0.1; // 10% chance de conflito
            resolve(isConflict);
        }, 500);
    });
}

// Event listeners para elementos dinâmicos
document.addEventListener('click', function(e) {
    // Fechar resultados de busca ao clicar fora
    if (!e.target.closest('.search-container')) {
        const searchResults = document.getElementById('search-results');
        if (searchResults) {
            searchResults.style.display = 'none';
        }
    }
});

// Função para exportar dados (futura implementação)
function exportData(type) {
    showNotification('Funcionalidade de exportação em desenvolvimento', 'warning');
}

// Função para imprimir (futura implementação)
function printReport(type) {
    showNotification('Funcionalidade de impressão em desenvolvimento', 'warning');
}

// Atualizar dashboard a cada 5 minutos se estiver na página inicial
if (window.location.pathname.includes('index.php') || window.location.pathname === '/') {
    setInterval(updateDashboard, 300000); // 5 minutos
}