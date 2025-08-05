# 🦷 Sistema de Clínica Odontológica

Um sistema completo para gerenciamento de clínicas odontológicas desenvolvido em PHP, MySQL, HTML, CSS e JavaScript.

## 🚀 Funcionalidades

- ✅ **Dashboard** com estatísticas em tempo real
- ✅ **Cadastro de Pacientes** com validação completa
- ✅ **Agendamento de Consultas** com verificação de conflitos
- ✅ **Gestão de Consultas** (agendar, concluir, cancelar)
- ✅ **Busca Dinâmica** de pacientes e consultas
- ✅ **Validação de Formulários** em tempo real
- ✅ **Máscaras Automáticas** (CPF, telefone)
- ✅ **Design Responsivo** e moderno
- ✅ **Notificações** de sucesso e erro

## 📋 Pré-requisitos

- XAMPP, WAMP ou similar (Apache + MySQL + PHP)
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Visual Studio Code (recomendado)

## 🛠️ Instalação

### 1. Configurar o Ambiente

1. Instale o **XAMPP** ou similar
2. Inicie os serviços **Apache** e **MySQL**
3. Abra o **phpMyAdmin** (http://localhost/phpmyadmin)

### 2. Criar o Banco de Dados

Execute o seguinte script SQL no phpMyAdmin:

```sql
CREATE DATABASE clinica_odontologica;
USE clinica_odontologica;

CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    email VARCHAR(100),
    data_nascimento DATE,
    endereco TEXT,
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    data_consulta DATE NOT NULL,
    horario TIME NOT NULL,
    procedimento VARCHAR(50) NOT NULL,
    observacoes TEXT,
    status ENUM('agendado', 'concluido', 'cancelado') DEFAULT 'agendado',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE
);

-- Inserir dados de exemplo
INSERT INTO pacientes (nome, cpf, telefone, email, data_nascimento, endereco, observacoes) VALUES
('João Silva', '123.456.789-10', '(11) 99999-9999', 'joao@email.com', '1985-05-15', 'Rua A, 123', 'Alérgico à penicilina'),
('Maria Santos', '987.654.321-00', '(11) 88888-8888', 'maria@email.com', '1992-03-22', 'Rua B, 456', 'Diabetes tipo 2'),
('Pedro Oliveira', '456.789.123-45', '(11) 77777-7777', 'pedro@email.com', '1978-11-08', 'Rua C, 789', 'Pressão alta'),
('Ana Costa', '789.123.456-78', '(11) 66666-6666', 'ana@email.com', '1990-07-30', 'Rua D, 321', 'Sem observações');

INSERT INTO consultas (paciente_id, data_consulta, horario, procedimento, observacoes) VALUES
(1, CURDATE(), '09:00', 'Consulta', 'Primeira consulta'),
(2, CURDATE(), '14:00', 'Limpeza', 'Limpeza semestral'),
(3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:00', 'Obturação', 'Obturação dente 16'),
(4, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '15:00', 'Canal', 'Canal dente 36');
```

### 3. Criar a Estrutura de Arquivos

Crie a seguinte estrutura de pastas na pasta `htdocs` do XAMPP:

```
clinica-odontologica/
├── index.php
├── marcar_concluida.php
├── cancelar_consulta.php
├── config/
│   └── database.php
├── css/
│   └── style.css
├── js/
│   └── clinica.js
├── pages/
│   ├── dashboard.php
│   ├── pacientes.php
│   ├── agendamentos.php
│   └── consultas.php
├── includes/
│   ├── header.php
│   └── footer.php
└── api/
    ├── save_patient.php
    ├── search_patients.php
    ├── save_appointment.php
    ├── delete_patient.php
    └── get_stats.php
```

### 4. Configurar o Banco de Dados

Edite o arquivo `config/database.php` com suas configurações:

```php
<?php
$host = 'localhost';
$dbname = 'clinica_odontologica';
$username = 'root';
$password = '';
```

### 5. Copiar os Arquivos

Copie todos os arquivos fornecidos para suas respectivas pastas seguindo a estrutura acima.

## 🎯 Como Usar

1. Acesse: `http://localhost/clinica-odontologica`
2. Navegue pelas seções usando o menu superior:
   - **Dashboard**: Visualize estatísticas
   - **Pacientes**: Cadastre e gerencie pacientes
   - **Agendamentos**: Agende novas consultas
   - **Consultas**: Visualize e gerencie consultas

## 🔧 Principais Funcionalidades

### Dashboard
- Estatísticas em tempo real
- Próximas consultas do dia
- Indicadores visuais

### Pacientes
- Cadastro com validação de CPF
- Busca dinâmica
- Exclusão segura
- Máscaras automáticas

### Agendamentos
- Seleção de paciente
- Verificação de conflitos
- Agenda do dia
- Múltiplos procedimentos

### Consultas
- Listagem completa
- Marcar como concluída
- Cancelar consultas
- Busca e filtros

## 🎨 Tecnologias Utilizadas

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+
- **Banco de Dados**: MySQL
- **Design**: CSS Grid, Flexbox, Animações CSS
- **Funcionalidades**: AJAX, Validação em tempo real

## 🔒 Segurança

- Validação de dados no frontend e backend
- Prepared statements (PDO)
- Sanitização de inputs
- Verificação de conflitos
- Tratamento de erros

## 📱 Responsividade

O sistema é totalmente responsivo e funciona em:
- Desktop
- Tablet
- Smartphone

## 🆘 Solução de Problemas

### Erro de Conexão com Banco
- Verifique se o MySQL está rodando
- Confirme as credenciais em `config/database.php`
- Certifique-se que o banco foi criado

### Formulários não Funcionam
- Verifique se o JavaScript está carregando
- Confirme se os arquivos da pasta `api/` existem
- Verifique permissões de escrita

### Páginas em Branco
- Ative a exibição de erros no PHP
- Verifique os logs de erro do Apache
- Confirme que todos os arquivos foram copiados

## 📄 Licença

Este projeto é livre para uso pessoal e educacional.

## 🤝 Contribuição

Sinta-se à vontade para contribuir com melhorias:
1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

## 📞 Suporte

Para dúvidas ou problemas:
- Verifique se seguiu todos os passos de instalação
- Confirme que o XAMPP está rodando corretamente
- Teste as funcionalidades uma por vez

---

**Desenvolvido com ❤️ para facilitar a gestão de clínicas odontológicas**