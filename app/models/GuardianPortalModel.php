<?php

class GuardianPortalModel extends Model
{
    public function getStudentProfileByUserId(int $userId): ?array
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
            'reports' => count($this->getReports($studentId)),
            'attendance' => count($this->getAttendance($studentId)),
            'assignments' => count($this->getAssignments($studentId)),
        ];
    }

    public function getReports(int $studentId): array
    {
        $stmt = $this->db->prepare("
            SELECT g.id, g.semester, g.title, g.score, ay.year_label, s.name AS subject_name, u.full_name AS student_name
            FROM grades g
            INNER JOIN students st ON st.id = g.student_id
            INNER JOIN users u ON u.id = st.user_id
            INNER JOIN academic_years ay ON ay.id = g.academic_year_id
            INNER JOIN subjects s ON s.id = g.subject_id
            WHERE g.grade_type = 'rapor' AND g.student_id = ?
            ORDER BY ay.start_date DESC, u.full_name, s.name
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public function getAttendance(int $studentId): array
    {
        $stmt = $this->db->prepare("
            SELECT ar.id, ar.status, ats.attendance_date, sb.name AS subject_name, c.name AS classroom_name, u.full_name AS student_name
            FROM attendance_records ar
            INNER JOIN students st ON st.id = ar.student_id
            INNER JOIN users u ON u.id = st.user_id
            INNER JOIN attendance_sessions ats ON ats.id = ar.attendance_session_id
            INNER JOIN subjects sb ON sb.id = ats.subject_id
            INNER JOIN classrooms c ON c.id = ats.classroom_id
            WHERE ar.student_id = ?
            ORDER BY ats.attendance_date DESC
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public function getAssignments(int $studentId): array
    {
        $stmt = $this->db->prepare("
            SELECT a.id, a.title, a.due_date, sb.name AS subject_name, c.name AS classroom_name,
                   su.full_name AS student_name, sub.status AS submission_status
            FROM assignments a
            INNER JOIN classroom_students cs ON cs.classroom_id = a.classroom_id
            INNER JOIN students st ON st.id = cs.student_id
            INNER JOIN users su ON su.id = st.user_id
            INNER JOIN subjects sb ON sb.id = a.subject_id
            INNER JOIN classrooms c ON c.id = a.classroom_id
            LEFT JOIN assignment_submissions sub ON sub.assignment_id = a.id AND sub.student_id = st.id
            WHERE st.id = ?
            ORDER BY a.due_date DESC
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }
}
