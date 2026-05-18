<?php
$pageTitle = isset($title) ? $title . ' — ' . app_config('app.name') : app_config('app.name');
$user = Auth::user();
$success = flash('success');
$error = flash('error');
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AKADEMIX — Sistem Informasi Akademik Sekolah">
    <title><?= e($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?= e(asset_url('css/app.css')) ?>">
</head>
<body>
<?php if ($user): ?>
<div class="app-wrapper">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <?php
            $homeRoute = 'dashboard';
            if ($user['role'] === 'teacher') $homeRoute = 'teacher';
            if ($user['role'] === 'admin') $homeRoute = 'admin';
            ?>
            <a href="<?= e(route_url($homeRoute)) ?>" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:12px;">
                <div style="background: #ffffff; border-radius: 8px; padding: 6px; display: inline-flex;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                </div>
                <div>
                    <div class="sidebar-brand"><span>AKADEMIX</span></div>
                    <div class="sidebar-subtitle">Portal Institusi</div>
                </div>
            </a>
        </div>

        <nav class="sidebar-nav">
            <?php if ($user['role'] === 'admin'): ?>
                <a class="<?= is_active_route('admin') && current_route() == 'admin' ? 'active' : '' ?>" href="<?= e(route_url('admin')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>DASHBOARD</span>
                </a>
                <a class="<?= is_active_route('admin/classrooms') || is_active_route('admin/students') ? 'active' : '' ?>" href="<?= e(route_url('admin/classrooms')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>SISWA</span>
                </a>
                <a class="<?= is_active_route('admin/teachers') ? 'active' : '' ?>" href="<?= e(route_url('admin/teachers')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span>GURU & STAF</span>
                </a>
                <a class="<?= is_active_route('admin/academic-years') || is_active_route('admin/subjects') ? 'active' : '' ?>" href="<?= e(route_url('admin/academic-years')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    <span>DATA AKADEMIK</span>
                </a>
                <a class="<?= is_active_route('admin/schedules') ? 'active' : '' ?>" href="<?= e(route_url('admin/schedules')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span>JADWAL</span>
                </a>
            <?php elseif ($user['role'] === 'parent'): ?>
                <a class="<?= is_active_route('parent') && current_route() == 'parent' ? 'active' : '' ?>" href="<?= e(route_url('parent')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>DASHBOARD ANAK</span>
                </a>
                <a class="<?= is_active_route('parent/report') ? 'active' : '' ?>" href="<?= e(route_url('parent/report')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    <span>RAPOR NILAI</span>
                </a>
                <a class="<?= is_active_route('parent/attendance') ? 'active' : '' ?>" href="<?= e(route_url('parent/attendance')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span>KEHADIRAN</span>
                </a>
                <a class="<?= is_active_route('parent/assignments') ? 'active' : '' ?>" href="<?= e(route_url('parent/assignments')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>TUGAS ANAK</span>
                </a>
            <?php elseif ($user['role'] === 'student'): ?>
                <a class="<?= current_route() === 'dashboard' ? 'active' : '' ?>" href="<?= e(route_url('dashboard')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>RINGKASAN</span>
                </a>
                <!-- Portal Siswa removed per request -->
                <a class="<?= is_active_route('student/schedule') ? 'active' : '' ?>" href="<?= e(route_url('student/schedule')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span>JADWAL</span>
                </a>
                <a class="<?= is_active_route('student/classroom') || is_active_route('student/materials') || is_active_route('student/assignments') ? 'active' : '' ?>" href="<?= e(route_url('student/classroom')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    <span>KELAS</span>
                </a>
                <a class="<?= is_active_route('student/attendance') ? 'active' : '' ?>" href="<?= e(route_url('student/attendance')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>KEHADIRAN</span>
                </a>
                <a class="<?= is_active_route('student/report') ? 'active' : '' ?>" href="<?= e(route_url('student/report')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    <span>RAPOR</span>
                </a>
            <?php else: ?>
                <!-- Teacher Nav -->
                <a class="<?= current_route() === 'teacher' ? 'active' : '' ?>" href="<?= e(route_url('teacher')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span>BERANDA</span>
                </a>
                <a class="<?= is_active_route('teacher/materials') ? 'active' : '' ?>" href="<?= e(route_url('teacher/materials')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    <span>MATERI</span>
                </a>
                <a class="<?= is_active_route('teacher/assignments') ? 'active' : '' ?>" href="<?= e(route_url('teacher/assignments')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    <span>TUGAS</span>
                </a>
                <a class="<?= is_active_route('teacher/attendance') ? 'active' : '' ?>" href="<?= e(route_url('teacher/attendance')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>ABSENSI</span>
                </a>
                <a class="<?= is_active_route('teacher/grades') ? 'active' : '' ?>" href="<?= e(route_url('teacher/grades')) ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    <span>NILAI</span>
                </a>
            <?php endif; ?>
        </nav>
        
        <div class="sidebar-footer">
            <a href="<?= e(route_url('logout')) ?>" style="color: var(--sidebar-text); text-decoration: none; display: flex; align-items: center; gap: 12px; font-size: 0.85rem; padding: 8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <header class="topbar">
            <!-- Left side empty to let right side float right, unless we add breadcrumbs later -->
            <div style="flex-grow: 1; display: flex; align-items: center; gap: 12px;">
                <strong style="font-size: 1.1rem;"><?= e(isset($title) ? $title : 'Ringkasan') ?></strong>
            </div>

            <div class="topbar-actions">
                <details style="position:relative;">
                    <summary style="list-style:none; cursor:pointer; display:flex; align-items:center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--muted);"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        <span style="position:absolute; top:-2px; right:-2px; background:#ef4444; width:8px; height:8px; border-radius:50%;"></span>
                    </summary>
                    <div style="position:absolute; right:0; top:calc(100% + 12px); width:300px; background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:16px; box-shadow:0 10px 25px rgba(0,0,0,0.1); z-index:100;">
                        <h3 style="margin:0 0 12px 0; font-size:14px; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">Notifikasi</h3>
                        <div style="font-size:13px; color:#475569; padding:8px 0;">Belum ada notifikasi baru.</div>
                    </div>
                </details>
                <div class="user-chip">
                    <div class="user-chip-info" style="text-align: right;">
                        <strong><?= e($user['full_name']) ?></strong>
                        <span><?= e(role_label($user['role'])) ?></span>
                    </div>
                    <div class="avatar-circle"><?= e(strtoupper(mb_substr($user['full_name'], 0, 1))) ?></div>
                </div>
            </div>
        </header>

        <main class="container">
            <?php if ($success): ?>
                <div class="alert success"><?= e($success) ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert danger"><?= e($error) ?></div>
            <?php endif; ?>
<?php else: ?>
<!-- For login page -->
<div class="hero-wrapper">
<?php endif; ?>
