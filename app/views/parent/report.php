<section class="page-head">
    <div>
        <span class="eyebrow">Orang Tua</span>
        <h1>Rapor Anak</h1>
    </div>
</section>



<section class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Periode</th><th>Mapel</th><th>Komponen</th><th>Nilai</th></tr>
            </thead>
            <tbody>
                <?php foreach ($report as $grade): ?>
                    <tr>
                        <td><?= e($grade['year_label']) ?> / <?= e(ucfirst($grade['semester'])) ?></td>
                        <td><?= e($grade['subject_name']) ?></td>
                        <td><?= e($grade['title']) ?></td>
                        <td><span class="badge <?= e(grade_badge_class((float) $grade['score'])) ?>"><?= e((string) $grade['score']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
