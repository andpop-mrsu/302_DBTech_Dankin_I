<?php
require_once __DIR__ . '/../includes/db.php';

class ExamController {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    public function index($studentId) {
        $sql = "SELECT e.*, d.name as discipline_name, s.last_name, s.first_name 
                FROM exams e
                JOIN disciplines d ON e.discipline_id = d.id
                JOIN students s ON e.student_id = s.id
                WHERE e.student_id = :student_id
                ORDER BY e.exam_date";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':student_id' => $studentId]);
        $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $studentStmt = $this->db->prepare("SELECT * FROM students WHERE id = :id");
        $studentStmt->execute([':id' => $studentId]);
        $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$student) {
            header('Location: index.php');
            exit;
        }
        
        require_once __DIR__ . '/../templates/exam_list_template.php';
    }
    
    public function create($studentId) {
        $studentStmt = $this->db->prepare("SELECT * FROM students WHERE id = :id");
        $studentStmt->execute([':id' => $studentId]);
        $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$student) {
            header('Location: index.php');
            exit;
        }
        
        $groups = $this->db->query("SELECT * FROM groups ORDER BY number")->fetchAll();
        $disciplines = $this->db->query("SELECT * FROM disciplines ORDER BY course, name")->fetchAll();
        
        require_once __DIR__ . '/../templates/exam_form_template.php';
    }
    
    public function store($data) {
        $sql = "INSERT INTO exams (student_id, discipline_id, exam_date, grade, course) 
                VALUES (:student_id, :discipline_id, :exam_date, :grade, :course)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        header("Location: exam_list.php?student_id=" . $data[':student_id']);
        exit;
    }
    
    public function edit($id, $studentId) {
        $stmt = $this->db->prepare("SELECT * FROM exams WHERE id = :id AND student_id = :student_id");
        $stmt->execute([':id' => $id, ':student_id' => $studentId]);
        $exam = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$exam) {
            header("Location: exam_list.php?student_id=" . $studentId);
            exit;
        }
        
        $studentStmt = $this->db->prepare("SELECT * FROM students WHERE id = :id");
        $studentStmt->execute([':id' => $studentId]);
        $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$student) {
            header('Location: index.php');
            exit;
        }
        
        // Получаем группу студента для предзаполнения формы
        $exam['group_id'] = $student['group_id'];
        
        $groups = $this->db->query("SELECT * FROM groups ORDER BY number")->fetchAll();
        $disciplines = $this->db->query("SELECT * FROM disciplines ORDER BY course, name")->fetchAll();
        
        require_once __DIR__ . '/../templates/exam_form_template.php';
    }
    
    public function update($id, $data) {
        $data[':id'] = $id;
        $sql = "UPDATE exams SET 
                student_id = :student_id,
                discipline_id = :discipline_id,
                exam_date = :exam_date,
                grade = :grade,
                course = :course
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        header("Location: exam_list.php?student_id=" . $data[':student_id']);
        exit;
    }
    
    public function delete($id, $studentId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("DELETE FROM exams WHERE id = :id AND student_id = :student_id");
            $stmt->execute([':id' => $id, ':student_id' => $studentId]);
            header("Location: exam_list.php?student_id=" . $studentId);
            exit;
        } else {
            $stmt = $this->db->prepare("SELECT e.*, d.name as discipline_name FROM exams e 
                                       JOIN disciplines d ON e.discipline_id = d.id 
                                       WHERE e.id = :id AND e.student_id = :student_id");
            $stmt->execute([':id' => $id, ':student_id' => $studentId]);
            $exam = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$exam) {
                header("Location: exam_list.php?student_id=" . $studentId);
                exit;
            }
            
            $studentStmt = $this->db->prepare("SELECT * FROM students WHERE id = :id");
            $studentStmt->execute([':id' => $studentId]);
            $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
            
            require_once __DIR__ . '/../templates/exam_delete_confirmation.php';
        }
    }
}
?>