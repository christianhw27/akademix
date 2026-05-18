<?php
$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$byDay = [];
foreach ($days as $d) { $byDay[$d] = []; }
foreach ($schedules as $s) { 
    $byDay[$s['day_of_week']][] = $s; 
}
// Sort by start_time
foreach ($days as $d) {
    usort($byDay[$d], function($a, $b) {
        return strcmp($a['start_time'], $b['start_time']);
    });
}
?>

<style>
/* Modern Premium Aesthetic Overrides */
.aes-wrapper {
    background-color: #f8fafc;
    color: #191c1e;
    margin: -14px -28px -60px; /* Offset .container padding */
    padding: 40px;
    min-height: calc(100vh - 80px);
    font-family: 'Inter', sans-serif;
}

.aes-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 32px;
}

.aes-title .eyebrow {
    font-size: 13px;
    font-weight: 600;
    color: #1e3a8a;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 8px;
    display: block;
}

.aes-title h1 {
    font-size: 32px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 8px 0;
    letter-spacing: -0.01em;
}

.aes-title .meta {
    font-size: 14px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 12px;
}

.aes-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.2s;
}

.aes-btn-primary {
    background: #1e3a8a;
    color: #ffffff;
}
.aes-btn-primary:hover {
    background: #172554;
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
}

/* Timetable Grid */
.timetable-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    align-items: start;
}

.day-column {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.day-header {
    background: #f1f5f9;
    padding: 16px;
    text-align: center;
    font-weight: 600;
    color: #334155;
    border-bottom: 1px solid #e2e8f0;
    border-radius: 12px 12px 0 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-size: 13px;
}

.day-body {
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    min-height: 100px;
}

/* Schedule Card */
.sched-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #3b82f6;
    border-radius: 8px;
    padding: 12px;
    position: relative;
    transition: transform 0.2s, box-shadow 0.2s;
}

.sched-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    border-left-color: #2563eb;
    z-index: 10;
}

/* Fix dropdown overlapping by elevating the card that has an open dropdown */
.sched-card:has(details[open]) {
    z-index: 100;
}

