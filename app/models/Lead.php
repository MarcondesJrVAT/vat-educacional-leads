<?php
// SUGESTÃO: namespace App\Models;

/**
 * Class Lead
 * Responsável por representar e manipular dados de um lead.
 *
 * @package App\Models
 */
class Lead {
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $descricao;
    private $dataCadastro;
    
    /**
     * Lead constructor.
     * @param array $data Dados do lead (nome, email, telefone, descricao)
     */
    public function __construct($data = []) {
        if (!empty($data)) {
            $this->nome = $data['nome'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->telefone = $data['telefone'] ?? '';
            $this->descricao = $data['descricao'] ?? '';
            $this->dataCadastro = date('Y-m-d H:i:s');
        }
    }
    
    /**
     * Valida os dados do lead.
     * @return array Lista de erros encontrados
     */
    public function validate() {
        $errors = [];
        
        // Validar nome
        if (empty($this->nome)) {
            $errors[] = 'Nome é obrigatório';
        }
        
        // Validar email
        if (empty($this->email)) {
            $errors[] = 'Email é obrigatório';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        }
        
        // Validar telefone
        if (empty($this->telefone)) {
            $errors[] = 'Telefone é obrigatório';
        }
        
        // Validar descrição
        if (empty($this->descricao)) {
            $errors[] = 'Descrição é obrigatória';
        }
        
        return $errors;
    }
    
    /**
     * Sanitiza os dados do lead.
     */
    public function sanitize() {
        $this->nome = $this->sanitizeString($this->nome);
        $this->email = $this->sanitizeString($this->email);
        $this->telefone = $this->sanitizeString($this->telefone);
        $this->descricao = $this->sanitizeString($this->descricao);
    }
    
    /**
     * Sanitiza uma string.
     * @param string $data
     * @return string
     */
    private function sanitizeString($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }
    
    /**
     * Salva o lead em arquivo CSV.
     * @return bool
     */
    public function saveToCSV() {
        $csvFile = CSV_PATH;
        $isNewFile = !file_exists($csvFile);
        
        // Criar diretório se não existir
        $dir = dirname($csvFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $file = fopen($csvFile, 'a');
        
        if ($isNewFile) {
            fputcsv($file, ['Data', 'Nome', 'Email', 'Telefone', 'Descrição']);
        }
        
        fputcsv($file, [
            $this->dataCadastro,
            $this->nome,
            $this->email,
            $this->telefone,
            $this->descricao
        ]);
        
        fclose($file);
        return true;
    }
    
    /**
     * Salva o lead no banco de dados (se habilitado).
     * @return bool
     */
    public function saveToDatabase() {
        if (!SAVE_TO_DATABASE) {
            return false;
        }
        
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "INSERT INTO leads (nome, email, telefone, descricao, data_cadastro) 
                    VALUES (:nome, :email, :telefone, :descricao, :data_cadastro)";
            
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                ':nome' => $this->nome,
                ':email' => $this->email,
                ':telefone' => $this->telefone,
                ':descricao' => $this->descricao,
                ':data_cadastro' => $this->dataCadastro
            ]);
            
            if ($result) {
                // retornar id inserido para uso posterior e atribuir ao objeto
                $lastId = $pdo->lastInsertId();
                $this->id = $lastId;
                return $lastId;
            }
            return false;
        } catch(PDOException $e) {
            $this->logError("Erro ao salvar no banco: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca um lead pelo ID e retorna uma instância Lead ou null
     * @param int $id
     * @return Lead|null
     */
    public static function findById($id) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT nome, email, telefone, descricao, data_cadastro FROM leads WHERE id = :id LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;

            $lead = new Lead();
            $lead->id = $id;
            $lead->setNome($row['nome']);
            $lead->setEmail($row['email']);
            $lead->setTelefone($row['telefone']);
            $lead->setDescricao($row['descricao']);
            $lead->dataCadastro = $row['data_cadastro'];
            return $lead;
        } catch(PDOException $e) {
            // instanciar temporário para logar
            $tmp = new Lead();
            $tmp->logError("Erro ao buscar lead por ID: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Registra erro no log da aplicação.
     * @param string $message
     */
    private function logError($message) {
        $logFile = LOG_PATH;
        $dir = dirname($logFile);
        
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
    }
    
    // Getters
    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getTelefone() { return $this->telefone; }
    public function getDescricao() { return $this->descricao; }
    public function getDataCadastro() { return $this->dataCadastro; }
    public function getId() { return $this->id; }
    
    // Setters
    public function setNome($nome) { $this->nome = $nome; }
    public function setEmail($email) { $this->email = $email; }
    public function setTelefone($telefone) { $this->telefone = $telefone; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }

    public function setId($id) { $this->id = $id; }
}
?>
