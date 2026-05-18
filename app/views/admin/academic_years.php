<section class="page-head">
    <div>
        <span class="eyebrow">Pengaturan</span>
        <h1>Tahun Ajaran & Semester</h1>
    </div>
</section>

<section class="card">
    <h2>Tambah Periode</h2>
    <form method="post" action="<?= e(route_url('admin/academic-years/save')) ?>" class="form-grid">
        <label>Tahun Ajaran<input type="text" name="year_label" placeholder="2025/2026" required></label>
        <label>Semester
            <select name="semester">
                <option value="ganjil">Ganjil</option>
                <option value="genap">Genap</option>
            </select>
        </label>
        <label>Tanggal Mulai<input type="date" name="start_date" required></label>
        <label>Tanggal Selesai<input type="date" name="end_date" required></label>
        <label>Status Aktif
            <select name="is_active">
                <option value="1">Aktif</option>
                <option value="0">Arsip</option>
            </select>
        </label>
        <button type="submit" class="btn primary">Simpan Periode</button>
    </form>
</section>

<section class="card">
    <h2>Daftar Periode</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Tahun</th><th>Semester</th><th>Rentang</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($academicYears as $period): ?>
                    <tr>
                        <td><?= e($period['year_label']) ?></td>
                        <td><?= e(ucfirst($period['semester'])) ?></td>
                        <td><?= e(format_date($period['start_date'])) ?> - <?= e(format_date($period['end_date'])) ?></td>
                        <td><span class="badge <?= $period['is_active'] ? 'success' : 'dark' ?>"><?= $period['is_active'] ? 'Aktif' : 'Arsip' ?></span></td>
                        <td>
                            <details style="position:relative;">
                                <summary class="btn small" style="cursor:pointer; display:inline-block;">Edit Data</summary>
                                <div class="aes-dropdown" style="position:absolute; right:0; top:calc(100% + 4px); width:320px; background:#fff; border:1px solid #e2e8f0; padding:16px; border-radius:12px; z-index:100; box-shadow:0 10px 25px rgba(0,0,0,0.1);">
                                    <h3 style="margin-top:0; font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-bottom:12px;">Edit Tahun Ajaran</h3>
                                    <form method="post" action="<?= e(route_url('admin/academic-years/save')) ?>" style="display:flex; flex-direction:column; gap:8px;">
                                    <input type="hidden" name="id" value="<?= e((string) $period['id']) ?>">
                                    <input type="text" name="year_label" value="<?= e($period['year_label']) ?>" required>
                                    <select name="semester">
                                        <option value="ganjil" <?= selected($period['semester'], 'ganjil') ?>>Ganjil</option>
                                        <option value="genap" <?= selected($period['semester'], 'genap') ?>>Genap</option>
                                    </select>
                                    <input type="date" name="start_date" value="<?= e($period['start_date']) ?>" required>
                                    <input type="date" name="end_date" value="<?= e($period['end_date']) ?>" required>
                                    <select name="is_active">
                                        <option value="1" <?= selected($period['is_active'], 1) ?>>Aktif</option>
                                        <option value="0" <?= selected($period['is_active'], 0) ?>>Arsip</option>
                                    </select>
                                        <button type="submit" class="btn primary" style="width:100%; justify-content:center; padding:8px; margin-top:4px;">Simpan Perubahan</button>
                                    </form>
                                    
                                    <div style="margin:12px 0; border-bottom:1px solid #e2e8f0;"></div>
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <span class="muted" style="font-size:12px; color:#dc2626;">Hapus Permanen:</span>
                                <form method="post" action="<?= e(route_url('admin/academic-years/delete')) ?>" onsubmit="return confirm('Hapus periode ini?')">
                                    <input type="hidden" name="id" value="<?= e((string) $period['id']) ?>">
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
