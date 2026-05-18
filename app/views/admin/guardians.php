<section class="page-head">
    <div>
        <span class="eyebrow">Data Master</span>
        <h1>Kelola Orang Tua</h1>
        <p class="muted">Orang tua otomatis terhubung dengan anak yang sudah diset di data siswa.</p>
    </div>
</section>

<section class="card">
    <h2>➕ Tambah Orang Tua</h2>
    <form method="post" action="<?= e(route_url('admin/guardians/save')) ?>" class="form-grid">
        <label>Nama Lengkap<input type="text" name="full_name" required></label>
        <label>Email <span class="muted">(untuk login)</span><input type="email" name="email" required placeholder="orangtua@email.com"></label>
        <label>Password Awal<input type="text" name="password" placeholder="password"></label>
        <label>Telepon<input type="text" name="phone"></label>
        <label class="full">Alamat<textarea name="address" rows="2"></textarea></label>
        <button type="submit" class="btn primary">Simpan Orang Tua</button>
    </form>
</section>

<section class="card">
    <h2>Daftar Orang Tua</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Nama</th><th>Email</th><th>Kontak</th><th>Anak</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($guardians as $guardian): ?>
                    <tr>
                        <td><?= e($guardian['full_name']) ?></td>
                        <td><code><?= e($guardian['email']) ?></code></td>
                        <td><?= e($guardian['phone'] ?: '-') ?></td>
                        <td>
                            <?php if ($guardian['children_names']): ?>
                                <?php foreach (explode(', ', $guardian['children_names']) as $cn): ?>
                                    <span class="badge success"><?= e($cn) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="muted">Belum ada</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <details>
                                <summary>Edit</summary>
                                <form method="post" action="<?= e(route_url('admin/guardians/save')) ?>" class="inline-form">
                                    <input type="hidden" name="id" value="<?= e((string) $guardian['id']) ?>">
                                    <input type="text" name="full_name" value="<?= e($guardian['full_name']) ?>" required>
                                    <input type="email" name="email" value="<?= e($guardian['email']) ?>">
                                    <input type="text" name="password" placeholder="Kosongkan jika tetap">
                                    <input type="text" name="phone" value="<?= e($guardian['phone']) ?>">
                                    <textarea name="address" rows="2"><?= e($guardian['address']) ?></textarea>
                                    <button type="submit" class="btn small">Update</button>
                                </form>
                                <form method="post" action="<?= e(route_url('admin/guardians/delete')) ?>" onsubmit="return confirm('Hapus orang tua ini?')">
                                    <input type="hidden" name="id" value="<?= e((string) $guardian['id']) ?>">
                                    <button type="submit" class="btn danger small">Hapus</button>
                                </form>
                            </details>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
