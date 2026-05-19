<section class="page-head">
    <div>
        <span class="eyebrow">Siswa</span>
        <h1>Tugas & Pengumpulan</h1>
    </div>
</section>

<section class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Tugas</th><th>Kelas</th><th>Deadline</th><th>Status</th><th>Kumpulkan</th></tr>
            </thead>
            <tbody>
                <?php foreach ($assignments as $assignment): ?>
                    <tr>
                        <td><?= e($assignment['title']) ?><br><span class="muted"><?= e($assignment['subject_name']) ?> · <?= e($assignment['description']) ?></span></td>
                        <td><?= e($assignment['classroom_name']) ?><br><span class="muted"><?= e($assignment['year_label']) ?> / <?= e(ucfirst($assignment['semester'])) ?></span></td>
                        <td><?= e(format_datetime($assignment['due_date'])) ?></td>
                        <td>
                            <?php $status = $assignment['submission_status'] ?: 'belum'; ?>
                            <span class="badge <?= $status === 'submitted' ? 'success' : 'warning' ?>"><?= e($status) ?></span>
                        </td>
                        <td>
                            <form method="post" action="<?= e(route_url('student/assignments/submit')) ?>" class="inline-form" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:8px;">
                                <input type="hidden" name="assignment_id" value="<?= e((string) $assignment['id']) ?>">
                                <textarea name="content" rows="2" placeholder="Tuliskan jawaban / catatan pengumpulan" required><?= e($assignment['submission_content']) ?></textarea>
                                
                                <?= render_file_preview($assignment['submission_attachment'], 'File Tersimpan Saat Ini') ?>
                                <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">(Pilih file baru untuk mengganti file lama)</div>
                                
                                <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" style="font-size:12px; padding:4px;">
                                <button type="submit" class="btn small"><?= $status === 'submitted' ? 'Perbarui Jawaban' : 'Kumpulkan' ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
