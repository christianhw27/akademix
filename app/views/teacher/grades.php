<?php
$groupedByGrade = [];
foreach ($classrooms as $c) {
    $groupedByGrade[$c['grade_level']][] = $c;
}
ksort($groupedByGrade);
?>
<section class="page-head">
    <div>
        <span class="eyebrow">Guru</span>
        <h1>Sistem Nilai Siswa</h1>
    </div>
</section>

<?php if (!$selectedClassroomId): ?>
    <section class="card" style="margin-bottom: 32px;">
        <h2 style="margin-bottom: 8px;">Pilih Kelas</h2>
        <p style="color: var(--muted); margin-top: 0; margin-bottom: 24px;">Silakan pilih tingkat kelas terlebih dahulu untuk mulai menginput nilai.</p>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            <?php foreach ($groupedByGrade as $gradeLevel => $classes): ?>
                <div style="border: 1px solid var(--outline); border-radius: 8px; overflow: hidden; background: var(--surface-raised);">
                    <div style="padding: 16px; background: var(--surface-container); border-bottom: 1px solid var(--outline); font-weight: 600; font-size: 16px; color: var(--on-surface);">
                        Kelas <?= e((string) $gradeLevel) ?>
                    </div>
                    <div style="padding: 16px; display: flex; flex-wrap: wrap; gap: 12px;">
                        <?php foreach ($classes as $cls): ?>
                            <a href="<?= e(route_url('teacher/grades&classroom_id=' . $cls['id'] . '&type=harian')) ?>" class="btn" style="background: var(--surface-container); border: 1px solid var(--outline-strong); color: var(--on-surface-variant); padding: 8px 24px; font-weight: 500; box-shadow: var(--shadow-sm);">
                                <?= e($cls['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php else: ?>
    <?php
    $selectedClass = null;
    foreach ($classrooms as $c) {
        if ($c['id'] == $selectedClassroomId) $selectedClass = $c;
    }
    ?>
    <section class="card" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h2 style="margin: 0;">Input Nilai Kelas <?= e($selectedClass['name'] ?? '') ?></h2>
                <p style="margin: 4px 0 0 0; color: var(--muted);">(Kelas <?= e($selectedClass['grade_level'] ?? '') ?> - <?= e($selectedClass['year_label'] ?? '') ?>)</p>
            </div>
            <a href="<?= e(route_url('teacher/grades')) ?>" class="btn small" style="background: var(--surface-raised); color: var(--on-surface-variant); border: 1px solid var(--outline-strong);">← Kembali ke Daftar Kelas</a>
        </div>

        <!-- Tabs for Grade Type -->
        <div class="tabs" style="display: flex; gap: 16px; margin-bottom: 24px; border-bottom: 1px solid var(--outline); padding-bottom: 8px;">
            <a href="<?= e(route_url('teacher/grades&classroom_id=' . $selectedClassroomId . '&type=harian')) ?>" style="text-decoration: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; <?= $gradeType === 'harian' ? 'background: var(--primary); color: #fff;' : 'color: var(--muted);' ?>">Nilai Harian</a>
            <a href="<?= e(route_url('teacher/grades&classroom_id=' . $selectedClassroomId . '&type=tugas')) ?>" style="text-decoration: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; <?= $gradeType === 'tugas' ? 'background: var(--primary); color: #fff;' : 'color: var(--muted);' ?>">Tugas</a>
            <a href="<?= e(route_url('teacher/grades&classroom_id=' . $selectedClassroomId . '&type=rapor')) ?>" style="text-decoration: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; <?= $gradeType === 'rapor' ? 'background: var(--primary); color: #fff;' : 'color: var(--muted);' ?>">Rapor</a>
        </div>

        <form method="post" action="<?= e(route_url('teacher/grades/saveMass')) ?>">
            <input type="hidden" name="classroom_id" value="<?= e((string) $selectedClassroomId) ?>">
            <input type="hidden" name="grade_type" value="<?= e($gradeType) ?>">
            
            <div class="form-grid" style="margin-bottom: 24px; padding: 16px; background: var(--surface-raised); border-radius: 8px; border: 1px solid var(--outline);">
                <label>Mata Pelajaran
                    <select name="subject_id" required>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?= e((string) $subject['id']) ?>"><?= e($subject['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Semester
                    <select name="semester" required>
                        <option value="ganjil">Ganjil</option>
                        <option value="genap">Genap</option>
                    </select>
                </label>
                <label class="full">Judul / Komponen Penilaian
                    <input type="text" name="title" placeholder="Misal: Ulangan Harian 1, Tugas Akhir, Ujian Tengah Semester" required>
                </label>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Siswa</th>
                            <th>NIS</th>
                            <th style="width: 150px;">Nilai (0-100)</th>
                            <th>Catatan (Opsional)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($students as $student): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td style="font-weight: 500; color: var(--on-surface);"><?= e($student['full_name']) ?></td>
                                <td><?= e($student['nis']) ?></td>
                                <td>
                                    <input type="number" name="scores[<?= e((string) $student['id']) ?>]" min="0" max="100" step="0.01" style="width: 100%; border: 1px solid var(--outline-strong); padding: 8px; border-radius: 4px;" placeholder="--">
                                </td>
                                <td>
                                    <input type="text" name="notes[<?= e((string) $student['id']) ?>]" style="width: 100%; border: 1px solid var(--outline-strong); padding: 8px; border-radius: 4px;" placeholder="...">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($students)): ?>
                            <tr><td colspan="5" style="text-align: center; padding: 24px; color: var(--muted);">Tidak ada siswa di kelas ini.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn primary" style="padding: 10px 32px; font-size: 15px;">Simpan Semua Nilai</button>
            </div>
        </form>
    </section>

    <?php if (!empty($gradeSessions)): ?>
    <section class="card" style="margin-bottom: 24px;">
        <h2 style="margin-bottom: 16px;">Riwayat Nilai</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul / Komponen</th>
                        <th>Mata Pelajaran</th>
                        <th>Tahun Ajaran / Semester</th>
                        <th style="width: 150px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($gradeSessions as $session): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td style="font-weight: 500; color: var(--on-surface);"><?= e($session['title']) ?></td>
                            <td><?= e($session['subject_name']) ?></td>
                            <td><?= e($session['year_label']) ?> / <?= e(ucfirst($session['semester'])) ?></td>
                            <td style="text-align: center;">
                                <a href="<?= e(base_url('export.php') . '?export_type=grades&classroom_id=' . $selectedClassroomId . '&grade_type=' . urlencode($gradeType) . '&title=' . urlencode($session['title']) . '&subject_id=' . $session['subject_id']) ?>" class="btn small primary" style="display: inline-flex; align-items: center; gap: 4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    Ekspor Excel
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php endif; ?>
<?php endif; ?>
