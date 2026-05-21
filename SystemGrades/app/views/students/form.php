<?php
$isEdit   = isset($student['id']);
$action   = $isEdit
    ? 'index.php?action=update&id=' . (int) $student['id']
    : 'index.php?action=store';

/** Helper: return old value for a field, html-escaped. */
function old(array|null $student, string $field, string $default = ''): string
{
    $val = $student[$field] ?? $default;
    return htmlspecialchars((string) $val);
}
?>

<h2><?= $isEdit ? 'Edit Student' : 'Add New Student' ?></h2>

<?php if (!empty($errors)): ?>
    <div>
        <strong>Please fix the following errors:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?= $action ?>">

    <fieldset>
        <legend>Personal Information</legend>

        <p>
            <label for="name">Full Name: *</label><br>
            <input type="text"
                   id="name"
                   name="name"
                   value="<?= old($student, 'name') ?>"
                   required>
        </p>

        <p>
            <label for="id_number">ID Number (Cédula): *</label><br>
            <input type="text"
                   id="id_number"
                   name="id_number"
                   value="<?= old($student, 'id_number') ?>"
                   required>
        </p>

        <p>
            <label for="email">Email Address: *</label><br>
            <input type="email"
                   id="email"
                   name="email"
                   value="<?= old($student, 'email') ?>"
                   required>
        </p>

        <p>
            <label for="birth_date">Date of Birth: *</label><br>
            <input type="date"
                   id="birth_date"
                   name="birth_date"
                   value="<?= old($student, 'birth_date') ?>"
                   required>
        </p>
    </fieldset>

    <fieldset>
        <legend>Favorite Sport (select one): *</legend>
        <?php foreach ($sports as $sport): ?>
            <label>
                <input type="radio"
                       name="favorite_sport"
                       value="<?= htmlspecialchars($sport) ?>"
                       <?= (old($student, 'favorite_sport') === $sport) ? 'checked' : '' ?>>
                <?= htmlspecialchars($sport) ?>
            </label>
            <br>
        <?php endforeach; ?>
    </fieldset>

    <fieldset>
        <legend>Favorite Subject: *</legend>
        <p>
            <label for="favorite_subject">Select subject:</label><br>
            <select id="favorite_subject" name="favorite_subject" required>
                <option value="">-- Select a subject --</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= htmlspecialchars($subject) ?>"
                            <?= (old($student, 'favorite_subject') === $subject) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($subject) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
    </fieldset>

    <fieldset>
        <legend>Grades (0 – 10)</legend>

        <p>
            <label for="grade1">Grade 1: *</label><br>
            <input type="number"
                   id="grade1"
                   name="grade1"
                   min="0" max="10" step="0.01"
                   value="<?= old($student, 'grade1') ?>"
                   oninput="calcAverage()"
                   required>
        </p>

        <p>
            <label for="grade2">Grade 2: *</label><br>
            <input type="number"
                   id="grade2"
                   name="grade2"
                   min="0" max="10" step="0.01"
                   value="<?= old($student, 'grade2') ?>"
                   oninput="calcAverage()"
                   required>
        </p>

        <p>
            <label for="grade3">Grade 3: *</label><br>
            <input type="number"
                   id="grade3"
                   name="grade3"
                   min="0" max="10" step="0.01"
                   value="<?= old($student, 'grade3') ?>"
                   oninput="calcAverage()"
                   required>
        </p>

        <p>
            <label>Calculated Average:</label><br>
            <output id="average_display">
                <?php
                $g1 = (float) ($student['grade1'] ?? 0);
                $g2 = (float) ($student['grade2'] ?? 0);
                $g3 = (float) ($student['grade3'] ?? 0);
                echo ($isEdit && $g1 + $g2 + $g3 > 0)
                    ? number_format(($g1 + $g2 + $g3) / 3, 2)
                    : '—';
                ?>
            </output>
        </p>
    </fieldset>

    <p>
        <button type="submit"><?= $isEdit ? 'Update Student' : 'Add Student' ?></button>
        &nbsp;
        <a href="index.php?action=list">Cancel</a>
    </p>

</form>

<script>
function calcAverage() {
    const g1 = parseFloat(document.getElementById('grade1').value);
    const g2 = parseFloat(document.getElementById('grade2').value);
    const g3 = parseFloat(document.getElementById('grade3').value);

    const display = document.getElementById('average_display');

    if (!isNaN(g1) && !isNaN(g2) && !isNaN(g3)) {
        const avg = (g1 + g2 + g3) / 3;
        display.textContent = avg.toFixed(2);
    } else {
        display.textContent = '—';
    }
}

// Run on page load for edit mode
calcAverage();
</script>
