<?php

require_once __DIR__ . '/Database.php';

/**
 * Model - Base ORM class
 * Provides basic CRUD operations using PDO.
 * Subclasses define $table and $fillable.
 */
abstract class Model
{
    protected PDO    $db;
    protected string $table    = '';
    protected string $primary  = 'id';
    protected array  $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /* ------------------------------------------------------------------ */
    /*  Core query helpers                                                  */
    /* ------------------------------------------------------------------ */

    /** Return every row in the table. */
    public function all(string $orderBy = '', string $direction = 'ASC'): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy} {$direction}";
        }
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /** Find a single row by primary key. Returns array|null. */
    public function find(int|string $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE {$this->primary} = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Insert a row.
     * Only columns listed in $fillable are accepted.
     * Returns the new primary-key value.
     */
    public function create(array $data): int|string
    {
        $data    = $this->filterFillable($data);
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn($k) => ":{$k}", array_keys($data)));

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders}) RETURNING {$this->primary}"
        );
        $stmt->execute($this->prefixKeys($data));

        $row = $stmt->fetch();
        return $row[$this->primary];
    }

    /**
     * Update a row by primary key.
     * Only fillable columns are updated.
     * Returns number of affected rows.
     */
    public function update(int|string $id, array $data): int
    {
        $data = $this->filterFillable($data);
        $set  = implode(', ', array_map(fn($k) => "{$k} = :{$k}", array_keys($data)));

        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET {$set} WHERE {$this->primary} = :__id"
        );

        $params          = $this->prefixKeys($data);
        $params[':__id'] = $id;

        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /** Delete a row by primary key. Returns number of affected rows. */
    public function delete(int|string $id): int
    {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} WHERE {$this->primary} = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    /** Execute a raw SELECT query with optional bound parameters. */
    public function rawQuery(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Execute a raw INSERT / UPDATE / DELETE and return affected rows. */
    public function rawExecute(string $sql, array $params = []): int
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /* ------------------------------------------------------------------ */
    /*  Internal helpers                                                    */
    /* ------------------------------------------------------------------ */

    private function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data; // No whitelist defined — allow all
        }
        return array_intersect_key($data, array_flip($this->fillable));
    }

    private function prefixKeys(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[":{$key}"] = $value;
        }
        return $result;
    }
}
