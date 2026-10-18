<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherStudentSeeder extends Seeder
{
    public const DEFAULT_PASSWORD = 'Password123';

    /**
     * Seed 2 teacher accounts and 10 student accounts idempotently.
     */
    public function run(): void
    {
        $hashedPassword = Hash::make(self::DEFAULT_PASSWORD);

        $teachers = [
            ['name' => 'Nalar Teacher 01', 'email' => 'teacher01@nalar.com'],
            ['name' => 'Nalar Teacher 02', 'email' => 'teacher02@nalar.com'],
        ];

        foreach ($teachers as $teacher) {
            $this->upsertUser($teacher['name'], $teacher['email'], UserRole::Teacher, $hashedPassword);
        }

        for ($i = 1; $i <= 10; $i++) {
            $number = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            $this->upsertUser(
                "Nalar Student {$number}",
                "student{$number}@nalar.com",
                UserRole::Student,
                $hashedPassword
            );
        }
    }

    private function upsertUser(string $name, string $email, UserRole $role, string $hashedPassword): void
    {
        $user = User::firstOrNew(['email' => $email]);
        $user->name = $name;
        $user->password = $hashedPassword;
        $user->email_verified_at ??= now();
        $user->assignRole($role);
        $user->save();
    }
}
