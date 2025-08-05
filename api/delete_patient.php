<?php
require_once '../config/database.php';

if (isset($_POST['patient_id'])) {
    $patient_id = $_POST['patient_id'];
    
    try {
        // Verificar se o paciente tem consultas agendadas
        $sql_check = "SELECT COUNT(*) as total FROM consultas WHERE paciente_id = ? AND status = 'agendado'";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$patient_id]);
        $result = $stmt_check->fetch();
        
        if ($result['total'] > 0) {
            echo json_encode(['success' => false, 'message' => 'Não é possível excluir paciente com consultas agendadas!']);
            exit;
        }
        
        // Excluir o paciente
        $sql = "DELETE FROM pacientes WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$patient_id])) {
            echo json_encode(['success' => true, 'message' => 'Paciente excluído com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir paciente']);
        }
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID do paciente não fornecido']);
}
?>