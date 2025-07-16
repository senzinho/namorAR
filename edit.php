<?php
require_once 'config.php';
require_once 'Memory.php';

// Instanciar modelo
$memory = new Memory();

// Processar formulários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Token de segurança inválido!');
        redirect('edit.php');
    }

    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            handleCreate($memory);
            break;
        case 'update':
            handleUpdate($memory);
            break;
        case 'delete':
            handleDelete($memory);
            break;
        case 'import':
            handleImport($memory);
            break;
    }
}

// Funções para processar ações
function handleCreate($memory) {
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
        setFlashMessage('error', 'Dados inválidos: ' . implode(', ', $errors));
        redirect('edit.php');
    }
    
    // Processar upload de imagem
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadResult = uploadImage($_FILES['photo']);
        if (!$uploadResult['success']) {
            setFlashMessage('error', 'Erro no upload da imagem: ' . $uploadResult['message']);
            redirect('edit.php');
        }
        $data['photo'] = $uploadResult['filename'];
    }
    
    // Processar upload de vídeo
    if (isset($_FILES['video']) && $_FILES['video']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadResult = uploadVideo($_FILES['video']);
        if (!$uploadResult['success']) {
            setFlashMessage('error', 'Erro no upload do vídeo: ' . $uploadResult['message']);
            redirect('edit.php');
        }
        $data['video'] = $uploadResult['filename'];
    }
    
    // Criar memória
    $result = $memory->create($data);
    if ($result['success']) {
        setFlashMessage('success', 'Memória criada com sucesso!');
    } else {
        setFlashMessage('error', 'Erro ao criar memória: ' . $result['message']);
    }
    
    redirect('edit.php');
}

function handleUpdate($memory) {
    $id = $_POST['id'] ?? '';
    
    if (!$id) {
        setFlashMessage('error', 'ID da memória obrigatório!');
        redirect('edit.php');
    }
    
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
        setFlashMessage('error', 'Dados inválidos: ' . implode(', ', $errors));
        redirect('edit.php');
    }
    
    // Obter memória atual para deletar arquivos antigos se necessário
    $currentMemory = $memory->getById($id);
    
    // Processar upload de imagem
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Deletar foto antiga se existir
        if ($currentMemory['success'] && $currentMemory['data']['photo']) {
            deleteImage($currentMemory['data']['photo']);
        }
        
        $uploadResult = uploadImage($_FILES['photo']);
        if (!$uploadResult['success']) {
            setFlashMessage('error', 'Erro no upload da imagem: ' . $uploadResult['message']);
            redirect('edit.php');
        }
        $data['photo'] = $uploadResult['filename'];
    }
    
    // Processar upload de vídeo
    if (isset($_FILES['video']) && $_FILES['video']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Deletar vídeo antigo se existir
        if ($currentMemory['success'] && $currentMemory['data']['video']) {
            deleteVideo($currentMemory['data']['video']);
        }
        
        $uploadResult = uploadVideo($_FILES['video']);
        if (!$uploadResult['success']) {
            setFlashMessage('error', 'Erro no upload do vídeo: ' . $uploadResult['message']);
            redirect('edit.php');
        }
        $data['video'] = $uploadResult['filename'];
    }
    
    // Atualizar memória
    $result = $memory->update($id, $data);
    if ($result['success']) {
        setFlashMessage('success', 'Memória atualizada com sucesso!');
    } else {
        setFlashMessage('error', 'Erro ao atualizar memória: ' . $result['message']);
    }
    
    redirect('edit.php');
}

function handleDelete($memory) {
    $id = $_POST['id'] ?? '';
    
    if (!$id) {
        setFlashMessage('error', 'ID da memória obrigatório!');
        redirect('edit.php');
    }
    
    $result = $memory->delete($id);
    if ($result['success']) {
        setFlashMessage('success', 'Memória deletada com sucesso!');
    } else {
        setFlashMessage('error', 'Erro ao deletar memória: ' . $result['message']);
    }
    
    redirect('edit.php');
}

function handleImport($memory) {
    if (!isset($_FILES['json_file']) || $_FILES['json_file']['error'] !== UPLOAD_ERR_OK) {
        setFlashMessage('error', 'Arquivo JSON obrigatório!');
        redirect('edit.php');
    }
    
    $jsonContent = file_get_contents($_FILES['json_file']['tmp_name']);
    if (!$jsonContent) {
        setFlashMessage('error', 'Erro ao ler arquivo JSON!');
        redirect('edit.php');
    }
    
    $result = $memory->importFromJson($jsonContent);
    if ($result['success']) {
        setFlashMessage('success', $result['message']);
    } else {
        setFlashMessage('error', 'Erro na importação: ' . $result['message']);
    }
    
    redirect('edit.php');
}

