<section class="page-head">
    <div>
        <span class="eyebrow">Orang Tua</span>
        <h1>Tugas Anak</h1>
    </div>
</section>



<section class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Tugas</th><th>Kelas</th><th>Deadline</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php foreach ($assignments as $assignment): ?>
                    <tr>
                        <td><?= e($assignment['title']) ?><br><span class="muted"><?= e($assignment['subject_name']) ?></span></td>
                        <td><?= e($assignment['classroom_name']) ?></td>
                        <td><?= e(format_datetime($assignment['due_date'])) ?></td>
                        <td><span class="badge <?= ($assignment['submission_status'] === 'submitted') ? 'success' : 'warning' ?>"><?= e($assignment['submission_status'] ?: 'belum') ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
