<?php

namespace App\Models;

use App\Database;

class User
{
    /**
     * The fields that make up a user, with their constraints.
     * Single source of truth for both input collection and validation.
     *
     * - type:   'text' (max = character length) or 'int' (max = maximum value)
     * - max:    upper bound, interpreted per type
     * - unique: whether the value must not already exist in the table
     *
     * @var array<string, array{label: string, type: string, max: int, unique?: bool}>
     */
    private const FIELDS = [
        'username'   => ['label' => 'Username',   'type' => 'text', 'max' => 20,  'unique' => true],
        'first_name' => ['label' => 'First name', 'type' => 'text', 'max' => 255],
        'last_name'  => ['label' => 'Last name',  'type' => 'text', 'max' => 255],
        'age'        => ['label' => 'Age',        'type' => 'int',  'max' => 150],
    ];

    public function __construct(
        public string $username,
        public string $first_name,
        public string $last_name,
        public int $age,
    ) {
    }

    /**
     * The names of the fields a user is built from.
     *
     * @return list<string>
     */
    public static function fieldKeys(): array
    {
        return array_keys(self::FIELDS);
    }

    /**
     * Validate raw (already-trimmed) user input against the domain rules.
     *
     * @param array<string, string> $input
     * @return array<string, string>  field => error message
     */
    public static function validate(array $input): array
    {
        $errors = [];

        foreach (self::FIELDS as $field => $meta) {
            $value = $input[$field];

            if ($value === '') {
                $errors[$field] = "{$meta['label']} is required.";
                continue;
            }

            // Type-specific rule (length for text, numeric + ceiling for int).
            $error = match ($meta['type']) {
                'int'  => self::validateInt($value, $meta),
                'text' => self::validateText($value, $meta),
            };

            // Uniqueness only matters once the value itself is well-formed.
            if ($error === null && ($meta['unique'] ?? false) && self::exists($field, $value)) {
                $error = 'That ' . lcfirst($meta['label']) . ' is already taken.';
            }

            if ($error !== null) {
                $errors[$field] = $error;
            }
        }

        return $errors;
    }

    /**
     * @param array{label: string, max: int} $meta
     */
    private static function validateText(string $value, array $meta): ?string
    {
        return mb_strlen($value) > $meta['max']
            ? "{$meta['label']} must be at most {$meta['max']} characters."
            : null;
    }

    /**
     * @param array{label: string, max: int} $meta
     */
    private static function validateInt(string $value, array $meta): ?string
    {
        if (!ctype_digit($value)) {
            return "{$meta['label']} must be a whole number.";
        }

        return (int) $value > $meta['max']
            ? "{$meta['label']} must be at most {$meta['max']}."
            : null;
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
     * Insert a new user. Values are bound, so this is injection-safe.
     */
    public static function create(string $username, string $firstName, string $lastName, int $age): void
    {
        $stmt = Database::connect()->prepare(
            'INSERT INTO users (username, first_name, last_name, age) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$username, $firstName, $lastName, $age]);
    }

    /**
     * Whether a row already exists with the given value in the given column.
     *
     * The column name is whitelisted against self::FIELDS (never user input),
     * so interpolating it into the SQL is safe; the value is still bound.
     */
    private static function exists(string $column, string $value): bool
    {
        if (!isset(self::FIELDS[$column])) {
            throw new \InvalidArgumentException("Unknown field: $column");
        }

        $stmt = Database::connect()->prepare("SELECT 1 FROM users WHERE {$column} = ?");
        $stmt->execute([$value]);

        return $stmt->fetchColumn() !== false;
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
