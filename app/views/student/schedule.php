<?php
$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$byDay = [];
foreach ($days as $d) { $byDay[$d] = []; }
foreach ($schedules as $s) { $byDay[$s['day_of_week']][] = $s; }
?>

<section class="page-head">
    <div>
        <span class="eyebrow">Jadwal</span>
        <h1>Jadwal Pelajaran</h1>
        <?php if ($classroom): ?>
            <p class="muted">Kelas <?= e($classroom['name']) ?> · <?= e($classroom['year_label'] . ' ' . ucfirst($classroom['semester'])) ?></p>
        <?php endif; ?>
    </div>
</section>

<?php if (!$classroom): ?>
    <section class="card">
        <p class="muted" style="text-align:center;padding:32px;">Kamu belum ditempatkan di kelas manapun untuk semester aktif.</p>
    </section>
<?php elseif (empty($schedules)): ?>
    <section class="card">
        <p class="muted" style="text-align:center;padding:32px;">Belum ada jadwal untuk kelas ini.</p>
    </section>
<?php else: ?>
    <section class="card">
        <h2>📅 Jadwal Mingguan — <?= e($classroom['name']) ?></h2>
        <style>
            .schedule-grid {
                display: flex;
                gap: 16px;
                overflow-x: auto;
                padding-bottom: 16px;
            }
            .schedule-col {
                flex: 1;
                min-width: 200px;
                background: #f8fafc;
                border-radius: 12px;
                border: 1px solid #e2e8f0;
                display: flex;
                flex-direction: column;
            }
            .schedule-col-header {
                font-size: 13px;
                font-weight: 700;
                color: #475569;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                padding: 12px;
                text-align: center;
                border-bottom: 1px dashed #cbd5e1;
            }
            .schedule-col-body {
                padding: 12px;
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .schedule-card {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-left: 3px solid #3b82f6;
                border-radius: 8px;
                padding: 12px;
                box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            }
            .schedule-time {
                font-size: 11px;
                color: #64748b;
                display: flex;
                align-items: center;
                gap: 4px;
                margin-bottom: 4px;
            }
            .schedule-subject {
                font-size: 13px;
                font-weight: 700;
                color: #0f172a;
                margin-bottom: 4px;
                line-height: 1.3;
            }
            .schedule-teacher {
                font-size: 12px;
                color: #475569;
                display: flex;
                align-items: center;
                gap: 4px;
            }
        </style>
        <div class="schedule-grid">
            <?php foreach ($days as $day): ?>
                <div class="schedule-col">
                    <div class="schedule-col-header"><?= e($day) ?></div>
                    <div class="schedule-col-body">
                        <?php if (empty($byDay[$day])): ?>
                            <div style="text-align:center; padding: 20px; font-size:12px; color:#94a3b8; border: 1px dashed #e2e8f0; border-radius: 8px;">
                                Kosong
                            </div>
                        <?php else: ?>
                            <?php foreach ($byDay[$day] as $s): ?>
                                <div class="schedule-card">
                                    <div class="schedule-time">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        <?= e(substr($s['start_time'], 0, 5)) ?> — <?= e(substr($s['end_time'], 0, 5)) ?>
                                    </div>
                                    <div class="schedule-subject"><?= e($s['subject_name']) ?></div>
                                    <div class="schedule-teacher">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        <?= e($s['teacher_name']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
