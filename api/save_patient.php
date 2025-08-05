<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['name'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $telefone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nascimento = $_POST['birthdate'] ?? null;
    $endereco = trim($_POST['address'] ?? '');
    $observacoes = trim($_POST['observations'] ?? '');
    
    // Validações
    if (empty($nome) || empty($cpf) || empty($telefone)) {
        echo json_encode(['success' => false, 'message' => 'Nome, CPF e telefone são obrigatórios!']);
        exit;
    }
    
    // Validar CPF
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) !== 11) {
        echo json_encode(['success' => false, 'message' => 'CPF deve ter 11 dígitos!']);
        exit;
    }
    
    // Formatar CPF
    $cpf_formatado = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    
    // Limpar telefone
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    $telefone_formatado = '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7, 4);
    
    try {
        // Verificar se CPF já existe
        $sql_check = "SELECT id FROM pacientes WHERE cpf = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$cpf_formatado]);
        
        if ($stmt_check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'CPF já cadastrado no sistema!']);
            exit;
        }
        
        // Inserir paciente
        $sql = "INSERT INTO pacientes (nome, cpf, telefone, email, data_nascimento, endereco, observacoes) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        $email = empty($email) ? null : $email;
        $nascimento = empty($nascimento) ? null : $nascimento;
        $endereco = empty($endereco) ? null : $endereco;
        $observacoes = empty($observacoes) ? null : $observacoes;
        
        if ($stmt->execute([$nome, $cpf_formatado, $telefone_formatado, $email, $nascimento, $endereco, $observacoes])) {
            echo json_encode(['success' => true, 'message' => 'Paciente cadastrado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar paciente']);
        }
        
    } catch(PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'CPF já cadastrado no sistema!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}
?>