-- Dummy Materi & Tugas untuk Raka Mahendra (student_id=1)
-- Kelas IPA 1 (classroom_id=4), Tahun Ajaran 2025/2026 Genap (academic_year_id=4)

-- ==========================================
-- MATERI
-- ==========================================

-- Fisika (subject_id=19, teacher_id=19 Hendra Wijaya)
INSERT INTO materials (subject_id, classroom_id, teacher_id, academic_year_id, title, content, created_at) VALUES
(19, 4, 19, 4, 'Hukum Newton tentang Gerak', 'Hukum I Newton (Inersia): Setiap benda akan tetap diam atau bergerak lurus beraturan kecuali ada gaya yang bekerja padanya.\n\nHukum II Newton: F = m × a\nGaya yang bekerja pada benda sama dengan massa dikali percepatan.\n\nHukum III Newton: Setiap aksi memiliki reaksi yang sama besar dan berlawanan arah.', '2026-04-14 08:00:00'),
(19, 4, 19, 4, 'Usaha dan Energi', 'Usaha (W) = F × s × cos θ\n\nEnergi Kinetik: Ek = ½mv²\nEnergi Potensial: Ep = mgh\n\nHukum Kekekalan Energi: Energi tidak dapat diciptakan atau dimusnahkan, hanya dapat berubah bentuk.', '2026-04-28 08:00:00'),
(19, 4, 19, 4, 'Gelombang dan Bunyi', 'Gelombang adalah rambatan energi tanpa disertai perpindahan materi.\n\nJenis gelombang:\n1. Gelombang Transversal\n2. Gelombang Longitudinal\n\nRumus: v = λ × f', '2026-05-12 08:00:00');

-- Matematika (subject_id=16, teacher_id=16 Budi Santoso)
INSERT INTO materials (subject_id, classroom_id, teacher_id, academic_year_id, title, content, created_at) VALUES
(16, 4, 16, 4, 'Matriks dan Operasinya', 'Matriks adalah susunan bilangan yang diatur dalam baris dan kolom.\n\nOperasi Matriks:\n1. Penjumlahan dan Pengurangan\n2. Perkalian Skalar\n3. Perkalian Matriks\n4. Transpose Matriks', '2026-04-07 09:00:00'),
(16, 4, 16, 4, 'Determinan dan Invers Matriks', 'Determinan matriks 2×2:\ndet(A) = ad - bc\n\nInvers matriks:\nA⁻¹ = (1/det(A)) × adj(A)\n\nSyarat: det(A) ≠ 0', '2026-04-21 09:00:00'),
(16, 4, 16, 4, 'Sistem Persamaan Linear Tiga Variabel', 'SPLTV adalah sistem yang terdiri dari tiga persamaan linear dengan tiga variabel (x, y, z).\n\nMetode penyelesaian:\n1. Eliminasi\n2. Substitusi\n3. Campuran\n4. Menggunakan Matriks (Metode Cramer)', '2026-05-05 09:00:00');

-- Ekonomi (subject_id=24, teacher_id=24 Dewi Lestari)
INSERT INTO materials (subject_id, classroom_id, teacher_id, academic_year_id, title, content, created_at) VALUES
(24, 4, 24, 4, 'Permintaan dan Penawaran', 'Hukum Permintaan: Jika harga naik, jumlah barang yang diminta turun (ceteris paribus).\nHukum Penawaran: Jika harga naik, jumlah barang yang ditawarkan naik.\n\nKeseimbangan pasar terjadi saat Qd = Qs.', '2026-04-10 10:00:00'),
(24, 4, 24, 4, 'Elastisitas Harga', 'Elastisitas harga permintaan mengukur seberapa responsif jumlah barang yang diminta terhadap perubahan harga.\n\nEd = (% perubahan Qd) / (% perubahan P)\n\nJenis:\n- Elastis (Ed > 1)\n- Inelastis (Ed < 1)\n- Uniter (Ed = 1)', '2026-05-01 10:00:00');

-- Bahasa Indonesia (subject_id=17, teacher_id=17 Siti Aminah)
INSERT INTO materials (subject_id, classroom_id, teacher_id, academic_year_id, title, content, created_at) VALUES
(17, 4, 17, 4, 'Teks Eksposisi', 'Teks eksposisi adalah teks yang bertujuan memberikan informasi atau penjelasan secara jelas dan padat.\n\nStruktur:\n1. Tesis (Pernyataan Pendapat)\n2. Argumentasi (Alasan)\n3. Penegasan Ulang\n\nCiri kebahasaan: menggunakan kata teknis, konjungsi kausalitas, dan kata persuasif.', '2026-04-15 11:00:00'),
(17, 4, 17, 4, 'Teks Debat', 'Debat adalah kegiatan beradu argumentasi antara dua pihak (afirmasi dan negasi) mengenai suatu mosi.\n\nStruktur debat:\n1. Pengenalan\n2. Penyampaian Argumentasi\n3. Bantahan/Sanggahan\n4. Kesimpulan', '2026-05-06 11:00:00');

