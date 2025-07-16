<?php
require_once 'config.php';

class Memory {
    private $db;
    
    public function __construct() {
        $this->db = getConnection();
    }
    
    /**
     * Criar nova memória
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO memories (date, title, description, verse, verse_reference, photo, video, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $data['date'],
                $data['title'],
                $data['description'],
                $data['verse'] ?? null,
                $data['verse_reference'] ?? null,
                $data['photo'] ?? null,
                $data['video'] ?? null
            ]);
            
            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Memória criada com sucesso!',
                    'id' => $this->db->lastInsertId()
                ];
            }
            
            return ['success' => false, 'message' => 'Erro ao criar memória'];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro no banco: ' . $e->getMessage()];
        }
    }
    
    /**
     * Atualizar memória existente
     */
    public function update($id, $data) {
        try {
            // Verificar se a memória existe
            $existingMemory = $this->getById($id);
            if (!$existingMemory['success']) {
                return ['success' => false, 'message' => 'Memória não encontrada'];
            }
            
            // Preparar campos para atualização
            $fields = [];
            $values = [];
            
            if (isset($data['date'])) {
                $fields[] = 'date = ?';
                $values[] = $data['date'];
            }
            
            if (isset($data['title'])) {
                $fields[] = 'title = ?';
                $values[] = $data['title'];
            }
            
            if (isset($data['description'])) {
                $fields[] = 'description = ?';
                $values[] = $data['description'];
            }
            
            if (isset($data['verse'])) {
                $fields[] = 'verse = ?';
                $values[] = $data['verse'];
            }
            
            if (isset($data['verse_reference'])) {
                $fields[] = 'verse_reference = ?';
                $values[] = $data['verse_reference'];
            }
            
            if (isset($data['photo'])) {
                $fields[] = 'photo = ?';
                $values[] = $data['photo'];
            }
            
            if (isset($data['video'])) {
                $fields[] = 'video = ?';
                $values[] = $data['video'];
            }
            
            $fields[] = 'updated_at = NOW()';
            $values[] = $id;
            
            $sql = "UPDATE memories SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($values);
            
            if ($result) {
                return ['success' => true, 'message' => 'Memória atualizada com sucesso!'];
            }
            
            return ['success' => false, 'message' => 'Erro ao atualizar memória'];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro no banco: ' . $e->getMessage()];
        }
    }
    
