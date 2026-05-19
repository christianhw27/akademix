<?php

class TeacherModel extends Model
{
    public function getProfileByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT t.*, u.full_name
            FROM teachers t
            INNER JOIN users u ON u.id = t.user_id
            WHERE t.user_id = :user_id
            LIMIT 1
        ');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public function getStats(int $teacherId): array
    {
        return [
            'materials' => $this->count('materials', $teacherId),
            'assignments' => $this->count('assignments', $teacherId),
            'attendance' => $this->count('attendance_sessions', $teacherId),
            'grades' => $this->count('grades', $teacherId),
        ];
    }

    public function getMaterials(int $teacherId): array
    {
        $stmt = $this->db->prepare('
            SELECT m.id, m.title, m.content, m.attachment, m.created_at, s.name AS subject_name, cl.name AS classroom_name, ay.year_label, ay.semester
            FROM materials m
            INNER JOIN subjects s ON s.id = m.subject_id
            INNER JOIN classrooms c ON c.id = m.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            INNER JOIN academic_years ay ON ay.id = m.academic_year_id
            WHERE m.teacher_id = :teacher_id
            ORDER BY m.created_at DESC
        ');
        $stmt->execute(['teacher_id' => $teacherId]);
        return $stmt->fetchAll();
    }

    public function getMaterialById(int $teacherId, int $id): ?array
    {
        $stmt = $this->db->prepare('
            SELECT m.*, s.name AS subject_name, cl.name AS classroom_name, c.grade_level
            FROM materials m
            INNER JOIN subjects s ON s.id = m.subject_id
            INNER JOIN classrooms c ON c.id = m.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            WHERE m.id = :id AND m.teacher_id = :teacher_id
        ');
        $stmt->execute(['id' => $id, 'teacher_id' => $teacherId]);
        return $stmt->fetch() ?: null;
    }

    public function saveMaterial(int $teacherId, array $data, ?array $file = null): void
    {
        $academicYearId = $this->getAcademicYearByClassroom((int) $data['classroom_id']);
        
        $attachment = null;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $attachment = $this->handleUpload($file, 'materials');
        }
        
        $params = [
            'teacher_id' => $teacherId,
            'subject_id' => $data['subject_id'],
            'classroom_id' => $data['classroom_id'],
            'academic_year_id' => $academicYearId,
            'title' => $data['title'],
            'content' => $data['content'],
        ];

        if (!empty($data['id'])) {
            $params['id'] = $data['id'];
            if ($attachment) {
                $params['attachment'] = $attachment;
                $stmt = $this->db->prepare('
                    UPDATE materials
                    SET subject_id = :subject_id, classroom_id = :classroom_id, academic_year_id = :academic_year_id, title = :title, content = :content, attachment = :attachment
                    WHERE id = :id AND teacher_id = :teacher_id
                ');
            } else {
                $stmt = $this->db->prepare('
                    UPDATE materials
                    SET subject_id = :subject_id, classroom_id = :classroom_id, academic_year_id = :academic_year_id, title = :title, content = :content
                    WHERE id = :id AND teacher_id = :teacher_id
                ');
            }
        } else {
            $params['attachment'] = $attachment;
            $stmt = $this->db->prepare('
                INSERT INTO materials (subject_id, classroom_id, teacher_id, academic_year_id, title, content, attachment)
                VALUES (:subject_id, :classroom_id, :teacher_id, :academic_year_id, :title, :content, :attachment)
            ');
        }

        $stmt->execute($params);
    }

    public function deleteMaterial(int $teacherId, int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM materials WHERE id = :id AND teacher_id = :teacher_id');
        $stmt->execute([
            'id' => $id,
            'teacher_id' => $teacherId,
        ]);
    }

    public function getAssignments(int $teacherId): array
    {
        $stmt = $this->db->prepare('
            SELECT a.id, a.title, a.description, a.attachment, a.due_date, s.name AS subject_name, cl.name AS classroom_name,
                   ay.year_label, ay.semester,
                   (SELECT COUNT(*) FROM assignment_submissions sb WHERE sb.assignment_id = a.id) AS submission_count
            FROM assignments a
            INNER JOIN subjects s ON s.id = a.subject_id
            INNER JOIN classrooms c ON c.id = a.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            INNER JOIN academic_years ay ON ay.id = a.academic_year_id
            WHERE a.teacher_id = :teacher_id
            ORDER BY a.due_date DESC
        ');
        $stmt->execute(['teacher_id' => $teacherId]);
        return $stmt->fetchAll();
    }
    public function getAssignmentById(int $teacherId, int $assignmentId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT a.*, s.name AS subject_name, cl.name AS classroom_name, c.grade_level
            FROM assignments a
            INNER JOIN subjects s ON s.id = a.subject_id
            INNER JOIN classrooms c ON c.id = a.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            WHERE a.id = :id AND a.teacher_id = :teacher_id
        ');
        $stmt->execute(['id' => $assignmentId, 'teacher_id' => $teacherId]);
        return $stmt->fetch() ?: null;
    }

    public function getSubmissionsByAssignment(int $assignmentId): array
    {
        // Get all students in the classroom for this assignment, and left join their submissions
        $stmt = $this->db->prepare('
            SELECT st.id AS student_id, u.full_name AS student_name, st.nis,
                   sb.id AS submission_id, sb.status, sb.content, sb.attachment, sb.submitted_at, sb.score
            FROM assignments a
            INNER JOIN classroom_students cs ON cs.classroom_id = a.classroom_id
            INNER JOIN students st ON st.id = cs.student_id
            INNER JOIN users u ON u.id = st.user_id
            LEFT JOIN assignment_submissions sb ON sb.assignment_id = a.id AND sb.student_id = st.id
            WHERE a.id = :assignment_id
            ORDER BY u.full_name ASC
        ');
        $stmt->execute(['assignment_id' => $assignmentId]);
        return $stmt->fetchAll();
    }

    public function saveAssignment(int $teacherId, array $data, ?array $file = null): void
    {
        $academicYearId = $this->getAcademicYearByClassroom((int) $data['classroom_id']);
        
        $attachment = null;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $attachment = $this->handleUpload($file, 'assignments');
        }
        
        $params = [
            'teacher_id' => $teacherId,
            'subject_id' => $data['subject_id'],
            'classroom_id' => $data['classroom_id'],
            'academic_year_id' => $academicYearId,
            'title' => $data['title'],
            'description' => $data['description'],
            'due_date' => $data['due_date'],
        ];

        if (!empty($data['id'])) {
            $params['id'] = $data['id'];
            if ($attachment) {
                $params['attachment'] = $attachment;
                $stmt = $this->db->prepare('
                    UPDATE assignments
                    SET subject_id = :subject_id, classroom_id = :classroom_id, academic_year_id = :academic_year_id,
                        title = :title, description = :description, due_date = :due_date, attachment = :attachment
                    WHERE id = :id AND teacher_id = :teacher_id
                ');
            } else {
                $stmt = $this->db->prepare('
                    UPDATE assignments
                    SET subject_id = :subject_id, classroom_id = :classroom_id, academic_year_id = :academic_year_id,
                        title = :title, description = :description, due_date = :due_date
                    WHERE id = :id AND teacher_id = :teacher_id
                ');
            }
        } else {
            $params['attachment'] = $attachment;
            $stmt = $this->db->prepare('
                INSERT INTO assignments (subject_id, classroom_id, teacher_id, academic_year_id, title, description, due_date, attachment)
                VALUES (:subject_id, :classroom_id, :teacher_id, :academic_year_id, :title, :description, :due_date, :attachment)
            ');
        }

        $stmt->execute($params);
    }

    public function getAttendanceSessions(int $teacherId): array
    {
        $stmt = $this->db->prepare("
            SELECT ats.id, ats.attendance_date, ats.notes, cl.name AS classroom_name, s.name AS subject_name, ats.created_at,
                   (SELECT COUNT(*) FROM attendance_records ar WHERE ar.attendance_session_id = ats.id) AS total_records,
                   (SELECT COUNT(*) FROM attendance_records ar WHERE ar.attendance_session_id = ats.id AND ar.status = 'hadir') AS count_hadir,
                   (SELECT COUNT(*) FROM attendance_records ar WHERE ar.attendance_session_id = ats.id AND ar.status = 'izin') AS count_izin,
                   (SELECT COUNT(*) FROM attendance_records ar WHERE ar.attendance_session_id = ats.id AND ar.status = 'sakit') AS count_sakit,
                   (SELECT COUNT(*) FROM attendance_records ar WHERE ar.attendance_session_id = ats.id AND ar.status = 'alpha') AS count_alpha
            FROM attendance_sessions ats
            INNER JOIN classrooms c ON c.id = ats.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            INNER JOIN subjects s ON s.id = ats.subject_id
            WHERE ats.teacher_id = :teacher_id
            ORDER BY ats.created_at DESC
        ");
        $stmt->execute(['teacher_id' => $teacherId]);
        return $stmt->fetchAll();
    }

    public function saveAttendance(int $teacherId, array $data): void
    {
        $studentIds = $data['student_ids'] ?? [];
        $statuses = $data['statuses'] ?? [];

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare('
                INSERT INTO attendance_sessions (classroom_id, subject_id, teacher_id, academic_year_id, attendance_date, notes)
                VALUES (:classroom_id, :subject_id, :teacher_id, :academic_year_id, :attendance_date, :notes)
            ');
            $stmt->execute([
                'classroom_id' => $data['classroom_id'],
                'subject_id' => $data['subject_id'],
                'teacher_id' => $teacherId,
                'academic_year_id' => $this->getAcademicYearByClassroom((int) $data['classroom_id']),
                'attendance_date' => $data['attendance_date'],
                'notes' => $data['notes'],
            ]);

            $sessionId = (int) $this->db->lastInsertId();
            $detail = $this->db->prepare('
                INSERT INTO attendance_records (attendance_session_id, student_id, status, notes)
                VALUES (:attendance_session_id, :student_id, :status, :notes)
            ');

            foreach ($studentIds as $studentId) {
                $detail->execute([
                    'attendance_session_id' => $sessionId,
                    'student_id' => $studentId,
                    'status' => $statuses[$studentId] ?? 'hadir',
                    'notes' => null,
                ]);
            }

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getGrades(int $teacherId): array
    {
        $stmt = $this->db->prepare('
            SELECT g.id, g.semester, g.grade_type, g.title, g.score, g.notes,
                   su.full_name AS student_name, cl.name AS classroom_name, sb.name AS subject_name, ay.year_label
            FROM grades g
            INNER JOIN students st ON st.id = g.student_id
            INNER JOIN users su ON su.id = st.user_id
            INNER JOIN classrooms c ON c.id = g.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            INNER JOIN subjects sb ON sb.id = g.subject_id
            INNER JOIN academic_years ay ON ay.id = g.academic_year_id
            WHERE g.teacher_id = :teacher_id
            ORDER BY g.created_at DESC
        ');
        $stmt->execute(['teacher_id' => $teacherId]);
        return $stmt->fetchAll();
    }

    public function getGradeSessions(int $teacherId, int $classroomId, string $gradeType): array
    {
        $stmt = $this->db->prepare('
            SELECT g.title, g.subject_id, sb.name AS subject_name, g.semester, ay.year_label, MAX(g.created_at) as latest_created
            FROM grades g
            INNER JOIN subjects sb ON sb.id = g.subject_id
            INNER JOIN academic_years ay ON ay.id = g.academic_year_id
            WHERE g.teacher_id = :teacher_id AND g.classroom_id = :classroom_id AND g.grade_type = :grade_type
            GROUP BY g.title, g.subject_id, sb.name, g.semester, ay.year_label
            ORDER BY latest_created DESC
        ');
        $stmt->execute([
            'teacher_id' => $teacherId,
            'classroom_id' => $classroomId,
            'grade_type' => $gradeType,
        ]);
        return $stmt->fetchAll();
    }

    public function getGradesBySession(int $teacherId, int $classroomId, string $gradeType, string $title, int $subjectId): array
    {
        $stmt = $this->db->prepare('
            SELECT COALESCE(g.score, NULL) AS score, 
                   COALESCE(g.notes, NULL) AS notes,
                   su.full_name AS student_name, st.nis
            FROM classroom_students cs
            INNER JOIN students st ON st.id = cs.student_id
            INNER JOIN users su ON su.id = st.user_id
            LEFT JOIN grades g ON g.student_id = cs.student_id
              AND g.teacher_id = :teacher_id
              AND g.classroom_id = :classroom_id
              AND g.grade_type = :grade_type
              AND g.title = :title
              AND g.subject_id = :subject_id
            WHERE cs.classroom_id = :classroom_id2
            ORDER BY su.full_name ASC
        ');
        $stmt->execute([
            'teacher_id' => $teacherId,
            'classroom_id' => $classroomId,
            'grade_type' => $gradeType,
            'title' => $title,
            'subject_id' => $subjectId,
            'classroom_id2' => $classroomId,
        ]);
        return $stmt->fetchAll();
    }

    public function saveGrade(int $teacherId, array $data): void
    {
        $stmt = $this->db->prepare('
            INSERT INTO grades (student_id, subject_id, teacher_id, classroom_id, academic_year_id, semester, grade_type, title, score, notes)
            VALUES (:student_id, :subject_id, :teacher_id, :classroom_id, :academic_year_id, :semester, :grade_type, :title, :score, :notes)
        ');
        $stmt->execute([
            'student_id' => $data['student_id'],
            'subject_id' => $data['subject_id'],
            'teacher_id' => $teacherId,
            'classroom_id' => $data['classroom_id'],
            'academic_year_id' => $this->getAcademicYearByClassroom((int) $data['classroom_id']),
            'semester' => $data['semester'],
            'grade_type' => $data['grade_type'],
            'title' => $data['title'],
            'score' => $data['score'],
            'notes' => $data['notes'],
        ]);
    }

    public function saveMassGrades(int $teacherId, array $data): void
    {
        $scores = $data['scores'] ?? [];
        $notes = $data['notes'] ?? [];

        $academicYearId = $this->getAcademicYearByClassroom((int) $data['classroom_id']);
        
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare('
                INSERT INTO grades (student_id, subject_id, teacher_id, classroom_id, academic_year_id, semester, grade_type, title, score, notes)
                VALUES (:student_id, :subject_id, :teacher_id, :classroom_id, :academic_year_id, :semester, :grade_type, :title, :score, :notes)
            ');

            foreach ($scores as $studentId => $score) {
                if ($score === '' || $score === null) {
                    continue; // Skip if no score provided for this student
                }

                $stmt->execute([
                    'student_id' => $studentId,
                    'subject_id' => $data['subject_id'],
                    'teacher_id' => $teacherId,
                    'classroom_id' => $data['classroom_id'],
                    'academic_year_id' => $academicYearId,
                    'semester' => $data['semester'],
                    'grade_type' => $data['grade_type'],
                    'title' => $data['title'],
                    'score' => $score,
                    'notes' => $notes[$studentId] ?? null,
                ]);
            }

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getScheduleMap(int $teacherId): array
    {
        $stmt = $this->db->prepare('
            SELECT DISTINCT c.id, cl.name, c.grade_level, ay.year_label
            FROM schedules sc
            INNER JOIN classrooms c ON c.id = sc.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            INNER JOIN academic_years ay ON ay.id = c.academic_year_id
            WHERE sc.teacher_id = :teacher_id
            ORDER BY cl.name ASC, c.grade_level ASC
        ');
        $stmt->execute(['teacher_id' => $teacherId]);
        return $stmt->fetchAll();
    }

    public function getSubjectsByTeacher(int $teacherId): array
    {
        $stmt = $this->db->prepare('
            SELECT DISTINCT s.id, s.name, s.code
            FROM schedules sc
            INNER JOIN subjects s ON s.id = sc.subject_id
            WHERE sc.teacher_id = :teacher_id
            ORDER BY s.name
        ');
        $stmt->execute(['teacher_id' => $teacherId]);
        return $stmt->fetchAll();
    }

    public function getStudentsByClassroom(int $teacherId, int $classroomId): array
    {
        $check = $this->db->prepare('SELECT COUNT(*) FROM schedules WHERE teacher_id = :teacher_id AND classroom_id = :classroom_id');
        $check->execute([
            'teacher_id' => $teacherId,
            'classroom_id' => $classroomId,
        ]);

        if ((int) $check->fetchColumn() === 0) {
            return [];
        }

        $stmt = $this->db->prepare('
            SELECT st.id, st.nis, u.full_name
            FROM classroom_students cs
            INNER JOIN students st ON st.id = cs.student_id
            INNER JOIN users u ON u.id = st.user_id
            WHERE cs.classroom_id = :classroom_id
            ORDER BY u.full_name
        ');
        $stmt->execute(['classroom_id' => $classroomId]);
        return $stmt->fetchAll();
    }

    public function getSubmissions(int $teacherId): array
    {
        $stmt = $this->db->prepare('
            SELECT sb.id, sb.content, sb.status, sb.submitted_at, sb.score, sb.feedback,
                   a.title AS assignment_title, s.name AS subject_name, u.full_name AS student_name
            FROM assignment_submissions sb
            INNER JOIN assignments a ON a.id = sb.assignment_id
            INNER JOIN subjects s ON s.id = a.subject_id
            INNER JOIN students st ON st.id = sb.student_id
            INNER JOIN users u ON u.id = st.user_id
            WHERE a.teacher_id = :teacher_id
            ORDER BY sb.submitted_at DESC
        ');
        $stmt->execute(['teacher_id' => $teacherId]);
        return $stmt->fetchAll();
    }

    public function getSchedulesByTeacher(int $teacherId): array
    {
        $stmt = $this->db->prepare('
            SELECT sc.day_of_week, sc.start_time, sc.end_time,
                   s.name AS subject_name, cl.name AS classroom_name, c.grade_level, sc.classroom_id, sc.subject_id
            FROM schedules sc
            INNER JOIN subjects s ON s.id = sc.subject_id
            INNER JOIN classrooms c ON c.id = sc.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            WHERE sc.teacher_id = :teacher_id
            ORDER BY FIELD(sc.day_of_week, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"), sc.start_time
        ');
        $stmt->execute(['teacher_id' => $teacherId]);
        return $stmt->fetchAll();
    }

    public function getAttendanceSessionsByDate(int $teacherId, string $date): array
    {
        $stmt = $this->db->prepare('
            SELECT classroom_id, subject_id, id AS session_id
            FROM attendance_sessions
            WHERE teacher_id = :teacher_id AND attendance_date = :attendance_date
        ');
        $stmt->execute([
            'teacher_id' => $teacherId,
            'attendance_date' => $date,
        ]);
        
        $sessions = [];
        foreach ($stmt->fetchAll() as $row) {
            $key = $row['classroom_id'] . '_' . $row['subject_id'];
            $sessions[$key] = $row['session_id'];
        }
        return $sessions;
    }

    public function getAttendanceRecordsBySession(int $sessionId): array
    {
        $stmt = $this->db->prepare('
            SELECT ar.id, ar.student_id, ar.status, ar.notes,
                   u.full_name AS student_name, st.nis
            FROM attendance_records ar
            INNER JOIN students st ON st.id = ar.student_id
            INNER JOIN users u ON u.id = st.user_id
            WHERE ar.attendance_session_id = :session_id
            ORDER BY u.full_name
        ');
        $stmt->execute(['session_id' => $sessionId]);
        return $stmt->fetchAll();
    }

    public function updateAttendanceRecord(int $teacherId, int $recordId, string $status): void
    {
        $stmt = $this->db->prepare('
            UPDATE attendance_records ar
            INNER JOIN attendance_sessions ats ON ats.id = ar.attendance_session_id
            SET ar.status = :status
            WHERE ar.id = :id AND ats.teacher_id = :teacher_id
        ');
        $stmt->execute([
            'status' => $status,
            'id' => $recordId,
            'teacher_id' => $teacherId,
        ]);
    }

    private function count(string $table, int $teacherId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$table} WHERE teacher_id = :teacher_id");
        $stmt->execute(['teacher_id' => $teacherId]);
        return (int) $stmt->fetchColumn();
    }

    private function getAcademicYearByClassroom(int $classroomId): int
    {
        $stmt = $this->db->prepare('SELECT academic_year_id FROM classrooms WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $classroomId]);
        $academicYearId = $stmt->fetchColumn();

        if (!$academicYearId) {
            throw new RuntimeException('Kelas tidak valid.');
        }

        return (int) $academicYearId;
    }

    private function handleUpload(array $file, string $subfolder): string
    {
        $uploadDir = __DIR__ . '/../../public/uploads/' . $subfolder;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar'];
        if (!in_array($ext, $allowed)) {
            throw new RuntimeException('Format file tidak diizinkan: ' . $ext);
        }

        $filename = uniqid($subfolder . '_') . '.' . $ext;
        $destination = $uploadDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new RuntimeException('Gagal mengunggah file.');
        }

        return 'uploads/' . $subfolder . '/' . $filename;
    }
}
