<section class="page-head">
    <div>
        <span class="eyebrow">Akademik</span>
        <h1>Mata Pelajaran</h1>
        <p class="muted">Kelola daftar mata pelajaran. Guru pengampu diatur di menu Guru.</p>
    </div>
</section>

<section class="card">
    <h2>➕ Tambah Mata Pelajaran</h2>
    <form method="post" action="<?= e(route_url('admin/subjects/save')) ?>" class="form-grid">
        <label>Kode Mapel<input type="text" name="code" required></label>
        <label>Nama Mapel<input type="text" name="name" required></label>
        <div class="full">
            <button type="submit" class="btn primary" style="width: max-content;">Simpan Mapel</button>
        </div>
    </form>
</section>

<section class="card">
    <h2>Daftar Mapel</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Kode</th><th>Nama</th><th>Guru Pengampu</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $subject): ?>
                    <tr>
                        <td><code><?= e($subject['code']) ?></code></td>
                        <td><?= e($subject['name']) ?><br><span class="muted" style="font-size:.82rem;"><?= e($subject['description']) ?></span></td>
                        <td>
                            <?php if ($subject['teacher_names']): ?>
                                <?php foreach (explode(', ', $subject['teacher_names']) as $tn): ?>
                                    <span class="badge info"><?= e($tn) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <details style="position:relative;">
                                <summary class="btn small" style="cursor:pointer; display:inline-block;">Edit Data</summary>
                                <div class="aes-dropdown" style="position:absolute; right:0; top:calc(100% + 4px); width:320px; background:#fff; border:1px solid #e2e8f0; padding:16px; border-radius:12px; z-index:100; box-shadow:0 10px 25px rgba(0,0,0,0.1); text-align:left;">
                                    <h3 style="margin-top:0; font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-bottom:12px;">Edit Mata Pelajaran</h3>
                                    <form method="post" action="<?= e(route_url('admin/subjects/save')) ?>" style="display:flex; flex-direction:column; gap:8px;">
                                        <input type="hidden" name="id" value="<?= e((string) $subject['id']) ?>">
                                        <input type="text" name="code" value="<?= e($subject['code']) ?>" required style="padding:6px; border:1px solid #cbd5e1; border-radius:6px;">
                                        <input type="text" name="name" value="<?= e($subject['name']) ?>" required style="padding:6px; border:1px solid #cbd5e1; border-radius:6px;">
                                        <textarea name="description" rows="2" style="width:100%; padding:6px; border:1px solid #cbd5e1; border-radius:6px;"><?= e($subject['description']) ?></textarea>
                                        <button type="submit" class="btn primary" style="width:100%; justify-content:center; padding:8px; margin-top:4px;">Simpan Perubahan</button>
                                    </form>
                                    <div style="margin:12px 0; border-bottom:1px solid #e2e8f0;"></div>
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <span class="muted" style="font-size:12px; color:#dc2626;">Hapus Permanen:</span>
                                        <form method="post" action="<?= e(route_url('admin/subjects/delete')) ?>" onsubmit="return confirm('Hapus mapel ini?')">
                                            <input type="hidden" name="id" value="<?= e((string) $subject['id']) ?>">
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
