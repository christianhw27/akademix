<section class="hero-wrapper" style="width: 100%; display: flex; align-items: center; justify-content: center; background: var(--surface); position: relative; overflow: hidden; padding: 24px;">
    
    <!-- Decor element -->
    <div style="position: absolute; top: -100px; left: -100px; width: 400px; height: 400px; background: var(--primary); filter: blur(150px); opacity: 0.05; border-radius: 50%; pointer-events: none;"></div>
    <div style="position: absolute; bottom: -100px; right: -100px; width: 300px; height: 300px; background: var(--secondary); filter: blur(150px); opacity: 0.05; border-radius: 50%; pointer-events: none;"></div>

    <div style="display: flex; gap: 64px; align-items: center; max-width: 1100px; width: 100%; z-index: 1;">
        
        <div style="flex: 1.2; display: none; @media (min-width: 900px) { display: block; }">
            <span class="eyebrow" style="padding: 6px 12px; background: rgba(30,58,138,0.05); border-radius: 999px; border: 1px solid rgba(30,58,138,0.1); letter-spacing: 0.1em; color: var(--primary); display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                Sistem Informasi Akademik
            </span>
            <h1 style="font-size: 3.5rem; font-weight: 700; letter-spacing: -0.02em; margin-bottom: 24px; line-height: 1.1; color: var(--on-surface);">Transformasi Digital<br><span style="color: var(--primary);">Sekolah Modern</span></h1>
            <p class="lead" style="font-size: 1.15rem; margin-bottom: 40px; color: var(--muted); max-width: 90%;">Satu portal terpadu untuk Admin, Guru, Siswa, dan Orang Tua. Akses data nilai, jadwal, dan administrasi akademik dalam satu platform yang elegan.</p>

            <div class="card" style="background: var(--surface-container); border: 1px solid var(--outline); padding: 20px 24px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(30,58,138,0.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 0.95rem; color: var(--on-surface);">Panduan Akses Demo</h3>
                        <p class="muted" style="margin: 0; font-size: 0.8rem;">Password universal: <code>password</code></p>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div style="padding: 12px; background: var(--surface-raised); border-radius: 8px; border: 1px solid var(--outline);">
                        <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); margin-bottom: 4px;">👑 Administrator</div>
                        <code style="background: transparent; padding: 0; font-size: 0.9rem; color: var(--on-surface);">admin</code>
                    </div>
                    <div style="padding: 12px; background: var(--surface-raised); border-radius: 8px; border: 1px solid var(--outline);">
                        <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); margin-bottom: 4px;">👨‍🏫 Guru</div>
                        <code style="background: transparent; padding: 0; font-size: 0.9rem; color: var(--on-surface);">guru.budi</code>
                    </div>
                    <div style="padding: 12px; background: var(--surface-raised); border-radius: 8px; border: 1px solid var(--outline);">
                        <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); margin-bottom: 4px;">🎓 Siswa</div>
                        <code style="background: transparent; padding: 0; font-size: 0.9rem; color: var(--on-surface);">siswa.1</code>
                    </div>
                </div>
            </div>
        </div>

        <div style="flex: 1; display: flex; justify-content: center;">
            <div class="hero-card">
                <div style="text-align: center; margin-bottom: 32px;">
                    <div style="width: 56px; height: 56px; background: var(--primary); border-radius: 16px; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px rgba(30,58,138,0.2);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                    </div>
                    <h2 style="font-size: 1.6rem; font-weight: 700; margin-bottom: 6px; color: var(--on-surface);">Autentikasi</h2>
                    <p class="muted" style="font-size: 0.9rem;">Masukkan kredensial untuk melanjutkan</p>
                </div>
                
                <form method="post" action="<?= e(route_url('login')) ?>" style="display: grid; gap: 20px;">

                    <label style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;" id="label-credential">
                        Username atau Email
                        <div style="position: relative; margin-top: 8px;">
                            <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted);" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <input type="text" id="input-credential" name="credential" value="<?= e(old('credential')) ?>" placeholder="nama@email.com" required autofocus style="width: 100%; padding-left: 42px;">
                        </div>
                    </label>
                    <label style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;" id="label-password">
                        Password
                        <div style="position: relative; margin-top: 8px;">
                            <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted);" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <input type="password" id="input-password" name="password" placeholder="••••••••" required style="width: 100%; padding-left: 42px;">
                        </div>
                    </label>
                    
                    <button type="submit" class="btn primary" style="width: 100%; justify-content: center; padding: 14px; font-size: 1rem; margin-top: 8px; border-radius: var(--radius); box-shadow: 0 4px 12px rgba(30,58,138,0.2);">Masuk ke Sistem</button>
                </form>
            </div>
        </div>
    </div>
</section>
