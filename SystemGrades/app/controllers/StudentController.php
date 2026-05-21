<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/StudentModel.php';

/**
 * StudentController
 * Handles listing, creating, editing, and deleting students with grades.
 */
class StudentController extends Controller
{
    private StudentModel $model;

    // Sport options (radio buttons)
    public const SPORTS = [
        'Soccer',
        'Basketball',
        'Swimming',
        'Tennis',
        'Volleyball',
        'Athletics',
        'Baseball',
        'Cycling',
    ];

    // Subject options (select list)
    public const SUBJECTS = [
        'Mathematics',
        'Physics',
        'Chemistry',
        'Biology',
        'History',
        'Geography',
        'Literature',
        'Computer Science',
        'Art',
        'Music',
        'Physical Education',
        'Philosophy',
    ];

    public function __construct()
    {
        $this->model = new StudentModel();
    }

    /* ------------------------------------------------------------------ */
    /*  LIST  –  GET /index.php  or  GET /index.php?action=list            */
    /* ------------------------------------------------------------------ */

    public function list(): void
    {
        $students      = $this->model->allWithGrades();
        $courseAverage = $this->model->courseAverage();
        $totalStudents = $this->model->count();

        $this->view('layouts.main', [
            'title'         => 'Student Grades',
            'content'       => 'students.list',
            'students'      => $students,
            'courseAverage' => $courseAverage,
            'totalStudents' => $totalStudents,
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  CREATE FORM  –  GET /index.php?action=create                       */
    /* ------------------------------------------------------------------ */

    public function create(): void
    {
        $this->view('layouts.main', [
            'title'   => 'Add Student',
            'content' => 'students.form',
            'student' => null,
            'errors'  => [],
            'sports'  => self::SPORTS,
            'subjects'=> self::SUBJECTS,
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  STORE  –  POST /index.php?action=store                             */
    /* ------------------------------------------------------------------ */

    public function store(): void
    {
        $data   = $this->collectFormData();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $this->view('layouts.main', [
                'title'   => 'Add Student',
                'content' => 'students.form',
                'student' => $data,
                'errors'  => $errors,
                'sports'  => self::SPORTS,
                'subjects'=> self::SUBJECTS,
            ]);
            return;
        }

        $data['average'] = $this->calcAverage(
            (float) $data['grade1'],
            (float) $data['grade2'],
            (float) $data['grade3']
        );

        $this->model->create($data);
        $this->redirect('index.php?action=list&flash=created');
    }

    /* ------------------------------------------------------------------ */
    /*  EDIT FORM  –  GET /index.php?action=edit&id=X                      */
    /* ------------------------------------------------------------------ */

    public function edit(): void
    {
        $id      = (int) $this->get('id');
        $student = $this->model->find($id);

        if (!$student) {
            $this->redirect('index.php?action=list&flash=not_found');
        }

        $this->view('layouts.main', [
            'title'   => 'Edit Student',
            'content' => 'students.form',
            'student' => $student,
            'errors'  => [],
            'sports'  => self::SPORTS,
            'subjects'=> self::SUBJECTS,
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  UPDATE  –  POST /index.php?action=update&id=X                      */
    /* ------------------------------------------------------------------ */

    public function update(): void
    {
        $id     = (int) $this->get('id');
        $data   = $this->collectFormData();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $data['id'] = $id;
            $this->view('layouts.main', [
                'title'   => 'Edit Student',
                'content' => 'students.form',
                'student' => $data,
                'errors'  => $errors,
                'sports'  => self::SPORTS,
                'subjects'=> self::SUBJECTS,
            ]);
            return;
        }

        $data['average'] = $this->calcAverage(
            (float) $data['grade1'],
            (float) $data['grade2'],
            (float) $data['grade3']
        );

        $this->model->update($id, $data);
        $this->redirect('index.php?action=list&flash=updated');
    }

    /* ------------------------------------------------------------------ */
    /*  DELETE  –  POST /index.php?action=delete&id=X                      */
    /* ------------------------------------------------------------------ */

    public function delete(): void
    {
        $id = (int) $this->get('id');
        $this->model->delete($id);
        $this->redirect('index.php?action=list&flash=deleted');
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                             */
    /* ------------------------------------------------------------------ */

    /** Pull and sanitize all student form fields from POST. */
    private function collectFormData(): array
    {
        return [
            'name'             => $this->post('name', ''),
            'id_number'        => $this->post('id_number', ''),
            'email'            => $this->post('email', ''),
            'favorite_sport'   => $this->post('favorite_sport', ''),
            'favorite_subject' => $this->post('favorite_subject', ''),
            'birth_date'       => $this->post('birth_date', ''),
            'grade1'           => $this->post('grade1', ''),
            'grade2'           => $this->post('grade2', ''),
            'grade3'           => $this->post('grade3', ''),
        ];
    }

    /** Validate the student data. Returns array of error messages. */
    private function validate(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'Name is required.';
        }
        if (empty($data['id_number'])) {
            $errors[] = 'ID number is required.';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email address is required.';
        }
        if (empty($data['favorite_sport'])) {
            $errors[] = 'Please select a favorite sport.';
        }
        if (empty($data['favorite_subject'])) {
            $errors[] = 'Please select a favorite subject.';
        }
        if (empty($data['birth_date'])) {
            $errors[] = 'Birth date is required.';
        }

        foreach (['grade1' => 'Grade 1', 'grade2' => 'Grade 2', 'grade3' => 'Grade 3'] as $field => $label) {
            if ($data[$field] === '') {
                $errors[] = "{$label} is required.";
            } elseif (!is_numeric($data[$field]) || $data[$field] < 0 || $data[$field] > 10) {
                $errors[] = "{$label} must be a number between 0 and 10.";
            }
        }

        return $errors;
    }

    /** Compute average rounded to 2 decimal places. */
    private function calcAverage(float $g1, float $g2, float $g3): float
    {
        return round(($g1 + $g2 + $g3) / 3, 2);
    }
}
