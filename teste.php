<?php
// test.php - Script para testar e debugar o sistema

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'Memory.php';

echo "<h1>🔧 Teste do Sistema de Memórias</h1>";

// Teste 1: Conexão com banco
echo "<h2>1. Testando Conexão com Banco</h2>";
try {
    $pdo = getConnection();
    echo "✅ Conexão com banco: <strong>OK</strong><br>";
    
    // Testar se a tabela existe
    $stmt = $pdo->query("DESCRIBE memories");
    $columns = $stmt->fetchAll();
    echo "✅ Tabela 'memories' encontrada com " . count($columns) . " colunas<br>";
    
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Teste 2: Classe Memory
echo "<h2>2. Testando Classe Memory</h2>";
try {
    $memory = new Memory();
    echo "✅ Classe Memory: <strong>OK</strong><br>";
    
    // Testar contagem de memórias
    $count = $memory->count();
    if ($count['success']) {
        echo "✅ Total de memórias no banco: <strong>{$count['total']}</strong><br>";
    } else {
        echo "❌ Erro ao contar memórias: " . $count['message'] . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro na classe Memory: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Teste 3: Criar memória de teste
echo "<h2>3. Testando Criação de Memória</h2>";
try {
    $memory = new Memory();
    
    $testData = [
        'date' => date('d/m/Y H:i'),
        'title' => 'Teste de Memória - ' . date('H:i:s'),
        'description' => 'Esta é uma memória de teste criada automaticamente para verificar se o sistema está funcionando.',
        'verse' => 'Teste de versículo bíblico',
        'verse_reference' => 'Teste 1:1'
    ];
    
    echo "Dados para teste:<br>";
    echo "<pre>" . print_r($testData, true) . "</pre>";
    
    $result = $memory->create($testData);
    
    if ($result['success']) {
        echo "✅ <strong>Memória criada com sucesso!</strong><br>";
        echo "ID da memória: " . $result['id'] . "<br>";
        
        // Testar busca da memória criada
        $getResult = $memory->getById($result['id']);
        if ($getResult['success']) {
            echo "✅ Memória recuperada do banco:<br>";
            echo "<pre>" . print_r($getResult['data'], true) . "</pre>";
        }
        
    } else {
        echo "❌ <strong>Erro ao criar memória:</strong> " . $result['message'] . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro no teste de criação: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Teste 4: Listar todas as memórias
echo "<h2>4. Testando Listagem de Memórias</h2>";
try {
    $memory = new Memory();
    $result = $memory->getAll();
    
    if ($result['success']) {
        echo "✅ Memórias recuperadas: <strong>{$result['count']}</strong><br>";
        
        if ($result['count'] > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Data</th><th>Título</th><th>Criado em</th></tr>";
            
            foreach ($result['data'] as $mem) {
                echo "<tr>";
                echo "<td>{$mem['id']}</td>";
                echo "<td>{$mem['date']}</td>";
                echo "<td>" . htmlspecialchars($mem['title']) . "</td>";
                echo "<td>{$mem['created_at']}</td>";
                echo "</tr>";
            }
            
            echo "</table>";
        }
        
    } else {
        echo "❌ Erro ao listar memórias: " . $result['message'] . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro no teste de listagem: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Teste 5: Configurações
echo "<h2>5. Verificando Configurações</h2>";
echo "- DB_HOST: " . DB_HOST . "<br>";
echo "- DB_NAME: " . DB_NAME . "<br>";
echo "- DB_USER: " . DB_USER . "<br>";
echo "- UPLOAD_DIR: " . UPLOAD_DIR . "<br>";
echo "- Diretório uploads existe: " . (is_dir(UPLOAD_DIR) ? '✅ SIM' : '❌ NÃO') . "<br>";
echo "- Diretório uploads é gravável: " . (is_writable(UPLOAD_DIR) ? '✅ SIM' : '❌ NÃO') . "<br>";

echo "<hr>";

// Teste 6: Token CSRF
echo "<h2>6. Testando Token CSRF</h2>";
$token = generateCSRFToken();
echo "Token gerado: " . $token . "<br>";
$validation = validateCSRFToken($token);
echo "Validação: " . ($validation ? '✅ OK' : '❌ ERRO') . "<br>";

echo "<hr>";

echo "<h2>🎯 Resumo dos Testes</h2>";
echo "<p>Se todos os testes acima mostraram ✅, seu sistema está funcionando corretamente!</p>";
echo "<p>Se algum teste mostrou ❌, verifique:</p>";
echo "<ul>";
echo "<li>Se o MySQL está rodando</li>";
echo "<li>Se as credenciais do banco estão corretas no config.php</li>";
echo "<li>Se o banco 'catholic_memories' foi criado</li>";
echo "<li>Se a pasta 'uploads' existe e tem permissões de escrita</li>";
echo "</ul>";

echo "<p><a href='view.php'>🔗 Ir para View</a> | <a href='edit.php'>🔗 Ir para Edit</a></p>";
?>