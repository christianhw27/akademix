<section class="page-head">
    <div class="breadcrumb">
        <a href="<?= e(route_url('admin/classrooms')) ?>">Kelas & Siswa</a>
        <span class="sep">›</span>
        <?php if ($classroomId): ?>
            <a href="<?= e(route_url('admin/classrooms&view=' . $classroomId)) ?>">Kelas</a>
            <span class="sep">›</span>
        <?php endif; ?>
        <span class="current"><?= e($student['full_name']) ?></span>
    </div>
    <h1><?= e($student['full_name']) ?></h1>
</section>

<section class="grid two-cols">
    <article class="card">
        <h2>📋 Data Diri</h2>
        <dl class="detail-grid">
            <dt>Nama Lengkap</dt>
            <dd><?= e($student['full_name']) ?></dd>

            <dt>Email</dt>
            <dd><?= e($student['email'] ?: '-') ?></dd>

            <dt>NIS</dt>
            <dd><code><?= e($student['nis']) ?></code></dd>

            <dt>Gender</dt>
            <dd><span class="badge <?= $student['gender'] === 'L' ? 'info' : 'warning' ?>"><?= $student['gender'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></span></dd>

            <dt>Tanggal Lahir</dt>
            <dd><?= e(format_date($student['birth_date'])) ?></dd>

            <dt>Tahun Masuk</dt>
            <dd><?= e((string) $student['cohort_name']) ?></dd>

            <dt>Telepon</dt>
            <dd><?= e($student['phone'] ?: '-') ?></dd>

            <dt>Alamat</dt>
            <dd><?= e($student['address'] ?: '-') ?></dd>
        </dl>
    </article>
</section>


<?php
// Get grades for this student
$grades = (new AdminModel())->getStudentGrades($student['id']);
$raporGrades = array_filter($grades, fn($g) => $g['grade_type'] === 'rapor');
$otherGrades = array_filter($grades, fn($g) => $g['grade_type'] !== 'rapor');
?>

<?php if (!empty($raporGrades)): ?>
<section class="card">
    <h2>📊 Nilai Rapor</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Mata Pelajaran</th><th>Kelas</th><th>Periode</th><th>Semester</th><th>Nilai</th><th>Catatan</th></tr>
            </thead>
            <tbody>
                <?php foreach ($raporGrades as $grade): ?>
                    <tr>
                        <td><?= e($grade['subject_name']) ?></td>
                        <td><?= e($grade['classroom_name']) ?></td>
                        <td><?= e($grade['year_label']) ?></td>
                        <td><span class="badge dark"><?= e(ucfirst($grade['semester'])) ?></span></td>
                        <td><span class="badge <?= e(grade_badge_class((float) $grade['score'])) ?>"><?= e(number_format((float) $grade['score'], 1)) ?></span></td>
                        <td class="muted"><?= e($grade['notes'] ?: '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($otherGrades)): ?>
<section class="card">
    <h2>📝 Nilai Harian & Tugas</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Judul</th><th>Mapel</th><th>Tipe</th><th>Nilai</th><th>Catatan</th></tr>
            </thead>
            <tbody>
                <?php foreach ($otherGrades as $grade): ?>
                    <tr>
                        <td><?= e($grade['title']) ?></td>
                        <td><?= e($grade['subject_name']) ?></td>
                        <td><span class="badge dark"><?= e(ucfirst($grade['grade_type'])) ?></span></td>
                        <td><span class="badge <?= e(grade_badge_class((float) $grade['score'])) ?>"><?= e(number_format((float) $grade['score'], 1)) ?></span></td>
                        <td class="muted"><?= e($grade['notes'] ?: '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php endif; ?>
