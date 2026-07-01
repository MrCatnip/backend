<?php

namespace App\Models;

use App\Database;

class User
{
    public function __construct(
        public string $username,
        public string $first_name,
        public string $last_name,
        public int $age,
    ) {
    }

    /**
     * Fetch users, optionally filtered by name and/or an age range.
     *
     * @return User[]
     */
    public static function select(?string $name = null, ?int $ageMin = null, ?int $ageMax = null): array
    {
        $sql = 'SELECT username, first_name, last_name, age FROM users';
        $conditions = [];
        $params = [];

        if ($name !== null && $name !== '') {
            // Column names are hardcoded; only the value is bound.
            $conditions[] = '(first_name LIKE ? OR last_name LIKE ?)';
            $params[] = "%{$name}%";
            $params[] = "%{$name}%";
        }

        if ($ageMin !== null) {
            $conditions[] = 'age >= ?';
            $params[] = $ageMin;
        }

        if ($ageMax !== null) {
            $conditions[] = 'age <= ?';
            $params[] = $ageMax;
        }

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY created_at DESC';

        // Values are passed to execute() separately from the SQL text,
        // so they can never be interpreted as SQL = no injection.
        $stmt = Database::connect()->prepare($sql);
        $stmt->execute($params);

        return array_map(fn (array $row) => self::fromRow($row), $stmt->fetchAll());
    }

    /**
     * Build a User object from a DB row.
     *
     * @param array<string, mixed> $row
     */
    private static function fromRow(array $row): self
    {
        return new self(
            $row['username'],
            $row['first_name'],
            $row['last_name'],
            (int) $row['age'],
        );
    }
}
