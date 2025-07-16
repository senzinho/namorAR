<?php
// Memory.php - Versão final corrigida

class Memory {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getConnection();
    }
    
    // Criar nova memória
    public function create($data) {
        try {
            $sql = "INSERT INTO memories (date, title, description, verse, verse_reference, photo) 
                    VALUES (:date, :title, :description, :verse, :verse_reference, :photo)";
            
            $stmt = $this->pdo->prepare($sql);
            
            $result = $stmt->execute([
                ':date' => $data['date'] ?? '',
                ':title' => $data['title'] ?? '',
                ':description' => $data['description'] ?? '',
                ':verse' => $data['verse'] ?? null,
                ':verse_reference' => $data['verse_reference'] ?? null,
                ':photo' => $data['photo'] ?? null
            ]);
            
            if ($result) {
                $id = $this->pdo->lastInsertId();
                
                return [
                    'success' => true,
                    'id' => $id,
                    'message' => 'Memória criada com sucesso!'
                ];
            }
            
            return ['success' => false, 'message' => 'Erro ao criar memória'];
            
        } catch (PDOException $e) {
            error_log("Erro SQL: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()];
        }
    }
    
    // Obter todas as memórias - CORRIGIDO
    public function getAll() {
        try {
            $sql = "SELECT * FROM memories ORDER BY created_at ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            $memories = $stmt->fetchAll();
            
            return [
                'success' => true,
                'data' => $memories,
                'count' => count($memories) // ADICIONADO
            ];
            
        } catch (PDOException $e) {
            error_log("Erro ao buscar memórias: " . $e->getMessage());
            return [
                'success' => false, 
                'message' => 'Erro ao buscar memórias: ' . $e->getMessage(),
                'data' => [],
                'count' => 0 // ADICIONADO
            ];
        }
    }
    
    // Obter memória por ID
    public function getById($id) {
        try {
            $sql = "SELECT * FROM memories WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            $memory = $stmt->fetch();
            
            if ($memory) {
                return ['success' => true, 'data' => $memory];
            }
            
            return ['success' => false, 'message' => 'Memória não encontrada'];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao buscar memória: ' . $e->getMessage()];
        }
    }
    
    // Atualizar memória
    public function update($id, $data) {
        try {
            $fields = [];
            $params = [':id' => $id];
            
            if (isset($data['date'])) {
                $fields[] = "date = :date";
                $params[':date'] = $data['date'];
            }
            
            if (isset($data['title'])) {
                $fields[] = "title = :title";
                $params[':title'] = $data['title'];
            }
            
            if (isset($data['description'])) {
                $fields[] = "description = :description";
                $params[':description'] = $data['description'];
            }
            
            if (isset($data['verse'])) {
                $fields[] = "verse = :verse";
                $params[':verse'] = $data['verse'];
            }
            
            if (isset($data['verse_reference'])) {
                $fields[] = "verse_reference = :verse_reference";
                $params[':verse_reference'] = $data['verse_reference'];
            }
            
            if (isset($data['photo'])) {
                $fields[] = "photo = :photo";
                $params[':photo'] = $data['photo'];
            }
            
            if (empty($fields)) {
                return ['success' => false, 'message' => 'Nenhum campo para atualizar'];
            }
            
            $sql = "UPDATE memories SET " . implode(', ', $fields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result && $stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Memória atualizada com sucesso!'];
            }
            
            return ['success' => false, 'message' => 'Nenhuma alteração realizada'];
            
        } catch (PDOException $e) {
            error_log("Erro ao atualizar: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erro ao atualizar memória: ' . $e->getMessage()];
        }
    }
    
    // Deletar memória
    public function delete($id) {
        try {
            $memoryResult = $this->getById($id);
            
            if (!$memoryResult['success']) {
                return $memoryResult;
            }
            
            $memory = $memoryResult['data'];
            
            $sql = "DELETE FROM memories WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([':id' => $id]);
            
            if ($result && $stmt->rowCount() > 0) {
                if ($memory['photo']) {
                    deleteImage($memory['photo']);
                }
                
                return ['success' => true, 'message' => 'Memória deletada com sucesso!'];
            }
            
            return ['success' => false, 'message' => 'Memória não encontrada'];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao deletar memória: ' . $e->getMessage()];
        }
    }
    
    // Validar dados
    public function validateData($data) {
        $errors = [];
        
        if (empty($data['date'])) {
            $errors['date'] = 'Data é obrigatória';
        } elseif (strlen($data['date']) > 100) {
            $errors['date'] = 'Data muito longa (máximo 100 caracteres)';
        }
        
        if (empty($data['title'])) {
            $errors['title'] = 'Título é obrigatório';
        } elseif (strlen($data['title']) > 255) {
            $errors['title'] = 'Título muito longo (máximo 255 caracteres)';
        }
        
        if (empty($data['description'])) {
            $errors['description'] = 'Descrição é obrigatória';
        } elseif (strlen($data['description']) > 10000) {
            $errors['description'] = 'Descrição muito longa (máximo 10000 caracteres)';
        }
        
        if (isset($data['verse']) && strlen($data['verse']) > 10000) {
            $errors['verse'] = 'Versículo muito longo (máximo 10000 caracteres)';
        }
        
        if (isset($data['verse_reference']) && strlen($data['verse_reference']) > 100) {
            $errors['verse_reference'] = 'Referência muito longa (máximo 100 caracteres)';
        }
        
        return $errors;
    }
    
    // Contar memórias
    public function count() {
        try {
            $sql = "SELECT COUNT(*) as total FROM memories";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->fetch();
            
            return [
                'success' => true,
                'total' => $result['total']
            ];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao contar memórias: ' . $e->getMessage()];
        }
    }
    
    // Buscar memórias
    public function search($term) {
        try {
            $sql = "SELECT * FROM memories 
                    WHERE title LIKE :term 
                    OR description LIKE :term 
                    OR verse LIKE :term 
                    OR date LIKE :term 
                    ORDER BY created_at ASC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':term' => "%{$term}%"]);
            
            return [
                'success' => true,
                'data' => $stmt->fetchAll()
            ];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro na busca: ' . $e->getMessage()];
        }
    }
    
    // Exportar para JSON
    public function exportToJson() {
        $result = $this->getAll();
        
        if ($result['success']) {
            $export = [
                'memories' => $result['data'],
                'exported_at' => date('Y-m-d H:i:s'),
                'total' => count($result['data'])
            ];
            
            return [
                'success' => true,
                'data' => json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ];
        }
        
        return $result;
    }
    
    // Importar de JSON
    public function importFromJson($jsonData) {
        try {
            $data = json_decode($jsonData, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['success' => false, 'message' => 'JSON inválido'];
            }
            
            if (!isset($data['memories']) || !is_array($data['memories'])) {
                return ['success' => false, 'message' => 'Formato de dados inválido'];
            }
            
            $imported = 0;
            $errors = [];
            
            foreach ($data['memories'] as $memory) {
                $validationErrors = $this->validateData($memory);
                if (!empty($validationErrors)) {
                    $errors[] = "Memória '{$memory['title']}': " . implode(', ', $validationErrors);
                    continue;
                }
                
                $result = $this->create($memory);
                if ($result['success']) {
                    $imported++;
                } else {
                    $errors[] = "Erro ao importar '{$memory['title']}': " . $result['message'];
                }
            }
            
            $message = "Importadas {$imported} memórias com sucesso!";
            if (!empty($errors)) {
                $message .= " Erros: " . implode('; ', $errors);
            }
            
            return ['success' => true, 'message' => $message, 'imported' => $imported];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erro na importação: ' . $e->getMessage()];
        }
    }
}
?>