-- Pendidikan Pancasila (subject_id=27, teacher_id=27 Tri Hastuti)
INSERT INTO materials (subject_id, classroom_id, teacher_id, academic_year_id, title, content, created_at) VALUES
(27, 4, 27, 4, 'Ancaman terhadap NKRI', 'Ancaman terhadap integrasi nasional meliputi:\n\n1. Ancaman Militer: agresi, pelanggaran wilayah, spionase\n2. Ancaman Non-Militer: ideologi, politik, ekonomi, sosial budaya\n\nStrategi menghadapi ancaman: diplomasi, pertahanan negara, dan bela negara.', '2026-04-16 12:00:00');

-- Seni Budaya (subject_id=29, teacher_id=29 Maya Indah)
INSERT INTO materials (subject_id, classroom_id, teacher_id, academic_year_id, title, content, created_at) VALUES
(29, 4, 29, 4, 'Seni Rupa Dua Dimensi', 'Seni rupa dua dimensi adalah karya seni yang memiliki panjang dan lebar (tanpa ruang).\n\nContoh: lukisan, gambar, fotografi, batik.\n\nUnsur-unsur seni rupa: garis, bidang, warna, tekstur, gelap-terang.', '2026-04-22 13:00:00');

-- ==========================================
-- TUGAS (ASSIGNMENTS)
-- ==========================================

-- Fisika
INSERT INTO assignments (subject_id, classroom_id, teacher_id, academic_year_id, title, description, due_date, created_at) VALUES
(19, 4, 19, 4, 'Latihan Soal Hukum Newton', 'Kerjakan 10 soal latihan tentang Hukum Newton I, II, dan III. Gunakan konsep F = ma untuk soal hitungan. Tuliskan langkah-langkah penyelesaiannya secara lengkap.', '2026-04-21 23:59:00', '2026-04-14 08:30:00'),
(19, 4, 19, 4, 'Laporan Praktikum Usaha dan Energi', 'Buatlah laporan praktikum tentang percobaan kekekalan energi mekanik menggunakan bidang miring. Format laporan: Tujuan, Alat & Bahan, Langkah Kerja, Data Pengamatan, Analisis, dan Kesimpulan.', '2026-05-10 23:59:00', '2026-04-28 09:00:00'),
(19, 4, 19, 4, 'Kuis Gelombang dan Bunyi', 'Jawab 5 soal uraian tentang gelombang dan bunyi. Sertakan rumus dan perhitungan yang jelas.', '2026-05-19 23:59:00', '2026-05-12 08:30:00');

-- Matematika
INSERT INTO assignments (subject_id, classroom_id, teacher_id, academic_year_id, title, description, due_date, created_at) VALUES
(16, 4, 16, 4, 'PR Operasi Matriks', 'Kerjakan soal halaman 45-47 buku paket Matematika kelas XI. Nomor 1-15 tentang penjumlahan, pengurangan, dan perkalian matriks.', '2026-04-14 23:59:00', '2026-04-07 09:30:00'),
(16, 4, 16, 4, 'Tugas Determinan Matriks', 'Tentukan determinan dan invers dari 5 matriks berordo 2×2 dan 3 matriks berordo 3×3. Tunjukkan seluruh langkah penyelesaian.', '2026-04-28 23:59:00', '2026-04-21 09:30:00'),
(16, 4, 16, 4, 'Proyek SPLTV dalam Kehidupan Sehari-hari', 'Carilah 3 permasalahan dalam kehidupan sehari-hari yang dapat diselesaikan menggunakan SPLTV. Buatlah model matematikanya dan selesaikan menggunakan metode eliminasi-substitusi.', '2026-05-15 23:59:00', '2026-05-05 09:30:00');

-- Ekonomi
INSERT INTO assignments (subject_id, classroom_id, teacher_id, academic_year_id, title, description, due_date, created_at) VALUES
(24, 4, 24, 4, 'Analisis Kurva Permintaan dan Penawaran', 'Buatlah analisis kurva permintaan dan penawaran untuk produk sembako di daerah tempat tinggalmu. Sertakan grafik, tabel, dan penjelasan faktor-faktor yang memengaruhi.', '2026-04-20 23:59:00', '2026-04-10 10:30:00'),
(24, 4, 24, 4, 'Studi Kasus Elastisitas Harga', 'Pilih 3 produk berbeda dan analisis elastisitas harga permintaannya. Jelaskan mengapa produk tersebut elastis/inelastis berdasarkan karakteristiknya.', '2026-05-12 23:59:00', '2026-05-01 10:30:00');

