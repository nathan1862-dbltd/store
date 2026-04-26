<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/Session.php';

class Auth {

    public static function login($username, $password) {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT id, username, password, role 
            FROM users 
            WHERE username = :username 
            LIMIT 1
        ");
        $stmt->execute(['username' => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            Session::start();
            Session::regenerate();

            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            return true;
        }

        return false;
    }

    public static function logout() {
        Session::start();
        session_unset();
        session_destroy();
    }

    public static function check() {
        Session::start();
        return isset($_SESSION['user_id']);
    }

    public static function user() {
        Session::start();
        return $_SESSION ?? null;
    }
}
