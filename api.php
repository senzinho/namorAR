<?php
// api.php - Controller para operações CRUD via API

require_once 'config.php';
require_once 'Memory.php';

// Configurar headers CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Responder a requisições OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Obter método HTTP e ação
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Instanciar modelo
$memory = new Memory();

// Roteamento das ações
switch ($method) {
    case 'GET':
        handleGetRequest($memory, $action);
        break;
        
    case 'POST':
        handlePostRequest($memory, $action);
        break;
        
    case 'PUT':
        handlePutRequest($memory, $action);
        break;
        
    case 'DELETE':
        handleDeleteRequest($memory, $action);
        break;
        
    default:
        jsonResponse(['success' => false, 'message' => 'Método não permitido'], 405);
}

// Lidar com requisições GET
function handleGetRequest($memory, $action) {
    switch ($action) {
        case 'all':
            $result = $memory->getAll();
            jsonResponse($result);
            break;
            
        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                jsonResponse(['success' => false, 'message' => 'ID obrigatório'], 400);
            }
            
            $result = $memory->getById($id);
            jsonResponse($result);
            break;
            
        case 'search':
            $term = $_GET['term'] ?? '';
            if (empty($term)) {
                jsonResponse(['success' => false, 'message' => 'Termo de busca obrigatório'], 400);
            }
            
            $result = $memory->search($term);
            jsonResponse($result);
            break;
            
        case 'search_advanced':
            handleSearchMemories($memory);
            break;
            
        case 'recent':
            $limit = $_GET['limit'] ?? 5;
            $result = $memory->getRecent($limit);
            jsonResponse($result);
            break;
            
        case 'count':
            $result = $memory->count();
            jsonResponse($result);
            break;
            
        case 'stats':
            $result = $memory->getStatistics();
            jsonResponse($result);
            break;
            
        case 'count_by_period':
            $period = $_GET['period'] ?? 'month';
            $result = $memory->countByPeriod($period);
            jsonResponse($result);
            break;
            
        case 'export':
            $result = $memory->exportToJson();
            if ($result['success']) {
                header('Content-Type: application/json');
                header('Content-Disposition: attachment; filename="memories_export_' . date('Y-m-d_H-i-s') . '.json"');
                echo $result['data'];
                exit;
            } else {
                jsonResponse($result, 500);
            }
            break;
            
        case 'backup':
            createBackup($memory);
            break;
            
        case 'docs':
            showApiDocumentation();
            break;
            
        default:
            jsonResponse(['success' => false, 'message' => 'Ação inválida'], 400);
    }
}

// Lidar com requisições POST
function handlePostRequest($memory, $action) {
    switch ($action) {
        case 'create':
            handleCreateMemory($memory);
            break;
            
        case 'import':
            handleImportMemories($memory);
            break;
            
        case 'bulk_upload':
            handleBulkUpload($memory);
            break;
            
        case 'duplicate':
            $id = $_POST['id'] ?? null;
            if (!$id) {
                jsonResponse(['success' => false, 'message' => 'ID obrigatório'], 400);
            }
            
            if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
                jsonResponse(['success' => false, 'message' => 'Token CSRF inválido'], 403);
            }
            
            $result = $memory->duplicate($id);
            jsonResponse($result);
            break;
            
        case 'reorder':
            handleReorder($memory);
            break;
            
        case 'clear_all':
            clearAllData($memory);
            break;
            
        default:
            jsonResponse(['success' => false, 'message' => 'Ação inválida'], 400);
    }
}

// Lidar com requisições PUT
function handlePutRequest($memory, $action) {
    switch ($action) {
        case 'update':
            handleUpdateMemory($memory);
            break;
            
        default:
            jsonResponse(['success' => false, 'message' => 'Ação inválida'], 400);
    }
}

// Lidar com requisições DELETE
function handleDeleteRequest($memory, $action) {
    switch ($action) {
        case 'delete':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                jsonResponse(['success' => false, 'message' => 'ID obrigatório'], 400);
            }
            
            $result = $memory->delete($id);
            jsonResponse($result);
            break;
            
        default:
            jsonResponse(['success' => false, 'message' => 'Ação inválida'], 400);
    }
}

