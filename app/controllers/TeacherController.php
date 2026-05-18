<?php

class TeacherController extends Controller
{
    private TeacherModel $teacherModel;

    public function __construct()
    {
        $this->teacherModel = $this->model('TeacherModel');
    }

    private function teacherId(): int
    {
        $profile = $this->teacherModel->getProfileByUserId((int) Auth::id());

        if (!$profile) {
            throw new RuntimeException('Profil guru tidak ditemukan.');
        }

        return (int) $profile['id'];
    }

    public function index(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $allSchedules = $this->teacherModel->getSchedulesByTeacher($teacherId);

        // Group by day
        $schedulesByDay = [];
        foreach ($allSchedules as $s) {
            $schedulesByDay[$s['day_of_week']][] = $s;
        }

        // Today's schedule
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $todayName = $days[date('w')];
        $todaySchedule = $schedulesByDay[$todayName] ?? [];

        // Preload students per classroom
        $studentsByClassroom = [];
        foreach ($todaySchedule as $s) {
            $cid = $s['classroom_id'];
            if (!isset($studentsByClassroom[$cid])) {
                $studentsByClassroom[$cid] = $this->teacherModel->getStudentsByClassroom($teacherId, $cid);
            }
        }

        // Today's attendance sessions already taken
        $todaySessions = $this->teacherModel->getAttendanceSessionsByDate($teacherId, date('Y-m-d'));

        $this->view('teacher/index', [
            'title' => 'Beranda Guru',
            'stats' => $this->teacherModel->getStats($teacherId),
            'submissions' => $this->teacherModel->getSubmissions($teacherId),
            'schedulesByDay' => $schedulesByDay,
            'todaySchedule' => $todaySchedule,
            'todayName' => $todayName,
            'studentsByClassroom' => $studentsByClassroom,
            'todaySessions' => $todaySessions,
        ]);
    }

    public function materials(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $this->view('teacher/materials', [
            'title' => 'Materi',
            'materials' => $this->teacherModel->getMaterials($teacherId),
            'subjects' => $this->teacherModel->getSubjectsByTeacher($teacherId),
            'classrooms' => $this->teacherModel->getScheduleMap($teacherId),
        ]);
    }

    public function saveMaterial(): void
    {
        Auth::requireRole('teacher');

        try {
            $file = $_FILES['attachment'] ?? null;
            $this->teacherModel->saveMaterial($this->teacherId(), $_POST, $file);
            flash('success', 'Materi berhasil disimpan.');
        } catch (Throwable $e) {
            flash('error', 'Gagal menyimpan materi: ' . $e->getMessage());
        }

        $this->redirect('teacher/materials');
    }

    public function editMaterial(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $id = (int) ($_GET['id'] ?? 0);

        if (!$id) {
            $this->redirect('teacher/materials');
        }

        $material = $this->teacherModel->getMaterialById($teacherId, $id);
        if (!$material) {
            flash('error', 'Materi tidak ditemukan atau Anda tidak memiliki akses.');
            $this->redirect('teacher/materials');
        }

        $this->view('teacher/edit_material', [
            'title' => 'Edit Materi',
            'material' => $material,
            'subjects' => $this->teacherModel->getSubjectsByTeacher($teacherId),
            'classrooms' => $this->teacherModel->getScheduleMap($teacherId),
        ]);
    }

    public function deleteMaterial(): void
    {
        Auth::requireRole('teacher');

        try {
            $this->teacherModel->deleteMaterial($this->teacherId(), (int) $this->request('id'));
            flash('success', 'Materi berhasil dihapus.');
        } catch (Throwable $e) {
            flash('error', 'Gagal menghapus materi: ' . $e->getMessage());
        }

        $this->redirect('teacher/materials');
    }

    public function assignments(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $this->view('teacher/assignments', [
            'title' => 'Tugas',
            'assignments' => $this->teacherModel->getAssignments($teacherId),
            'subjects' => $this->teacherModel->getSubjectsByTeacher($teacherId),
            'classrooms' => $this->teacherModel->getScheduleMap($teacherId),
            'submissions' => $this->teacherModel->getSubmissions($teacherId),
        ]);
    }

    public function saveAssignment(): void
    {
        Auth::requireRole('teacher');

        try {
            $file = $_FILES['attachment'] ?? null;
            $this->teacherModel->saveAssignment($this->teacherId(), $_POST, $file);
            flash('success', 'Tugas berhasil disimpan.');
        } catch (Throwable $e) {
            flash('error', 'Gagal menyimpan tugas: ' . $e->getMessage());
        }

        $this->redirect('teacher/assignments');
    }

    public function assignmentDetail(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $assignmentId = (int) ($_GET['id'] ?? 0);

        if (!$assignmentId) {
            $this->redirect('teacher/assignments');
        }

        $assignment = $this->teacherModel->getAssignmentById($teacherId, $assignmentId);
        if (!$assignment) {
            flash('error', 'Tugas tidak ditemukan atau Anda tidak memiliki akses.');
            $this->redirect('teacher/assignments');
        }

        $this->view('teacher/assignment_detail', [
            'title' => 'Detail Tugas',
            'assignment' => $assignment,
            'submissions' => $this->teacherModel->getSubmissionsByAssignment($assignmentId),
        ]);
    }

    public function attendance(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $classrooms = $this->teacherModel->getScheduleMap($teacherId);
        $selectedClassroomId = (int) ($this->request('classroom_id', $classrooms[0]['id'] ?? 0));

        $this->view('teacher/attendance', [
            'title' => 'Absensi',
            'sessions' => $this->teacherModel->getAttendanceSessions($teacherId),
            'subjects' => $this->teacherModel->getSubjectsByTeacher($teacherId),
            'classrooms' => $classrooms,
            'selectedClassroomId' => $selectedClassroomId,
            'students' => $selectedClassroomId ? $this->teacherModel->getStudentsByClassroom($teacherId, $selectedClassroomId) : [],
        ]);
    }

