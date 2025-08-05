<?php
require_once '../config/database.php';

try {
    // Total de pacientes
    $sql_patients = "SELECT COUNT(*) as total FROM pacientes";
    $stmt = $pdo->prepare($sql_patients);
    $stmt->execute();
    $total_patients = $stmt->fetch()['total'];
    
    // Consultas hoje
    $sql_today = "SELECT COUNT(*) as total FROM consultas WHERE DATE(data_consulta) = CURDATE() AND status = 'agendado'";
    $stmt = $pdo->prepare($sql_today);
    $stmt->execute();
    $consultations_today = $stmt->fetch()['total'];
    
    // Consultas esta semana
    $sql_week = "SELECT COUNT(*) as total FROM consultas 
                 WHERE YEARWEEK(data_consulta, 1) = YEARWEEK(CURDATE(), 1) 
                 AND status IN ('agendado', 'concluido')";
    $stmt = $pdo->prepare($sql_week);
    $stmt->execute();
    $consultations_week = $stmt->fetch()['total'];
    
    // Faturamento mensal (exemplo com valor fixo por consulta)
    $sql_revenue = "SELECT COUNT(*) as total FROM consultas 
                    WHERE YEAR(data_consulta) = YEAR(CURDATE()) 
                    AND MONTH(data_consulta) = MONTH(CURDATE()) 
                    AND status = 'concluido'";
    $stmt = $pdo->prepare($sql_revenue);
    $stmt->execute();
    $consultas_concluidas = $stmt->fetch()['total'];
    $monthly_revenue = $consultas_concluidas * 150; // R$ 150 por consulta (exemplo)
    
    // Retornar dados em JSON
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'total_patients' => $total_patients,
        'consultations_today' => $consultations_today,
        'consultations_week' => $consultations_week,
        'monthly_revenue' => 'R$ ' . number_format($monthly_revenue, 2, ',', '.')
    ]);
    
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao buscar estatísticas: ' . $e->getMessage()
    ]);
}
?>