// Criar memória
function handleCreateMemory($memory) {
    // Validar token CSRF
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        jsonResponse(['success' => false, 'message' => 'Token CSRF inválido'], 403);
    }
    
    // Sanitizar dados
    $data = [
        'date' => sanitizeInput($_POST['date'] ?? ''),
        'title' => sanitizeInput($_POST['title'] ?? ''),
        'description' => sanitizeInput($_POST['description'] ?? ''),
        'verse' => sanitizeInput($_POST['verse'] ?? ''),
        'verse_reference' => sanitizeInput($_POST['verse_reference'] ?? ''),
    ];
    
    // Validar dados
    $errors = $memory->validateData($data);
    if (!empty($errors)) {
        jsonResponse(['success' => false, 'message' => 'Dados inválidos', 'errors' => $errors], 400);
    }
    
    // Processar upload de imagem
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadResult = uploadImage($_FILES['photo']);
        if (!$uploadResult['success']) {
            jsonResponse(['success' => false, 'message' => $uploadResult['message']], 400);
        }
        $data['photo'] = $uploadResult['filename'];
    }
    
    // Criar memória
    $result = $memory->create($data);
    jsonResponse($result);
}

// Atualizar memória
function handleUpdateMemory($memory) {
    // Obter dados do corpo da requisição
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Se não for JSON, tentar $_POST
    if (!$input) {
        $input = $_POST;
    }
    
    $id = $input['id'] ?? $_GET['id'] ?? null;
    
    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'ID obrigatório'], 400);
    }
    
    // Validar token CSRF
    if (!validateCSRFToken($input['csrf_token'] ?? '')) {
        jsonResponse(['success' => false, 'message' => 'Token CSRF inválido'], 403);
    }
    
    // Sanitizar dados
    $data = [];
    $allowedFields = ['date', 'title', 'description', 'verse', 'verse_reference'];
    
    foreach ($allowedFields as $field) {
        if (isset($input[$field])) {
            $data[$field] = sanitizeInput($input[$field]);
        }
    }
    
    // Validar dados
    $errors = $memory->validateData($data);
    if (!empty($errors)) {
        jsonResponse(['success' => false, 'message' => 'Dados inválidos', 'errors' => $errors], 400);
    }
    
    // Processar upload de imagem se fornecida
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Obter memória atual para deletar foto antiga
        $currentMemory = $memory->getById($id);
        if ($currentMemory['success'] && $currentMemory['data']['photo']) {
            deleteImage($currentMemory['data']['photo']);
        }
        
        $uploadResult = uploadImage($_FILES['photo']);
        if (!$uploadResult['success']) {
            jsonResponse(['success' => false, 'message' => $uploadResult['message']], 400);
        }
        $data['photo'] = $uploadResult['filename'];
    }
    
    // Atualizar memória
    $result = $memory->update($id, $data);
    jsonResponse($result);
}

// Importar memórias
function handleImportMemories($memory) {
    // Validar token CSRF
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        jsonResponse(['success' => false, 'message' => 'Token CSRF inválido'], 403);
    }
    
    // Verificar se arquivo foi enviado
    if (!isset($_FILES['json_file']) || $_FILES['json_file']['error'] !== UPLOAD_ERR_OK) {
        jsonResponse(['success' => false, 'message' => 'Arquivo JSON obrigatório'], 400);
    }
    
    // Ler conteúdo do arquivo
    $jsonContent = file_get_contents($_FILES['json_file']['tmp_name']);
    
    if (!$jsonContent) {
        jsonResponse(['success' => false, 'message' => 'Erro ao ler arquivo'], 400);
    }
    
    // Importar dados
    $result = $memory->importFromJson($jsonContent);
    jsonResponse($result);
}

// Função para buscar memórias por filtros
function handleSearchMemories($memory) {
    $filters = [
        'term' => $_GET['term'] ?? '',
        'date_from' => $_GET['date_from'] ?? '',
        'date_to' => $_GET['date_to'] ?? '',
        'has_photo' => isset($_GET['has_photo']) ? (bool)$_GET['has_photo'] : null,
        'has_verse' => isset($_GET['has_verse']) ? (bool)$_GET['has_verse'] : null,
        'limit' => $_GET['limit'] ?? 50,
        'offset' => $_GET['offset'] ?? 0
    ];
    
    $result = $memory->searchWithFilters($filters);
    jsonResponse($result);
}

// Função para limpar dados
function clearAllData($memory) {
    // Validar token CSRF
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        jsonResponse(['success' => false, 'message' => 'Token CSRF inválido'], 403);
    }
    
    // Obter todas as memórias para deletar fotos
    $memories = $memory->getAll();
    if ($memories['success']) {
        foreach ($memories['data'] as $mem) {
            if ($mem['photo']) {
                deleteImage($mem['photo']);
            }
        }
    }
    
    // Executar limpeza
    $result = $memory->clearAll();
    jsonResponse($result);
}

// Função para fazer backup
function createBackup($memory) {
    $result = $memory->createBackup();
    
    if ($result['success']) {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="backup_memories_' . date('Y-m-d_H-i-s') . '.json"');
        echo $result['data'];
        exit;
    } else {
        jsonResponse($result, 500);
    }
}

