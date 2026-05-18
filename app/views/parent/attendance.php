<section class="page-head">
    <div>
        <span class="eyebrow">Orang Tua</span>
        <h1>Kehadiran Anak</h1>
    </div>
</section>



<section class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Tanggal</th><th>Mapel</th><th>Kelas</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php foreach ($attendance as $item): ?>
                    <tr>
                        <td><?= e(format_date($item['attendance_date'])) ?></td>
                        <td><?= e($item['subject_name']) ?></td>
                        <td><?= e($item['classroom_name']) ?></td>
                        <td><span class="badge <?= e(attendance_badge_class($item['status'])) ?>"><?= e($item['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
