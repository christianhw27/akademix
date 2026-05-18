<?php

class AdminController extends Controller
{
    private AdminModel $adminModel;

    public function __construct()
    {
        $this->adminModel = $this->model('AdminModel');
    }

    public function index(): void
    {
        Auth::requireRole('admin');
        $this->view('admin/index', [
            'title' => 'Admin / TU',
            'stats' => $this->adminModel->getStats(),
        ]);
    }

    // ===== TEACHERS =====
    public function teachers(): void
    {
        Auth::requireRole('admin');
        $teachers = $this->adminModel->getTeachers();
        $allTeacherSubjects = [];
        foreach ($teachers as $t) {
            $allTeacherSubjects[$t['id']] = $this->adminModel->getTeacherSubjectIds((int) $t['id']);
        }
        $this->view('admin/teachers', [
            'title' => 'Data Guru',
            'teachers' => $teachers,
            'subjects' => $this->adminModel->getSubjects(),
            'allTeacherSubjects' => $allTeacherSubjects,
        ]);
    }

    public function saveTeacher(): void
    {
        Auth::requireRole('admin');
        try {
            $this->adminModel->saveTeacher($_POST);
            flash('success', 'Data guru berhasil disimpan.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menyimpan data guru: ' . $e->getMessage());
        }
        $this->redirect('admin/teachers');
    }

    public function deleteTeacher(): void
    {
        Auth::requireRole('admin');
        try {
            $this->adminModel->deleteTeacher((int) $this->request('id'));
            flash('success', 'Data guru berhasil dihapus.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menghapus data guru: ' . $e->getMessage());
        }
        $this->redirect('admin/teachers');
    }

    // ===== STUDENTS (now part of classrooms section) =====
    public function students(): void
    {
        Auth::requireRole('admin');
        $this->view('admin/students', [
            'title' => 'Data Siswa',
            'students' => $this->adminModel->getStudents(),
            'cohorts' => $this->adminModel->getCohortOptions(),
        ]);
    }

    public function saveStudent(): void
    {
        Auth::requireRole('admin');
        try {
            $this->adminModel->saveStudent($_POST);
            flash('success', 'Data siswa berhasil disimpan.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menyimpan data siswa: ' . $e->getMessage());
        }
        $this->back('admin/classrooms');
    }

    public function deleteStudent(): void
    {
        Auth::requireRole('admin');
        try {
            $this->adminModel->deleteStudent((int) $this->request('id'));
            flash('success', 'Data siswa berhasil dihapus.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menghapus data siswa: ' . $e->getMessage());
        }
        $this->back('admin/classrooms');
    }

    // ===== SUBJECTS =====
    public function subjects(): void
    {
        Auth::requireRole('admin');
        $this->view('admin/subjects', [
            'title' => 'Mata Pelajaran',
            'subjects' => $this->adminModel->getSubjects(),
        ]);
    }

    public function saveSubject(): void
    {
        Auth::requireRole('admin');
        try {
            $this->adminModel->saveSubject($_POST);
            flash('success', 'Data mata pelajaran berhasil disimpan.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menyimpan mata pelajaran: ' . $e->getMessage());
        }
        $this->redirect('admin/subjects');
    }

    public function deleteSubject(): void
    {
        Auth::requireRole('admin');
        try {
            $this->adminModel->deleteSubject((int) $this->request('id'));
            flash('success', 'Mata pelajaran berhasil dihapus.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menghapus mata pelajaran: ' . $e->getMessage());
        }
        $this->redirect('admin/subjects');
    }

    // ===== ACADEMIC YEARS =====
    public function academicYears(): void
    {
        Auth::requireRole('admin');
        $this->view('admin/academic_years', [
            'title' => 'Tahun Ajaran & Semester',
            'academicYears' => $this->adminModel->getAcademicYears(),
        ]);
    }

    public function saveAcademicYear(): void
    {
        Auth::requireRole('admin');
        try {
            $this->adminModel->saveAcademicYear($_POST);
            flash('success', 'Tahun ajaran berhasil disimpan.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menyimpan tahun ajaran: ' . $e->getMessage());
        }
        $this->redirect('admin/academic-years');
    }

    public function deleteAcademicYear(): void
    {
        Auth::requireRole('admin');
        try {
            $this->adminModel->deleteAcademicYear((int) $this->request('id'));
            flash('success', 'Tahun ajaran berhasil dihapus.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menghapus tahun ajaran: ' . $e->getMessage());
        }
        $this->redirect('admin/academic-years');
    }

