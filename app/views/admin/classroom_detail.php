<section class="page-head">
    <div class="breadcrumb">
        <a href="<?= e(route_url('admin/classrooms')) ?>">Kelas & Siswa</a>
        <span class="sep">›</span>
        <span class="current"><?= e($classroom['name']) ?></span>
    </div>
    <h1><?= e($classroom['name']) ?></h1>
    <p class="muted"><?= e($classroom['year_label'] . ' — ' . ucfirst($classroom['semester'])) ?> · Kelas <?= e((string) $classroom['grade_level']) ?> · Wali Kelas: <?= e($classroom['homeroom_teacher'] ?: '-') ?></p>
</section>

<section class="card">
    <h2>➕ Tempatkan Siswa</h2>
    <form method="post" action="<?= e(route_url('admin/classrooms/enroll/save')) ?>" style="display:flex;gap:12px;align-items:end;flex-wrap:wrap;">
        <input type="hidden" name="classroom_id" value="<?= e((string) $classroom['id']) ?>">
        <label style="flex:1;min-width:200px;">Pilih Siswa
            <select name="student_id" required>
                <?php foreach ($students as $s): ?>
                    <option value="<?= e((string) $s['id']) ?>"><?= e($s['full_name'] . ' (' . $s['nis'] . ')') ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit" class="btn primary">Tambahkan</button>
    </form>
</section>

