<?php

require_once __DIR__ . '/../../core/Model.php';

/**
 * Student Model
 * Maps to the `students` table in Supabase.
 */
class StudentModel extends Model
{
    protected string $table   = 'students';
    protected string $primary = 'id';
    protected array  $fillable = [
        'name',
        'id_number',
        'email',
        'favorite_sport',
        'favorite_subject',
        'birth_date',
        'grade1',
        'grade2',
        'grade3',
        'average',
    ];

    /* ------------------------------------------------------------------ */
    /*  Custom queries                                                      */
    /* ------------------------------------------------------------------ */

    /** All students ordered by name, including calculated average. */
    public function allWithGrades(): array
    {
        return $this->rawQuery(
            "SELECT * FROM {$this->table} ORDER BY name ASC"
        );
    }

    /** Overall course average (average of all student averages). */
    public function courseAverage(): float
    {
        $rows = $this->rawQuery(
            "SELECT ROUND(AVG(average)::numeric, 2) AS course_avg FROM {$this->table}"
        );
        return isset($rows[0]['course_avg']) ? (float) $rows[0]['course_avg'] : 0.0;
    }

    /** Total number of enrolled students. */
    public function count(): int
    {
        $rows = $this->rawQuery("SELECT COUNT(*) AS total FROM {$this->table}");
        return (int) ($rows[0]['total'] ?? 0);
    }
}
