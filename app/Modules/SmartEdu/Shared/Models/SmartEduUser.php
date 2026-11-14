<?php

namespace App\Modules\SmartEdu\Shared\Models;

class SmartEduUser
{
    public static function defaultUsers(): array
    {
        return [
            ['id' => 'TU-001', 'nama' => 'Admin Sekolah', 'email' => 'admin@smartedu.id', 'password' => 'password', 'role' => 'Staff TU'],
            ['id' => 'GR-0012', 'nama' => 'Ibu Dewi', 'email' => 'dewi@smartedu.id', 'password' => 'password', 'role' => 'Guru'],
            ['id' => '20240001', 'nama' => 'Andi Prasetyo', 'email' => 'andi@smartedu.id', 'password' => 'password', 'role' => 'Siswa'],
            ['id' => 'OT-20240001', 'nama' => 'Bapak Prasetyo', 'email' => 'prasetyo@gmail.com', 'password' => 'password', 'role' => 'Orang Tua'],
        ];
    }

    public static function all(): array
    {
        $users = session('smartedu_pengguna');

        if (! is_array($users) || empty($users)) {
            return self::defaultUsers();
        }

        $existingEmails = collect($users)
            ->map(fn ($user) => strtolower((string) ($user['email'] ?? '')))
            ->filter()
            ->all();

        foreach (self::defaultUsers() as $user) {
            if (! in_array(strtolower($user['email']), $existingEmails, true)) {
                $users[] = $user;
                $existingEmails[] = strtolower($user['email']);
            }
        }

        return array_values($users);
    }

    public static function findByEmail(string $email): ?array
    {
        return collect(self::all())->first(
            fn ($user) => strtolower((string) ($user['email'] ?? '')) === strtolower(trim($email))
        );
    }

    public static function create(array $user): void
    {
        $users = self::all();
        $users[] = $user;
        session(['smartedu_pengguna' => $users]);
    }

    public static function update(string $id, array $attributes): bool
    {
        $users = self::all();

        foreach ($users as $index => $user) {
            if (($user['id'] ?? null) === $id) {
                $users[$index] = $attributes;
                session(['smartedu_pengguna' => $users]);

                return true;
            }
        }

        return false;
    }

    public static function delete(string $id): bool
    {
        $users = self::all();
        $remaining = array_values(array_filter(
            $users,
            fn ($user) => ($user['id'] ?? null) !== $id
        ));

        if (count($remaining) === count($users)) {
            return false;
        }

        session(['smartedu_pengguna' => $remaining]);

        return true;
    }
}
