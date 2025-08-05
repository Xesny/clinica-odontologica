<?php
require_once '../config/database.php';
include '../includes/header.php';

// Buscar todas as consultas
$sql = "SELECT c.*, p.nome FROM consultas c 
        JOIN pacientes p ON c.paciente_id = p.id 
        WHERE c.status = 'agendado'
        ORDER BY c.data_consulta, c.horario";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$consultas = $stmt->fetchAll();
?>

<div class="container">
    <h2>🦷 Consultas Agendadas</h2>
    
    <div class="search-container">
        <input type="text" id="consultation-search" class="search-input" placeholder="🔍 Buscar consulta..." onkeyup="searchConsultations()">
    </div>

    <div class="cards-grid">
        <?php foreach ($consultas as $consulta): ?>
            <div class="card">
                <h3>👤 <?= htmlspecialchars($consulta['nome']) ?></h3>
                <p><strong>📅 Data:</strong> <?= date('d/m/Y', strtotime($consulta['data_consulta'])) ?></p>
                <p><strong>⏰ Horário:</strong> <?= $consulta['horario'] ?></p>
                <p><strong>🦷 Procedimento:</strong> <?= htmlspecialchars($consulta['procedimento']) ?></p>
                <?php if ($consulta['observacoes']): ?>
                    <p><strong>📝 Observações:</strong> <?= htmlspecialchars($consulta['observacoes']) ?></p>
                <?php endif; ?>
                <p><strong>📊 Status:</strong> <span style="color: #f39c12; font-weight: bold;">Agendado</span></p>
                <a href="marcar_concluida.php?id=<?= $consulta['id'] ?>" class="btn btn-success" onclick="return confirm('Marcar como concluída?')">
                    ✅ Concluir Consulta
                </a>
                <a href="cancelar_consulta.php?id=<?= $consulta['id'] ?>" class="btn btn-danger" onclick="return confirm('Cancelar consulta?')">
                    ❌ Cancelar
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="notification" class="notification"></div>

<?php include '../includes/footer.php'; ?>