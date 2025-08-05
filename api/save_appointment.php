<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paciente_id = $_POST['patient_id'] ?? '';
    $data_consulta = $_POST['date'] ?? '';
    $horario = $_POST['time'] ?? '';
    $procedimento = $_POST['procedure'] ?? '';
    $observacoes = trim($_POST['notes'] ?? '');
    
    // Validações
    if (empty($paciente_id) || empty($data_consulta) || empty($horario) || empty($procedimento)) {
        echo json_encode(['success' => false, 'message' => 'Todos os campos obrigatórios devem ser preenchidos!']);
        exit;
    }
    
    // Verificar se a data não é no passado
    if (date('Y-m-d') > $data_consulta) {
        echo json_encode(['success' => false, 'message' => 'Não é possível agendar consulta em data passada!']);
        exit;
    }
    
    try {
        // Verificar se já existe consulta agendada para esse horário
        $sql_check = "SELECT COUNT(*) as total FROM consultas 
                     WHERE data_consulta = ? AND horario = ? AND status = 'agendado'";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$data_consulta, $horario]);
        $result = $stmt_check->fetch();
        
        if ($result['total'] > 0) {
            echo json_encode(['success' => false, 'message' => 'Já existe uma consulta agendada para este horário!']);
            exit;
        }
        
        // Verificar se o paciente já tem consulta no mesmo dia
        $sql_check_patient = "SELECT COUNT(*) as total FROM consultas 
                             WHERE paciente_id = ? AND data_consulta = ? AND status = 'agendado'";
        $stmt_check_patient = $pdo->prepare($sql_check_patient);
        $stmt_check_patient->execute([$paciente_id, $data_consulta]);
        $result_patient = $stmt_check_patient->fetch();
        
        if ($result_patient['total'] > 0) {
            echo json_encode(['success' => false, 'message' => 'Paciente já possui consulta agendada para este dia!']);
            exit;
        }
        
        // Inserir consulta
        $sql = "INSERT INTO consultas (paciente_id, data_consulta, horario, procedimento, observacoes, status) 
                VALUES (?, ?, ?, ?, ?, 'agendado')";
        $stmt = $pdo->prepare($sql);
        
        $observacoes = empty($observacoes) ? null : $observacoes;
        
        if ($stmt->execute([$paciente_id, $data_consulta, $horario, $procedimento, $observacoes])) {
            echo json_encode(['success' => true, 'message' => 'Consulta agendada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao agendar consulta']);
        }
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}
?>