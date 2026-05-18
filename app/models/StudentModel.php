<?php

class StudentModel extends Model
{
    public function getProfileByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT s.*, u.full_name
            FROM students s
            INNER JOIN users u ON u.id = s.user_id
            WHERE s.user_id = :user_id
            LIMIT 1
        ');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public function getStats(int $studentId): array
    {
        return [
            'materials' => count($this->getMaterials($studentId)),
            'assignments' => count($this->getAssignments($studentId)),
            'attendance' => count($this->getAttendance($studentId)),
            'report' => count($this->getReport($studentId)),
        ];
    }

    public function getMaterials(int $studentId): array
    {
        $stmt = $this->db->prepare('
            SELECT m.id, m.title, m.content, m.attachment, m.created_at, s.name AS subject_name,
                   cl.name AS classroom_name, ay.year_label, ay.semester, u.full_name AS teacher_name
            FROM materials m
            INNER JOIN classroom_students cs ON cs.classroom_id = m.classroom_id
            INNER JOIN subjects s ON s.id = m.subject_id
            INNER JOIN classrooms c ON c.id = m.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            INNER JOIN academic_years ay ON ay.id = m.academic_year_id
            INNER JOIN teachers t ON t.id = m.teacher_id
            INNER JOIN users u ON u.id = t.user_id
            WHERE cs.student_id = :student_id
            ORDER BY ay.start_date DESC, m.created_at DESC
        ');
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll();
    }

    public function getAssignments(int $studentId): array
    {
        $stmt = $this->db->prepare('
            SELECT a.id, a.title, a.description, a.attachment, a.due_date,
                   s.name AS subject_name, cl.name AS classroom_name, ay.year_label, ay.semester,
                   sb.id AS submission_id, sb.status AS submission_status, sb.submitted_at, sb.content AS submission_content, sb.attachment AS submission_attachment, u.full_name AS teacher_name
            FROM assignments a
            INNER JOIN classroom_students cs ON cs.classroom_id = a.classroom_id
            INNER JOIN subjects s ON s.id = a.subject_id
            INNER JOIN classrooms c ON c.id = a.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            INNER JOIN academic_years ay ON ay.id = a.academic_year_id
            INNER JOIN teachers t ON t.id = a.teacher_id
            INNER JOIN users u ON u.id = t.user_id
            LEFT JOIN assignment_submissions sb ON sb.assignment_id = a.id AND sb.student_id = cs.student_id
            WHERE cs.student_id = :student_id
            ORDER BY a.due_date ASC
        ');
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll();
    }

    public function submitAssignment(int $studentId, int $assignmentId, string $content, ?array $file = null): void
    {
        $attachment = null;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $attachment = $this->handleSubmissionUpload($file);
        }

        if ($attachment) {
            $stmt = $this->db->prepare('
                INSERT INTO assignment_submissions (assignment_id, student_id, content, attachment, status, submitted_at)
                VALUES (:assignment_id, :student_id, :content, :attachment, "submitted", NOW())
                ON DUPLICATE KEY UPDATE content = VALUES(content), attachment = VALUES(attachment), status = "submitted", submitted_at = NOW()
            ');
            $stmt->execute([
                'assignment_id' => $assignmentId,
                'student_id' => $studentId,
                'content' => $content,
                'attachment' => $attachment,
            ]);
        } else {
            $stmt = $this->db->prepare('
                INSERT INTO assignment_submissions (assignment_id, student_id, content, status, submitted_at)
                VALUES (:assignment_id, :student_id, :content, "submitted", NOW())
                ON DUPLICATE KEY UPDATE content = VALUES(content), status = "submitted", submitted_at = NOW()
            ');
            $stmt->execute([
                'assignment_id' => $assignmentId,
                'student_id' => $studentId,
                'content' => $content,
            ]);
        }
    }

    private function handleSubmissionUpload(array $file): ?string
    {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            return null;
        }

        if ($file['size'] > 10 * 1024 * 1024) {
            return null;
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/submissions';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = uniqid('sub_') . '.' . $ext;
        $destination = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return 'uploads/submissions/' . $filename;
        }

        return null;
    }

    public function getAttendance(int $studentId): array
    {
        $stmt = $this->db->prepare('
            SELECT ar.id, ar.status, ats.attendance_date, s.name AS subject_name, cl.name AS classroom_name
            FROM attendance_records ar
            INNER JOIN attendance_sessions ats ON ats.id = ar.attendance_session_id
            INNER JOIN subjects s ON s.id = ats.subject_id
            INNER JOIN classrooms c ON c.id = ats.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            WHERE ar.student_id = :student_id
            ORDER BY ats.attendance_date DESC
        ');
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll();
    }

    public function getReport(int $studentId): array
    {
        $stmt = $this->db->prepare('
            SELECT g.id, g.semester, g.title, g.score, g.notes, ay.year_label, s.name AS subject_name
            FROM grades g
            INNER JOIN academic_years ay ON ay.id = g.academic_year_id
            INNER JOIN subjects s ON s.id = g.subject_id
            WHERE g.student_id = :student_id AND g.grade_type = "rapor"
            ORDER BY ay.start_date DESC, s.name
        ');
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll();
    }

    public function getCurrentClassroom(int $studentId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT c.id, cl.name, c.grade_level, ay.year_label, ay.semester
            FROM classroom_students cs
            INNER JOIN classrooms c ON c.id = cs.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            INNER JOIN academic_years ay ON ay.id = c.academic_year_id
            WHERE cs.student_id = :student_id AND ay.is_active = 1
            LIMIT 1
        ');
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetch() ?: null;
    }

    public function getSchedulesByClassroom(int $classroomId): array
    {
        $stmt = $this->db->prepare('
            SELECT sc.*, s.name AS subject_name, u.full_name AS teacher_name
            FROM schedules sc
            INNER JOIN subjects s ON s.id = sc.subject_id
            INNER JOIN teachers t ON t.id = sc.teacher_id
            INNER JOIN users u ON u.id = t.user_id
            WHERE sc.classroom_id = :classroom_id
            ORDER BY FIELD(sc.day_of_week, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"), sc.start_time
        ');
        $stmt->execute(['classroom_id' => $classroomId]);
        return $stmt->fetchAll();
    }
}
