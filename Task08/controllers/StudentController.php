<?php
require_once __DIR__ . '/../includes/db.php';

class StudentController {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    public function index() {
        $groupFilter = $_GET['group_id'] ?? null;
        
        $sql = "SELECT s.*, g.number as group_number 
                FROM students s 
                JOIN groups g ON s.group_id = g.id";
        
        $params = [];
        if ($groupFilter) {
            $sql .= " WHERE s.group_id = :group_id";
            $params[':group_id'] = $groupFilter;
        }
        
        $sql .= " ORDER BY g.number, s.last_name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $groups = $this->db->query("SELECT * FROM groups ORDER BY number")->fetchAll();
        
        require_once __DIR__ . '/../templates/student_list.php';
    }
    
    public function create() {
        $groups = $this->db->query("SELECT * FROM groups ORDER BY number")->fetchAll();
        require_once __DIR__ . '/../templates/student_form_template.php';
    }
    
    public function store($data) {
        $sql = "INSERT INTO students (last_name, first_name, middle_name, gender, group_id) 
                VALUES (:last_name, :first_name, :middle_name, :gender, :group_id)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        header('Location: index.php');
        exit;
    }
    
    public function edit($id) {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$student) {
            header('Location: index.php');
            exit;
        }
        
        $groups = $this->db->query("SELECT * FROM groups ORDER BY number")->fetchAll();
        
        require_once __DIR__ . '/../templates/student_form_template.php';
    }
    
    public function update($id, $data) {
        $data[':id'] = $id;
        $sql = "UPDATE students SET 
                last_name = :last_name,
                first_name = :first_name,
                middle_name = :middle_name,
                gender = :gender,
                group_id = :group_id
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        header('Location: index.php');
        exit;
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("DELETE FROM students WHERE id = :id");
            $stmt->execute([':id' => $id]);
            header('Location: index.php');
            exit;
        } else {
            $stmt = $this->db->prepare("SELECT * FROM students WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$student) {
                header('Location: index.php');
                exit;
            }
            
            require_once __DIR__ . '/../templates/delete_confirmation.php';
        }
    }
}
?>