-- Bahasa Indonesia
INSERT INTO assignments (subject_id, classroom_id, teacher_id, academic_year_id, title, description, due_date, created_at) VALUES
(17, 4, 17, 4, 'Menulis Teks Eksposisi', 'Tulislah sebuah teks eksposisi dengan tema pendidikan di Indonesia (minimal 500 kata). Pastikan mengandung struktur tesis, argumentasi, dan penegasan ulang.', '2026-04-22 23:59:00', '2026-04-15 11:30:00'),
(17, 4, 17, 4, 'Simulasi Debat Kelompok', 'Persiapkan materi debat dengan mosi: "Media sosial lebih banyak membawa dampak positif daripada negatif bagi pelajar." Tulis argumen afirmasi dan negasi masing-masing minimal 3 poin.', '2026-05-13 23:59:00', '2026-05-06 11:30:00');

-- Prakarya dan Kewirausahaan (subject_id=30, teacher_id=30 Andi Saputra)
INSERT INTO assignments (subject_id, classroom_id, teacher_id, academic_year_id, title, description, due_date, created_at) VALUES
(30, 4, 30, 4, 'Proposal Usaha Sederhana', 'Buatlah proposal usaha sederhana yang mencakup: latar belakang, analisis SWOT, rencana pemasaran, dan proyeksi keuangan sederhana. Usaha harus bisa dijalankan oleh siswa SMA.', '2026-05-20 23:59:00', '2026-05-01 12:00:00');

-- ==========================================
-- SUBMISSION (Beberapa tugas sudah dikumpulkan)
-- ==========================================

-- Raka sudah mengumpulkan beberapa tugas
SET @fisika1 = (SELECT id FROM assignments WHERE title = 'Latihan Soal Hukum Newton' AND classroom_id = 4 LIMIT 1);
SET @mat1 = (SELECT id FROM assignments WHERE title = 'PR Operasi Matriks' AND classroom_id = 4 LIMIT 1);
SET @mat2 = (SELECT id FROM assignments WHERE title = 'Tugas Determinan Matriks' AND classroom_id = 4 LIMIT 1);
SET @eko1 = (SELECT id FROM assignments WHERE title = 'Analisis Kurva Permintaan dan Penawaran' AND classroom_id = 4 LIMIT 1);
SET @bind1 = (SELECT id FROM assignments WHERE title = 'Menulis Teks Eksposisi' AND classroom_id = 4 LIMIT 1);

INSERT INTO assignment_submissions (assignment_id, student_id, content, status, submitted_at) VALUES
(@fisika1, 1, 'Jawaban soal Hukum Newton telah dilampirkan.', 'submitted', '2026-04-20 21:30:00'),
(@mat1, 1, 'PR Operasi Matriks halaman 45-47 sudah dikerjakan.', 'reviewed', '2026-04-13 20:00:00'),
(@mat2, 1, 'Determinan dan invers matriks telah diselesaikan.', 'submitted', '2026-04-27 19:45:00'),
(@eko1, 1, 'Analisis kurva permintaan dan penawaran untuk beras, minyak goreng, dan gula pasir.', 'reviewed', '2026-04-19 22:00:00'),
(@bind1, 1, 'Teks eksposisi tentang pentingnya literasi digital bagi generasi muda Indonesia.', 'submitted', '2026-04-21 18:30:00');

-- Update scores for reviewed submissions
UPDATE assignment_submissions SET score = 85.00, feedback = 'Sangat baik! Langkah penyelesaian sudah runtut.' WHERE assignment_id = @mat1 AND student_id = 1;
UPDATE assignment_submissions SET score = 90.00, feedback = 'Analisis yang mendalam. Grafik dan tabel sangat informatif.' WHERE assignment_id = @eko1 AND student_id = 1;

-- ==========================================
-- GRADES / RAPOR
-- ==========================================
INSERT INTO grades (student_id, subject_id, teacher_id, classroom_id, academic_year_id, grade_type, semester, title, score, notes) VALUES
(1, 19, 19, 4, 4, 'rapor', 'genap', 'Nilai Akhir Fisika', 82, 'Perlu meningkatkan kemampuan praktikum'),
(1, 16, 16, 4, 4, 'rapor', 'genap', 'Nilai Akhir Matematika', 88, 'Konsisten dan rajin mengerjakan tugas'),
(1, 24, 24, 4, 4, 'rapor', 'genap', 'Nilai Akhir Ekonomi', 90, 'Pemahaman konsep sangat baik'),
(1, 17, 17, 4, 4, 'rapor', 'genap', 'Nilai Akhir Bahasa Indonesia', 85, 'Kemampuan menulis cukup baik'),
(1, 27, 27, 4, 4, 'rapor', 'genap', 'Nilai Akhir PPKn', 87, 'Aktif dalam diskusi kelas');
