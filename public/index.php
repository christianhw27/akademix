<?php

declare(strict_types=1);

require __DIR__ . '/../app/core/helpers.php';
require __DIR__ . '/../app/core/Database.php';
require __DIR__ . '/../app/core/Model.php';
require __DIR__ . '/../app/core/Controller.php';
require __DIR__ . '/../app/core/Session.php';
require __DIR__ . '/../app/core/Auth.php';
require __DIR__ . '/../app/core/App.php';

spl_autoload_register(function (string $class): void {
    $paths = [
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/models/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

date_default_timezone_set(app_config('app.timezone'));
Session::start();

$routes = [
    'GET' => [
        '' => ['DashboardController', 'home'],
        'login' => ['AuthController', 'login'],
        'logout' => ['AuthController', 'logout'],
        'dashboard' => ['DashboardController', 'index'],
        'admin' => ['AdminController', 'index'],
        'admin/teachers' => ['AdminController', 'teachers'],
        'admin/students' => ['AdminController', 'students'],

        'admin/subjects' => ['AdminController', 'subjects'],
        'admin/classrooms' => ['AdminController', 'classrooms'],
        'admin/schedules' => ['AdminController', 'schedules'],
        'admin/academic-years' => ['AdminController', 'academicYears'],
        'teacher' => ['TeacherController', 'index'],
        'teacher/materials' => ['TeacherController', 'materials'],
        'teacher/materials/edit' => ['TeacherController', 'editMaterial'],
        'teacher/assignments' => ['TeacherController', 'assignments'],
        'teacher/assignments/detail' => ['TeacherController', 'assignmentDetail'],
        'teacher/attendance' => ['TeacherController', 'attendance'],
        'teacher/grades' => ['TeacherController', 'grades'],
        'teacher/attendance/edit' => ['TeacherController', 'editAttendance'],
        'student' => ['StudentController', 'index'],
        'student/schedule' => ['StudentController', 'schedule'],
        'student/classroom' => ['StudentController', 'classroom'],
        'student/materials' => ['StudentController', 'materials'],
        'student/assignments' => ['StudentController', 'assignments'],
        'student/attendance' => ['StudentController', 'attendance'],
        'student/report' => ['StudentController', 'report'],
        'parent' => ['GuardianController', 'index'],
        'parent/report' => ['GuardianController', 'report'],
        'parent/attendance' => ['GuardianController', 'attendance'],
        'parent/assignments' => ['GuardianController', 'assignments'],
        'teacher/grades/export' => ['TeacherController', 'exportGrades'],
    ],
    'POST' => [
        'login' => ['AuthController', 'authenticate'],
        'admin/teachers/save' => ['AdminController', 'saveTeacher'],
        'admin/teachers/delete' => ['AdminController', 'deleteTeacher'],
        'admin/students/save' => ['AdminController', 'saveStudent'],
        'admin/students/delete' => ['AdminController', 'deleteStudent'],

        'admin/subjects/save' => ['AdminController', 'saveSubject'],
        'admin/subjects/delete' => ['AdminController', 'deleteSubject'],
        'admin/classrooms/save' => ['AdminController', 'saveClassroom'],
        'admin/classrooms/delete' => ['AdminController', 'deleteClassroom'],
        'admin/classrooms/enroll/save' => ['AdminController', 'saveEnrollment'],
        'admin/classrooms/enroll/delete' => ['AdminController', 'deleteEnrollment'],
        'admin/schedules/save' => ['AdminController', 'saveSchedule'],
        'admin/schedules/delete' => ['AdminController', 'deleteSchedule'],
        'admin/academic-years/save' => ['AdminController', 'saveAcademicYear'],
        'admin/academic-years/delete' => ['AdminController', 'deleteAcademicYear'],
        'teacher/materials/save' => ['TeacherController', 'saveMaterial'],
        'teacher/materials/delete' => ['TeacherController', 'deleteMaterial'],
        'teacher/assignments/save' => ['TeacherController', 'saveAssignment'],
        'teacher/attendance/save' => ['TeacherController', 'saveAttendance'],
        'teacher/attendance/update' => ['TeacherController', 'updateAttendance'],
        'teacher/grades/save' => ['TeacherController', 'saveGrade'],
        'teacher/grades/saveMass' => ['TeacherController', 'saveMassGrades'],
        'student/assignments/submit' => ['StudentController', 'submitAssignment'],
    ],
];

(new App())->run($routes);