// Função para validar estrutura do JSON
function validateJsonStructure($jsonData) {
    $data = json_decode($jsonData, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['valid' => false, 'message' => 'JSON inválido: ' . json_last_error_msg()];
    }
    
    if (!isset($data['memories']) || !is_array($data['memories'])) {
        return ['valid' => false, 'message' => 'Estrutura inválida: campo "memories" não encontrado'];
    }
    
    $requiredFields = ['date', 'title', 'description'];
    $optionalFields = ['verse', 'verse_reference', 'photo'];
    
    foreach ($data['memories'] as $index => $memory) {
        foreach ($requiredFields as $field) {
            if (!isset($memory[$field]) || empty($memory[$field])) {
                return ['valid' => false, 'message' => "Memória {$index}: campo '{$field}' é obrigatório"];
            }
        }
    }
    
    return ['valid' => true, 'message' => 'Estrutura válida', 'count' => count($data['memories'])];
}

// Função para processar upload em lote
function handleBulkUpload($memory) {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        jsonResponse(['success' => false, 'message' => 'Token CSRF inválido'], 403);
    }
    
    if (!isset($_FILES['files']) || !is_array($_FILES['files']['name'])) {
        jsonResponse(['success' => false, 'message' => 'Nenhum arquivo enviado'], 400);
    }
    
    $results = [];
    $successCount = 0;
    $errorCount = 0;
    
    for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
        $file = [
            'name' => $_FILES['files']['name'][$i],
            'type' => $_FILES['files']['type'][$i],
            'tmp_name' => $_FILES['files']['tmp_name'][$i],
            'error' => $_FILES['files']['error'][$i],
            'size' => $_FILES['files']['size'][$i]
        ];
        
        $uploadResult = uploadImage($file);
        
        if ($uploadResult['success']) {
            $successCount++;
            $results[] = [
                'filename' => $file['name'],
                'uploaded_as' => $uploadResult['filename'],
                'success' => true
            ];
        } else {
            $errorCount++;
            $results[] = [
                'filename' => $file['name'],
                'error' => $uploadResult['message'],
                'success' => false
            ];
        }
    }
    
    jsonResponse([
        'success' => true,
        'message' => "Upload concluído: {$successCount} sucessos, {$errorCount} erros",
        'results' => $results,
        'summary' => [
            'total' => count($_FILES['files']['name']),
            'success' => $successCount,
            'errors' => $errorCount
        ]
    ]);
}

// Função para reordenar memórias
function handleReorder($memory) {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        jsonResponse(['success' => false, 'message' => 'Token CSRF inválido'], 403);
    }
    
    $ids = $_POST['ids'] ?? [];
    
    if (!is_array($ids) || empty($ids)) {
        jsonResponse(['success' => false, 'message' => 'Array de IDs obrigatório'], 400);
    }
    
    $result = $memory->reorder($ids);
    jsonResponse($result);
}

// Tratamento de erros globais
function handleError($errno, $errstr, $errfile, $errline) {
    $error = [
        'success' => false,
        'message' => 'Erro interno do servidor',
        'debug' => [
            'error' => $errstr,
            'file' => $errfile,
            'line' => $errline
        ]
    ];
    
    jsonResponse($error, 500);
}

// Definir handler de erro
set_error_handler('handleError');

// Tratamento de exceções não capturadas
set_exception_handler(function($exception) {
    jsonResponse([
        'success' => false,
        'message' => 'Erro interno do servidor',
        'debug' => [
            'exception' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ]
    ], 500);
});

