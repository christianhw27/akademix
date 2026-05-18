<?php

class AdminModel extends Model
{
    // ===== STATS =====
    public function getStats(): array
    {
        return [
            'teachers' => (int) $this->db->query('SELECT COUNT(*) FROM teachers')->fetchColumn(),
            'students' => (int) $this->db->query('SELECT COUNT(*) FROM students')->fetchColumn(),
            'classrooms' => (int) $this->db->query('SELECT COUNT(*) FROM classrooms')->fetchColumn(),
        ];
    }

    // ===== TEACHERS =====
    public function getTeachers(): array
    {
        return $this->db->query('
            SELECT t.id, t.user_id, t.nip, t.phone, t.address, u.username, u.full_name, u.email,
                   GROUP_CONCAT(s.name ORDER BY s.name SEPARATOR ", ") AS subject_names
            FROM teachers t
            INNER JOIN users u ON u.id = t.user_id
            LEFT JOIN teacher_subjects ts ON ts.teacher_id = t.id
            LEFT JOIN subjects s ON s.id = ts.subject_id
            GROUP BY t.id
            ORDER BY u.full_name
        ')->fetchAll();
    }

    public function saveTeacher(array $data): void
    {
        $this->db->beginTransaction();
        try {
            if (!empty($data['id'])) {
                $teacher = $this->findTeacher((int) $data['id']);
                $this->updateUser($teacher['user_id'], $data, 'teacher');
                $stmt = $this->db->prepare('UPDATE teachers SET nip = :nip, phone = :phone, address = :address WHERE id = :id');
                $stmt->execute(['id' => $data['id'], 'nip' => $data['nip'], 'phone' => $data['phone'], 'address' => $data['address']]);
                $teacherId = (int) $data['id'];
            } else {
                $userId = $this->insertUser($data, 'teacher');
                $stmt = $this->db->prepare('INSERT INTO teachers (user_id, nip, phone, address) VALUES (:user_id, :nip, :phone, :address)');
                $stmt->execute(['user_id' => $userId, 'nip' => $data['nip'], 'phone' => $data['phone'], 'address' => $data['address']]);
                $teacherId = (int) $this->db->lastInsertId();
            }

            // Sync subjects
            $this->db->prepare('DELETE FROM teacher_subjects WHERE teacher_id = ?')->execute([$teacherId]);
            if (!empty($data['subject_ids']) && is_array($data['subject_ids'])) {
                $ins = $this->db->prepare('INSERT INTO teacher_subjects (teacher_id, subject_id) VALUES (?, ?)');
                foreach ($data['subject_ids'] as $subjectId) {
                    $ins->execute([$teacherId, (int) $subjectId]);
                }
            }

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteTeacher(int $id): void
    {
        $teacher = $this->findTeacher($id);
        $this->deleteUser((int) $teacher['user_id']);
    }

    public function getTeacherSubjectIds(int $teacherId): array
    {
        $stmt = $this->db->prepare('SELECT subject_id FROM teacher_subjects WHERE teacher_id = ?');
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    // ===== STUDENTS =====
    public function getStudents(): array
    {
        return $this->db->query('
            SELECT s.id, s.user_id, s.nis, s.gender, s.birth_date, s.phone, s.address, s.cohort_id,
                   co.year_name as cohort_name, u.username, u.full_name, u.email
            FROM students s
            INNER JOIN users u ON u.id = s.user_id
            LEFT JOIN cohorts co ON co.id = s.cohort_id
            ORDER BY u.full_name
        ')->fetchAll();
    }

    public function saveStudent(array $data): void
    {
        $this->db->beginTransaction();
        try {
            if (!empty($data['id'])) {
                $student = $this->findStudent((int) $data['id']);
                $this->updateUser($student['user_id'], $data, 'student');
                $stmt = $this->db->prepare('
                    UPDATE students SET nis = :nis, gender = :gender,
                    birth_date = :birth_date, phone = :phone, address = :address, cohort_id = :cohort_id WHERE id = :id
                ');
                $stmt->execute([
                    'id' => $data['id'], 'nis' => $data['nis'],
                    'gender' => $data['gender'], 'birth_date' => $data['birth_date'] ?: null,
                    'phone' => $data['phone'] ?? null, 'address' => $data['address'],
                    'cohort_id' => $data['cohort_id'],
                ]);
            } else {
                $userId = $this->insertUser($data, 'student');
                $stmt = $this->db->prepare('
                    INSERT INTO students (user_id, nis, gender, birth_date, phone, address, cohort_id)
                    VALUES (:user_id, :nis, :gender, :birth_date, :phone, :address, :cohort_id)
                ');
                $stmt->execute([
                    'user_id' => $userId, 'nis' => $data['nis'],
                    'gender' => $data['gender'], 'birth_date' => $data['birth_date'] ?: null,
                    'phone' => $data['phone'] ?? null, 'address' => $data['address'],
                    'cohort_id' => $data['cohort_id'],
                ]);
            }
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteStudent(int $id): void
    {
        $student = $this->findStudent($id);
        $this->deleteUser((int) $student['user_id']);
    }

    public function getStudentDetail(int $id): ?array
    {
        $stmt = $this->db->prepare('
            SELECT s.*, co.year_name as cohort_name, u.full_name, u.email
            FROM students s
            INNER JOIN users u ON u.id = s.user_id
            LEFT JOIN cohorts co ON co.id = s.cohort_id
            WHERE s.id = :id LIMIT 1
        ');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getStudentGrades(int $studentId): array
    {
        $stmt = $this->db->prepare('
            SELECT g.*, s.name AS subject_name, cl.name AS classroom_name, ay.year_label, ay.semester
            FROM grades g
            INNER JOIN subjects s ON s.id = g.subject_id
            INNER JOIN classrooms c ON c.id = g.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            INNER JOIN academic_years ay ON ay.id = g.academic_year_id
            WHERE g.student_id = :student_id
            ORDER BY ay.start_date DESC, g.grade_type, s.name
        ');
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll();
    }

    // ===== SUBJECTS =====
    public function getSubjects(): array
    {
        return $this->db->query('
            SELECT s.id, s.code, s.name, s.description,
                   GROUP_CONCAT(u.full_name ORDER BY u.full_name SEPARATOR ", ") AS teacher_names
            FROM subjects s
            LEFT JOIN teacher_subjects ts ON ts.subject_id = s.id
            LEFT JOIN teachers t ON t.id = ts.teacher_id
            LEFT JOIN users u ON u.id = t.user_id
            GROUP BY s.id
            ORDER BY s.name
        ')->fetchAll();
    }

    public function saveSubject(array $data): void
    {
        $params = ['code' => $data['code'], 'name' => $data['name'], 'description' => $data['description']];
        if (!empty($data['id'])) {
            $params['id'] = $data['id'];
            $stmt = $this->db->prepare('UPDATE subjects SET code = :code, name = :name, description = :description WHERE id = :id');
        } else {
            $stmt = $this->db->prepare('INSERT INTO subjects (code, name, description) VALUES (:code, :name, :description)');
        }
        $stmt->execute($params);
    }

    public function deleteSubject(int $id): void
    {
        $this->db->prepare('DELETE FROM subjects WHERE id = :id')->execute(['id' => $id]);
    }

    // ===== ACADEMIC YEARS =====
    public function getAcademicYears(): array
    {
        return $this->db->query('SELECT * FROM academic_years ORDER BY is_active DESC, start_date DESC')->fetchAll();
    }

    public function saveAcademicYear(array $data): void
    {
        $this->db->beginTransaction();
        try {
            if ((int) ($data['is_active'] ?? 0) === 1) {
                $this->db->exec('UPDATE academic_years SET is_active = 0');
            }
            $params = [
                'year_label' => $data['year_label'], 'semester' => $data['semester'],
                'start_date' => $data['start_date'], 'end_date' => $data['end_date'],
                'is_active' => (int) ($data['is_active'] ?? 0),
            ];
            if (!empty($data['id'])) {
                $params['id'] = $data['id'];
                $stmt = $this->db->prepare('UPDATE academic_years SET year_label=:year_label, semester=:semester, start_date=:start_date, end_date=:end_date, is_active=:is_active WHERE id=:id');
            } else {
                $stmt = $this->db->prepare('INSERT INTO academic_years (year_label, semester, start_date, end_date, is_active) VALUES (:year_label, :semester, :start_date, :end_date, :is_active)');
            }
            $stmt->execute($params);
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteAcademicYear(int $id): void
    {
        $this->db->prepare('DELETE FROM academic_years WHERE id = :id')->execute(['id' => $id]);
    }

    // ===== CLASSROOMS =====
    public function getClassrooms(): array
    {
        return $this->db->query('
            SELECT c.id, c.academic_year_id, c.class_id, c.grade_level, c.homeroom_teacher_id,
                   cl.name, ay.year_label, ay.semester, u.full_name AS homeroom_teacher,
                   (SELECT COUNT(*) FROM classroom_students cs WHERE cs.classroom_id = c.id) AS student_count
            FROM classrooms c
            INNER JOIN classes cl ON cl.id = c.class_id
            INNER JOIN academic_years ay ON ay.id = c.academic_year_id
            LEFT JOIN teachers t ON t.id = c.homeroom_teacher_id
            LEFT JOIN users u ON u.id = t.user_id
            ORDER BY ay.start_date DESC, c.grade_level ASC, cl.name ASC
        ')->fetchAll();
    }

    public function getCohortOptions(): array
    {
        return $this->db->query('SELECT * FROM cohorts ORDER BY year_name DESC')->fetchAll();
    }

    public function getClassroomsByAcademicYear(int $academicYearId): array
    {
        $stmt = $this->db->prepare('
            SELECT c.*, cl.name, u.full_name AS homeroom_teacher,
                   (SELECT COUNT(*) FROM classroom_students cs WHERE cs.classroom_id = c.id) AS student_count
            FROM classrooms c
            INNER JOIN classes cl ON cl.id = c.class_id
            LEFT JOIN teachers t ON t.id = c.homeroom_teacher_id
            LEFT JOIN users u ON u.id = t.user_id
            WHERE c.academic_year_id = :ay_id
            ORDER BY c.grade_level, cl.name
        ');
        $stmt->execute(['ay_id' => $academicYearId]);
        return $stmt->fetchAll();
    }

    public function getClassroomStudents(int $classroomId): array
    {
        $stmt = $this->db->prepare('
            SELECT cs.id AS enrollment_id, s.id, s.nis, s.gender, s.cohort_id, co.year_name as cohort_name,
                   u.full_name, u.email, u.username, s.birth_date, s.phone, s.address
            FROM classroom_students cs
            INNER JOIN students s ON s.id = cs.student_id
            LEFT JOIN cohorts co ON co.id = s.cohort_id
            INNER JOIN users u ON u.id = s.user_id
            WHERE cs.classroom_id = :classroom_id
            ORDER BY u.full_name
        ');
        $stmt->execute(['classroom_id' => $classroomId]);
        return $stmt->fetchAll();
    }

    public function getClassroomDetail(int $id): ?array
    {
        $stmt = $this->db->prepare('
            SELECT c.*, cl.name, ay.year_label, ay.semester, u.full_name AS homeroom_teacher
            FROM classrooms c
            INNER JOIN classes cl ON cl.id = c.class_id
            INNER JOIN academic_years ay ON ay.id = c.academic_year_id
            LEFT JOIN teachers t ON t.id = c.homeroom_teacher_id
            LEFT JOIN users u ON u.id = t.user_id
            WHERE c.id = :id LIMIT 1
        ');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function saveClassroom(array $data): void
    {
        $homeroomTeacherId = ($data['homeroom_teacher_id'] ?? '') !== '' ? (int) $data['homeroom_teacher_id'] : null;
        $params = [
            'academic_year_id' => $data['academic_year_id'], 'class_id' => $data['class_id'],
            'grade_level' => $data['grade_level'], 'homeroom_teacher_id' => $homeroomTeacherId,
        ];
        if (!empty($data['id'])) {
            $params['id'] = $data['id'];
            $stmt = $this->db->prepare('UPDATE classrooms SET academic_year_id=:academic_year_id, class_id=:class_id, grade_level=:grade_level, homeroom_teacher_id=:homeroom_teacher_id WHERE id=:id');
        } else {
            $stmt = $this->db->prepare('INSERT INTO classrooms (academic_year_id, class_id, grade_level, homeroom_teacher_id) VALUES (:academic_year_id, :class_id, :grade_level, :homeroom_teacher_id)');
        }
        $stmt->execute($params);
    }

    public function deleteClassroom(int $id): void
    {
        $this->db->prepare('DELETE FROM classrooms WHERE id = :id')->execute(['id' => $id]);
    }

    public function saveEnrollment(int $classroomId, int $studentId): void
    {
        $stmt = $this->db->prepare('INSERT INTO classroom_students (classroom_id, student_id) VALUES (:classroom_id, :student_id)');
        $stmt->execute(['classroom_id' => $classroomId, 'student_id' => $studentId]);
    }

    public function deleteEnrollment(int $id): void
    {
        $this->db->prepare('DELETE FROM classroom_students WHERE id = :id')->execute(['id' => $id]);
    }

    public function getClassroomEnrollments(): array
    {
        return $this->db->query('
            SELECT cs.id, cl.name AS classroom_name, c.grade_level, ay.year_label, ay.semester, su.full_name AS student_name, s.nis
            FROM classroom_students cs
            INNER JOIN classrooms c ON c.id = cs.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            INNER JOIN academic_years ay ON ay.id = c.academic_year_id
            INNER JOIN students s ON s.id = cs.student_id
            INNER JOIN users su ON su.id = s.user_id
            ORDER BY ay.start_date DESC, cl.name, su.full_name
        ')->fetchAll();
    }

    // ===== SCHEDULES =====
    public function getSchedules(): array
    {
        return $this->db->query('
            SELECT sc.id, sc.classroom_id, sc.subject_id, sc.teacher_id, sc.day_of_week, sc.start_time, sc.end_time,
                   cl.name AS classroom_name, c.grade_level, s.name AS subject_name, u.full_name AS teacher_name
            FROM schedules sc
            INNER JOIN classrooms c ON c.id = sc.classroom_id
            INNER JOIN classes cl ON cl.id = c.class_id
            INNER JOIN subjects s ON s.id = sc.subject_id
            INNER JOIN teachers t ON t.id = sc.teacher_id
            INNER JOIN users u ON u.id = t.user_id
            ORDER BY FIELD(sc.day_of_week, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"), sc.start_time
        ')->fetchAll();
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

    public function saveSchedule(array $data): void
    {
        $params = [
            'classroom_id' => $data['classroom_id'], 'subject_id' => $data['subject_id'],
            'teacher_id' => $data['teacher_id'], 'day_of_week' => $data['day_of_week'],
            'start_time' => $data['start_time'], 'end_time' => $data['end_time'],
        ];
        if (!empty($data['id'])) {
            $params['id'] = $data['id'];
            $stmt = $this->db->prepare('UPDATE schedules SET classroom_id=:classroom_id, subject_id=:subject_id, teacher_id=:teacher_id, day_of_week=:day_of_week, start_time=:start_time, end_time=:end_time WHERE id=:id');
        } else {
            $stmt = $this->db->prepare('INSERT INTO schedules (classroom_id, subject_id, teacher_id, day_of_week, start_time, end_time) VALUES (:classroom_id, :subject_id, :teacher_id, :day_of_week, :start_time, :end_time)');
        }
        $stmt->execute($params);
    }

    public function deleteSchedule(int $id): void
    {
        $this->db->prepare('DELETE FROM schedules WHERE id = :id')->execute(['id' => $id]);
    }

    // ===== OPTIONS =====
    public function getTeacherOptions(): array
    {
        return $this->db->query('SELECT t.id, u.full_name FROM teachers t INNER JOIN users u ON u.id = t.user_id ORDER BY u.full_name')->fetchAll();
    }

    public function getStudentOptions(): array
    {
        return $this->db->query('SELECT s.id, s.nis, u.full_name FROM students s INNER JOIN users u ON u.id = s.user_id ORDER BY u.full_name')->fetchAll();
    }

    public function getClassOptions(): array
    {
        return $this->db->query('SELECT * FROM classes ORDER BY name')->fetchAll();
    }

    // ===== PRIVATE HELPERS =====
    private function insertUser(array $data, string $role): int
    {
        $username = !empty($data['username']) ? $data['username'] : null;
        $stmt = $this->db->prepare('INSERT INTO users (username, password_hash, role, full_name, email, is_active) VALUES (:username, :password_hash, :role, :full_name, :email, 1)');
        $stmt->execute([
            'username' => $username,
            'password_hash' => password_hash($data['password'] ?: 'password', PASSWORD_BCRYPT),
            'role' => $role,
            'full_name' => $data['full_name'],
            'email' => $data['email'] ?: null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    private function updateUser(int $userId, array $data, string $role): void
    {
        $fields = ['role = :role', 'full_name = :full_name', 'email = :email'];
        $params = ['id' => $userId, 'role' => $role, 'full_name' => $data['full_name'], 'email' => $data['email'] ?: null];

        if (isset($data['username'])) {
            $fields[] = 'username = :username';
            $params['username'] = $data['username'] ?: null;
        }

        if (!empty($data['password'])) {
            $fields[] = 'password_hash = :password_hash';
            $params['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $this->db->prepare($sql)->execute($params);
    }

    private function deleteUser(int $userId): void
    {
        $this->db->prepare('DELETE FROM users WHERE id = :id')->execute(['id' => $userId]);
    }

    private function findTeacher(int $id): array
    {
        $stmt = $this->db->prepare('SELECT * FROM teachers WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: throw new \RuntimeException('Data guru tidak ditemukan.');
    }

    private function findStudent(int $id): array
    {
        $stmt = $this->db->prepare('SELECT * FROM students WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: throw new \RuntimeException('Data siswa tidak ditemukan.');
    }
}
