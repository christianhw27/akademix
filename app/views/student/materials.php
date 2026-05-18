<section class="page-head">
    <div>
        <span class="eyebrow">Siswa</span>
        <h1>Materi Semua Tahun</h1>
    </div>
</section>

<section class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Judul</th><th>Mapel</th><th>Kelas</th><th>Guru</th><th>Periode</th></tr>
            </thead>
            <tbody>
                <?php foreach ($materials as $material): ?>
                    <tr>
                        <td><?= e($material['title']) ?><br><span class="muted"><?= e($material['content']) ?></span></td>
                        <td><?= e($material['subject_name']) ?></td>
                        <td><?= e($material['classroom_name']) ?></td>
                        <td><?= e($material['teacher_name']) ?></td>
                        <td><?= e($material['year_label']) ?> / <?= e(ucfirst($material['semester'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
