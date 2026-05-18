<?php

class DashboardModel extends Model
{
    public function getSummary(array $user): array
    {
        return match ($user['role']) {
            'admin' => [
                ['label' => 'Guru', 'value' => $this->countTable('teachers')],
                ['label' => 'Siswa', 'value' => $this->countTable('students')],
                ['label' => 'Kelas', 'value' => $this->countTable('classrooms')],
            ],
            'teacher' => [
                ['label' => 'Materi', 'value' => $this->countByTeacher('materials', (int) $user['profile_id'])],
                ['label' => 'Tugas', 'value' => $this->countByTeacher('assignments', (int) $user['profile_id'])],
                ['label' => 'Jadwal', 'value' => $this->countByTeacher('schedules', (int) $user['profile_id'])],
                ['label' => 'Absensi', 'value' => $this->countByTeacher('attendance_sessions', (int) $user['profile_id'])],
            ],
            'student' => [
                ['label' => 'Materi Tersedia', 'value' => $this->countStudentMaterials((int) $user['profile_id'])],
                ['label' => 'Tugas Aktif', 'value' => $this->countStudentAssignments((int) $user['profile_id'])],
                ['label' => 'Izin', 'value' => $this->countStudentAttendanceStatus((int) $user['profile_id'], 'izin')],
                ['label' => 'Sakit', 'value' => $this->countStudentAttendanceStatus((int) $user['profile_id'], 'sakit')],
                ['label' => 'Alfa', 'value' => $this->countStudentAttendanceStatus((int) $user['profile_id'], 'alpha')],
            ],
            'parent' => [
                ['label' => 'Nilai Rapor', 'value' => $this->countStudentReports((int) $user['profile_id'])],
                ['label' => 'Kehadiran', 'value' => $this->countStudentAttendance((int) $user['profile_id'])],
                ['label' => 'Tugas Anak', 'value' => $this->countStudentAssignments((int) $user['profile_id'])],
            ],
            default => [],
        };
    }

    private function countTable(string $table): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
    }

    private function countByTeacher(string $table, int $teacherId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$table} WHERE teacher_id = :teacher_id");
        $stmt->execute(['teacher_id' => $teacherId]);
        return (int) $stmt->fetchColumn();
    }

    private function countStudentMaterials(int $studentId): int
    {
        $stmt = $this->db->prepare('
            SELECT COUNT(DISTINCT m.id)
            FROM materials m
            INNER JOIN classroom_students cs ON cs.classroom_id = m.classroom_id
            WHERE cs.student_id = :student_id
        ');
        $stmt->execute(['student_id' => $studentId]);
        return (int) $stmt->fetchColumn();
    }

    private function countStudentAssignments(int $studentId): int
    {
        $stmt = $this->db->prepare('
            SELECT COUNT(DISTINCT a.id)
            FROM assignments a
            INNER JOIN classroom_students cs ON cs.classroom_id = a.classroom_id
            WHERE cs.student_id = :student_id
        ');
        $stmt->execute(['student_id' => $studentId]);
        return (int) $stmt->fetchColumn();
    }

    private function countStudentAttendanceStatus(int $studentId, string $status): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM attendance_records WHERE student_id = :student_id AND status = :status');
        $stmt->execute(['student_id' => $studentId, 'status' => $status]);
        return (int) $stmt->fetchColumn();
    }

    private function countStudentReports(int $studentId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM grades WHERE student_id = :student_id AND grade_type = 'rapor'");
        $stmt->execute(['student_id' => $studentId]);
        return (int) $stmt->fetchColumn();
    }

}
