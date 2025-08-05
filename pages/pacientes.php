<?php
require_once '../config/database.php';
include '../includes/header.php';

// Processar formulário se enviado
if ($_POST) {
    $nome = $_POST['name'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['phone'];
    $email = $_POST['email'];
    $nascimento = $_POST['birthdate'];
    $endereco = $_POST['address'];
    $observacoes = $_POST['observations'];
    
    $sql = "INSERT INTO pacientes (nome, cpf, telefone, email, data_nascimento, endereco, observacoes) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nome, $cpf, $telefone, $email, $nascimento, $endereco, $observacoes])) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Paciente cadastrado com sucesso!', 'success');
                });
              </script>";
    }
}

// Buscar todos os pacientes
$sql = "SELECT * FROM pacientes ORDER BY nome";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pacientes = $stmt->fetchAll();
?>

<div class="container">
    <div class="tabs">
        <button class="tab-button active" onclick="showSubTab('cadastrar-paciente')">Cadastrar Paciente</button>
        <button class="tab-button" onclick="showSubTab('listar-pacientes')">Lista de Pacientes</button>
    </div>

    <!-- CADASTRAR PACIENTE -->
    <div id="cadastrar-paciente" class="sub-tab-content active">
        <h2>👤 Cadastrar Novo Paciente</h2>
        
        <form id="patient-form" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Nome Completo *</label>
                    <input type="text" id="name" name="name" required>
                    <div class="error" id="name-error"></div>
                </div>

                <div class="form-group">
                    <label for="cpf">CPF *</label>
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                    <div class="error" id="cpf-error"></div>
                    <div class="success" id="cpf-success"></div>
                </div>

                <div class="form-group">
                    <label for="phone">Telefone *</label>
                    <input type="text" id="phone" name="phone" placeholder="(00) 00000-0000" required>
                    <div class="error" id="phone-error"></div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                    <div class="error" id="email-error"></div>
                </div>

                <div class="form-group">
                    <label for="birthdate">Data de Nascimento</label>
                    <input type="date" id="birthdate" name="birthdate">
                </div>

                <div class="form-group">
                    <label for="address">Endereço</label>
                    <input type="text" id="address" name="address">
                </div>
            </div>

            <div class="form-group">
                <label for="observations">Observações Médicas</label>
                <textarea id="observations" name="observations" rows="4" placeholder="Alergias, medicamentos, histórico médico..."></textarea>
            </div>

            <button type="submit" class="btn">💾 Cadastrar Paciente</button>
            <button type="button" class="btn btn-secondary" onclick="clearForm()">🗑️ Limpar</button>
        </form>
    </div>

    <!-- LISTAR PACIENTES -->
    <div id="listar-pacientes" class="sub-tab-content">
        <h2>📋 Lista de Pacientes</h2>
        
        <div class="search-container">
            <input type="text" id="patient-search" class="search-input" placeholder="🔍 Buscar paciente..." onkeyup="searchPatients()">
            <div id="search-results"></div>
        </div>

        <div class="cards-grid">
            <?php foreach ($pacientes as $paciente): ?>
                <div class="card">
                    <h3>👤 <?= htmlspecialchars($paciente['nome']) ?></h3>
                    <p><strong>CPF:</strong> <?= htmlspecialchars($paciente['cpf']) ?></p>
                    <p><strong>Telefone:</strong> <?= htmlspecialchars($paciente['telefone']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($paciente['email'] ?? 'Não informado') ?></p>
                    <p><strong>Nascimento:</strong> <?= $paciente['data_nascimento'] ? date('d/m/Y', strtotime($paciente['data_nascimento'])) : 'Não informado' ?></p>
                    <p><strong>Endereço:</strong> <?= htmlspecialchars($paciente['endereco'] ?? 'Não informado') ?></p>
                    <p><strong>Observações:</strong> <?= htmlspecialchars($paciente['observacoes'] ?? 'Nenhuma') ?></p>
                    <button class="btn btn-danger" onclick="deletePatient('<?= htmlspecialchars($paciente['nome']) ?>', <?= $paciente['id'] ?>)">
                        🗑️ Excluir
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div id="notification" class="notification"></div>

<?php include '../includes/footer.php'; ?>