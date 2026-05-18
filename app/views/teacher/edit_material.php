<section class="page-head">
    <div>
        <a href="<?= e(route_url('teacher/materials')) ?>" class="btn small" style="margin-bottom: 12px; display: inline-block;">&larr; Kembali ke Daftar Materi</a>
        <span class="eyebrow">Guru</span>
        <h1>Edit Materi</h1>
    </div>
</section>

<section class="card">
    <form method="post" action="<?= e(route_url('teacher/materials/save')) ?>" class="form-grid" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= e((string) $material['id']) ?>">
        
        <label>Kelas
            <select name="classroom_id" required>
                <?php foreach ($classrooms as $classroom): ?>
                    <option value="<?= e((string) $classroom['id']) ?>" <?= selected($material['classroom_id'], $classroom['id']) ?>><?= e($classroom['name'] . ' · Kelas ' . $classroom['grade_level']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        
        <label>Mata Pelajaran
            <select name="subject_id" required>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= e((string) $subject['id']) ?>" <?= selected($material['subject_id'], $subject['id']) ?>><?= e($subject['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        
        <label class="full">Judul<input type="text" name="title" value="<?= e($material['title']) ?>" required></label>
        
        <label class="full">Isi Materi / Deskripsi<textarea name="content" rows="6" required><?= e($material['content']) ?></textarea></label>
        
        <label class="full">Lampiran File (opsional)
            <?= render_file_preview($material['attachment'], 'File Tersimpan Saat Ini') ?>
            <div style="font-size:11px; color:#64748b; margin-top:4px; margin-bottom: 8px;">(Pilih file baru jika ingin mengganti lampiran)</div>
            <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" style="padding: 8px;">
            <span class="muted" style="font-size: 12px;">Format: JPG, PNG, PDF, DOC, XLS, PPT, ZIP (maks. 10MB)</span>
        </label>
        
        <button type="submit" class="btn primary" style="width: max-content;">Simpan Perubahan</button>
    </form>
</section>
