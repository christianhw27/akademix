<section class="page-head">
    <div>
        <span class="eyebrow">Guru</span>
        <h1>Materi Pembelajaran</h1>
    </div>
</section>

<section class="card">
    <h2>Tambah Materi</h2>
    <form method="post" action="<?= e(route_url('teacher/materials/save')) ?>" class="form-grid" enctype="multipart/form-data">
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
        <label class="full">Judul<input type="text" name="title" required></label>
        <label class="full">Isi Materi<textarea name="content" rows="5" required></textarea></label>
        <label class="full">Lampiran (opsional)
            <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" style="padding: 8px;">
            <span class="muted" style="font-size: 12px;">Format: JPG, PNG, PDF, DOC, XLS, PPT, ZIP (maks. 10MB)</span>
        </label>
        <button type="submit" class="btn primary" style="width: max-content;">Simpan Materi</button>
    </form>
</section>

<section class="card">
    <h2>Daftar Materi</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Judul</th><th>Kelas</th><th>Mapel</th><th>Lampiran</th><th>Periode</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($materials as $material): ?>
                    <tr>
                        <td><?= e($material['title']) ?><br><span class="muted" style="font-size:12px;"><?= e(mb_substr($material['content'], 0, 80)) ?>...</span></td>
                        <td><?= e($material['classroom_name']) ?></td>
                        <td><?= e($material['subject_name']) ?></td>
                        <td>
                            <?= render_file_preview($material['attachment']) ?>
                        </td>
                        <td><?= e($material['year_label']) ?> / <?= e(ucfirst($material['semester'])) ?></td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <a href="<?= e(route_url('teacher/materials/edit&id=' . $material['id'])) ?>" class="btn small" style="text-decoration:none; display:inline-flex; align-items:center; border: 1px solid #e2e8f0; background: #f8fafc; color: #475569;">✏️ Edit</a>
                                <form method="post" action="<?= e(route_url('teacher/materials/delete')) ?>" onsubmit="return confirm('Hapus materi ini?')" style="margin:0;">
                                    <input type="hidden" name="id" value="<?= e((string) $material['id']) ?>">
                                    <button type="submit" class="btn danger small" style="border: 1px solid #fecaca; display:inline-flex; align-items:center;">🗑️ Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
