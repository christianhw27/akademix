<?php

class DashboardController extends Controller
{
    public function home(): void
    {
        if (Auth::check()) {
            $this->redirect('dashboard');
        }

        $this->redirect('login');
    }

    public function index(): void
    {
        Auth::requireLogin();

        $user = Auth::user();
        if ($user['role'] === 'teacher') {
            $this->redirect('teacher');
        }

        $summary = $this->model('DashboardModel')->getSummary($user);

        $todaySchedule = [];
        if ($user['role'] === 'student') {
            $studentModel = $this->model('StudentModel');
            $classroom = $studentModel->getCurrentClassroom((int) $user['profile_id']);
            if ($classroom) {
                $allSchedules = $studentModel->getSchedulesByClassroom((int) $classroom['id']);
                $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $todayName = $days[date('w')];
                foreach ($allSchedules as $s) {
                    if ($s['day_of_week'] === $todayName) {
                        $todaySchedule[] = $s;
                    }
                }
            }
        }

        $this->view('dashboard/index', [
            'title' => 'Ringkasan',
            'user' => $user,
            'summary' => $summary,
            'todaySchedule' => $todaySchedule,
        ]);
    }
}
