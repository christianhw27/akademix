<section class="page-head">
    <div>
        <span class="eyebrow">Siswa</span>
        <h1>Kelas</h1>
        <p class="muted">Pilih mata pelajaran untuk melihat materi dan tugas yang diberikan guru.</p>
    </div>
</section>

<style>
.gc-container {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
    min-height: 400px;
}

.gc-sidebar {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.gc-subject-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    background: var(--bg-card);
    cursor: pointer;
    text-decoration: none;
    color: var(--text-color);
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s;
}

.gc-subject-btn:hover {
    border-color: var(--primary);
    background: var(--surface-raised);
}

.gc-subject-btn.active {
    border-color: var(--primary);
    background: var(--surface-raised);
    color: var(--primary);
    box-shadow: 0 0 0 1px var(--primary);
}

.gc-subject-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: var(--surface-raised);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.gc-subject-btn.active .gc-subject-icon {
    background: var(--primary);
    color: var(--surface-container);
}

.gc-subject-meta {
    font-size: 12px;
    font-weight: 400;
    color: var(--text-muted);
    margin-top: 2px;
}

.gc-content {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 28px;
    min-height: 400px;
}

.gc-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 350px;
    color: var(--text-muted);
    font-size: 15px;
    text-align: center;
    gap: 12px;
}

.gc-tabs {
    display: flex;
    gap: 4px;
    margin-bottom: 24px;
    border-bottom: 2px solid var(--border-color);
}

.gc-tab {
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-muted);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: all 0.2s;
    background: none;
    border-top: none;
    border-left: none;
    border-right: none;
}

.gc-tab:hover { color: var(--text-color); }
.gc-tab.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
}

.gc-item {
    padding: 16px;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    margin-bottom: 12px;
    transition: all 0.2s;
}

.gc-item:hover {
    border-color: var(--primary);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.gc-item-header {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 8px;
}

.gc-item-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 16px;
}

.gc-item-icon.material { background: var(--surface-raised); color: var(--primary); }
.gc-item-icon.assignment { background: var(--surface-raised); color: var(--accent); }

.gc-item-title {
    font-weight: 700;
    font-size: 15px;
    color: var(--text-color);
    line-height: 1.4;
}

.gc-item-meta {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}

.gc-item-body {
    font-size: 13px;
    color: var(--text-muted);
    line-height: 1.6;
    padding-left: 48px;
}

