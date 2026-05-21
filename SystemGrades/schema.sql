-- ============================================================
-- School Grades System – Supabase SQL Schema
-- Run this in your Supabase project's SQL Editor
-- ============================================================

-- Drop if re-running during development
DROP TABLE IF EXISTS students;

-- Main students table
CREATE TABLE students (
    id                BIGSERIAL     PRIMARY KEY,
    name              VARCHAR(150)  NOT NULL,
    id_number         VARCHAR(20)   NOT NULL UNIQUE,
    email             VARCHAR(200)  NOT NULL UNIQUE,
    favorite_sport    VARCHAR(50)   NOT NULL,
    favorite_subject  VARCHAR(100)  NOT NULL,
    birth_date        DATE          NOT NULL,
    grade1            NUMERIC(5,2)  NOT NULL CHECK (grade1 >= 0 AND grade1 <= 10),
    grade2            NUMERIC(5,2)  NOT NULL CHECK (grade2 >= 0 AND grade2 <= 10),
    grade3            NUMERIC(5,2)  NOT NULL CHECK (grade3 >= 0 AND grade3 <= 10),
    average           NUMERIC(5,2)  NOT NULL CHECK (average >= 0 AND average <= 10),
    created_at        TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
    updated_at        TIMESTAMPTZ   NOT NULL DEFAULT NOW()
);

-- Index for faster lookups by name
CREATE INDEX idx_students_name ON students (name);

-- Auto-update updated_at on row changes
CREATE OR REPLACE FUNCTION set_updated_at()
RETURNS TRIGGER LANGUAGE plpgsql AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$;

CREATE TRIGGER trg_students_updated_at
    BEFORE UPDATE ON students
    FOR EACH ROW EXECUTE FUNCTION set_updated_at();

-- ============================================================
-- Sample data – 4 students
-- averages are pre-calculated: ROUND((g1+g2+g3)/3, 2)
-- ============================================================
INSERT INTO students
    (name, id_number, email, favorite_sport, favorite_subject, birth_date, grade1, grade2, grade3, average)
VALUES
    (
        'Laura Martínez',
        '1750123456',
        'laura.martinez@example.com',
        'Swimming',
        'Biology',
        '2005-04-18',
        8.00, 7.50, 9.00,
        ROUND((8.00 + 7.50 + 9.00) / 3, 2)   -- 8.17
    ),
    (
        'Andrés Gómez',
        '1720987654',
        'andres.gomez@example.com',
        'Soccer',
        'Mathematics',
        '2004-11-02',
        6.50, 7.00, 5.50,
        ROUND((6.50 + 7.00 + 5.50) / 3, 2)   -- 6.33
    ),
    (
        'Sofía Ramírez',
        '1700456789',
        'sofia.ramirez@example.com',
        'Volleyball',
        'Literature',
        '2006-07-30',
        9.50, 10.00, 9.00,
        ROUND((9.50 + 10.00 + 9.00) / 3, 2)  -- 9.50
    ),
    (
        'Diego Herrera',
        '1710654321',
        'diego.herrera@example.com',
        'Basketball',
        'Computer Science',
        '2005-01-15',
        7.00, 6.00, 8.00,
        ROUND((7.00 + 6.00 + 8.00) / 3, 2)   -- 7.00
    );
