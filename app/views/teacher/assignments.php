<section class="page-head">
    <div>
        <span class="eyebrow">Guru</span>
        <h1>Tugas & Pengumpulan</h1>
    </div>
</section>

<section class="card">
    <h2>Buat Tugas</h2>
    <form method="post" action="<?= e(route_url('teacher/assignments/save')) ?>" class="form-grid" enctype="multipart/form-data">
        <label>Kelas
            <select name="classroom_id" required>
                <?php foreach ($classrooms as $classroom): ?>
                    <option value="<?= e((string) $classroom['id']) ?>"><?= e($classroom['name'] . ' · Kelas ' . $classroom['grade_level']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Mata Pelajaran
            <select name="subject_id" required>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= e((string) $subject['id']) ?>"><?= e($subject['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Tenggat Waktu<input type="datetime-local" name="due_date" required></label>
        <label class="full">Judul<input type="text" name="title" required></label>
        <label class="full">Deskripsi<textarea name="description" rows="4" required></textarea></label>
        <label class="full">Lampiran (opsional)
            <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" style="padding: 8px;">
            <span class="muted" style="font-size: 12px;">Format: JPG, PNG, PDF, DOC, XLS, PPT, ZIP (maks. 10MB)</span>
        </label>
        <button type="submit" class="btn primary" style="width: max-content;">Simpan Tugas</button>
    </form>
</section>

<section class="card">
    <h2>Daftar Tugas</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Judul</th><th>Kelas</th><th>Mapel</th><th>Lampiran</th><th>Tenggat</th><th>Pengumpulan</th></tr>
            </thead>
            <tbody>
                <?php foreach ($assignments as $assignment): ?>
                    <tr>
                        <td><?= e($assignment['title']) ?><br><span class="muted" style="font-size:12px;"><?= e(mb_substr($assignment['description'], 0, 80)) ?>...</span></td>
                        <td><?= e($assignment['classroom_name']) ?></td>
                        <td><?= e($assignment['subject_name']) ?></td>
                        <td>
                            <?= render_file_preview($assignment['attachment']) ?>
                        </td>
                        <td><?= e(format_datetime($assignment['due_date'])) ?></td>
                        <td>
                            <a href="<?= e(route_url('teacher/assignments/detail&id=' . $assignment['id'])) ?>" class="btn small" style="text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                                👁️ Lihat (<?= e((string) $assignment['submission_count']) ?>)
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="card">
    <h2>Pengumpulan Siswa</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Siswa</th><th>Tugas</th><th>Status</th><th>Isi</th><th>Waktu</th></tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $submission): ?>
                    <tr>
                        <td><?= e($submission['student_name']) ?></td>
                        <td><?= e($submission['assignment_title']) ?><br><span class="muted"><?= e($submission['subject_name']) ?></span></td>
                        <td><span class="badge info"><?= e($submission['status']) ?></span></td>
                        <td><?= e($submission['content']) ?></td>
                        <td><?= e(format_datetime($submission['submitted_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