    /**
     * Deletar memória
     */
    public function delete($id) {
        try {
            // Obter dados da memória antes de deletar para remover arquivos
            $memory = $this->getById($id);
            if ($memory['success']) {
                $memoryData = $memory['data'];
                
                // Deletar arquivos associados
                if (!empty($memoryData['photo'])) {
                    deleteImage($memoryData['photo']);
                }
                
                if (!empty($memoryData['video'])) {
                    deleteVideo($memoryData['video']);
                }
            }
            
            $sql = "DELETE FROM memories WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$id]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Memória deletada com sucesso!'];
            }
            
            return ['success' => false, 'message' => 'Erro ao deletar memória'];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro no banco: ' . $e->getMessage()];
        }
    }
    
    /**
     * Obter memória por ID
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM memories WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            $memory = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($memory) {
                return ['success' => true, 'data' => $memory];
            }
            
            return ['success' => false, 'message' => 'Memória não encontrada'];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro no banco: ' . $e->getMessage()];
        }
    }
    
    /**
     * Obter todas as memórias
     */
    public function getAll($orderBy = 'created_at', $orderDirection = 'DESC') {
        try {
            $allowedColumns = ['id', 'date', 'title', 'created_at', 'updated_at'];
            $allowedDirections = ['ASC', 'DESC'];
            
            if (!in_array($orderBy, $allowedColumns)) {
                $orderBy = 'created_at';
            }
            
            if (!in_array($orderDirection, $allowedDirections)) {
                $orderDirection = 'DESC';
            }
            
            $sql = "SELECT * FROM memories ORDER BY {$orderBy} {$orderDirection}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            $memories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return ['success' => true, 'data' => $memories];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro no banco: ' . $e->getMessage()];
        }
    }
    
    /**
     * Buscar memórias por termo
     */
    public function search($term) {
        try {
            $sql = "SELECT * FROM memories 
                    WHERE title LIKE ? 
                    OR description LIKE ? 
                    OR verse LIKE ? 
                    OR date LIKE ?
                    ORDER BY created_at DESC";
            
            $searchTerm = "%{$term}%";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
            
            $memories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return ['success' => true, 'data' => $memories];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro no banco: ' . $e->getMessage()];
        }
    }
    
    /**
     * Obter memórias por período
     */
    public function getByDateRange($startDate, $endDate) {
        try {
            $sql = "SELECT * FROM memories 
                    WHERE STR_TO_DATE(date, '%d de %M de %Y') BETWEEN ? AND ?
                    ORDER BY STR_TO_DATE(date, '%d de %M de %Y') DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$startDate, $endDate]);
            
            $memories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return ['success' => true, 'data' => $memories];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro no banco: ' . $e->getMessage()];
        }
    }
    
    /**
     * Contar total de memórias
     */
    public function getCount() {
        try {
            $sql = "SELECT COUNT(*) as total FROM memories";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return ['success' => true, 'data' => $result['total']];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro no banco: ' . $e->getMessage()];
        }
    }
    
    /**
     * Obter estatísticas
     */
    public function getStatistics() {
        try {
            $stats = [];
            
            // Total de memórias
            $countResult = $this->getCount();
            $stats['total_memories'] = $countResult['success'] ? $countResult['data'] : 0;
            
            // Memórias com fotos
            $sql = "SELECT COUNT(*) as total FROM memories WHERE photo IS NOT NULL AND photo != ''";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['memories_with_photos'] = $result['total'];
            
            // Memórias com vídeos
            $sql = "SELECT COUNT(*) as total FROM memories WHERE video IS NOT NULL AND video != ''";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['memories_with_videos'] = $result['total'];
            
            // Memórias com versículos
            $sql = "SELECT COUNT(*) as total FROM memories WHERE verse IS NOT NULL AND verse != ''";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['memories_with_verses'] = $result['total'];
            
            // Primeira memória
            $sql = "SELECT date FROM memories ORDER BY created_at ASC LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['first_memory_date'] = $result ? $result['date'] : null;
            
            // Última memória
            $sql = "SELECT date FROM memories ORDER BY created_at DESC LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['last_memory_date'] = $result ? $result['date'] : null;
            
            return ['success' => true, 'data' => $stats];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro no banco: ' . $e->getMessage()];
        }
    }
    
    /**
     * Validar dados da memória
     */
    public function validateData($data) {
        $errors = [];
        
        // Validar data
        if (empty($data['date'])) {
            $errors[] = 'Data é obrigatória';
        }
        
        // Validar título
        if (empty($data['title'])) {
            $errors[] = 'Título é obrigatório';
        } elseif (strlen($data['title']) > 255) {
            $errors[] = 'Título muito longo (máx 255 caracteres)';
        }
        
        // Validar descrição
        if (empty($data['description'])) {
            $errors[] = 'Descrição é obrigatória';
        } elseif (strlen($data['description']) > 2000) {
            $errors[] = 'Descrição muito longa (máx 2000 caracteres)';
        }
        
        // Validar versículo (opcional)
        if (!empty($data['verse']) && strlen($data['verse']) > 1000) {
            $errors[] = 'Versículo muito longo (máx 1000 caracteres)';
        }
        
        // Validar referência do versículo (opcional)
        if (!empty($data['verse_reference']) && strlen($data['verse_reference']) > 100) {
            $errors[] = 'Referência do versículo muito longa (máx 100 caracteres)';
        }
        
        return $errors;
    }
    
    /**
     * Exportar memórias para JSON
     */
    public function exportToJson() {
        try {
            $result = $this->getAll();
            
            if (!$result['success']) {
                return $result;
            }
            
            $exportData = [
                'export_date' => date('Y-m-d H:i:s'),
                'total_memories' => count($result['data']),
                'memories' => $result['data']
            ];
            
            $json = json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            
            if ($json === false) {
                return ['success' => false, 'message' => 'Erro ao gerar JSON'];
            }
            
            return ['success' => true, 'data' => $json];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erro na exportação: ' . $e->getMessage()];
        }
    }
    
    /**
     * Importar memórias do JSON
     */
    public function importFromJson($jsonContent) {
        try {
            $data = json_decode($jsonContent, true);
            
            if ($data === null) {
                return ['success' => false, 'message' => 'JSON inválido'];
            }
            
            if (!isset($data['memories']) || !is_array($data['memories'])) {
                return ['success' => false, 'message' => 'Formato de JSON inválido'];
            }
            
            $imported = 0;
            $errors = 0;
            
            foreach ($data['memories'] as $memoryData) {
                // Remover campos de sistema
                unset($memoryData['id']);
                unset($memoryData['created_at']);
                unset($memoryData['updated_at']);
                
                // Validar dados
                $validationErrors = $this->validateData($memoryData);
                if (!empty($validationErrors)) {
                    $errors++;
                    continue;
                }
                
                // Criar memória
                $result = $this->create($memoryData);
                if ($result['success']) {
                    $imported++;
                } else {
                    $errors++;
                }
            }
            
            $message = "Importação concluída: {$imported} memórias importadas";
            if ($errors > 0) {
                $message .= ", {$errors} erros";
            }
            
            return ['success' => true, 'message' => $message];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erro na importação: ' . $e->getMessage()];
        }
    }
    
    /**
     * Criar backup das memórias
     */
    public function createBackup() {
        try {
            $exportResult = $this->exportToJson();
            
            if (!$exportResult['success']) {
                return $exportResult;
            }
            
            $backupDir = 'backups/';
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $filename = 'backup_memories_' . date('Y-m-d_H-i-s') . '.json';
            $filepath = $backupDir . $filename;
            
            if (file_put_contents($filepath, $exportResult['data'])) {
                return [
                    'success' => true, 
                    'message' => 'Backup criado com sucesso!',
                    'filename' => $filename
                ];
            }
            
            return ['success' => false, 'message' => 'Erro ao salvar backup'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erro ao criar backup: ' . $e->getMessage()];
        }
    }
    
    /**
     * Limpar memórias antigas (opcional)
     */
    public function cleanOldMemories($days = 365) {
        try {
            $sql = "DELETE FROM memories WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$days]);
            
            $deletedCount = $stmt->rowCount();
            
            return [
                'success' => true, 
                'message' => "Limpeza concluída: {$deletedCount} memórias antigas removidas"
            ];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro na limpeza: ' . $e->getMessage()];
        }
    }
}