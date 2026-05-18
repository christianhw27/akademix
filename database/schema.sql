SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS attendance_records;
DROP TABLE IF EXISTS attendance_sessions;
DROP TABLE IF EXISTS assignment_submissions;
DROP TABLE IF EXISTS grades;
DROP TABLE IF EXISTS assignments;
DROP TABLE IF EXISTS materials;
DROP TABLE IF EXISTS schedules;
DROP TABLE IF EXISTS classroom_students;
DROP TABLE IF EXISTS classrooms;
DROP TABLE IF EXISTS classes;
DROP TABLE IF EXISTS teacher_subjects;
DROP TABLE IF EXISTS subjects;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS cohorts;
DROP TABLE IF EXISTS guardians;
DROP TABLE IF EXISTS teachers;
DROP TABLE IF EXISTS academic_years;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'teacher', 'student', 'parent') NOT NULL,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NULL UNIQUE,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE academic_years (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year_label VARCHAR(20) NOT NULL,
    semester ENUM('ganjil', 'genap') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    nip VARCHAR(50) NOT NULL UNIQUE,
    phone VARCHAR(40) NULL,
    address TEXT NULL,
    CONSTRAINT fk_teachers_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE guardians (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    phone VARCHAR(40) NULL,
    address TEXT NULL,
    CONSTRAINT fk_guardians_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE cohorts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year_name VARCHAR(20) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    guardian_id INT NULL,
    cohort_id INT NOT NULL,
    nis VARCHAR(50) NOT NULL UNIQUE,
    gender ENUM('L', 'P') NOT NULL,
    birth_date DATE NULL,
    phone VARCHAR(40) NULL,
    address TEXT NULL,
    CONSTRAINT fk_students_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_students_guardian FOREIGN KEY (guardian_id) REFERENCES guardians(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_students_cohort FOREIGN KEY (cohort_id) REFERENCES cohorts(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Many-to-many: teacher can teach multiple subjects
CREATE TABLE teacher_subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    subject_id INT NOT NULL,
    UNIQUE KEY uq_teacher_subject (teacher_id, subject_id),
    CONSTRAINT fk_ts_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ts_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE classrooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    academic_year_id INT NOT NULL,
    class_id INT NOT NULL,
    grade_level INT NOT NULL,
    homeroom_teacher_id INT NULL,
    CONSTRAINT fk_classrooms_academic_year FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_classrooms_class FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_classrooms_teacher FOREIGN KEY (homeroom_teacher_id) REFERENCES teachers(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE classroom_students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classroom_id INT NOT NULL,
    student_id INT NOT NULL,
    UNIQUE KEY uq_classroom_student (classroom_id, student_id),
    CONSTRAINT fk_classroom_students_classroom FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_classroom_students_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classroom_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    day_of_week ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    CONSTRAINT fk_schedules_classroom FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_schedules_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_schedules_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    classroom_id INT NOT NULL,
    teacher_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_materials_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_materials_classroom FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_materials_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_materials_academic_year FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    classroom_id INT NOT NULL,
    teacher_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    due_date DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_assignments_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_assignments_classroom FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_assignments_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_assignments_academic_year FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE assignment_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id INT NOT NULL,
    student_id INT NOT NULL,
    content TEXT NOT NULL,
    status ENUM('belum', 'submitted', 'reviewed') NOT NULL DEFAULT 'submitted',
    submitted_at DATETIME NULL,
    score DECIMAL(5,2) NULL,
    feedback TEXT NULL,
    UNIQUE KEY uq_assignment_submission (assignment_id, student_id),
    CONSTRAINT fk_assignment_submissions_assignment FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_assignment_submissions_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE attendance_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classroom_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    notes VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_attendance_sessions_classroom FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_attendance_sessions_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_attendance_sessions_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_attendance_sessions_academic_year FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE attendance_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attendance_session_id INT NOT NULL,
    student_id INT NOT NULL,
    status ENUM('hadir', 'izin', 'sakit', 'alpha') NOT NULL DEFAULT 'hadir',
    notes VARCHAR(255) NULL,
    CONSTRAINT fk_attendance_records_session FOREIGN KEY (attendance_session_id) REFERENCES attendance_sessions(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_attendance_records_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    classroom_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    semester ENUM('ganjil', 'genap') NOT NULL,
    grade_type ENUM('harian', 'tugas', 'rapor') NOT NULL,
    title VARCHAR(150) NOT NULL,
    score DECIMAL(5,2) NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_grades_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_grades_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_grades_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_grades_classroom FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_grades_academic_year FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===========================
-- SEED DATA
-- ===========================
-- Password for all demo accounts: password
-- Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

INSERT INTO academic_years (id, year_label, semester, start_date, end_date, is_active) VALUES
(1, '2024/2025', 'ganjil', '2024-07-15', '2024-12-20', 0),
(2, '2024/2025', 'genap', '2025-01-06', '2025-06-20', 0),
(3, '2025/2026', 'ganjil', '2025-07-15', '2025-12-20', 0),
(4, '2025/2026', 'genap', '2026-01-06', '2026-06-20', 1);

INSERT INTO users (id, username, password_hash, role, full_name, email, is_active) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Admin Akademix', 'admin@akademix.test', 1),
(2, 'guru.budi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'Budi Santoso, S.Pd.', 'budi@akademix.test', 1),
(3, 'guru.sari', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'Sari Wulandari, M.Pd.', 'sari@akademix.test', 1),
(4, 'guru.deni', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'Deni Permana, S.Pd.', 'deni@akademix.test', 1),
(5, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'parent', 'Andi Pratama', 'andi@akademix.test', 1),
(6, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'parent', 'Maya Lestari', 'maya@akademix.test', 1),
(7, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'Raka Mahendra', 'raka@akademix.test', 1),
(8, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'Nadia Putri', 'nadia@akademix.test', 1),
(9, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'Fajar Ramadhan', 'fajar@akademix.test', 1),
(10, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'Sinta Dewi', 'sinta@akademix.test', 1),
(11, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'Ahmad Rizky', 'rizky@akademix.test', 1);

INSERT INTO teachers (id, user_id, nip, phone, address) VALUES
(1, 2, '198712012010011001', '081234567890', 'Jl. Melati No. 10'),
(2, 3, '198903022011012002', '081298765432', 'Jl. Kenanga No. 5'),
(3, 4, '199005032012011003', '081355556666', 'Jl. Anggrek No. 8');

INSERT INTO guardians (id, user_id, phone, address) VALUES
(1, 5, '081211111111', 'Jl. Mawar No. 21'),
(2, 6, '081222222222', 'Jl. Flamboyan No. 18');

-- Cohorts (Angkatan)
INSERT INTO cohorts (id, year_name) VALUES
(1, '2024'),
(2, '2025');

-- Students: guardian_id auto-connects parent to child
-- cohort_id determines which "angkatan" folder the student belongs to
INSERT INTO students (id, user_id, guardian_id, cohort_id, nis, gender, birth_date, phone, address) VALUES
(1, 7, 1, 1, '20240001', 'L', '2010-04-12', NULL, 'Perum Griya Asri Blok A1'),
(2, 8, 1, 1, '20240002', 'P', '2010-09-08', NULL, 'Perum Griya Asri Blok A1'),
(3, 9, 2, 1, '20240003', 'L', '2009-11-30', NULL, 'Jl. Flamboyan No. 18'),
(4, 10, 2, 2, '20250001', 'P', '2011-03-15', NULL, 'Jl. Flamboyan No. 18'),
(5, 11, NULL, 2, '20250002', 'L', '2011-07-22', NULL, 'Jl. Dahlia No. 3');

-- Subjects: no longer tied to a single teacher
INSERT INTO subjects (id, code, name, description) VALUES
(1, 'MAT', 'Matematika', 'Materi dasar hingga lanjutan matematika sekolah.'),
(2, 'BIO', 'Biologi', 'Pembelajaran konsep dasar biologi.'),
(3, 'BIN', 'Bahasa Indonesia', 'Keterampilan membaca, menulis, dan presentasi.'),
(4, 'FIS', 'Fisika', 'Konsep-konsep dasar fisika.'),
(5, 'BIG', 'Bahasa Inggris', 'Keterampilan bahasa Inggris.');

-- Teacher-Subject mapping (many-to-many)
INSERT INTO teacher_subjects (teacher_id, subject_id) VALUES
(1, 1), -- Budi → Matematika
(1, 3), -- Budi → Bahasa Indonesia
(2, 2), -- Sari → Biologi
(2, 4), -- Sari → Fisika
(3, 5); -- Deni → Bahasa Inggris

-- Classes (Master data kelas)
INSERT INTO classes (id, name) VALUES
(1, 'IPA 1'),
(2, 'IPA 2'),
(3, 'IPS 1');

INSERT INTO classrooms (id, academic_year_id, class_id, grade_level, homeroom_teacher_id) VALUES
(1, 1, 1, 10, 1), -- X IPA 1
(2, 2, 1, 10, 1), -- X IPA 1
(3, 3, 1, 11, 1), -- XI IPA 1
(4, 4, 1, 11, 1), -- XI IPA 1
(5, 4, 1, 10, 2), -- X IPA 1
(6, 4, 2, 10, 3); -- X IPA 2

INSERT INTO classroom_students (id, classroom_id, student_id) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 2, 2),
(5, 2, 3),
(6, 3, 1),
(7, 3, 2),
(8, 3, 3),
(9, 4, 1),
(10, 4, 2),
(11, 4, 3),
(12, 5, 4),
(13, 5, 5),
(14, 6, 4);

INSERT INTO schedules (id, classroom_id, subject_id, teacher_id, day_of_week, start_time, end_time) VALUES
(1, 4, 1, 1, 'Senin', '07:30:00', '09:00:00'),
(2, 4, 2, 2, 'Senin', '09:15:00', '10:45:00'),
(3, 4, 3, 1, 'Selasa', '07:30:00', '09:00:00'),
(4, 4, 4, 2, 'Selasa', '09:15:00', '10:45:00'),
(5, 4, 5, 3, 'Rabu', '07:30:00', '09:00:00'),
(6, 4, 1, 1, 'Rabu', '09:15:00', '10:45:00'),
(7, 4, 2, 2, 'Kamis', '07:30:00', '09:00:00'),
(8, 4, 3, 1, 'Kamis', '09:15:00', '10:45:00'),
(9, 4, 4, 2, 'Jumat', '07:30:00', '09:00:00'),
(10, 5, 1, 1, 'Senin', '07:30:00', '09:00:00'),
(11, 5, 5, 3, 'Selasa', '07:30:00', '09:00:00'),
(12, 5, 2, 2, 'Rabu', '07:30:00', '09:00:00'),
(13, 6, 3, 1, 'Senin', '07:30:00', '09:00:00'),
(14, 6, 4, 2, 'Selasa', '07:30:00', '09:00:00');

INSERT INTO materials (id, subject_id, classroom_id, teacher_id, academic_year_id, title, content, created_at) VALUES
(1, 1, 4, 1, 4, 'Fungsi Kuadrat', 'Penjelasan bentuk umum fungsi kuadrat dan grafik.', '2026-02-10 09:00:00'),
(2, 2, 4, 2, 4, 'Struktur Sel', 'Materi biologi mengenai organel sel.', '2026-02-15 10:00:00'),
(3, 5, 5, 3, 4, 'Tenses Overview', 'Overview of English tenses.', '2026-03-01 08:00:00');

INSERT INTO assignments (id, subject_id, classroom_id, teacher_id, academic_year_id, title, description, due_date, created_at) VALUES
(1, 1, 4, 1, 4, 'Latihan Fungsi Kuadrat', 'Kerjakan 10 soal fungsi kuadrat.', '2026-05-20 23:59:00', '2026-05-01 08:00:00'),
(2, 3, 4, 1, 4, 'Resume Artikel', 'Resume artikel berita pendidikan minimal 300 kata.', '2026-05-18 21:00:00', '2026-05-03 09:00:00'),
(3, 2, 4, 2, 4, 'Praktikum Sel', 'Kirimkan catatan observasi praktikum sel.', '2026-05-22 20:00:00', '2026-05-04 10:00:00');

INSERT INTO assignment_submissions (id, assignment_id, student_id, content, status, submitted_at, score, feedback) VALUES
(1, 1, 1, 'Jawaban latihan fungsi kuadrat sudah saya kerjakan.', 'submitted', '2026-05-09 19:30:00', NULL, NULL),
(2, 2, 2, 'Resume artikel mengenai literasi digital.', 'submitted', '2026-05-10 18:15:00', NULL, NULL);

INSERT INTO attendance_sessions (id, classroom_id, subject_id, teacher_id, academic_year_id, attendance_date, notes, created_at) VALUES
(1, 4, 1, 1, 4, '2026-05-07', 'Pembelajaran reguler', '2026-05-07 08:00:00'),
(2, 4, 2, 2, 4, '2026-05-08', 'Praktikum laboratorium', '2026-05-08 08:00:00');

INSERT INTO attendance_records (id, attendance_session_id, student_id, status, notes) VALUES
(1, 1, 1, 'hadir', NULL),
(2, 1, 2, 'izin', 'Izin dokter'),
(3, 1, 3, 'hadir', NULL),
(4, 2, 1, 'hadir', NULL),
(5, 2, 2, 'hadir', NULL),
(6, 2, 3, 'sakit', 'Sakit demam');

INSERT INTO grades (id, student_id, subject_id, teacher_id, classroom_id, academic_year_id, semester, grade_type, title, score, notes, created_at) VALUES
(1, 1, 1, 1, 4, 4, 'genap', 'harian', 'Kuis Aljabar', 88.00, 'Pemahaman baik.', '2026-05-01 08:00:00'),
(2, 1, 1, 1, 4, 4, 'genap', 'tugas', 'PR Fungsi Kuadrat', 90.00, 'Tepat waktu.', '2026-05-03 08:00:00'),
(3, 1, 1, 1, 4, 4, 'genap', 'rapor', 'Nilai Akhir Matematika', 89.00, 'Konsisten meningkat.', '2026-05-05 08:00:00'),
(4, 2, 3, 1, 4, 4, 'genap', 'rapor', 'Nilai Akhir B. Indonesia', 84.00, 'Aktif di kelas.', '2026-05-05 09:00:00'),
(5, 3, 2, 2, 4, 4, 'genap', 'rapor', 'Nilai Akhir Biologi', 91.00, 'Sangat baik.', '2026-05-06 10:00:00');
