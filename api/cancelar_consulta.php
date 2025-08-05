<?php
require_once 'config/database.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        // Verificar se a consulta existe e pode ser cancelada
        $sql_check = "SELECT * FROM consultas WHERE id = ? AND status IN ('agendado')";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id]);
        $consulta = $stmt_check->fetch();
        
        if (!$consulta) {
            header('Location: pages/consultas.php?error=not_found');
            exit;
        }
        
        // Cancelar consulta
        $sql = "UPDATE consultas SET status = 'cancelado' WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$id])) {
            header('Location: pages/consultas.php?success=cancelada');
        } else {
            header('Location: pages/consultas.php?error=update_failed');
        }
        
    } catch(PDOException $e) {
        error_log("Erro ao cancelar consulta: " . $e->getMessage());
        header('Location: pages/consultas.php?error=database');
    }
} else {
    header('Location: pages/consultas.php?error=no_id');
}
exit;
?>