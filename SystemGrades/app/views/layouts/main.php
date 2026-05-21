<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'School Grades System') ?></title>

    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<header>
    <h1>School Grades System</h1>
    <nav>
        <a href="index.php?action=list">[ View Grades ]</a>
        &nbsp;|&nbsp;
        <a href="index.php?action=create">[ Add Student ]</a>
    </nav>
    <hr>
</header>

<main>
    <?php
    // Flash messages
    $flash = $_GET['flash'] ?? null;
    if ($flash === 'created') echo '<p><strong>Student added successfully.</strong></p>';
    if ($flash === 'updated') echo '<p><strong>Student updated successfully.</strong></p>';
    if ($flash === 'deleted') echo '<p><strong>Student deleted.</strong></p>';
    if ($flash === 'not_found') echo '<p><strong>Student not found.</strong></p>';
    ?>

    <?php
    // Render the inner content view
    $contentFile = dirname(__DIR__) . '/' . str_replace('.', '/', $content) . '.php';
    if (file_exists($contentFile)) {
        require $contentFile;
    } else {
        echo "<p>Content view not found: {$content}</p>";
    }
    ?>
</main>

<hr>
<footer>
    <small>School Grades System &mdash; Monolithic PHP MVC</small>
</footer>

</body>
</html>
