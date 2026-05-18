<?php
// Find active academic year
$activeYear = null;
foreach ($academicYears as $y) {
    if ($y['is_active']) {
        $activeYear = $y;
        break;
    }
}

// Filter classrooms for active year
$activeClassrooms = [];
if ($activeYear) {
    foreach ($classrooms as $c) {
        if ($c['academic_year_id'] == $activeYear['id']) {
            $activeClassrooms[] = $c;
        }
    }
}

// Group by Grade Level (Angkatan)
$grouped = [];
foreach ($activeClassrooms as $c) {
    $grouped[$c['grade_level']][] = $c;
}
ksort($grouped);
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
    margin-bottom: 40px;
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

.aes-title .muted {
    font-size: 15px;
    color: #64748b;
}

.period-section {
    margin-bottom: 48px;
}

.period-title {
    font-size: 20px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.period-title::after {
    content: "";
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}

.folder-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}

.folder-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
    text-decoration: none;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    transition: all 0.2s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.folder-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -8px rgba(30, 58, 138, 0.15);
    border-color: #bfdbfe;
}

.folder-icon {
    width: 48px;
    height: 48px;
    background: #eff6ff;
    color: #2563eb;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.folder-info {
    flex: 1;
}

.folder-name {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.folder-meta {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}
</style>

<div class="aes-wrapper">
    <div class="aes-header">
        <div class="aes-title">
            <span class="eyebrow">Akademik</span>
            <h1>Jadwal Pelajaran</h1>
            <div class="muted">Pilih kelas untuk melihat dan mengelola jadwal mingguan secara terstruktur.</div>
        </div>
    </div>

    <?php if (empty($grouped)): ?>
        <div style="text-align: center; padding: 64px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1;">
            <p style="color: #64748b; font-size: 15px;">Belum ada kelas yang terdaftar pada tahun ajaran aktif.</p>
        </div>
    <?php else: ?>
        <?php foreach ($grouped as $grade => $classes): ?>
            <div class="period-section">
                <div class="period-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                    Kelas <?= e((string) $grade) ?>
                </div>
                
                <div class="folder-grid">
                    <?php foreach ($classes as $classroom): ?>
                        <a href="<?= e(route_url('admin/schedules&classroom=' . $classroom['id'])) ?>" class="folder-card">
                            <div class="folder-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            </div>
                            <div class="folder-info">
                                <div class="folder-name"><?= e($classroom['name']) ?></div>
                                <div class="folder-meta">Kelas <?= e((string) $classroom['grade_level']) ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
