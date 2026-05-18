-- Dummy attendance data for student_id=1, classroom_id=4, academic_year_id=4
-- May 2026 weekdays

-- Helper: Map dates to day_of_week
-- May 1 (Jumat), May 4 (Senin), May 5 (Selasa), May 6 (Rabu), May 7 (Kamis), May 8 (Jumat)
-- May 11 (Senin), May 12 (Selasa), May 13 (Rabu), May 14 (Kamis), May 15 (Jumat)

-- Senin schedules: subject_ids 19,28,30,24 teacher_ids 19,28,30,24
-- Selasa schedules: subject_ids 26,25,29,17 teacher_ids 26,25,29,17
-- Rabu schedules: subject_ids 24,16,19,27 teacher_ids 24,16,19,27
-- Kamis schedules: subject_ids 30,29,28,18 teacher_ids 30,29,28,18
-- Jumat schedules: subject_ids 28,19,19,24 teacher_ids 28,19,19,24

-- ============ May 1 (Jumat) - HIJAU (all hadir) ============
INSERT INTO attendance_sessions (classroom_id, subject_id, teacher_id, academic_year_id, attendance_date) VALUES
(4, 28, 28, 4, '2026-05-01'), (4, 19, 19, 4, '2026-05-01'), (4, 19, 19, 4, '2026-05-01'), (4, 24, 24, 4, '2026-05-01');
SET @s1 = LAST_INSERT_ID();
INSERT INTO attendance_records (attendance_session_id, student_id, status) VALUES
(@s1, 1, 'hadir'), (@s1+1, 1, 'hadir'), (@s1+2, 1, 'hadir'), (@s1+3, 1, 'hadir');

-- ============ May 4 (Senin) - HIJAU ============
INSERT INTO attendance_sessions (classroom_id, subject_id, teacher_id, academic_year_id, attendance_date) VALUES
(4, 19, 19, 4, '2026-05-04'), (4, 28, 28, 4, '2026-05-04'), (4, 30, 30, 4, '2026-05-04'), (4, 24, 24, 4, '2026-05-04');
SET @s2 = LAST_INSERT_ID();
INSERT INTO attendance_records (attendance_session_id, student_id, status) VALUES
(@s2, 1, 'hadir'), (@s2+1, 1, 'hadir'), (@s2+2, 1, 'hadir'), (@s2+3, 1, 'hadir');

-- ============ May 5 (Selasa) - KUNING (hadir sebagian, 1 izin) ============
INSERT INTO attendance_sessions (classroom_id, subject_id, teacher_id, academic_year_id, attendance_date) VALUES
(4, 26, 26, 4, '2026-05-05'), (4, 25, 25, 4, '2026-05-05'), (4, 29, 29, 4, '2026-05-05'), (4, 17, 17, 4, '2026-05-05');
SET @s3 = LAST_INSERT_ID();
INSERT INTO attendance_records (attendance_session_id, student_id, status) VALUES
(@s3, 1, 'hadir'), (@s3+1, 1, 'hadir'), (@s3+2, 1, 'izin'), (@s3+3, 1, 'hadir');

-- ============ May 6 (Rabu) - HIJAU ============
INSERT INTO attendance_sessions (classroom_id, subject_id, teacher_id, academic_year_id, attendance_date) VALUES
(4, 24, 24, 4, '2026-05-06'), (4, 16, 16, 4, '2026-05-06'), (4, 19, 19, 4, '2026-05-06'), (4, 27, 27, 4, '2026-05-06');
SET @s4 = LAST_INSERT_ID();
INSERT INTO attendance_records (attendance_session_id, student_id, status) VALUES
(@s4, 1, 'hadir'), (@s4+1, 1, 'hadir'), (@s4+2, 1, 'hadir'), (@s4+3, 1, 'hadir');

-- ============ May 7 (Kamis) - MERAH (semua alpha) ============
INSERT INTO attendance_sessions (classroom_id, subject_id, teacher_id, academic_year_id, attendance_date) VALUES
(4, 30, 30, 4, '2026-05-07'), (4, 29, 29, 4, '2026-05-07'), (4, 28, 28, 4, '2026-05-07'), (4, 18, 18, 4, '2026-05-07');
SET @s5 = LAST_INSERT_ID();
INSERT INTO attendance_records (attendance_session_id, student_id, status) VALUES
(@s5, 1, 'alpha'), (@s5+1, 1, 'alpha'), (@s5+2, 1, 'alpha'), (@s5+3, 1, 'alpha');

