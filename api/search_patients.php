<?php
require_once '../config/database.php';

if (isset($_POST['search'])) {
    $search = '%' . $_POST['search'] . '%';
    
    $sql = "SELECT * FROM pacientes 
            WHERE nome LIKE ? OR cpf LIKE ? OR telefone LIKE ? 
            ORDER BY nome LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$search, $search, $search]);
    
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($pacientes);
}
?>