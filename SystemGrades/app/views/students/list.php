<h2>Student Grades</h2>

<p>
    Total Students: <strong><?= (int) $totalStudents ?></strong>
    &nbsp;&nbsp;|&nbsp;&nbsp;
    Course Average: <strong><?= number_format($courseAverage, 2) ?> / 10</strong>
</p>

<?php if (empty($students)): ?>
    <p>No students enrolled yet. <a href="index.php?action=create">Add the first student.</a></p>
<?php else: ?>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>ID Number</th>
            <th>Email</th>
            <th>Favorite Sport</th>
            <th>Favorite Subject</th>
            <th>Birth Date</th>
            <th>Grade 1</th>
            <th>Grade 2</th>
            <th>Grade 3</th>
            <th>Average</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $index => $student): ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($student['name']) ?></td>
            <td><?= htmlspecialchars($student['id_number']) ?></td>
            <td><?= htmlspecialchars($student['email']) ?></td>
            <td><?= htmlspecialchars($student['favorite_sport']) ?></td>
            <td><?= htmlspecialchars($student['favorite_subject']) ?></td>
            <td><?= htmlspecialchars($student['birth_date']) ?></td>
            <td><?= number_format((float) $student['grade1'], 2) ?></td>
            <td><?= number_format((float) $student['grade2'], 2) ?></td>
            <td><?= number_format((float) $student['grade3'], 2) ?></td>
            <td><strong><?= number_format((float) $student['average'], 2) ?></strong></td>
            <td>
                <a href="index.php?action=edit&id=<?= (int) $student['id'] ?>">[ Edit ]</a>
                &nbsp;
                <form method="POST"
                      action="index.php?action=delete&id=<?= (int) $student['id'] ?>"
                      style="display:inline;"
                      onsubmit="return confirm('Delete <?= htmlspecialchars(addslashes($student['name'])) ?>?');">
                    <button type="submit">[ Delete ]</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>
