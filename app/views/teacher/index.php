<section class="page-head">
    <div>
        <span class="eyebrow">Guru</span>
        <h1>Portal Guru</h1>
        <p class="muted">Kelola materi, tugas, absensi, dan penilaian siswa.</p>
    </div>
</section>

<section class="grid cards-4">
    <article class="card stat-card"><span class="muted">Materi</span><strong><?= e((string) $stats['materials']) ?></strong></article>
    <article class="card stat-card"><span class="muted">Tugas</span><strong><?= e((string) $stats['assignments']) ?></strong></article>
    <article class="card stat-card"><span class="muted">Absensi</span><strong><?= e((string) $stats['attendance']) ?></strong></article>
    <article class="card stat-card"><span class="muted">Nilai</span><strong><?= e((string) $stats['grades']) ?></strong></article>
</section>

<!-- Today's Schedule -->
<section class="card" style="margin-top: 24px;">
    <h2 style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">📅 Jadwal Mengajar Hari Ini (<?= e($todayName) ?>)</h2>
    <p class="muted" style="margin-top:-10px; margin-bottom:20px; font-size:13px;">Klik pada kartu jadwal untuk langsung mengisi absensi kelas.</p>

    <?php if (empty($todaySchedule)): ?>
        <div style="text-align: center; padding: 32px; background: #f8fafc; border-radius: 8px; border: 1px dashed #cbd5e1; color: #64748b;">
            Tidak ada jadwal mengajar untuk hari ini.
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
            <?php foreach ($todaySchedule as $i => $s): ?>
                <?php
                $key = $s['classroom_id'] . '_' . $s['subject_id'];
                $alreadyTaken = isset($todaySessions[$key]);
                $sessionId = $todaySessions[$key] ?? 0;
                ?>
                <div class="teacher-schedule-card-main" onclick="<?= $alreadyTaken ? "window.location.href='" . e(route_url('teacher/attendance/edit&session_id=' . $sessionId)) . "'" : "openAttendanceModal('modal_attend_" . $i . "')" ?>" style="background: #ffffff; border: 1px solid <?= $alreadyTaken ? '#22c55e' : '#e2e8f0' ?>; border-left: 4px solid <?= $alreadyTaken ? '#22c55e' : '#3b82f6' ?>; border-radius: 8px; padding: 16px; cursor: pointer; transition: all 0.2s; position: relative;">
                    <?php if ($alreadyTaken): ?>
                        <div style="position: absolute; top: 12px; right: 12px; color: #22c55e; font-size: 20px;">✓</div>
                    <?php endif; ?>
                    <div style="font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                        🕐 <?= e(substr($s['start_time'], 0, 5)) ?> — <?= e(substr($s['end_time'], 0, 5)) ?>
                    </div>
                    <div style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px;"><?= e($s['subject_name']) ?></div>
                    <div style="font-size: 13px; color: #475569;">🏫 Kelas <?= e($s['classroom_name']) ?> · Kelas <?= e((string) $s['grade_level']) ?></div>
                    <div style="margin-top: 12px; font-size: 12px; font-weight: 600; color: <?= $alreadyTaken ? '#16a34a' : '#3b82f6' ?>;">
                        <?= $alreadyTaken ? 'Absensi sudah diisi (Klik untuk edit)' : 'Isi Absensi →' ?>
                    </div>
                </div>

                <!-- Modal for this class/subject -->
                <?php if (!$alreadyTaken): ?>
                    <div id="modal_attend_<?= $i ?>" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
                        <div class="modal-content" style="background: #fff; width: 100%; max-width: 600px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); max-height: 90vh; display: flex; flex-direction: column;">
                            <div style="padding: 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h3 style="margin: 0; font-size: 1.2rem;">Isi Absensi</h3>
                                    <div style="font-size: 13px; color: #64748b; margin-top: 4px;"><?= e($s['subject_name']) ?> · <?= e($s['classroom_name']) ?> · Hari Ini</div>
                                </div>
                                <button type="button" onclick="closeAttendanceModal('modal_attend_<?= $i ?>')" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #94a3b8;">&times;</button>
                            </div>
                            <div style="padding: 20px; overflow-y: auto; flex: 1;">
                                <form method="post" action="<?= e(route_url('teacher/attendance/save')) ?>">
                                    <input type="hidden" name="classroom_id" value="<?= e((string) $s['classroom_id']) ?>">
                                    <input type="hidden" name="subject_id" value="<?= e((string) $s['subject_id']) ?>">
                                    <input type="hidden" name="attendance_date" value="<?= e(date('Y-m-d')) ?>">
                                    
                                    <label>Catatan Umum (Opsional)<input type="text" name="notes" placeholder="Contoh: Pertemuan 3 - Ulangan Harian"></label>
                                    
                                    <table class="table" style="margin-top: 16px;">
                                        <thead>
                                            <tr><th>Siswa</th><th>Status</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $students = $studentsByClassroom[$s['classroom_id']] ?? [];
                                            foreach ($students as $student): 
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div style="font-weight:600;"><?= e($student['full_name']) ?></div>
                                                        <div style="font-size:12px; color:#64748b;">NIS: <?= e($student['nis']) ?></div>
                                                        <input type="hidden" name="student_ids[]" value="<?= e((string) $student['id']) ?>">
                                                    </td>
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
                                    
                                    <div style="margin-top: 20px; text-align: right;">
                                        <button type="button" class="btn" onclick="closeAttendanceModal('modal_attend_<?= $i ?>')" style="margin-right: 8px;">Batal</button>
                                        <button type="submit" class="btn primary">Simpan Absensi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<style>
    .teacher-schedule-card-main:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
