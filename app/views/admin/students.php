<section class="page-head">
    <div>
        <span class="eyebrow">Data Master</span>
        <h1>Kelola Siswa</h1>
    </div>
</section>

<section class="card">
    <h2>Tambah Siswa</h2>
    <form method="post" action="<?= e(route_url('admin/students/save')) ?>" class="form-grid">
        <label>Nama Lengkap<input type="text" name="full_name" required></label>
        <label>Username<input type="text" name="username" required></label>
        <label>Email<input type="email" name="email"></label>
        <label>Password Awal<input type="text" name="password" placeholder="password"></label>
        <label>NIS<input type="text" name="nis" required></label>
        <label>Gender
            <select name="gender" required>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </label>
        <label>Tanggal Lahir<input type="date" name="birth_date"></label>

        <label class="full">Alamat<textarea name="address" rows="2"></textarea></label>
        <button type="submit" class="btn primary">Simpan Siswa</button>
    </form>
</section>

<section class="card">
    <h2>Daftar Siswa</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Nama</th><th>Username</th><th>NIS</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= e($student['full_name']) ?><br><span class="muted"><?= e($student['email']) ?></span></td>
                        <td><?= e($student['username']) ?></td>
                        <td><?= e($student['nis']) ?></td>

                        <td>
                            <details>
                                <summary>Edit</summary>
                                <form method="post" action="<?= e(route_url('admin/students/save')) ?>" class="inline-form">
                                    <input type="hidden" name="id" value="<?= e((string) $student['id']) ?>">
                                    <input type="text" name="full_name" value="<?= e($student['full_name']) ?>" required>
                                    <input type="text" name="username" value="<?= e($student['username']) ?>" required>
                                    <input type="email" name="email" value="<?= e($student['email']) ?>">
                                    <input type="text" name="password" placeholder="Kosongkan jika tetap">
                                    <input type="text" name="nis" value="<?= e($student['nis']) ?>" required>
                                    <select name="gender">
                                        <option value="L" <?= selected($student['gender'], 'L') ?>>Laki-laki</option>
                                        <option value="P" <?= selected($student['gender'], 'P') ?>>Perempuan</option>
                                    </select>
                                    <input type="date" name="birth_date" value="<?= e($student['birth_date']) ?>">

                                    <textarea name="address" rows="2"><?= e($student['address']) ?></textarea>
                                    <button type="submit" class="btn small">Update</button>
                                </form>
                                <form method="post" action="<?= e(route_url('admin/students/delete')) ?>" onsubmit="return confirm('Hapus siswa ini?')">
                                    <input type="hidden" name="id" value="<?= e((string) $student['id']) ?>">
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
