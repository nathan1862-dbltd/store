<?php
require_once __DIR__ . '/../adminconfig.php';
require_once __DIR__ . '/Session.php';

class Auth {

    public static function login($username, $password) {

        $pdo = getDB();

        $stmt = $pdo->prepare("

            SELECT id, username, password_hash, is_admin 

            FROM users 

            WHERE username = :username 

            LIMIT 1

        ");

        $stmt->execute(['username' => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {

            // Only allow admin login

            if ((int)$user['is_admin'] !== 1) {

                return false;

            }

            Session::start();

            Session::regenerate();

            $_SESSION['user_id']  = $user['id'];

            $_SESSION['username'] = $user['username'];

            $_SESSION['is_admin'] = $user['is_admin'];

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

    public static function isAdmin() {

        Session::start();

        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

    }

    public static function user() {

        Session::start();

        return $_SESSION ?? null;

    }

}