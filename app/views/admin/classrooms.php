<?php
// Find active academic year
$activeYear = null;
foreach ($academicYears as $y) {
    if ($y['is_active']) {
        $activeYear = $y;
        break;
    }
}
if (!$activeYear && count($academicYears) > 0) {
    $activeYear = $academicYears[0];
}

// Filter classrooms for active year
$activeClassrooms = [];
if ($activeYear) {
    foreach ($classrooms as $c) {
        if ($c['academic_year_id'] == $activeYear['id']) {
            $activeClassrooms[] = $c;
        }
    }
}

// Group by Grade Level
$groupedByGrade = [];
foreach ($activeClassrooms as $c) {
    $groupedByGrade[$c['grade_level']][] = $c;
}
ksort($groupedByGrade);
?>

<style>
/* Local override based on DESIGN.md */
.aes-wrapper {
    background-color: #f8fafc; /* Canvas */
    color: #191c1e;
    margin: -14px -28px -60px; /* Offset .container padding */
    padding: 40px;
    min-height: calc(100vh - 80px); /* Adjust based on topbar */
    font-family: 'Inter', sans-serif;
}

.aes-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 32px;
}

.aes-title h1 {
    font-size: 32px;
    font-weight: 600;
    color: #191c1e;
    margin: 0 0 8px 0;
    letter-spacing: -0.01em;
}

.aes-stats {
    display: flex;
    gap: 16px;
    color: #444651;
    font-size: 14px;
    font-weight: 500;
}

.aes-stats span {
    display: flex;
    align-items: center;
    gap: 6px;
}

.aes-actions {
    display: flex;
    gap: 12px;
}

.aes-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.2s;
}

.aes-btn-ghost {
    background: #f1f5f9;
    color: #444651;
    border-color: #e2e8f0;
}
.aes-btn-ghost:hover {
    background: #e2e8f0;
}

.aes-btn-primary {
    background: #1e3a8a; /* Academic Blue */
    color: #ffffff;
}
.aes-btn-primary:hover {
    background: #172554;
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
}

.aes-section-title {
    font-size: 20px;
    font-weight: 600;
    color: #191c1e;
    margin: 32px 0 16px 0;
    padding-bottom: 8px;
    border-bottom: 1px solid #e2e8f0;
}

.aes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 24px;
}

.aes-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0px 1px 3px rgba(0,0,0,0.1), 0px 1px 2px rgba(0,0,0,0.06);
    transition: transform 0.2s, box-shadow 0.2s;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
}

.aes-card:hover {
    transform: translateY(-2px);
    box-shadow: 0px 4px 12px rgba(0,0,0,0.08);
    color: inherit;
}

.aes-card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
}

.aes-card-title {
    font-size: 24px;
    font-weight: 600;
    color: #1e3a8a;
    margin: 0;
    line-height: 1.2;
}

.aes-badge {
    background: #ccfbf1; /* Teal light */
    color: #115e59; /* Teal dark */
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.aes-track {
    font-size: 12px;
    font-weight: 500;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 16px;
}

.aes-divider {
    height: 1px;
    background: #f1f5f9;
    margin: 0 -20px 16px -20px;
}

.aes-homeroom {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #444651;
}

.aes-homeroom svg {
    color: #94a3b8;
}

/* Override existing details/summary to play nice with new design */
.aes-actions details { position: relative; }
.aes-actions summary { list-style: none; outline: none; }
.aes-actions summary::-webkit-details-marker { display: none; }
.aes-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    width: 400px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    z-index: 50;
    color: #191c1e;
}

.aes-dropdown h3 {
    margin-top: 0;
    font-size: 18px;
    margin-bottom: 16px;
}

.aes-dropdown label {
    color: #444651;
}
.aes-dropdown input, .aes-dropdown select, .aes-dropdown textarea {
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    color: #191c1e;
}
.aes-dropdown input:focus, .aes-dropdown select:focus, .aes-dropdown textarea:focus {
    border-color: #1e3a8a;
    box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.2);
}
</style>

