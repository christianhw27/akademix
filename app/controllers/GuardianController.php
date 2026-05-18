<?php

class GuardianController extends Controller
{
    private GuardianPortalModel $guardianModel;

    public function __construct()
    {
        $this->guardianModel = $this->model('GuardianPortalModel');
    }

    private function studentId(): int
    {
        $profile = $this->guardianModel->getStudentProfileByUserId((int) Auth::id());
        if (!$profile) {
            throw new RuntimeException('Profil anak tidak ditemukan.');
        }
        return (int) $profile['id'];
    }

    public function index(): void
    {
        Auth::requireRole('parent');
        $studentId = $this->studentId();
        
        $this->view('parent/index', [
            'title' => 'Portal Orang Tua',
            'stats' => $this->guardianModel->getStats($studentId),
        ]);
    }

    public function report(): void
    {
        Auth::requireRole('parent');
        $studentId = $this->studentId();

        $this->view('parent/report', [
            'title' => 'Rapor Anak',
            'report' => $this->guardianModel->getReports($studentId),
        ]);
    }

    public function attendance(): void
    {
        Auth::requireRole('parent');
        $studentId = $this->studentId();

        $this->view('parent/attendance', [
            'title' => 'Kehadiran Anak',
            'attendance' => $this->guardianModel->getAttendance($studentId),
        ]);
    }

    public function assignments(): void
    {
        Auth::requireRole('parent');
        $studentId = $this->studentId();

        $this->view('parent/assignments', [
            'title' => 'Tugas Anak',
            'assignments' => $this->guardianModel->getAssignments($studentId),
        ]);
    }
}
