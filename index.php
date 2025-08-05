<?php
require_once 'config/database.php';
include 'includes/header.php';

// Buscar estatísticas do banco
$sql_patients = "SELECT COUNT(*) as total FROM pacientes";
$stmt = $pdo->prepare($sql_patients);
$stmt->execute();
$total_pacientes = $stmt->fetch()['total'];

$sql_today = "SELECT COUNT(*) as total FROM consultas WHERE DATE(data_consulta) = CURDATE()";
$stmt = $pdo->prepare($sql_today);
$stmt->execute();
$consultas_hoje = $stmt->fetch()['total'];
?>

<div class="container">
    <h1>📊 Dashboard</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?= $total_pacientes ?></div>
            <div class="stat-label">Pacientes Cadastrados</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #27ae60, #229954);">
            <div class="stat-number"><?= $consultas_hoje ?></div>
            <div class="stat-label">Consultas Hoje</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <div class="stat-number">0</div>
            <div class="stat-label">Consultas Esta Semana</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
            <div class="stat-number">R$ 0</div>
            <div class="stat-label">Faturamento Mensal</div>
        </div>
    </div>

    <h2>📅 Próximas Consultas</h2>
    <div id="proximas-consultas" class="cards-grid">
        <?php
        $sql = "SELECT c.*, p.nome FROM consultas c 
                JOIN pacientes p ON c.paciente_id = p.id 
                WHERE c.data_consulta >= CURDATE() 
                ORDER BY c.data_consulta, c.horario LIMIT 6";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $consultas = $stmt->fetchAll();

        foreach ($consultas as $consulta): ?>
            <div class="card">
                <h3>👤 <?= htmlspecialchars($consulta['nome']) ?></h3>
                <p><strong>📅 Data:</strong> <?= date('d/m/Y', strtotime($consulta['data_consulta'])) ?></p>
                <p><strong>⏰ Horário:</strong> <?= $consulta['horario'] ?></p>
                <p><strong>🦷 Procedimento:</strong> <?= htmlspecialchars($consulta['procedimento']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="notification" class="notification"></div>

<?php include 'includes/footer.php'; ?>