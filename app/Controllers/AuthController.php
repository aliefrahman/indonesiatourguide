<?php
// File: app/Controllers/AuthController.php

namespace App\Controllers;

use App\Models\UserModel;
use App\Core\RateLimiter;
use App\Core\AuditLogger;
use App\Core\Csrf;

class AuthController {
    
    // Tampilkan form login
    public function showLogin() {
        if (isset($_SESSION['user_id'])) {
            redirect('/dashboard');
        }
        view('auth.login');
    }

    // Proses login pengguna
    public function login() {
        Csrf::validate();

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        RateLimiter::checkLoginAttempt($ip);

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            flash('error', 'Semua bidang wajib diisi.');
            redirect('/login');
        }

        $user = UserModel::findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID untuk mencegah serangan Session Fixation (Rule 4)
            session_regenerate_id(true);

            // Reset counter rate limit jika berhasil login
            RateLimiter::resetAttempts();

            // Setup session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            AuditLogger::logAction('login_success', "Pengguna {$user['email']} berhasil masuk.");

            // Redirect ke halaman sebelum login jika ada
            if (isset($_SESSION['redirect_after_login'])) {
                $url = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                redirect($url);
            }

            redirect('/dashboard');
        } else {
            // Catat kegagalan
            RateLimiter::recordFailedAttempt();
            AuditLogger::logAction('login_failed', "Gagal masuk menggunakan email: $email");
            
            flash('error', 'Email atau kata sandi salah.');
            redirect('/login');
        }
    }

    // Tampilkan form registrasi
    public function showRegister() {
        if (isset($_SESSION['user_id'])) {
            redirect('/dashboard');
        }
        view('auth.register');
    }

    // Proses registrasi traveler
    public function register() {
        Csrf::validate();

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $phone = $_POST['phone'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            flash('error', 'Nama, email, dan kata sandi wajib diisi.');
            redirect('/register');
        }

        // Cek email duplikat
        if (UserModel::findByEmail($email)) {
            flash('error', 'Email tersebut sudah terdaftar.');
            redirect('/register');
        }

        $userId = UserModel::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'traveler', // default registrasi publik adalah traveler
            'phone' => $phone,
            'avatar' => null
        ]);

        if ($userId) {
            AuditLogger::logAction('user_registered', "User baru terdaftar: $email");
            flash('success', 'Registrasi berhasil! Silakan masuk.');
            redirect('/login');
        } else {
            flash('error', 'Terjadi kesalahan saat pendaftaran.');
            redirect('/register');
        }
    }

    // Logout pengguna
    public function logout() {
        if (isset($_SESSION['user_email'])) {
            AuditLogger::logAction('logout_success', "Pengguna {$_SESSION['user_email']} keluar dari sistem.");
        }
        
        // Hapus session
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        
        redirect('/login');
    }

    // Tampilkan lupa kata sandi
    public function showForgot() {
        view('auth.forgot');
    }

    // Proses lupa kata sandi (Simulasi)
    public function forgot() {
        Csrf::validate();
        $email = $_POST['email'] ?? '';
        
        AuditLogger::logAction('forgot_password_request', "Permintaan reset kata sandi untuk: $email");
        
        flash('success', 'Tautan untuk mereset kata sandi telah dikirim ke email Anda (Simulasi).');
        redirect('/forgot');
    }

    // Reset kata sandi (Simulasi)
    public function showReset() {
        view('auth.reset');
    }

    public function reset() {
        Csrf::validate();
        flash('success', 'Kata sandi Anda berhasil diperbarui (Simulasi).');
        redirect('/login');
    }

    // Verifikasi (Simulasi)
    public function verify() {
        flash('success', 'Akun Anda berhasil diverifikasi (Simulasi).');
        redirect('/login');
    }
}