.sched-time {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.sched-subject {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
    margin-bottom: 6px;
}

.sched-teacher {
    font-size: 12px;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 6px;
}

.sched-empty {
    text-align: center;
    padding: 24px 0;
    color: #94a3b8;
    font-size: 13px;
    font-weight: 500;
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
}

/* Actions Dropdown */
.action-wrapper { position: absolute; top: 8px; right: 8px; }
.action-wrapper details { position: relative; }
.action-wrapper summary { 
    list-style: none; outline: none; cursor: pointer; 
    color: #94a3b8; padding: 4px; border-radius: 4px;
}
.action-wrapper summary::-webkit-details-marker { display: none; }
.action-wrapper summary:hover { background: #f1f5f9; color: #0f172a; }

.aes-dropdown {
    position: absolute;
    top: calc(100% + 4px);
    right: 0;
    width: 300px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    z-index: 50;
    color: #191c1e;
}

.aes-dropdown h3 {
    margin-top: 0;
    font-size: 16px;
    margin-bottom: 16px;
    color: #0f172a;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 8px;
}

.aes-dropdown label {
    font-size: 13px;
    color: #475569;
    display: block;
    margin-bottom: 12px;
}
.aes-dropdown select, .aes-dropdown input {
    width: 100%;
    margin-top: 4px;
    padding: 8px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 13px;
}

.top-actions details { position: relative; }
.top-actions summary { list-style: none; outline: none; }
.top-actions summary::-webkit-details-marker { display: none; }
</style>

<div class="aes-wrapper">
    <div class="aes-header">
        <div class="aes-title">
            <span class="eyebrow">Manajemen Jadwal</span>
            <h1><?= e($classroom['name']) ?></h1>
            <div class="meta">
                <span>📅 <?= e($classroom['year_label'] . ' — ' . ucfirst($classroom['semester'])) ?></span>
                <span>•</span>
                <span>🏫 Kelas <?= e((string) $classroom['grade_level']) ?></span>
            </div>
        </div>
        
        <div class="top-actions">
            <details class="action-dropdown">
                <summary class="aes-btn aes-btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Jadwal
                </summary>
                <div class="aes-dropdown" style="right: 0; left: auto; width: 340px;">
                    <h3>Plot Jadwal Baru</h3>
                    <form method="post" action="<?= e(route_url('admin/schedules/save')) ?>">
                        <input type="hidden" name="classroom_id" value="<?= e((string) $classroom['id']) ?>">
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <label>Hari
                                <select name="day_of_week" required>
                                    <?php foreach ($days as $day): ?>
                                        <option value="<?= e($day) ?>"><?= e($day) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <div></div>
                            
                            <label>Waktu Mulai
                                <input type="time" name="start_time" required>
                            </label>
                            <label>Waktu Selesai
                                <input type="time" name="end_time" required>
                            </label>
                        </div>
                        
                        <label>Mata Pelajaran
                            <select name="subject_id" required>
                                <option value="">— Pilih Mapel —</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?= e((string) $subject['id']) ?>"><?= e($subject['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        
                        <label>Guru Pengampu
                            <select name="teacher_id" required>
                                <option value="">— Pilih Guru —</option>
                                <?php foreach ($teachers as $teacher): ?>
                                    <option value="<?= e((string) $teacher['id']) ?>"><?= e($teacher['full_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        
                        <div style="text-align: right; margin-top: 16px;">
                            <button type="submit" class="aes-btn aes-btn-primary" style="padding: 8px 16px;">Simpan</button>
                        </div>
                    </form>
                </div>
            </details>
        </div>
    </div>

    <div class="timetable-grid">
        <?php foreach ($days as $day): ?>
            <div class="day-column">
                <div class="day-header"><?= e($day) ?></div>
                <div class="day-body">
                    <?php if (empty($byDay[$day])): ?>
                        <div class="sched-empty">Kosong</div>
                    <?php else: ?>
                        <?php foreach ($byDay[$day] as $s): ?>
                            <div class="sched-card">
                                <div class="action-wrapper">
                                    <details class="action-dropdown">
                                        <summary>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                        </summary>
                                        <div class="aes-dropdown" style="width: 320px;">
                                            <h3>Edit Jadwal</h3>
                                            <form method="post" action="<?= e(route_url('admin/schedules/save')) ?>" style="margin-bottom: 16px;">
                                                <input type="hidden" name="id" value="<?= e((string) $s['id']) ?>">
                                                <input type="hidden" name="classroom_id" value="<?= e((string) $classroom['id']) ?>">
                                                
                                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                                    <label>Hari
                                                        <select name="day_of_week">
                                                            <?php foreach ($days as $d): ?>
                                                                <option value="<?= e($d) ?>" <?= selected($s['day_of_week'], $d) ?>><?= e($d) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </label>
                                                    <div></div>
                                                    
                                                    <label>Mulai
                                                        <input type="time" name="start_time" value="<?= e(substr($s['start_time'], 0, 5)) ?>">
                                                    </label>
                                                    <label>Selesai
                                                        <input type="time" name="end_time" value="<?= e(substr($s['end_time'], 0, 5)) ?>">
                                                    </label>
                                                </div>

                                                <label>Mapel
                                                    <select name="subject_id">
                                                        <?php foreach ($subjects as $sub): ?>
                                                            <option value="<?= e((string) $sub['id']) ?>" <?= selected($s['subject_id'], $sub['id']) ?>><?= e($sub['name']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </label>
                                                <label>Guru
                                                    <select name="teacher_id">
                                                        <?php foreach ($teachers as $t): ?>
                                                            <option value="<?= e((string) $t['id']) ?>" <?= selected($s['teacher_id'], $t['id']) ?>><?= e($t['full_name']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </label>
                                                
                                                <button type="submit" class="aes-btn aes-btn-primary" style="width: 100%; justify-content: center; margin-top: 8px; padding: 8px;">Update Jadwal</button>
                                            </form>
                                            
                                            <form method="post" action="<?= e(route_url('admin/schedules/delete')) ?>" onsubmit="return confirm('Hapus jadwal ini secara permanen?')">
                                                <input type="hidden" name="id" value="<?= e((string) $s['id']) ?>">
                                                <input type="hidden" name="classroom_id" value="<?= e((string) $classroom['id']) ?>">
                                                <button type="submit" style="width: 100%; background: #fee2e2; color: #dc2626; border: none; padding: 8px; border-radius: 6px; cursor: pointer; font-weight: 600;">Hapus</button>
                                            </form>
                                        </div>
                                    </details>
                                </div>
                                
                                <div class="sched-time">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    <?= e(substr($s['start_time'], 0, 5)) ?> — <?= e(substr($s['end_time'], 0, 5)) ?>
                                </div>
                                <div class="sched-subject"><?= e($s['subject_name']) ?></div>
                                <div class="sched-teacher">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <?= e($s['teacher_name']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
// Close all details when clicking outside
document.addEventListener('click', function(e) {
    document.querySelectorAll('.action-dropdown').forEach(function(details) {
        if (!details.contains(e.target)) {
            details.removeAttribute('open');
        }
    });
});
</script>