    public function saveAttendance(): void
    {
        Auth::requireRole('teacher');

        try {
            $this->teacherModel->saveAttendance($this->teacherId(), $_POST);
            flash('success', 'Absensi berhasil disimpan.');
        } catch (Throwable $e) {
            flash('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }

        $this->redirect('teacher/attendance');
    }

    public function grades(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        $classrooms = $this->teacherModel->getScheduleMap($teacherId);
        $selectedClassroomId = (int) ($this->request('classroom_id', 0));
        $gradeType = $this->request('type', 'harian');

        $gradeSessions = $selectedClassroomId ? $this->teacherModel->getGradeSessions($teacherId, $selectedClassroomId, $gradeType) : [];

        $this->view('teacher/grades', [
            'title' => 'Nilai',
            'subjects' => $this->teacherModel->getSubjectsByTeacher($teacherId),
            'classrooms' => $classrooms,
            'selectedClassroomId' => $selectedClassroomId,
            'gradeType' => $gradeType,
            'students' => $selectedClassroomId ? $this->teacherModel->getStudentsByClassroom($teacherId, $selectedClassroomId) : [],
            'gradeSessions' => $gradeSessions,
        ]);
    }

    public function saveGrade(): void
    {
        Auth::requireRole('teacher');

        try {
            $this->teacherModel->saveGrade($this->teacherId(), $_POST);
            flash('success', 'Nilai berhasil disimpan.');
        } catch (Throwable $e) {
            flash('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }

        $this->redirect('teacher/grades');
    }

    public function saveMassGrades(): void
    {
        Auth::requireRole('teacher');

        try {
            $this->teacherModel->saveMassGrades($this->teacherId(), $_POST);
            flash('success', 'Nilai berhasil disimpan.');
        } catch (Throwable $e) {
            flash('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }

        $this->redirect('teacher/grades&type=' . urlencode($_POST['grade_type'] ?? 'harian') . '&classroom_id=' . (int)($_POST['classroom_id'] ?? 0));
    }

    public function exportGrades(): void
    {
        Auth::requireRole('teacher');

        $teacherId = $this->teacherId();
        
        $classroomId = (int)$this->request('classroom_id', 0);
        $gradeType = $this->request('type', '');
        $title = $this->request('title', '');
        $subjectId = (int)$this->request('subject_id', 0);
        
        if (!$classroomId || !$gradeType || !$title || !$subjectId) {
            flash('error', 'Parameter ekspor tidak lengkap.');
            $this->redirect('teacher/grades');
        }

        $grades = $this->teacherModel->getGradesBySession($teacherId, $classroomId, $gradeType, $title, $subjectId);
        
        $className = '';
        $classrooms = $this->teacherModel->getScheduleMap($teacherId);
        foreach ($classrooms as $c) {
            if ($c['id'] == $classroomId) {
                $className = $c['name'];
                break;
            }
        }

        $filename = 'Nilai_' . $className . '_' . $title . '_' . date('Ymd_His') . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta http-equiv="Content-type" content="text/html;charset=utf-8"></head>';
        echo '<body>';
        echo '<table border="1">';
        echo '<tr>';
        echo '<th style="background-color: #f2f2f2;">No</th>';
        echo '<th style="background-color: #f2f2f2;">Nama Siswa</th>';
        echo '<th style="background-color: #f2f2f2;">NIS</th>';
        echo '<th style="background-color: #f2f2f2;">Nilai</th>';
        echo '<th style="background-color: #f2f2f2;">Catatan</th>';
        echo '</tr>';

        $no = 1;
        foreach ($grades as $grade) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . e($grade['student_name']) . '</td>';
            echo '<td style="mso-number-format:\'@\';">' . e($grade['nis']) . '</td>';
            echo '<td>' . e((string)$grade['score']) . '</td>';
            echo '<td>' . e((string)$grade['notes']) . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
        echo '</body></html>';
        exit;
    }

    public function editAttendance(): void
    {
        Auth::requireRole('teacher');

        $sessionId = (int) ($_GET['session_id'] ?? 0);
        $teacherId = $this->teacherId();
        $sessions = $this->teacherModel->getAttendanceSessions($teacherId);

        // Find the session
        $currentSession = null;
        foreach ($sessions as $s) {
            if ((int)$s['id'] === $sessionId) {
                $currentSession = $s;
                break;
            }
        }

        $records = $sessionId ? $this->teacherModel->getAttendanceRecordsBySession($sessionId) : [];

        $this->view('teacher/edit_attendance', [
            'title' => 'Edit Absensi',
            'session' => $currentSession,
            'records' => $records,
            'sessionId' => $sessionId,
        ]);
    }

    public function updateAttendance(): void
    {
        Auth::requireRole('teacher');

        try {
            $teacherId = $this->teacherId();
            $recordIds = $_POST['record_ids'] ?? [];
            $statuses = $_POST['statuses'] ?? [];

            foreach ($recordIds as $recordId) {
                $status = $statuses[$recordId] ?? 'hadir';
                $this->teacherModel->updateAttendanceRecord($teacherId, (int) $recordId, $status);
            }

            flash('success', 'Absensi berhasil diperbarui.');
        } catch (Throwable $e) {
            flash('error', 'Gagal memperbarui absensi: ' . $e->getMessage());
        }

        $this->redirect('teacher/attendance');
    }
}