<div class="aes-wrapper">
    <div class="aes-header">
        <div class="aes-title">
            <h1>Manajemen Kelas & Siswa</h1>
            <div class="aes-stats">
                <span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
                    Total Kelas: <?= $stats['classrooms'] ?>
                </span>
                <span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Total Siswa: <?= $stats['stats_students_in_classes'] ?? $stats['students'] ?>
                </span>
            </div>
        </div>
        <div class="aes-actions">
            <details class="action-dropdown">
                <summary class="aes-btn aes-btn-ghost">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Manage Classes
                </summary>
                <div class="aes-dropdown">
                    <h3>Buka Kelas Baru</h3>
                    <form method="post" action="<?= e(route_url('admin/classrooms/save')) ?>" class="form-grid">
                        <label class="full">Tahun Ajaran
                            <select name="academic_year_id" required>
                                <?php foreach ($academicYears as $period): ?>
                                    <option value="<?= e((string) $period['id']) ?>" <?= ($activeYear && $activeYear['id'] == $period['id']) ? 'selected' : '' ?>><?= e($period['year_label'] . ' — ' . ucfirst($period['semester'])) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label>Nama Kelas
                            <select name="class_id" required>
                                <?php foreach ($classes as $cls): ?>
                                    <option value="<?= e((string) $cls['id']) ?>"><?= e($cls['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label>Tingkat<input type="number" name="grade_level" min="1" max="12" required></label>
                        <label class="full">Wali Kelas
                            <select name="homeroom_teacher_id">
                                <option value="">— Tidak ada —</option>
                                <?php foreach ($teachers as $teacher): ?>
                                    <option value="<?= e((string) $teacher['id']) ?>"><?= e($teacher['full_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <div class="full" style="text-align: right; margin-top: 8px;">
                            <button type="submit" class="aes-btn aes-btn-primary">Simpan Kelas</button>
                        </div>
                    </form>
                </div>
            </details>

            <details class="action-dropdown">
                <summary class="aes-btn aes-btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                    Add New Student
                </summary>
                <div class="aes-dropdown" style="width: 480px;">
                    <h3>Pendaftaran Siswa Baru</h3>
                    <form method="post" action="<?= e(route_url('admin/students/save')) ?>" class="form-grid">
                        <label class="full">Nama Lengkap<input type="text" name="full_name" required></label>
                        <label>NISN / NIS<input type="text" name="nis" required></label>
                        <label>Gender
                            <select name="gender" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </label>
                        <label>Email<input type="email" name="email" required placeholder="siswa@email.com"></label>
                        <label>Password<input type="text" name="password" placeholder="Bawaan: password"></label>
                        <label>Tahun Masuk (Angkatan)
                            <select name="cohort_id" required>
                                <?php foreach ($cohorts as $cohort): ?>
                                    <option value="<?= e((string) $cohort['id']) ?>"><?= e($cohort['year_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label>Tanggal Lahir<input type="date" name="birth_date"></label>

                        <label class="full">Alamat<textarea name="address" rows="2"></textarea></label>
                        <div class="full" style="text-align: right; margin-top: 8px;">
                            <button type="submit" class="aes-btn aes-btn-primary">Daftarkan Siswa</button>
                        </div>
                    </form>
                </div>
            </details>
        </div>
    </div>

    <?php if (empty($groupedByGrade)): ?>
        <div style="text-align: center; padding: 64px; background: #ffffff; border-radius: 12px; border: 1px dashed #cbd5e1;">
            <p style="color: #64748b;">Tidak ada kelas aktif yang ditemukan untuk tahun ajaran ini.</p>
        </div>
    <?php else: ?>
        <div style="font-size: 16px; font-weight: 600; margin-bottom: 24px; color: #191c1e;">Ringkasan Kelas Aktif</div>
        
        <?php foreach ($groupedByGrade as $grade => $classesInGrade): ?>
            <h2 class="aes-section-title">Kelas <?= e((string) $grade) ?></h2>
            <div class="aes-grid">
                <?php foreach ($classesInGrade as $classroom): ?>
                    <?php
                        // Extract track from class name (e.g. "IPA 1" -> "SCIENCE TRACK")
                        $track = 'JURUSAN UMUM';
                        if (str_contains(strtoupper($classroom['name']), 'IPA') || str_contains(strtoupper($classroom['name']), 'MIPA')) {
                            $track = 'JURUSAN MIPA';
                        } elseif (str_contains(strtoupper($classroom['name']), 'IPS')) {
                            $track = 'JURUSAN IPS';
                        } elseif (str_contains(strtoupper($classroom['name']), 'BAHASA')) {
                            $track = 'JURUSAN BAHASA';
                        }
                        
                        $displayTitle = $grade . '-' . str_replace(' ', '', $classroom['name']);
                    ?>
                    <a href="<?= e(route_url('admin/classrooms')) ?>&view=<?= e((string) $classroom['id']) ?>" class="aes-card">
                        <div class="aes-card-top">
                            <h3 class="aes-card-title"><?= e($displayTitle) ?></h3>
                            <div class="aes-badge">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                <?= e((string) $classroom['student_count']) ?>
                            </div>
                        </div>
                        <div class="aes-track"><?= e($track) ?></div>
                        <div style="flex-grow: 1;"></div>
                        <div class="aes-divider"></div>
                        <div class="aes-homeroom">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Wali Kelas: <?= e($classroom['homeroom_teacher'] ?: 'Belum Ditugaskan') ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('click', function(e) {
    document.querySelectorAll('.action-dropdown').forEach(function(details) {
        if (!details.contains(e.target)) {
            details.removeAttribute('open');
        }
    });
});
</script>
