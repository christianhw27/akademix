<section class="page-head">
    <div>
        <span class="eyebrow">Guru</span>
        <h1>Absensi Siswa</h1>
    </div>
</section>

<section class="card">
    <h2>Pilih Kelas</h2>
    <form method="get" action="<?= e(route_url('teacher/attendance')) ?>" class="form-grid">
        <label>Kelas
            <select name="classroom_id" required>
                <?php foreach ($classrooms as $classroom): ?>
                    <option value="<?= e((string) $classroom['id']) ?>" <?= selected($selectedClassroomId, $classroom['id']) ?>><?= e($classroom['name'] . ' - Kelas ' . $classroom['grade_level'] . ' (' . $classroom['year_label'] . ')') ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit" class="btn" style="width: max-content;">Muat Siswa</button>
    </form>
</section>

<section class="card">
    <h2>Input Absensi Manual</h2>
    <form method="post" action="<?= e(route_url('teacher/attendance/save')) ?>">
        <div class="form-grid">
            <label>Kelas
                <select name="classroom_id" required>
                    <?php foreach ($classrooms as $classroom): ?>
                        <option value="<?= e((string) $classroom['id']) ?>" <?= selected($selectedClassroomId, $classroom['id']) ?>><?= e($classroom['name'] . ' - Kelas ' . $classroom['grade_level'] . ' (' . $classroom['year_label'] . ')') ?></option>
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
            <label>Tanggal<input type="date" name="attendance_date" value="<?= e(date('Y-m-d')) ?>" required></label>
            <label>Catatan<input type="text" name="notes" placeholder="Opsional"></label>
        </div>

        <div class="table-wrap" style="margin-top: 16px;">
            <table class="table">
                <thead>
                    <tr><th>Siswa</th><th>NIS</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td>
                                <?= e($student['full_name']) ?>
                                <input type="hidden" name="student_ids[]" value="<?= e((string) $student['id']) ?>">
                            </td>
                            <td><?= e($student['nis']) ?></td>
                            <td>
                                <select name="statuses[<?= e((string) $student['id']) ?>]">
                                    <option value="hadir">Hadir</option>
                                    <option value="izin">Izin</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="alpha">Alfa</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn primary" style="margin-top: 16px; width: max-content;">Simpan Absensi</button>
    </form>
</section>

<section class="card">
    <h2>Riwayat Absensi</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Tanggal</th><th>Kelas</th><th>Mapel</th><th>Rincian Kehadiran</th><th>Catatan</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($sessions as $session): ?>
                    <tr>
                        <td>
                            <?= e(format_date($session['attendance_date'])) ?><br>
                            <small style="color: #64748b;">Pukul <?= e(date('H:i', strtotime($session['created_at']))) ?></small>
                        </td>
                        <td><?= e($session['classroom_name']) ?></td>
                        <td><?= e($session['subject_name']) ?></td>
                        <td>
                            <div style="display:flex; gap:8px; font-size:12px;">
                                <span style="color:#16a34a; font-weight:600;"><?= e((string) $session['count_hadir']) ?> H</span>
                                <span style="color:#3b82f6; font-weight:600;"><?= e((string) $session['count_izin']) ?> I</span>
                                <span style="color:#eab308; font-weight:600;"><?= e((string) $session['count_sakit']) ?> S</span>
                                <span style="color:#dc2626; font-weight:600;"><?= e((string) $session['count_alpha']) ?> A</span>
                            </div>
                        </td>
                        <td><?= e($session['notes']) ?></td>
                        <td>
                            <a href="<?= e(route_url('teacher/attendance/edit&session_id=' . $session['id'])) ?>" class="btn small" style="text-decoration:none; border: 1px solid #e2e8f0;">✏️ Edit/Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
