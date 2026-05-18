<section class="page-head">
    <div>
        <span class="eyebrow">Orang Tua</span>
        <h1>Portal Orang Tua</h1>
        <p class="muted">Pantau rapor, kehadiran, dan tugas anak dari satu tempat.</p>
    </div>
</section>

<section class="grid cards-3">
    <article class="card stat-card"><span class="muted">Rapor</span><strong><?= e((string) $stats['reports']) ?></strong></article>
    <article class="card stat-card"><span class="muted">Kehadiran</span><strong><?= e((string) $stats['attendance']) ?></strong></article>
    <article class="card stat-card"><span class="muted">Tugas</span><strong><?= e((string) $stats['assignments']) ?></strong></article>
</section>