</style>

<script>
function openAttendanceModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeAttendanceModal(id) {
    document.getElementById(id).style.display = 'none';
}
</script>

<!-- Weekly Schedule -->
<?php
$dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
?>
<section class="card" style="margin-top: 24px;">
    <h2>📋 Jadwal Mingguan</h2>
    <style>
        .teacher-schedule-grid {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding-bottom: 12px;
        }
        .teacher-schedule-col {
            flex: 1;
            min-width: 180px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }
        .teacher-schedule-col-header {
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 10px;
            text-align: center;
            border-bottom: 1px dashed #cbd5e1;
        }
        .teacher-schedule-col-body {
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .teacher-schedule-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #8b5cf6;
            border-radius: 6px;
            padding: 10px;
            font-size: 12px;
        }
        .teacher-schedule-card .subj { font-weight: 700; color: #0f172a; margin-bottom: 2px; }
        .teacher-schedule-card .meta { color: #64748b; }
    </style>
    <div class="teacher-schedule-grid">
        <?php foreach ($dayOrder as $day): ?>
            <div class="teacher-schedule-col">
                <div class="teacher-schedule-col-header"><?= e($day) ?></div>
                <div class="teacher-schedule-col-body">
                    <?php if (empty($schedulesByDay[$day])): ?>
                        <div style="text-align:center; padding:16px; color:#94a3b8; font-size:11px;">Kosong</div>
                    <?php else: ?>
                        <?php foreach ($schedulesByDay[$day] as $s): ?>
                            <div class="teacher-schedule-card">
                                <div class="subj"><?= e($s['subject_name']) ?></div>
                                <div class="meta"><?= e(substr($s['start_time'], 0, 5)) ?> — <?= e(substr($s['end_time'], 0, 5)) ?></div>
                                <div class="meta">🏫 <?= e($s['classroom_name']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Recent Submissions -->
<section class="card" style="margin-top: 24px;">
    <h2>Pengumpulan Tugas Terkini</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Siswa</th><th>Tugas</th><th>Mapel</th><th>Status</th><th>Waktu</th></tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($submissions, 0, 8) as $submission): ?>
                    <tr>
                        <td><?= e($submission['student_name']) ?></td>
                        <td><?= e($submission['assignment_title']) ?></td>
                        <td><?= e($submission['subject_name']) ?></td>
                        <td><span class="badge info"><?= e($submission['status']) ?></span></td>
                        <td><?= e(format_datetime($submission['submitted_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
