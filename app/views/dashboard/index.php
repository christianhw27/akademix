<section class="page-head">
    <div>
        <span class="eyebrow">Dashboard</span>
        <h1>Ringkasan <?= e(role_label($user['role'])) ?></h1>
        <p class="muted">Akses cepat ke modul utama AKADEMIX.</p>
    </div>
</section>

<section class="grid <?= $user['role'] === 'admin' ? 'cards-3' : 'cards-4' ?>">
    <?php foreach ($summary as $item): ?>
        <article class="card stat-card">
            <span class="muted"><?= e($item['label']) ?></span>
            <strong><?= e((string) $item['value']) ?></strong>
        </article>
    <?php endforeach; ?>
</section>

<style>
.module-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 16px;
}

.module-btn {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px 20px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    text-decoration: none;
    color: #1e293b;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}

.module-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px -4px rgba(30, 58, 138, 0.12);
    border-color: #93c5fd;
    color: #1e3a8a;
}

.module-btn .icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.module-btn .icon.blue   { background: #eff6ff; }
.module-btn .icon.green  { background: #f0fdf4; }
.module-btn .icon.amber  { background: #fffbeb; }
.module-btn .icon.purple { background: #faf5ff; }
.module-btn .icon.rose   { background: #fff1f2; }
</style>

<section class="card">
    <h2>Modul Aktif</h2>
    <div class="module-grid">
        <?php if ($user['role'] === 'admin'): ?>
            <a href="<?= e(route_url('admin/teachers')) ?>" class="module-btn">
                <div class="icon blue">👨‍🏫</div><span>Kelola Guru</span>
            </a>
            <a href="<?= e(route_url('admin/subjects')) ?>" class="module-btn">
                <div class="icon purple">📚</div><span>Mata Pelajaran</span>
            </a>
            <a href="<?= e(route_url('admin/classrooms')) ?>" class="module-btn">
                <div class="icon green">🏫</div><span>Kelas & Siswa</span>
            </a>
            <a href="<?= e(route_url('admin/schedules')) ?>" class="module-btn">
                <div class="icon amber">📅</div><span>Jadwal</span>
            </a>
            <a href="<?= e(route_url('admin/academic-years')) ?>" class="module-btn">
                <div class="icon rose">📆</div><span>Tahun Ajaran</span>
            </a>
        <?php elseif ($user['role'] === 'teacher'): ?>
            <a href="<?= e(route_url('teacher/materials')) ?>" class="module-btn">
                <div class="icon blue">📖</div><span>Input Materi</span>
            </a>
            <a href="<?= e(route_url('teacher/assignments')) ?>" class="module-btn">
                <div class="icon amber">📝</div><span>Buat Tugas</span>
            </a>
            <a href="<?= e(route_url('teacher/attendance')) ?>" class="module-btn">
                <div class="icon green">✅</div><span>Absensi Siswa</span>
            </a>
            <a href="<?= e(route_url('teacher/grades')) ?>" class="module-btn">
                <div class="icon purple">📊</div><span>Nilai Harian & Rapor</span>
            </a>
        <?php elseif ($user['role'] === 'student'): ?>
            <a href="<?= e(route_url('student/schedule')) ?>" class="module-btn">
                <div class="icon amber">📅</div><span>Jadwal Pelajaran</span>
            </a>
            <a href="<?= e(route_url('student/classroom')) ?>" class="module-btn">
                <div class="icon blue">📚</div><span>Materi & Tugas</span>
            </a>
            <a href="<?= e(route_url('student/attendance')) ?>" class="module-btn">
                <div class="icon green">✅</div><span>Lihat Kehadiran</span>
            </a>
            <a href="<?= e(route_url('student/report')) ?>" class="module-btn">
                <div class="icon rose">📊</div><span>Lihat Rapor</span>
            </a>
        <?php elseif ($user['role'] === 'parent'): ?>
            <a href="<?= e(route_url('parent/report')) ?>" class="module-btn">
                <div class="icon purple">📊</div><span>Rapor Anak</span>
            </a>
            <a href="<?= e(route_url('parent/attendance')) ?>" class="module-btn">
                <div class="icon green">✅</div><span>Kehadiran Anak</span>
            </a>
            <a href="<?= e(route_url('parent/assignments')) ?>" class="module-btn">
                <div class="icon amber">📝</div><span>Tugas Anak</span>
            </a>
        <?php endif; ?>
    </div>
</section>

<?php if ($user['role'] === 'student'): ?>
<section class="card" style="margin-top: 24px;">
    <?php
    $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $todayName = $days[date('w')];
    ?>
    <h2 style="display:flex; align-items:center; gap:8px;">📅 Jadwal Pelajaran Hari Ini (<?= $todayName ?>)</h2>
    
    <?php if (empty($todaySchedule)): ?>
        <div style="text-align: center; padding: 32px; background: #f8fafc; border-radius: 8px; border: 1px dashed #cbd5e1; color: #64748b;">
            Tidak ada jadwal pelajaran untuk hari ini. Waktunya istirahat atau belajar mandiri!
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
            <?php foreach ($todaySchedule as $s): ?>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-left: 4px solid #3b82f6; border-radius: 8px; padding: 16px; transition: transform 0.2s, box-shadow 0.2s;">
                    <div style="font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <?= e($s['start_time']) ?> — <?= e($s['end_time']) ?>
                    </div>
                    <div style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 6px; line-height: 1.3;">
                        <?= e($s['subject_name']) ?>
                    </div>
                    <div style="font-size: 13px; color: #475569; display: flex; align-items: center; gap: 6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <?= e($s['teacher_name']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php endif; ?>

