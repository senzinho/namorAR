<?php
// view_simple.php - Versão simplificada para testar renderização

require_once 'config.php';
require_once 'Memory.php';

// Instanciar modelo
$memory = new Memory();

// Obter todas as memórias
$result = $memory->getAll();
$memories = $result['success'] ? $result['data'] : [];

echo "<h1>🔍 View Simplificada - Teste de Renderização</h1>";

echo "<p><strong>Status:</strong> " . ($result['success'] ? '✅ Sucesso' : '❌ Erro') . "</p>";
echo "<p><strong>Total de memórias:</strong> " . count($memories) . "</p>";

if (!empty($memories)) {
    echo "<h2>📋 Lista de Memórias</h2>";
    
    foreach ($memories as $index => $mem) {
        echo "<div style='border: 2px solid #4a69bd; margin: 20px 0; padding: 20px; border-radius: 10px; background: #f8f9fa;'>";
        
        echo "<h3 style='color: #2c3e50;'>🎯 Memória #" . ($index + 1) . "</h3>";
        
        echo "<p><strong>ID:</strong> " . htmlspecialchars($mem['id']) . "</p>";
        echo "<p><strong>Data:</strong> " . htmlspecialchars($mem['date']) . "</p>";
        echo "<p><strong>Título:</strong> " . htmlspecialchars($mem['title']) . "</p>";
        echo "<p><strong>Descrição:</strong> " . nl2br(htmlspecialchars($mem['description'])) . "</p>";
        
        if (!empty($mem['verse'])) {
            echo "<p><strong>Versículo:</strong> \"" . htmlspecialchars($mem['verse']) . "\"</p>";
            if (!empty($mem['verse_reference'])) {
                echo "<p><strong>Referência:</strong> " . htmlspecialchars($mem['verse_reference']) . "</p>";
            }
        }
        
        if (!empty($mem['photo'])) {
            $photo_path = UPLOAD_DIR . $mem['photo'];
            echo "<p><strong>Foto:</strong> " . htmlspecialchars($mem['photo']);
            
            if (file_exists($photo_path)) {
                echo " ✅</p>";
                echo "<img src='" . htmlspecialchars($photo_path) . "' style='max-width: 200px; border-radius: 5px;' alt='Foto da memória'>";
            } else {
                echo " ❌ (arquivo não encontrado)</p>";
            }
        }
        
        echo "<p><strong>Criado em:</strong> " . $mem['created_at'] . "</p>";
        echo "<p><strong>Atualizado em:</strong> " . $mem['updated_at'] . "</p>";
        
        echo "</div>";
    }
    
} else {
    echo "<div style='border: 2px solid #e74c3c; margin: 20px 0; padding: 20px; border-radius: 10px; background: #ffe6e6;'>";
    echo "<h2>❌ Nenhuma memória encontrada</h2>";
    
    if (!$result['success']) {
        echo "<p><strong>Erro:</strong> " . htmlspecialchars($result['message']) . "</p>";
    } else {
        echo "<p>O banco retornou sucesso, mas o array está vazio.</p>";
        echo "<p><strong>Possíveis causas:</strong></p>";
        echo "<ul>";
        echo "<li>Não há memórias cadastradas no banco</li>";
        echo "<li>Query SQL não retornou resultados</li>";
        echo "<li>Problema na configuração do banco</li>";
        echo "</ul>";
    }
    
    echo "</div>";
}

echo "<hr>";

echo "<h2>🔧 Debug Info</h2>";
echo "<p><strong>Resultado completo do getAll():</strong></p>";
echo "<pre style='background: #f4f4f4; padding: 10px; border-radius: 5px;'>";
print_r($result);
echo "</pre>";

echo "<hr>";

echo "<h2>🎯 Ações</h2>";
echo "<p>";
echo "<a href='view_debug.php' style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>🔍 Debug Completo</a> ";
echo "<a href='view.php' style='background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>👀 View Original</a> ";
echo "<a href='edit.php' style='background: #ffc107; color: black; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>✏️ Adicionar Memória</a> ";
echo "<a href='test.php' style='background: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>🧪 Executar Testes</a>";
echo "</p>";
?>