// Obter todas as memórias do BANCO
$result = $memory->getAll();
$memories = $result['success'] ? $result['data'] : [];

// Obter memória para edição se solicitado
$editMemory = null;
if (isset($_GET['edit'])) {
    $editResult = $memory->getById($_GET['edit']);
    if ($editResult['success']) {
        $editMemory = $editResult['data'];
    }
}

// Obter mensagem flash
$flashMessage = getFlashMessage();

// Gerar token CSRF
$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Memórias ✏️</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            min-height: 100vh;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .nav-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .nav-btn {
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
        }

        .nav-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .nav-btn.active {
            background: linear-gradient(45deg, #ffd700, #ff6b35);
            color: white;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 40px 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header h1 {
            font-size: 2.5em;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 10px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1em;
        }

        .flash-message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .flash-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .flash-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .actions-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .btn-primary {
            background: linear-gradient(45deg, #ffd700, #ff6b35);
            color: white;
        }

        .btn-success {
            background: linear-gradient(45deg, #2ecc71, #27ae60);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-info {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .memories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .memory-item {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .memory-item:hover {
            transform: translateY(-5px);
        }

        .memory-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .memory-date-badge {
            background: linear-gradient(45deg, #ffd700, #ff6b35);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
        }

        .memory-actions {
            display: flex;
            gap: 5px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 15px;
        }

        .memory-title {
            font-size: 1.3em;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .memory-description {
            color: #555;
            line-height: 1.4;
            margin-bottom: 10px;
        }

        .memory-verse {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            border-left: 3px solid #ffd700;
            font-style: italic;
            font-size: 0.9em;
            color: #666;
        }

        .memory-photo {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin: 10px 0;
        }

        .memory-video {
            width: 100%;
            max-height: 200px;
            border-radius: 8px;
            margin: 10px 0;
        }

        .media-container {
            display: flex;
            gap: 10px;
            align-items: center;
            margin: 10px 0;
        }

        .media-item {
            flex: 1;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 40px;
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
            font-size: 1.8em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #ffd700;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-display {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border: 2px dashed #e0e0e0;
            border-radius: 8px;
            transition: border-color 0.3s ease;
            cursor: pointer;
        }

        .file-input-display:hover {
            border-color: #ffd700;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .media-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 10px 0;
        }

        .media-grid .memory-photo,
        .media-grid .memory-video {
            margin: 0;
        }

        @media (max-width: 768px) {
            .memories-grid {
                grid-template-columns: 1fr;
            }
            
            .actions-bar {
                flex-direction: column;
            }

            .media-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-bar">
            <a href="view.php" class="nav-btn">👀 Visualizar</a>
            <a href="edit.php" class="nav-btn active">✏️ Editar</a>
        </div>

        <div class="header">
            <h1>Gerenciar Memórias ✏️</h1>
            <p>Adicione, edite e organize suas memórias de amor com fotos e vídeos</p>
        </div>

        <?php if ($flashMessage): ?>
            <div class="flash-message flash-<?php echo $flashMessage['type']; ?>">
                <?php echo htmlspecialchars($flashMessage['message']); ?>
            </div>
        <?php endif; ?>

        <div class="actions-bar">
            <button class="btn btn-primary" onclick="openAddModal()">➕ Nova Memória</button>
            <button class="btn btn-success" onclick="exportToJson()">💾 Exportar JSON</button>
            <label for="jsonFileInput" class="btn btn-info">📁 Importar JSON</label>
            <input type="file" id="jsonFileInput" accept=".json" style="display: none;" onchange="importJson(event)">
        </div>

        <div class="memories-grid">
            <?php if (empty($memories)): ?>
                <div style="grid-column: 1 / -1; text-align: center; color: white; padding: 40px;">
                    <h3>Nenhuma memória cadastrada ainda 💝</h3>
                    <p>Clique em "Nova Memória" para começar!</p>
                </div>
            <?php else: ?>
                <?php foreach ($memories as $mem): ?>
                    <div class="memory-item">
                        <div class="memory-header">
                            <div class="memory-date-badge"><?php echo htmlspecialchars($mem['date']); ?></div>
                            <div class="memory-actions">
                                <button class="btn btn-info btn-sm" onclick="editMemory(<?php echo $mem['id']; ?>, '<?php echo htmlspecialchars($mem['date']); ?>', '<?php echo htmlspecialchars($mem['title']); ?>', '<?php echo htmlspecialchars($mem['description']); ?>', '<?php echo htmlspecialchars($mem['verse'] ?? ''); ?>', '<?php echo htmlspecialchars($mem['verse_reference'] ?? ''); ?>')">✏️</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteMemory(<?php echo $mem['id']; ?>)">🗑️</button>
                            </div>
                        </div>
                        <div class="memory-title"><?php echo htmlspecialchars($mem['title']); ?></div>
                        <div class="memory-description"><?php echo nl2br(htmlspecialchars($mem['description'])); ?></div>
                        
                        <?php if (!empty($mem['verse'])): ?>
                            <div class="memory-verse">
                                "<?php echo htmlspecialchars($mem['verse']); ?>"
                                <?php if (!empty($mem['verse_reference'])): ?>
                                    <br><small><?php echo htmlspecialchars($mem['verse_reference']); ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($mem['photo']) || !empty($mem['video'])): ?>
                            <div class="media-grid">
                                <?php if (!empty($mem['photo'])): ?>
                                    <img src="<?php echo UPLOAD_DIR . htmlspecialchars($mem['photo']); ?>" 
                                         alt="<?php echo htmlspecialchars($mem['title']); ?>" 
                                         class="memory-photo"
                                         onerror="this.style.display='none'">
                                <?php endif; ?>
                                
                                <?php if (!empty($mem['video'])): ?>
                                    <video controls class="memory-video">
                                        <source src="<?php echo UPLOAD_DIR . htmlspecialchars($mem['video']); ?>" type="video/mp4">
                                        <source src="<?php echo UPLOAD_DIR . htmlspecialchars($mem['video']); ?>" type="video/webm">
                                        <source src="<?php echo UPLOAD_DIR . htmlspecialchars($mem['video']); ?>" type="video/ogg">
                                        Seu navegador não suporta o elemento de vídeo.
                                    </video>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Memória -->
    <div class="modal" id="memoryModal">
        <div class="modal-content">
            <h2 id="modalTitle">Adicionar Nova Memória 💖</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="id" id="memoryId" value="">
                
                <div class="form-group">
                    <label for="date">Data:</label>
                    <input type="text" id="date" name="date" placeholder="Ex: 15 de Junho de 2024" required>
                </div>
                
                <div class="form-group">
                    <label for="title">Título:</label>
                    <input type="text" id="title" name="title" placeholder="Ex: Nosso primeiro encontro" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Descrição:</label>
                    <textarea id="description" name="description" placeholder="Descreva este momento especial..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="verse">Versículo Bíblico:</label>
                    <input type="text" id="verse" name="verse" placeholder="Ex: Porque Deus amou o mundo...">
                </div>
                
                <div class="form-group">
                    <label for="verse_reference">Referência do Versículo:</label>
                    <input type="text" id="verse_reference" name="verse_reference" placeholder="Ex: João 3:16">
                </div>
                
                <div class="form-group">
                    <label for="photo">Foto:</label>
                    <input type="file" id="photo" name="photo" accept="image/*">
                    <small style="color: #666; margin-top: 5px; display: block;">
                        Formatos aceitos: JPG, PNG, GIF, WEBP (máx 5MB)
                    </small>
                </div>
                
                <div class="form-group">
                    <label for="video">Vídeo:</label>
                    <input type="file" id="video" name="video" accept="video/*">
                    <small style="color: #666; margin-top: 5px; display: block;">
                        Formatos aceitos: MP4, WEBM, OGG, AVI, MOV (máx 50MB)
                    </small>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">💾 Salvar Memória</button>
                    <button type="button" class="btn btn-danger" onclick="closeModal()">❌ Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Adicionar Nova Memória 💖';
            document.getElementById('formAction').value = 'create';
            document.getElementById('memoryId').value = '';
            document.querySelector('form').reset();
            document.getElementById('memoryModal').style.display = 'block';
        }

        function editMemory(id, date, title, description, verse, verseRef) {
            document.getElementById('modalTitle').textContent = 'Editar Memória ✏️';
            document.getElementById('formAction').value = 'update';
            document.getElementById('memoryId').value = id;
            document.getElementById('date').value = date;
            document.getElementById('title').value = title;
            document.getElementById('description').value = description;
            document.getElementById('verse').value = verse;
            document.getElementById('verse_reference').value = verseRef;
            document.getElementById('memoryModal').style.display = 'block';
        }

        function deleteMemory(id) {
            if (confirm('Tem certeza que deseja deletar esta memória? Esta ação não pode ser desfeita.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function closeModal() {
            document.getElementById('memoryModal').style.display = 'none';
        }

        function exportToJson() {
            window.location.href = 'api.php?action=export';
        }

        function importJson(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('csrf_token', '<?php echo $csrfToken; ?>');
            formData.append('action', 'import');
            formData.append('json_file', file);

            const form = document.createElement('form');
            form.method = 'POST';
            form.enctype = 'multipart/form-data';
            
            for (let [key, value] of formData.entries()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }
            
            document.body.appendChild(form);
            form.submit();
        }

        // Fechar modal clicando fora
        window.onclick = function(event) {
            const modal = document.getElementById('memoryModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>