<?php
require_once __DIR__ . '/adminconfig.php';

class Auth
{
    public static function login(string $username, string $password): bool
    {
        global $mysqli;

        $stmt = $mysqli->prepare(
            'SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1'
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if (!$user || !password_verify($password, (string) $user['password'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = (string) $user['username'];
        $_SESSION['role'] = (string) $user['role'];

        return true;
    }

    public static function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'] ?? '/',
                $params['domain'] ?? '',
                (bool) ($params['secure'] ?? false),
                (bool) ($params['httponly'] ?? true)
            );
        }

        session_destroy();
    }
}

function requireAuth(string $requiredRole = 'admin'): void
{
    if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
        header('Location: ' . ADMIN_LOGIN_PAGE);
        exit;
    }

    if ($_SESSION['role'] !== $requiredRole) {
        http_response_code(403);
        exit('Access denied.');
    }
}
