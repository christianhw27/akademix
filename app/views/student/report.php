<section class="page-head">
    <div>
        <span class="eyebrow">Siswa</span>
        <h1>Nilai Rapor</h1>
    </div>
</section>

<section class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Periode</th><th>Mapel</th><th>Komponen</th><th>Nilai</th><th>Catatan</th></tr>
            </thead>
            <tbody>
                <?php foreach ($report as $grade): ?>
                    <tr>
                        <td><?= e($grade['year_label']) ?> / <?= e(ucfirst($grade['semester'])) ?></td>
                        <td><?= e($grade['subject_name']) ?></td>
                        <td><?= e($grade['title']) ?></td>
                        <td><span class="badge <?= e(grade_badge_class((float) $grade['score'])) ?>"><?= e((string) $grade['score']) ?></span></td>
                        <td><?= e($grade['notes']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
