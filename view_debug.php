<?php
// view_debug.php - View com debug para identificar problemas

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 DEBUG DA VIEW</h1>";

// Verificar se os arquivos existem
echo "<h2>1. Verificando Arquivos</h2>";
$required_files = ['config.php', 'Memory.php'];
foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "✅ $file encontrado<br>";
    } else {
        echo "❌ $file NÃO encontrado<br>";
    }
}

echo "<hr>";

// Tentar incluir arquivos
echo "<h2>2. Incluindo Arquivos</h2>";
try {
    require_once 'config.php';
    echo "✅ config.php incluído<br>";
} catch (Exception $e) {
    echo "❌ Erro ao incluir config.php: " . $e->getMessage() . "<br>";
    die();
}

try {
    require_once 'Memory.php';
    echo "✅ Memory.php incluído<br>";
} catch (Exception $e) {
    echo "❌ Erro ao incluir Memory.php: " . $e->getMessage() . "<br>";
    die();
}

echo "<hr>";

// Testar conexão
echo "<h2>3. Testando Conexão</h2>";
try {
    $pdo = getConnection();
    echo "✅ Conexão com banco OK<br>";
} catch (Exception $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . "<br>";
    die();
}

echo "<hr>";

// Instanciar Memory
echo "<h2>4. Instanciando Memory</h2>";
try {
    $memory = new Memory();
    echo "✅ Classe Memory instanciada<br>";
} catch (Exception $e) {
    echo "❌ Erro ao instanciar Memory: " . $e->getMessage() . "<br>";
    die();
}

echo "<hr>";

// Obter memórias
echo "<h2>5. Obtendo Memórias</h2>";
try {
    $result = $memory->getAll();
    
    echo "<strong>Resultado bruto do getAll():</strong><br>";
    echo "<pre>" . print_r($result, true) . "</pre>";
    
    if ($result['success']) {
        echo "✅ getAll() executado com sucesso<br>";
        echo "Total de memórias: " . count($result['data']) . "<br>";
        
        if (!empty($result['data'])) {
            echo "<h3>Primeira memória:</h3>";
            echo "<pre>" . print_r($result['data'][0], true) . "</pre>";
        } else {
            echo "⚠️ Array de memórias está vazio<br>";
        }
    } else {
        echo "❌ getAll() retornou erro: " . $result['message'] . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro ao obter memórias: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Verificar se $memories está sendo definido corretamente
echo "<h2>6. Simulando o que a View faz</h2>";

$memories = $result['success'] ? $result['data'] : [];

echo "Variável \$memories definida como:<br>";
echo "<pre>" . print_r($memories, true) . "</pre>";

echo "Count de \$memories: " . count($memories) . "<br>";
echo "Empty \$memories: " . (empty($memories) ? 'SIM' : 'NÃO') . "<br>";

echo "<hr>";

// Testar renderização
echo "<h2>7. Teste de Renderização</h2>";

if (empty($memories)) {
    echo "🔴 PROBLEMA: Array de memórias está vazio - por isso a view mostra 'Ainda não há memórias'<br>";
    echo "<br><strong>Possíveis causas:</strong><br>";
    echo "- Não há memórias no banco de dados<br>";
    echo "- Erro na query SQL<br>";
    echo "- Problema na conexão com banco<br>";
} else {
    echo "✅ Array de memórias tem dados - a view deveria renderizar<br>";
    
    echo "<h3>Simulando renderização da primeira memória:</h3>";
    $memory = $memories[0];
    
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
    echo "<h4>" . htmlspecialchars($memory['title']) . "</h4>";
    echo "<p><strong>Data:</strong> " . htmlspecialchars($memory['date']) . "</p>";
    echo "<p><strong>Descrição:</strong> " . nl2br(htmlspecialchars($memory['description'])) . "</p>";
    
    if (!empty($memory['verse'])) {
        echo "<p><strong>Versículo:</strong> " . htmlspecialchars($memory['verse']) . "</p>";
        echo "<p><strong>Referência:</strong> " . htmlspecialchars($memory['verse_reference'] ?? '') . "</p>";
    }
    
    if (!empty($memory['photo'])) {
        $photo_path = UPLOAD_DIR . htmlspecialchars($memory['photo']);
        echo "<p><strong>Foto:</strong> " . $photo_path;
        if (file_exists($photo_path)) {
            echo " ✅ (arquivo existe)";
        } else {
            echo " ❌ (arquivo NÃO existe)";
        }
        echo "</p>";
    }
    echo "</div>";
}

echo "<hr>";

// Verificar mensagens flash
echo "<h2>8. Verificando Flash Messages</h2>";
$flashMessage = getFlashMessage();
if ($flashMessage) {
    echo "Flash message encontrada: " . $flashMessage['type'] . " - " . $flashMessage['message'] . "<br>";
} else {
    echo "Nenhuma flash message<br>";
}

echo "<hr>";

echo "<h2>🎯 Conclusão</h2>";
echo "<p>Se chegou até aqui sem erros, o problema pode estar:</p>";
echo "<ul>";
echo "<li>No CSS que está escondendo as memórias</li>";
echo "<li>No JavaScript que pode estar interferindo</li>";
echo "<li>Na lógica condicional da view original</li>";
echo "</ul>";

echo "<br><strong>Próximos passos:</strong><br>";
echo "1. Se não há memórias no banco, adicione algumas pelo edit.php<br>";
echo "2. Se há memórias mas não aparecem, o problema é no HTML/CSS<br>";
echo "3. Verifique o console do navegador por erros JavaScript<br>";

echo "<br><a href='view.php'>🔗 Voltar para View Normal</a>";
echo " | <a href='edit.php'>🔗 Ir para Edit</a>";
echo " | <a href='test.php'>🔗 Executar Testes</a>";
?>