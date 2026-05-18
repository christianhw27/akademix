<?php

class AuthModel extends Model
{
    /**
     * Find user by username (for admin, teacher, parent).
     */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username AND is_active = 1 LIMIT 1');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if (!$user) {
            return null;
        }

        $user['profile_id'] = $this->resolveProfileId((int) $user['id'], $user['role']);

        return $user;
    }

    /**
     * Find user by email (for all roles, but mainly students).
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email AND is_active = 1 LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user) {
            return null;
        }

        $user['profile_id'] = $this->resolveProfileId((int) $user['id'], $user['role']);

        return $user;
    }

    /**
     * Unified login: try email first, then fallback to username.
     */
    public function findByCredential(string $credential): ?array
    {
        // If it looks like an email, search by email first
        if (str_contains($credential, '@')) {
            return $this->findByEmail($credential);
        }

        // Otherwise, try username
        $user = $this->findByUsername($credential);

        // Fallback: try email if username fails
        if (!$user) {
            $user = $this->findByEmail($credential);
        }

        return $user;
    }

    private function resolveProfileId(int $userId, string $role): ?int
    {
        $table = match ($role) {
            'teacher' => 'teachers',
            'student' => 'students',
            'parent'  => 'students', // parent logs in via child credentials, profile_id = student.id
            default => null,
        };

        if ($table === null) {
            return null;
        }

        $stmt = $this->db->prepare("SELECT id FROM {$table} WHERE user_id = :user_id LIMIT 1");
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchColumn() ?: null;
    }
}