    // ===== CLASSROOMS (merged with Siswa) =====
    public function classrooms(): void
    {
        Auth::requireRole('admin');

        $viewClassroom = $this->request('view');
        $viewStudent = $this->request('student');

        // Detail siswa
        if ($viewStudent) {
            $student = $this->adminModel->getStudentDetail((int) $viewStudent);
            if (!$student) {
                flash('error', 'Siswa tidak ditemukan.');
                $this->redirect('admin/classrooms');
            }
            $this->view('admin/student_detail', [
                'title' => $student['full_name'],
                'student' => $student,
                'classroomId' => $viewClassroom,
            ]);
            return;
        }

        // Detail kelas (daftar siswa)
        if ($viewClassroom) {
            $classroom = $this->adminModel->getClassroomDetail((int) $viewClassroom);
            if (!$classroom) {
                flash('error', 'Kelas tidak ditemukan.');
                $this->redirect('admin/classrooms');
            }
            $this->view('admin/classroom_detail', [
                'title' => $classroom['name'],
                'classroom' => $classroom,
                'classroomStudents' => $this->adminModel->getClassroomStudents((int) $viewClassroom),
                'students' => $this->adminModel->getStudentOptions(),
                'cohorts' => $this->adminModel->getCohortOptions(),
            ]);
            return;
        }

        $this->view('admin/classrooms', [
            'title' => 'Kelas & Siswa',
            'stats' => $this->adminModel->getStats(),
            'academicYears' => $this->adminModel->getAcademicYears(),
            'classrooms' => $this->adminModel->getClassrooms(),
            'teachers' => $this->adminModel->getTeacherOptions(),
            'students' => $this->adminModel->getStudentOptions(),
            'cohorts' => $this->adminModel->getCohortOptions(),
            'classes' => $this->adminModel->getClassOptions(),
        ]);
    }

    public function saveClassroom(): void
    {
        Auth::requireRole('admin');
        try {
            $this->adminModel->saveClassroom($_POST);
            flash('success', 'Data kelas berhasil disimpan.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menyimpan kelas: ' . $e->getMessage());
        }
        $this->redirect('admin/classrooms');
    }

    public function deleteClassroom(): void
    {
        Auth::requireRole('admin');
        try {
            $this->adminModel->deleteClassroom((int) $this->request('id'));
            flash('success', 'Data kelas berhasil dihapus.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menghapus kelas: ' . $e->getMessage());
        }
        $this->redirect('admin/classrooms');
    }

    public function saveEnrollment(): void
    {
        Auth::requireRole('admin');
        try {
            $this->adminModel->saveEnrollment((int) $this->request('classroom_id'), (int) $this->request('student_id'));
            flash('success', 'Siswa berhasil ditempatkan ke kelas.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menempatkan siswa: ' . $e->getMessage());
        }
        $this->back('admin/classrooms');
    }

    public function deleteEnrollment(): void
    {
        Auth::requireRole('admin');
        try {
            $this->adminModel->deleteEnrollment((int) $this->request('id'));
            flash('success', 'Penempatan siswa berhasil dihapus.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menghapus penempatan siswa: ' . $e->getMessage());
        }
        $this->back('admin/classrooms');
    }

    // ===== SCHEDULES (folder by classroom) =====
    public function schedules(): void
    {
        Auth::requireRole('admin');

        $viewClassroom = $this->request('classroom');

        if ($viewClassroom) {
            $classroom = $this->adminModel->getClassroomDetail((int) $viewClassroom);
            if (!$classroom) {
                flash('error', 'Kelas tidak ditemukan.');
                $this->redirect('admin/schedules');
            }
            $this->view('admin/schedule_classroom', [
                'title' => 'Jadwal ' . $classroom['name'],
                'classroom' => $classroom,
                'schedules' => $this->adminModel->getSchedulesByClassroom((int) $viewClassroom),
                'subjects' => $this->adminModel->getSubjects(),
                'teachers' => $this->adminModel->getTeacherOptions(),
            ]);
            return;
        }

        // Main: pick a classroom
        $this->view('admin/schedules', [
            'title' => 'Jadwal Pelajaran',
            'academicYears' => $this->adminModel->getAcademicYears(),
            'classrooms' => $this->adminModel->getClassrooms(),
        ]);
    }

    public function saveSchedule(): void
    {
        Auth::requireRole('admin');
        try {
            $this->adminModel->saveSchedule($_POST);
            flash('success', 'Jadwal berhasil disimpan.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menyimpan jadwal: ' . $e->getMessage());
        }
        $classroomId = $this->request('classroom_id');
        $this->redirect('admin/schedules&classroom=' . $classroomId);
    }

    public function deleteSchedule(): void
    {
        Auth::requireRole('admin');
        $classroomId = $this->request('classroom_id');
        try {
            $this->adminModel->deleteSchedule((int) $this->request('id'));
            flash('success', 'Jadwal berhasil dihapus.');
        } catch (\Throwable $e) {
            flash('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
        $this->redirect('admin/schedules&classroom=' . $classroomId);
    }
}