// Função para documentação da API
function showApiDocumentation() {
    $docs = [
        'title' => 'Catholic Memories API',
        'version' => '1.0.0',
        'description' => 'API para gerenciamento de memórias católicas',
        'base_url' => 'api.php',
        'authentication' => 'CSRF Token obrigatório para operações POST/PUT/DELETE',
        'endpoints' => [
            'GET' => [
                'all' => [
                    'url' => '/api.php?action=all',
                    'description' => 'Obter todas as memórias',
                    'parameters' => 'Nenhum'
                ],
                'get' => [
                    'url' => '/api.php?action=get&id={id}',
                    'description' => 'Obter memória por ID',
                    'parameters' => 'id (required)'
                ],
                'search' => [
                    'url' => '/api.php?action=search&term={term}',
                    'description' => 'Buscar memórias por termo',
                    'parameters' => 'term (required)'
                ],
                'search_advanced' => [
                    'url' => '/api.php?action=search_advanced&term={term}&has_photo={bool}&has_verse={bool}&limit={int}',
                    'description' => 'Busca avançada com filtros',
                    'parameters' => 'term, has_photo, has_verse, date_from, date_to, limit, offset'
                ],
                'recent' => [
                    'url' => '/api.php?action=recent&limit={limit}',
                    'description' => 'Obter memórias recentes',
                    'parameters' => 'limit (optional, default: 5)'
                ],
                'count' => [
                    'url' => '/api.php?action=count',
                    'description' => 'Contar total de memórias',
                    'parameters' => 'Nenhum'
                ],
                'stats' => [
                    'url' => '/api.php?action=stats',
                    'description' => 'Obter estatísticas completas',
                    'parameters' => 'Nenhum'
                ],
                'count_by_period' => [
                    'url' => '/api.php?action=count_by_period&period={period}',
                    'description' => 'Contar memórias por período',
                    'parameters' => 'period (optional: month, day)'
                ],
                'export' => [
                    'url' => '/api.php?action=export',
                    'description' => 'Exportar memórias em JSON',
                    'parameters' => 'Nenhum'
                ],
                'backup' => [
                    'url' => '/api.php?action=backup',
                    'description' => 'Criar backup completo',
                    'parameters' => 'Nenhum'
                ]
            ],
            'POST' => [
                'create' => [
                    'url' => '/api.php?action=create',
                    'description' => 'Criar nova memória',
                    'parameters' => 'csrf_token, date, title, description, verse, verse_reference, photo (file)'
                ],
                'import' => [
                    'url' => '/api.php?action=import',
                    'description' => 'Importar memórias de JSON',
                    'parameters' => 'csrf_token, json_file (file)'
                ],
                'duplicate' => [
                    'url' => '/api.php?action=duplicate',
                    'description' => 'Duplicar memória existente',
                    'parameters' => 'csrf_token, id'
                ],
                'bulk_upload' => [
                    'url' => '/api.php?action=bulk_upload',
                    'description' => 'Upload múltiplo de imagens',
                    'parameters' => 'csrf_token, files[] (multiple files)'
                ],
                'reorder' => [
                    'url' => '/api.php?action=reorder',
                    'description' => 'Reordenar memórias',
                    'parameters' => 'csrf_token, ids[] (array of IDs)'
                ],
                'clear_all' => [
                    'url' => '/api.php?action=clear_all',
                    'description' => 'Limpar todas as memórias',
                    'parameters' => 'csrf_token'
                ]
            ],
            'PUT' => [
                'update' => [
                    'url' => '/api.php?action=update&id={id}',
                    'description' => 'Atualizar memória existente',
                    'parameters' => 'csrf_token, date, title, description, verse, verse_reference, photo (file)'
                ]
            ],
            'DELETE' => [
                'delete' => [
                    'url' => '/api.php?action=delete&id={id}',
                    'description' => 'Deletar memória',
                    'parameters' => 'id (required)'
                ]
            ]
        ],
        'data_structure' => [
            'memory' => [
                'id' => 'integer (auto-generated)',
                'date' => 'string (required)',
                'title' => 'string (required)',
                'description' => 'text (required)',
                'verse' => 'text (optional)',
                'verse_reference' => 'string (optional)',
                'photo' => 'string (optional)',
                'created_at' => 'timestamp (auto-generated)',
                'updated_at' => 'timestamp (auto-generated)'
            ]
        ],
        'file_upload' => [
            'supported_formats' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'max_size' => '5MB',
            'upload_directory' => 'uploads/'
        ],
        'response_format' => [
            'success' => [
                'success' => true,
                'data' => '...',
                'message' => 'Success message'
            ],
            'error' => [
                'success' => false,
                'message' => 'Error message',
                'errors' => 'Validation errors (if applicable)'
            ]
        ],
        'examples' => [
            'create_memory' => [
                'method' => 'POST',
                'url' => '/api.php?action=create',
                'data' => [
                    'csrf_token' => 'generated_token',
                    'date' => '15 de Julho de 2024',
                    'title' => 'Nosso primeiro encontro',
                    'description' => 'Um momento especial...',
                    'verse' => 'O amor é paciente...',
                    'verse_reference' => '1 Coríntios 13:4'
                ]
            ],
            'search_memories' => [
                'method' => 'GET',
                'url' => '/api.php?action=search&term=amor',
                'response' => [
                    'success' => true,
                    'data' => ['...memories matching "amor"...']
                ]
            ]
        ]
    ];
    
    header('Content-Type: application/json');
    echo json_encode($docs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Verificar se é solicitação de documentação
if (isset($_GET['docs']) || (isset($_GET['action']) && $_GET['action'] === 'docs')) {
    showApiDocumentation();
}

// Log de requisições (opcional para debug)
function logRequest() {
    $log = [
        'timestamp' => date('Y-m-d H:i:s'),
        'method' => $_SERVER['REQUEST_METHOD'],
        'action' => $_GET['action'] ?? 'none',
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];
    
    // Descomente para ativar log
    // file_put_contents('api_log.json', json_encode($log) . "\n", FILE_APPEND);
}

// Executar log da requisição
logRequest();
?>