<style>
/* Subject Chip Checkboxes */
.subject-chips {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 8px;
    margin-top: 8px;
}

.subject-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 400;
    font-size: 13px;
    color: #334155;
    transition: all 0.15s;
    user-select: none;
}

.subject-chip:hover {
    background: #eff6ff;
    border-color: #bfdbfe;
}

.subject-chip input[type="checkbox"] {
    accent-color: #1e3a8a;
    width: 16px;
    height: 16px;
    margin: 0;
    flex-shrink: 0;
}

.subject-chip input[type="checkbox"]:checked + span {
    color: #1e3a8a;
    font-weight: 600;
}

.subject-chip:has(input:checked) {
    background: #eff6ff;
    border-color: #93c5fd;
}

/* Compact version for inline edit forms */
.subject-chips-compact {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 6px;
    margin: 8px 0;
}

.subject-chips-compact .subject-chip {
    padding: 6px 10px;
    font-size: 12px;
}
</style>

<section class="page-head">
    <div>
        <span class="eyebrow">Data Master</span>
        <h1>Kelola Guru</h1>
        <p class="muted">Tambah, edit, dan kelola data guru beserta mata pelajaran yang diampu.</p>
    </div>
</section>

<section class="card">
    <h2>➕ Tambah Guru</h2>
    <form method="post" action="<?= e(route_url('admin/teachers/save')) ?>" class="form-grid">
        <input type="hidden" name="id" value="">
        <label>Nama Lengkap<input type="text" name="full_name" required></label>
        <label>Username<input type="text" name="username" required></label>
        <label>Email<input type="email" name="email"></label>
        <label>Password Awal<input type="text" name="password" placeholder="password"></label>
        <label>NIP<input type="text" name="nip" required></label>
        <label>Telepon<input type="text" name="phone"></label>
        <div class="full">
            <label style="margin-bottom: 4px; font-weight: 600;">Mata Pelajaran yang Diampu</label>
            <div class="subject-chips">
                <?php foreach ($subjects as $subject): ?>
                    <label class="subject-chip">
                        <input type="checkbox" name="subject_ids[]" value="<?= e((string) $subject['id']) ?>">
                        <span><?= e($subject['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <label class="full">Alamat<textarea name="address" rows="2"></textarea></label>
        <div class="full">
            <button type="submit" class="btn primary" style="width: max-content;">Simpan Guru</button>
        </div>
    </form>
</section>

<section class="card">
    <h2>Daftar Guru</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Nama</th><th>Username</th><th>NIP</th><th style="text-align:center;">Mata Pelajaran</th><th>Kontak</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $teacher): ?>
                    <tr>
                        <td><?= e($teacher['full_name']) ?><br><span class="muted"><?= e($teacher['email']) ?></span></td>
                        <td><code><?= e($teacher['username']) ?></code></td>
                        <td><?= e($teacher['nip']) ?></td>
                        <td style="text-align:center;">
                            <?php if ($teacher['subject_names']): ?>
                                <div style="display:flex;flex-wrap:wrap;gap:4px;justify-content:center;">
                                <?php foreach (explode(', ', $teacher['subject_names']) as $sn): ?>
                                    <span class="badge info"><?= e($sn) ?></span>
                                <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <span class="muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($teacher['phone']) ?></td>
                        <td>
                            <details style="position:relative;">
                                <summary class="btn small" style="cursor:pointer; display:inline-block;">Edit Data</summary>
                                <div class="aes-dropdown" style="position:absolute; right:0; top:calc(100% + 4px); width:320px; background:#fff; border:1px solid #e2e8f0; padding:16px; border-radius:12px; z-index:100; box-shadow:0 10px 25px rgba(0,0,0,0.1); text-align:left;">
                                    <h3 style="margin-top:0; font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-bottom:12px;">Edit Guru</h3>
                                    <form method="post" action="<?= e(route_url('admin/teachers/save')) ?>" style="display:flex; flex-direction:column; gap:8px;">
                                        <input type="hidden" name="id" value="<?= e((string) $teacher['id']) ?>">
                                    <input type="text" name="full_name" value="<?= e($teacher['full_name']) ?>" required>
                                    <input type="text" name="username" value="<?= e($teacher['username']) ?>" required>
                                    <input type="email" name="email" value="<?= e($teacher['email']) ?>">
                                    <input type="text" name="password" placeholder="Kosongkan jika tetap">
                                    <input type="text" name="nip" value="<?= e($teacher['nip']) ?>" required>
                                    <input type="text" name="phone" value="<?= e($teacher['phone']) ?>">
                                    <div class="subject-chips-compact">
                                        <?php $teacherSubjectIds = $allTeacherSubjects[$teacher['id']] ?? []; ?>
                                        <?php foreach ($subjects as $subject): ?>
                                            <label class="subject-chip">
                                                <input type="checkbox" name="subject_ids[]" value="<?= e((string) $subject['id']) ?>"
                                                    <?= in_array($subject['id'], $teacherSubjectIds) ? 'checked' : '' ?>>
                                                <span><?= e($subject['name']) ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                        <textarea name="address" rows="2" style="width:100%; padding:6px; border:1px solid #cbd5e1; border-radius:6px;"><?= e($teacher['address']) ?></textarea>
                                        <button type="submit" class="btn primary" style="width:100%; justify-content:center; padding:8px; margin-top:4px;">Simpan Perubahan</button>
                                    </form>
                                    <div style="margin:12px 0; border-bottom:1px solid #e2e8f0;"></div>
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <span class="muted" style="font-size:12px; color:#dc2626;">Hapus Permanen:</span>
                                        <form method="post" action="<?= e(route_url('admin/teachers/delete')) ?>" onsubmit="return confirm('Hapus guru ini?')">
                                            <input type="hidden" name="id" value="<?= e((string) $teacher['id']) ?>">
                                            <button type="submit" style="background:transparent; color:#dc2626; border:1px solid #fca5a5; padding:4px 8px; border-radius:6px; cursor:pointer; font-size:12px;">Hapus Data</button>
                                        </form>
                                    </div>
                                </div>
                            </details>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
