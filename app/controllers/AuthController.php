<?php

class AuthController extends Controller
{
    public function login(): void
    {
        if (Auth::check()) {
            $this->redirect('dashboard');
        }

        $this->view('auth/login', [
            'title' => 'Login',
        ]);
    }

    public function authenticate(): void
    {
        $credential = trim((string) $this->request('credential'));
        $password = (string) $this->request('password');
        $isParent = (bool) $this->request('is_parent');
        remember_old_input($_POST);

        if ($credential === '' || $password === '') {
            flash('error', 'Semua kolom harus diisi.');
            $this->redirect('login');
        }

        $model = $this->model('AuthModel');

        if ($isParent) {
            // Parent Login: $credential = username anak, $password = email anak
            $user = $model->findByCredential($credential);
            if (!$user || $user['role'] !== 'student' || strtolower($user['email']) !== strtolower($password)) {
                flash('error', 'Username atau Email anak tidak valid.');
                $this->redirect('login');
            }
            if (!$user['is_active']) {
                flash('error', 'Akun anak tidak aktif. Hubungi admin.');
                $this->redirect('login');
            }

            // Impersonate as Parent
            $parentUser = $user;
            $parentUser['role'] = 'parent';
            $parentUser['full_name'] = 'Orang Tua dari ' . $user['full_name'];

            clear_old_input();
            Auth::attempt($parentUser);
            flash('success', 'Login berhasil. Selamat datang di Portal Orang Tua!');
            $this->redirect('dashboard');
            return;
        }

        // Normal Login
        $user = $model->findByCredential($credential);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            flash('error', 'Email/Username atau password tidak valid.');
            $this->redirect('login');
        }

        if (!$user['is_active']) {
            flash('error', 'Akun Anda tidak aktif. Hubungi admin.');
            $this->redirect('login');
        }

        clear_old_input();
        Auth::attempt($user);
        flash('success', 'Login berhasil. Selamat datang, ' . $user['full_name'] . '!');
        $this->redirect('dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        flash('success', 'Anda telah logout.');
        $this->redirect('login');
    }
}