<section class="card">
    <h2>Daftar Siswa — <?= e($classroom['name']) ?> <span class="badge info"><?= count($classroomStudents) ?> siswa</span></h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>#</th><th>Nama</th><th>NIS</th><th>Email</th><th>Gender</th><th>Angkatan</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($classroomStudents as $i => $cs): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <a href="<?= e(route_url('admin/classrooms&view=' . $classroom['id'] . '&student=' . $cs['id'])) ?>" style="font-weight:600;">
                                <?= e($cs['full_name']) ?>
                            </a>
                        </td>
                        <td><code><?= e($cs['nis']) ?></code></td>
                        <td><span class="muted"><?= e($cs['email']) ?></span></td>
                        <td><span class="badge <?= $cs['gender'] === 'L' ? 'info' : 'warning' ?>"><?= $cs['gender'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></span></td>
                        <td><?= e((string) $cs['cohort_name']) ?></td>
                        <td>
                            <details style="position:relative;">
                                <summary class="btn small" style="cursor:pointer; display:inline-block;">Edit Data</summary>
                                <div class="aes-dropdown" style="position:absolute; right:0; top:calc(100% + 4px); width:320px; background:#fff; border:1px solid #e2e8f0; padding:16px; border-radius:12px; z-index:100; box-shadow:0 10px 25px rgba(0,0,0,0.1);">
                                    <h3 style="margin-top:0; font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-bottom:12px;">Edit Data Siswa</h3>
                                    <form method="post" action="<?= e(route_url('admin/students/save')) ?>">
                                        <input type="hidden" name="id" value="<?= e((string) $cs['id']) ?>">
                                        <input type="hidden" name="classroom_return_id" value="<?= e((string) $classroom['id']) ?>">
                                        
                                        <label style="display:block; margin-bottom:8px; font-size:13px; font-weight:600;">Nama Lengkap
                                            <input type="text" name="full_name" value="<?= e($cs['full_name']) ?>" required style="width:100%; padding:6px; border:1px solid #cbd5e1; border-radius:6px; margin-top:4px;">
                                        </label>
                                        
                                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                                            <label style="font-size:13px; font-weight:600;">NIS
                                                <input type="text" name="nis" value="<?= e($cs['nis']) ?>" required style="width:100%; padding:6px; border:1px solid #cbd5e1; border-radius:6px; margin-top:4px;">
                                            </label>
                                            <label style="font-size:13px; font-weight:600;">Gender
                                                <select name="gender" required style="width:100%; padding:6px; border:1px solid #cbd5e1; border-radius:6px; margin-top:4px;">
                                                    <option value="L" <?= selected($cs['gender'], 'L') ?>>Laki-laki</option>
                                                    <option value="P" <?= selected($cs['gender'], 'P') ?>>Perempuan</option>
                                                </select>
                                            </label>
                                        </div>
                                        
                                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                                            <label style="font-size:13px; font-weight:600;">Username
                                                <input type="text" name="username" value="<?= e($cs['username']) ?>" required style="width:100%; padding:6px; border:1px solid #cbd5e1; border-radius:6px; margin-top:4px;">
                                            </label>
                                            <label style="font-size:13px; font-weight:600;">Password
                                                <input type="text" name="password" placeholder="(kosongkan)" style="width:100%; padding:6px; border:1px solid #cbd5e1; border-radius:6px; margin-top:4px;">
                                            </label>
                                        </div>
                                        
                                        <label style="display:block; margin-bottom:8px; font-size:13px; font-weight:600;">Email
                                            <input type="email" name="email" value="<?= e($cs['email']) ?>" required style="width:100%; padding:6px; border:1px solid #cbd5e1; border-radius:6px; margin-top:4px;">
                                        </label>
                                        
                                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                                            <label style="font-size:13px; font-weight:600;">Angkatan
                                                <select name="cohort_id" required style="width:100%; padding:6px; border:1px solid #cbd5e1; border-radius:6px; margin-top:4px;">
                                                    <?php foreach ($cohorts as $cohort): ?>
                                                        <option value="<?= e((string) $cohort['id']) ?>" <?= selected($cs['cohort_id'], $cohort['id']) ?>><?= e($cohort['year_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </label>
                                            <label style="font-size:13px; font-weight:600;">Tgl Lahir
                                                <input type="date" name="birth_date" value="<?= e($cs['birth_date']) ?>" style="width:100%; padding:6px; border:1px solid #cbd5e1; border-radius:6px; margin-top:4px;">
                                            </label>
                                        </div>

                                        <label style="display:block; margin-bottom:12px; font-size:13px; font-weight:600;">Alamat
                                            <textarea name="address" rows="2" style="width:100%; padding:6px; border:1px solid #cbd5e1; border-radius:6px; margin-top:4px;"><?= e($cs['address']) ?></textarea>
                                        </label>
                                        
                                        <button type="submit" class="btn primary" style="width:100%; justify-content:center; padding:8px;">Simpan Perubahan</button>
                                    </form>
                                    
                                    <div style="margin:12px 0; border-bottom:1px solid #e2e8f0;"></div>
                                    
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <span class="muted" style="font-size:12px;">Hapus dari Kelas:</span>
                                        <form method="post" action="<?= e(route_url('admin/classrooms/enroll/delete')) ?>" onsubmit="return confirm('Keluarkan siswa dari kelas ini? (Data siswa tetap ada)')">
                                            <input type="hidden" name="id" value="<?= e((string) $cs['enrollment_id']) ?>">
                                            <button type="submit" class="btn danger small">Keluarkan</button>
                                        </form>
                                    </div>
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:8px;">
                                        <span class="muted" style="font-size:12px; color:#dc2626;">Hapus Permanen:</span>
                                        <form method="post" action="<?= e(route_url('admin/students/delete')) ?>" onsubmit="return confirm('Hapus siswa secara PERMANEN dari sistem?')">
                                            <input type="hidden" name="id" value="<?= e((string) $cs['id']) ?>">
                                            <button type="submit" style="background:transparent; color:#dc2626; border:1px solid #fca5a5; padding:4px 8px; border-radius:6px; cursor:pointer; font-size:12px;">Hapus Siswa</button>
                                        </form>
                                    </div>
                                </div>
                            </details>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($classroomStudents)): ?>
                    <tr><td colspan="7" class="muted" style="text-align:center;padding:32px;">Belum ada siswa di kelas ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
