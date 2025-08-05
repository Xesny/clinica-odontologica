<?php
require_once '../config/database.php';
include '../includes/header.php';

// Buscar pacientes para o select
$sql_pacientes = "SELECT id, nome FROM pacientes ORDER BY nome";
$stmt = $pdo->prepare($sql_pacientes);
$stmt->execute();
$pacientes = $stmt->fetchAll();

// Processar agendamento se enviado
if ($_POST) {
    $paciente_id = $_POST['appointment-patient'];
    $data = $_POST['appointment-date'];
    $horario = $_POST['appointment-time'];
    $procedimento = $_POST['appointment-procedure'];
    $observacoes = $_POST['appointment-notes'];
    
    $sql = "INSERT INTO consultas (paciente_id, data_consulta, horario, procedimento, observacoes, status) 
            VALUES (?, ?, ?, ?, ?, 'agendado')";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$paciente_id, $data, $horario, $procedimento, $observacoes])) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Consulta agendada com sucesso!', 'success');
                });
              </script>";
    }
}

// Buscar consultas de hoje
$sql_hoje = "SELECT c.*, p.nome FROM consultas c 
             JOIN pacientes p ON c.paciente_id = p.id 
             WHERE DATE(c.data_consulta) = CURDATE() AND c.status = 'agendado'  
             ORDER BY c.horario";
$stmt = $pdo->prepare($sql_hoje);
$stmt->execute();
$consultas_hoje = $stmt->fetchAll();
?>

<div class="container">
    <h2>📅 Sistema de Agendamento</h2>
    
    <div class="form-grid">
        <div>
            <h3>Agendar Nova Consulta</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="appointment-patient">Paciente *</label>
                    <select id="appointment-patient" name="appointment-patient" required>
                        <option value="">Selecione um paciente</option>
                        <?php foreach ($pacientes as $paciente): ?>
                            <option value="<?= $paciente['id'] ?>"><?= htmlspecialchars($paciente['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="appointment-date">Data *</label>
                    <input type="date" id="appointment-date" name="appointment-date" required min="<?= date('Y-m-d') ?>">
                </div>

                <div class="form-group">
                    <label for="appointment-time">Horário *</label>
                    <select id="appointment-time" name="appointment-time" required>
                        <option value="">Selecione um horário</option>
                        <option value="08:00">08:00</option>
                        <option value="09:00">09:00</option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                        <option value="17:00">17:00</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="appointment-procedure">Procedimento *</label>
                    <select id="appointment-procedure" name="appointment-procedure" required>
                        <option value="Consulta">Consulta</option>
                        <option value="Limpeza">Limpeza</option>
                        <option value="Extração">Extração</option>
                        <option value="Obturação">Obturação</option>
                        <option value="Canal">Canal</option>
                        <option value="Ortodontia">Ortodontia</option>
                        <option value="Prótese">Prótese</option>
                        <option value="Implante">Implante</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="appointment-notes">Observações</label>
                    <textarea id="appointment-notes" name="appointment-notes" rows="3" placeholder="Observações sobre a consulta..."></textarea>
                </div>

                <button type="submit" class="btn">📅 Agendar Consulta</button>
            </form>
        </div>

        <div>
            <h3>Agenda do Dia</h3>
            <?php if (empty($consultas_hoje)): ?>
                <p style="text-align: center; color: #7f8c8d; padding: 20px;">Nenhuma consulta agendada para hoje</p>
            <?php else: ?>
                <?php foreach ($consultas_hoje as $consulta): ?>
                    <div class="card" style="margin-bottom: 15px;">
                        <h4>⏰ <?= $consulta['horario'] ?> - <?= htmlspecialchars($consulta['nome']) ?></h4>
                        <p><strong>Procedimento:</strong> <?= htmlspecialchars($consulta['procedimento']) ?></p>
                        <?php if ($consulta['observacoes']): ?>
                            <p><strong>Observações:</strong> <?= htmlspecialchars($consulta['observacoes']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="notification" class="notification"></div>

<?php include '../includes/footer.php'; ?>