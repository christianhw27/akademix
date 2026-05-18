<section class="page-head">
    <div>
        <span class="eyebrow">Siswa</span>
        <h1>Portal Siswa</h1>
        <p class="muted">Akses materi lintas tahun, tugas, kehadiran, dan rapor.</p>
    </div>
</section>

<section class="grid cards-4">
    <article class="card stat-card"><span class="muted">Materi</span><strong><?= e((string) $stats['materials']) ?></strong></article>
    <article class="card stat-card"><span class="muted">Tugas</span><strong><?= e((string) $stats['assignments']) ?></strong></article>
    <article class="card stat-card"><span class="muted">Kehadiran</span><strong><?= e((string) $stats['attendance']) ?></strong></article>
    <article class="card stat-card"><span class="muted">Rapor</span><strong><?= e((string) $stats['report']) ?></strong></article>
</section>
