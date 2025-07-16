<?php
// setup.php - Script para configurar o sistema automaticamente

echo "<h1>🚀 Configuração do Sistema</h1>";

// Criar pasta uploads
if (!is_dir('uploads')) {
    if (mkdir('uploads', 0755, true)) {
        echo "✅ Pasta 'uploads' criada com sucesso!<br>";
    } else {
        echo "❌ Erro ao criar pasta 'uploads'<br>";
    }
} else {
    echo "✅ Pasta 'uploads' já existe<br>";
}

// Verificar permissões
if (is_writable('uploads')) {
    echo "✅ Pasta 'uploads' tem permissões de escrita<br>";
} else {
    echo "❌ Pasta 'uploads' não tem permissões de escrita<br>";
    echo "Execute: chmod 755 uploads<br>";
}

// Criar arquivo .htaccess para uploads
$htaccess_content = "# Proteger pasta uploads
Options -Indexes

# Permitir apenas imagens
<FilesMatch \"\.(jpg|jpeg|png|gif|webp)$\">
    Order allow,deny
    Allow from all
</FilesMatch>

# Negar acesso a outros arquivos
<FilesMatch \".*\">
    Order deny,allow
    Deny from all
</FilesMatch>";

if (!file_exists('uploads/.htaccess')) {
    if (file_put_contents('uploads/.htaccess', $htaccess_content)) {
        echo "✅ Arquivo .htaccess criado em uploads/<br>";
    } else {
        echo "❌ Erro ao criar .htaccess<br>";
    }
} else {
    echo "✅ Arquivo .htaccess já existe em uploads/<br>";
}

// Teste de escrita
$test_file = 'uploads/test.txt';
if (file_put_contents($test_file, 'teste')) {
    echo "✅ Teste de escrita na pasta uploads: OK<br>";
    unlink($test_file); // Deletar arquivo de teste
} else {
    echo "❌ Erro no teste de escrita na pasta uploads<br>";
}

echo "<hr>";
echo "<h2>🎯 Sistema Configurado!</h2>";
echo "<p>Agora você pode:</p>";
echo "<ul>";
echo "<li><a href='test.php'>🔧 Executar testes novamente</a></li>";
echo "<li><a href='view.php'>👀 Ver as memórias</a></li>";
echo "<li><a href='edit.php'>✏️ Editar memórias</a></li>";
echo "</ul>";
?>