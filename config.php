<?php
// config.php - Configuração do banco de dados e constantes

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'catholic_memories');
define('DB_USER', 'policia');
define('DB_PASS', 'Saopio22.20305');
define('DB_CHARSET', 'utf8mb4');

/*
$host     = 'localhost';
$dbname   = 'agendamentos';
$user     = 'policia';
$pass     = 'Saopio22.20305';
*/
// Configurações da aplicação
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Configurações de segurança
define('CSRF_TOKEN_NAME', 'csrf_token');

// Fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Função para conectar ao banco
function getConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('Erro na conexão com o banco de dados: ' . $e->getMessage());
        }
    }
    
    return $pdo;
}

// Função para gerar token CSRF
function generateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION[CSRF_TOKEN_NAME];
}

// Função para validar token CSRF
function validateCSRFToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

// Função para sanitizar dados
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Função para validar upload de imagem
function validateImageUpload($file) {
    $errors = [];
    
    // Verificar se o arquivo foi enviado
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => true, 'message' => 'Nenhum arquivo enviado'];
    }
    
    // Verificar erros de upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Erro no upload do arquivo'];
    }
    
    // Verificar tamanho do arquivo
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Arquivo muito grande. Máximo: ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB'];
    }
    
    // Verificar tipo do arquivo
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($fileInfo, $file['tmp_name']);
    finfo_close($fileInfo);
    
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mimeType, $allowedMimes)) {
        return ['success' => false, 'message' => 'Tipo de arquivo não permitido'];
    }
    
    // Verificar extensão
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return ['success' => false, 'message' => 'Extensão não permitida'];
    }
    
    return ['success' => true, 'message' => 'Arquivo válido'];
}
// Função para fazer upload de video

function uploadVideo($file) {
    $allowedTypes = ['video/mp4', 'video/webm', 'video/ogg', 'video/avi', 'video/quicktime'];
    $maxSize = 50 * 1024 * 1024; // 50MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Tipo de arquivo não permitido'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'Arquivo muito grande (máx 50MB)'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $destination = UPLOAD_DIR . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $filename];
    }
    
    return ['success' => false, 'message' => 'Erro ao mover arquivo'];
 }
 
 function deleteVideo($filename) {
    $filepath = UPLOAD_DIR . $filename;
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
 }

// Função para fazer upload de imagem
function uploadImage($file) {
    $validation = validateImageUpload($file);
    
    if (!$validation['success']) {
        return $validation;
    }
    
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => true, 'filename' => null];
    }
    
    // Criar diretório se não existir
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    
    // Gerar nome único para o arquivo
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = 'memory_' . time() . '_' . uniqid() . '.' . $extension;
    $filepath = UPLOAD_DIR . $filename;
    
    // Mover arquivo para o diretório de uploads
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'message' => 'Erro ao salvar arquivo'];
    }
}

// Função para deletar imagem
function deleteImage($filename) {
    if ($filename && file_exists(UPLOAD_DIR . $filename)) {
        return unlink(UPLOAD_DIR . $filename);
    }
    return true;
}

// Função para resposta JSON
function jsonResponse($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

// Função para redirecionar
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// Função para mostrar mensagem flash
function setFlashMessage($type, $message) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash_message'] = ['type' => $type, 'message' => $message];
}

// Função para obter mensagem flash
function getFlashMessage() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    
    return null;
}

// Função para debug (remover em produção)
function debug($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

// Inicializar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>