<section class="page-head">
    <div>
        <a href="<?= e(route_url('teacher/assignments')) ?>" class="btn small" style="margin-bottom: 12px; display: inline-block;">&larr; Kembali ke Daftar Tugas</a>
        <span class="eyebrow">Detail Tugas</span>
        <h1><?= e($assignment['title']) ?></h1>
        <p class="muted"><?= e($assignment['subject_name']) ?> · <?= e($assignment['classroom_name']) ?> · Tenggat: <?= e(format_datetime($assignment['due_date'])) ?></p>
    </div>
</section>

<section class="card" style="margin-bottom: 24px;">
    <h2>Deskripsi Tugas</h2>
    <div style="font-size: 14px; color: #475569; line-height: 1.6; margin-bottom: 16px;">
        <?= nl2br(e($assignment['description'])) ?>
    </div>
    <?= render_file_preview($assignment['attachment'], 'Lihat Lampiran Soal/Materi') ?>
</section>

<section class="card">
    <h2>Status Pengumpulan Siswa</h2>
    <p class="muted" style="margin-top: -10px; margin-bottom: 20px; font-size: 13px;">Daftar seluruh siswa di kelas ini beserta status pengumpulannya.</p>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Siswa</th><th>NIS</th><th>Status</th><th>Isi / Jawaban</th><th>Waktu Kumpul</th></tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $sub): ?>
                    <tr>
                        <td><strong><?= e($sub['student_name']) ?></strong></td>
                        <td><?= e($sub['nis']) ?></td>
                        <td>
                            <?php if ($sub['status'] === 'submitted'): ?>
                                <span class="badge success">Selesai</span>
                            <?php else: ?>
                                <span class="badge warning">Belum Kumpul</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($sub['content'])): ?>
                                <div style="font-size: 13px; color: #475569; max-width: 300px; white-space: pre-wrap;"><?= e($sub['content']) ?></div>
                            <?php else: ?>
                                <span class="muted">—</span>
                            <?php endif; ?>
                            
                            <?= render_file_preview($sub['attachment'], 'File Jawaban') ?>
                        </td>
                        <td>
                            <?php if (!empty($sub['submitted_at'])): ?>
                                <?= e(format_datetime($sub['submitted_at'])) ?>
                            <?php else: ?>
                                <span class="muted">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
