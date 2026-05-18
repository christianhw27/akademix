<?php

class StudentController extends Controller
{
    private StudentModel $studentModel;

    public function __construct()
    {
        $this->studentModel = $this->model('StudentModel');
    }

    private function studentId(): int
    {
        $profile = $this->studentModel->getProfileByUserId((int) Auth::id());

        if (!$profile) {
            throw new RuntimeException('Profil siswa tidak ditemukan.');
        }

        return (int) $profile['id'];
    }

    public function index(): void
    {
        Auth::requireRole('student');

        $studentId = $this->studentId();
        $this->view('student/index', [
            'title' => 'Portal Siswa',
            'stats' => $this->studentModel->getStats($studentId),
        ]);
    }

    public function classroom(): void
    {
        Auth::requireRole('student');

        $studentId = $this->studentId();
        $materials = $this->studentModel->getMaterials($studentId);
        $assignments = $this->studentModel->getAssignments($studentId);

        // Group by subject
        $subjects = [];
        foreach ($materials as $m) {
            $key = $m['subject_name'];
            if (!isset($subjects[$key])) {
                $subjects[$key] = ['materials' => [], 'assignments' => []];
            }
            $subjects[$key]['materials'][] = $m;
        }
        foreach ($assignments as $a) {
            $key = $a['subject_name'];
            if (!isset($subjects[$key])) {
                $subjects[$key] = ['materials' => [], 'assignments' => []];
            }
            $subjects[$key]['assignments'][] = $a;
        }
        ksort($subjects);

        $selectedSubject = $_GET['subject'] ?? null;

        $this->view('student/classroom', [
            'title' => 'Kelas',
            'subjects' => $subjects,
            'selectedSubject' => $selectedSubject,
        ]);
    }

    public function materials(): void
    {
        Auth::requireRole('student');

        $this->view('student/materials', [
            'title' => 'Materi Semua Tahun',
            'materials' => $this->studentModel->getMaterials($this->studentId()),
        ]);
    }

    public function assignments(): void
    {
        Auth::requireRole('student');

        $this->view('student/assignments', [
            'title' => 'Tugas',
            'assignments' => $this->studentModel->getAssignments($this->studentId()),
        ]);
    }

    public function submitAssignment(): void
    {
        Auth::requireRole('student');

        try {
            $file = $_FILES['attachment'] ?? null;
            $this->studentModel->submitAssignment(
                $this->studentId(),
                (int) $this->request('assignment_id'),
                trim((string) $this->request('content')),
                $file
            );
            flash('success', 'Tugas berhasil dikumpulkan.');
        } catch (Throwable $e) {
            flash('error', 'Gagal mengumpulkan tugas: ' . $e->getMessage());
        }

        $this->redirect('student/classroom');
    }

    public function attendance(): void
    {
        Auth::requireRole('student');
        
        $studentId = $this->studentId();
        
        $month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

        $attendanceRaw = $this->studentModel->getAttendance($studentId);

        $attendanceByDate = [];
        foreach ($attendanceRaw as $record) {
            $date = date('Y-m-d', strtotime($record['attendance_date']));
            $attendanceByDate[$date][] = $record;
        }

        // Get schedule for the student's current classroom
        $classroom = $this->studentModel->getCurrentClassroom($studentId);
        $schedulesByDay = [];
        if ($classroom) {
            $allSchedules = $this->studentModel->getSchedulesByClassroom((int) $classroom['id']);
            foreach ($allSchedules as $s) {
                $schedulesByDay[$s['day_of_week']][] = $s;
            }
        }

        $this->view('student/attendance', [
            'title' => 'Kehadiran',
            'month' => $month,
            'year' => $year,
            'attendanceByDate' => $attendanceByDate,
            'schedulesByDay' => $schedulesByDay,
        ]);
    }

    public function report(): void
    {
        Auth::requireRole('student');

        $this->view('student/report', [
            'title' => 'Nilai Rapor',
            'report' => $this->studentModel->getReport($this->studentId()),
        ]);
    }

    public function schedule(): void
    {
        Auth::requireRole('student');

        $studentId = $this->studentId();
        $classroom = $this->studentModel->getCurrentClassroom($studentId);

        $this->view('student/schedule', [
            'title' => 'Jadwal Pelajaran',
            'classroom' => $classroom,
            'schedules' => $classroom ? $this->studentModel->getSchedulesByClassroom((int) $classroom['id']) : [],
        ]);
    }
}
