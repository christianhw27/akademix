<section class="page-head">
    <div>
        <span class="eyebrow">Guru</span>
        <h1>Edit Absensi</h1>
        <?php if ($session): ?>
            <p class="muted"><?= e($session['subject_name']) ?> · <?= e($session['classroom_name']) ?> · <?= e(format_date($session['attendance_date'])) ?></p>
        <?php endif; ?>
    </div>
</section>

<?php if (!$session): ?>
    <section class="card">
        <div style="text-align: center; padding: 40px; color: #64748b;">
            Sesi absensi tidak ditemukan.
            <br><br>
            <a href="<?= e(route_url('teacher/attendance')) ?>" class="btn" style="text-decoration:none;">← Kembali</a>
        </div>
    </section>
<?php else: ?>
    <section class="card">
        <h2>Ubah Status Kehadiran</h2>
        <form method="post" action="<?= e(route_url('teacher/attendance/update')) ?>">
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>Siswa</th><th>NIS</th><th>Status Saat Ini</th><th>Ubah Menjadi</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td>
                                    <?= e($record['student_name']) ?>
                                    <input type="hidden" name="record_ids[]" value="<?= e((string) $record['id']) ?>">
                                </td>
                                <td><?= e($record['nis']) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = match(strtolower($record['status'])) {
                                        'hadir' => 'success',
                                        'izin' => 'info',
                                        'sakit' => 'warning',
                                        default => 'danger',
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= e(ucfirst($record['status'])) ?></span>
                                </td>
                                <td>
                                    <select name="statuses[<?= e((string) $record['id']) ?>]">
                                        <option value="hadir" <?= $record['status'] === 'hadir' ? 'selected' : '' ?>>Hadir</option>
                                        <option value="izin" <?= $record['status'] === 'izin' ? 'selected' : '' ?>>Izin</option>
                                        <option value="sakit" <?= $record['status'] === 'sakit' ? 'selected' : '' ?>>Sakit</option>
                                        <option value="alpha" <?= $record['status'] === 'alpha' ? 'selected' : '' ?>>Alfa</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 16px;">
                <button type="submit" class="btn primary" style="width: max-content;">Simpan Perubahan</button>
                <a href="<?= e(route_url('teacher/attendance')) ?>" class="btn" style="text-decoration:none; width: max-content;">Batal</a>
            </div>
        </form>
    </section>
<?php endif; ?>