.gc-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}
.gc-badge.material { background: var(--surface-raised); color: var(--primary); }
.gc-badge.assignment { background: var(--surface-raised); color: var(--accent); }
.gc-badge.submitted { background: var(--success); color: #ffffff; }
.gc-badge.pending { background: var(--warning); color: #ffffff; }

@media (max-width: 768px) {
    .gc-container {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="gc-container">
    <!-- Subject List Sidebar -->
    <div class="gc-sidebar">
        <?php if (empty($subjects)): ?>
            <div style="padding: 24px; text-align: center; color: var(--text-muted); font-size: 13px;">
                Belum ada mata pelajaran.
            </div>
        <?php else: ?>
            <?php foreach ($subjects as $name => $data): ?>
                <a href="<?= e(route_url('student/classroom&subject=' . urlencode($name))) ?>" 
                   class="gc-subject-btn <?= $selectedSubject === $name ? 'active' : '' ?>">
                    <div class="gc-subject-icon">📚</div>
                    <div>
                        <?= e($name) ?>
                        <div class="gc-subject-meta">
                            <?= count($data['materials']) ?> materi · <?= count($data['assignments']) ?> tugas
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Content Area -->
    <div class="gc-content">
        <?php if ($selectedSubject === null || !isset($subjects[$selectedSubject])): ?>
            <div class="gc-empty">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--border-color)" stroke-width="1.5">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <div style="font-weight: 600; color: var(--text-muted);">Pilih Mata Pelajaran</div>
                <div>Klik salah satu mata pelajaran di samping untuk melihat materi dan tugas.</div>
            </div>
        <?php else: ?>
            <?php $subjectData = $subjects[$selectedSubject]; ?>
            <h2 style="margin: 0 0 4px 0; font-size: 1.3rem; color: var(--text-color);"><?= e($selectedSubject) ?></h2>
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 20px;">
                <?= count($subjectData['materials']) ?> materi · <?= count($subjectData['assignments']) ?> tugas
            </p>

            <div class="gc-tabs">
                <button class="gc-tab active" onclick="showTab('all', this)">Semua</button>
                <button class="gc-tab" onclick="showTab('materials', this)">Materi</button>
                <button class="gc-tab" onclick="showTab('assignments', this)">Tugas</button>
            </div>

            <div id="tab-content">
                <?php
                // Merge all items into one timeline sorted by date
                $allItems = [];
                foreach ($subjectData['materials'] as $m) {
                    $allItems[] = ['type' => 'material', 'date' => $m['created_at'], 'data' => $m];
                }
                foreach ($subjectData['assignments'] as $a) {
                    $allItems[] = ['type' => 'assignment', 'date' => $a['due_date'] ?? $a['created_at'] ?? '', 'data' => $a];
                }
                usort($allItems, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
                ?>

                <?php if (empty($allItems)): ?>
                    <div style="padding: 40px; text-align: center; color: var(--text-muted);">Belum ada konten untuk mata pelajaran ini.</div>
                <?php else: ?>
                    <?php foreach ($allItems as $item): ?>
                        <?php if ($item['type'] === 'material'): ?>
                            <?php $m = $item['data']; ?>
                            <div class="gc-item" data-type="materials">
                                <div class="gc-item-header">
                                    <div class="gc-item-icon material">📖</div>
                                    <div style="flex:1;">
                                        <div class="gc-item-title"><?= e($m['title']) ?></div>
                                        <div class="gc-item-meta">
                                            <span class="gc-badge material">Materi</span>
                                            &nbsp;·&nbsp; <?= e($m['teacher_name']) ?>
                                            &nbsp;·&nbsp; <?= e(format_date($m['created_at'])) ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($m['content'])): ?>
                                    <div class="gc-item-body">
                                        <?= nl2br(e($m['content'])) ?>
                                        <?= render_file_preview($m['attachment'], 'Lihat Lampiran Materi') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <?php $a = $item['data']; ?>
                            <div class="gc-item" data-type="assignments">
                                <div class="gc-item-header">
                                    <div class="gc-item-icon assignment">📝</div>
                                    <div style="flex:1;">
                                        <div class="gc-item-title"><?= e($a['title']) ?></div>
                                        <div class="gc-item-meta">
                                            <span class="gc-badge assignment">Tugas</span>
                                            <?php if (!empty($a['due_date'])): ?>
                                                &nbsp;·&nbsp; Tenggat: <?= e(format_date($a['due_date'])) ?>
                                            <?php endif; ?>
                                            <?php if (!empty($a['submission_status'])): ?>
                                                &nbsp;·&nbsp; <span class="gc-badge submitted">Dikumpulkan</span>
                                            <?php else: ?>
                                                &nbsp;·&nbsp; <span class="gc-badge pending">Belum Dikumpulkan</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($a['description'])): ?>
                                    <div class="gc-item-body">
                                        <div style="margin-bottom:16px;">
                                            <?= nl2br(e($a['description'])) ?>
                                        </div>
                                        <?= render_file_preview($a['attachment'], 'Lihat Lampiran Soal/Materi') ?>
                                        
                                        <div style="background:var(--bg-body); padding:16px; border-radius:8px; border:1px solid var(--border-color); margin-top:16px;">
                                            <h4 style="margin:0 0 12px 0; font-size:13px; color:var(--text-muted);">Form Pengumpulan Tugas</h4>
                                            <?php $status = $a['submission_status'] ?: 'belum'; ?>
                                            <form method="post" action="<?= e(route_url('student/assignments/submit')) ?>" class="inline-form" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:8px;">
                                                <input type="hidden" name="assignment_id" value="<?= e((string) $a['id']) ?>">
                                                <textarea name="content" rows="2" placeholder="Tuliskan jawaban / catatan pengumpulan" required style="width:100%; border:1px solid var(--border-color); border-radius:6px; padding:8px; font-family:inherit; font-size:13px; background:var(--bg-card); color:var(--text-color);"><?= e($a['submission_content']) ?></textarea>
                                                
                                                <?= render_file_preview($a['submission_attachment'], 'File Tersimpan Saat Ini') ?>
                                                <div style="font-size:11px; color:var(--text-muted); margin-top:4px; margin-bottom: 8px;">(Pilih file baru untuk mengganti file lama)</div>
                                                
                                                <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" style="font-size:12px; padding:4px; color:var(--text-color);">
                                                <button type="submit" class="btn small" style="width:max-content;"><?= $status === 'submitted' ? 'Perbarui Jawaban' : 'Kumpulkan Tugas' ?></button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function showTab(type, btn) {
    document.querySelectorAll('.gc-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('.gc-item').forEach(item => {
        if (type === 'all') {
            item.style.display = '';
        } else {
            item.style.display = item.getAttribute('data-type') === type ? '' : 'none';
        }
    });
}
</script>
