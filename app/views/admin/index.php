<div class="grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 24px;">
    <!-- Students Stat -->
    <div class="card" style="margin-bottom: 0;">
        <div class="stat-card-title">
            Total Siswa Terdaftar
            <div style="background: var(--surface); padding: 6px; border-radius: var(--radius-sm); color: var(--primary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
        </div>
        <div style="display: flex; align-items: baseline; gap: 12px; margin-bottom: 16px;">
            <div class="stat-card-value"><?= number_format($stats['students']) ?></div>
        </div>
    </div>

    <!-- Staff Stat -->
    <div class="card" style="margin-bottom: 0;">
        <div class="stat-card-title">
            Guru Aktif
            <div style="background: var(--surface); padding: 6px; border-radius: var(--radius-sm); color: var(--secondary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
        </div>
        <div style="display: flex; align-items: baseline; gap: 12px; margin-bottom: 16px;">
            <div class="stat-card-value"><?= number_format($stats['teachers']) ?></div>
        </div>
    </div>

    <!-- Classes Stat -->
    <div class="card" style="margin-bottom: 0;">
        <div class="stat-card-title">
            Kelas Aktif
            <div style="background: var(--surface); padding: 6px; border-radius: var(--radius-sm); color: var(--on-surface-variant);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
            </div>
        </div>
        <div class="stat-card-value" style="margin-bottom: 16px;"><?= number_format($stats['classrooms']) ?></div>
    </div>
</div>

<div class="grid" style="grid-template-columns: 1.5fr 1fr;">
    <!-- Recent Activity -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <h2 style="margin: 0; font-size: 1.1rem;">Aktivitas Administratif Terbaru</h2>
            <a href="#" style="font-size: 0.85rem; font-weight: 600;">Lihat Semua</a>
        </div>
        <p style="color: var(--muted); font-size: 0.85rem; margin-bottom: 24px;">Pembaruan terbaru seputar data master dan penjadwalan.</p>

        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div style="display: flex; gap: 16px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #ccfbf1; color: #115e59; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                </div>
                <div>
                    <div style="font-size: 0.9rem; color: var(--on-surface);"><strong>Pendaftaran Siswa Baru:</strong> Siswa Dummy (ID: 2400192) telah ditambahkan ke sistem.</div>
                    <div style="font-size: 0.75rem; color: var(--muted); margin-top: 4px;">Jurusan: IPA • 10 mnt lalu</div>
                </div>
            </div>
            <div style="display: flex; gap: 16px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
                <div>
                    <div style="font-size: 0.9rem; color: var(--on-surface);"><strong>Modifikasi Jadwal:</strong> Perubahan ruangan untuk "Kalkulus Lanjut" (Kelas 3B). Pindah dari Aula A ke Lab 4.</div>
                    <div style="font-size: 0.75rem; color: var(--muted); margin-top: 4px;">Admin Sistem • 45 mnt lalu</div>
                </div>
            </div>
            <div style="display: flex; gap: 16px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #fee2e2; color: #991b1b; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
                <div>
                    <div style="font-size: 0.9rem; color: var(--on-surface);"><strong>Peringatan Sistem:</strong> 12 guru belum mengumpulkan nilai ujian tengah semester.</div>
                    <div style="font-size: 0.75rem; color: var(--muted); margin-top: 4px;">Cek Otomatis • 2 jam lalu</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Master Data -->
    <div class="card" style="display: flex; flex-direction: column;">
        <h2 style="margin: 0 0 4px 0; font-size: 1.1rem;">Data Master</h2>
        <p style="color: var(--muted); font-size: 0.85rem; margin-bottom: 24px;">Akses cepat ke daftar data utama.</p>
        
        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; flex-grow: 1;">
            <a href="<?= e(route_url('admin/classrooms')) ?>" style="background: var(--surface); border: 1px solid var(--outline); border-radius: var(--radius); padding: 20px 16px; text-align: center; color: var(--on-surface); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 0.95rem;">Siswa</strong>
                    <span style="font-size: 0.75rem; color: var(--muted);">Kelola Profil Siswa</span>
                </div>
            </a>
            
            <a href="<?= e(route_url('admin/teachers')) ?>" style="background: var(--surface); border: 1px solid var(--outline); border-radius: var(--radius); padding: 20px 16px; text-align: center; color: var(--on-surface); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: #ccfbf1; color: #115e59; display: flex; align-items: center; justify-content: center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 0.95rem;">Guru</strong>
                    <span style="font-size: 0.75rem; color: var(--muted);">Daftar Staf Guru</span>
                </div>
            </a>

            <a href="<?= e(route_url('admin/subjects')) ?>" style="background: var(--surface); border: 1px solid var(--outline); border-radius: var(--radius); padding: 20px 16px; text-align: center; color: var(--on-surface); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 0.95rem;">Mata Pelajaran</strong>
                    <span style="font-size: 0.75rem; color: var(--muted);">Data Kurikulum</span>
                </div>
            </a>

            <a href="<?= e(route_url('admin/classrooms')) ?>" style="background: var(--surface); border: 1px solid var(--outline); border-radius: var(--radius); padding: 20px 16px; text-align: center; color: var(--on-surface); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: #f1f5f9; color: #475569; display: flex; align-items: center; justify-content: center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 0.95rem;">Fasilitas & Kelas</strong>
                    <span style="font-size: 0.75rem; color: var(--muted);">Ruang Kelas</span>
                </div>
            </a>
        </div>

        <a href="<?= e(route_url('admin/academic-years')) ?>" style="display: block; width: 100%; text-align: center; padding: 12px; border: 1px solid var(--outline); border-radius: var(--radius); color: var(--primary); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; transition: background 0.2s;">
            Tahun Ajaran & Semester
        </a>
    </div>
</div>