-- ============ May 8 (Jumat) - HIJAU ============
INSERT INTO attendance_sessions (classroom_id, subject_id, teacher_id, academic_year_id, attendance_date) VALUES
(4, 28, 28, 4, '2026-05-08'), (4, 19, 19, 4, '2026-05-08'), (4, 19, 19, 4, '2026-05-08'), (4, 24, 24, 4, '2026-05-08');
SET @s6 = LAST_INSERT_ID();
INSERT INTO attendance_records (attendance_session_id, student_id, status) VALUES
(@s6, 1, 'hadir'), (@s6+1, 1, 'hadir'), (@s6+2, 1, 'hadir'), (@s6+3, 1, 'hadir');

-- ============ May 11 (Senin) - KUNING (sakit 2 mapel) ============
INSERT INTO attendance_sessions (classroom_id, subject_id, teacher_id, academic_year_id, attendance_date) VALUES
(4, 19, 19, 4, '2026-05-11'), (4, 28, 28, 4, '2026-05-11'), (4, 30, 30, 4, '2026-05-11'), (4, 24, 24, 4, '2026-05-11');
SET @s7 = LAST_INSERT_ID();
INSERT INTO attendance_records (attendance_session_id, student_id, status) VALUES
(@s7, 1, 'sakit'), (@s7+1, 1, 'sakit'), (@s7+2, 1, 'hadir'), (@s7+3, 1, 'hadir');

-- ============ May 12 (Selasa) - HIJAU ============
INSERT INTO attendance_sessions (classroom_id, subject_id, teacher_id, academic_year_id, attendance_date) VALUES
(4, 26, 26, 4, '2026-05-12'), (4, 25, 25, 4, '2026-05-12'), (4, 29, 29, 4, '2026-05-12'), (4, 17, 17, 4, '2026-05-12');
SET @s8 = LAST_INSERT_ID();
INSERT INTO attendance_records (attendance_session_id, student_id, status) VALUES
(@s8, 1, 'hadir'), (@s8+1, 1, 'hadir'), (@s8+2, 1, 'hadir'), (@s8+3, 1, 'hadir');

-- ============ May 13 (Rabu) - HIJAU ============
INSERT INTO attendance_sessions (classroom_id, subject_id, teacher_id, academic_year_id, attendance_date) VALUES
(4, 24, 24, 4, '2026-05-13'), (4, 16, 16, 4, '2026-05-13'), (4, 19, 19, 4, '2026-05-13'), (4, 27, 27, 4, '2026-05-13');
SET @s9 = LAST_INSERT_ID();
INSERT INTO attendance_records (attendance_session_id, student_id, status) VALUES
(@s9, 1, 'hadir'), (@s9+1, 1, 'hadir'), (@s9+2, 1, 'hadir'), (@s9+3, 1, 'hadir');

-- ============ May 14 (Kamis) - KUNING (izin 1 mapel) ============
INSERT INTO attendance_sessions (classroom_id, subject_id, teacher_id, academic_year_id, attendance_date) VALUES
(4, 30, 30, 4, '2026-05-14'), (4, 29, 29, 4, '2026-05-14'), (4, 28, 28, 4, '2026-05-14'), (4, 18, 18, 4, '2026-05-14');
SET @s10 = LAST_INSERT_ID();
INSERT INTO attendance_records (attendance_session_id, student_id, status) VALUES
(@s10, 1, 'hadir'), (@s10+1, 1, 'izin'), (@s10+2, 1, 'hadir'), (@s10+3, 1, 'hadir');

-- ============ May 15 (Jumat) - MERAH ============
INSERT INTO attendance_sessions (classroom_id, subject_id, teacher_id, academic_year_id, attendance_date) VALUES
(4, 28, 28, 4, '2026-05-15'), (4, 19, 19, 4, '2026-05-15'), (4, 19, 19, 4, '2026-05-15'), (4, 24, 24, 4, '2026-05-15');
SET @s11 = LAST_INSERT_ID();
INSERT INTO attendance_records (attendance_session_id, student_id, status) VALUES
(@s11, 1, 'alpha'), (@s11+1, 1, 'alpha'), (@s11+2, 1, 'alpha'), (@s11+3, 1, 'alpha');
