<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = trim($_POST['student_id']);
    $grade = floatval($_POST['grade']);
    
    $errors = [];
    
    if (empty($student_id)) {
        $errors[] = "Student ID is required";
    }
    
    if ($grade < 0 || $grade > 100) {
        $errors[] = "Grade must be between 0 and 100";
    }
    
    if (!is_numeric($student_id)) {
        $errors[] = "Student ID must contain only numbers";
    }
    
    if (empty($errors)) {
        try {
            $check_stmt = $pdo->prepare("SELECT id FROM grades WHERE student_id = ?");
            $check_stmt->execute([$student_id]);
            
            if ($check_stmt->fetch()) {
                $update_stmt = $pdo->prepare("UPDATE grades SET grade = ?, created_at = CURRENT_TIMESTAMP WHERE student_id = ?");
                $update_stmt->execute([$grade, $student_id]);
                $message = "Grade updated successfully for Student ID: $student_id";
            } else {
                $insert_stmt = $pdo->prepare("INSERT INTO grades (student_id, grade) VALUES (?, ?)");
                $insert_stmt->execute([$student_id, $grade]);
                $message = "Grade submitted successfully for Student ID: $student_id";
            }
            
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
} else {
    header('Location: submit.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSU Grade Calculator - Submission Result</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>KSU Grade Calculator</h1>
            <p>Submission Result</p>
        </header>

        <main>
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <h3>Please correct the following errors:</h3>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php else: ?>
                <div class="success-message">
                    <h3>Success!</h3>
                    <p><?php echo htmlspecialchars($message); ?></p>
                    <p>Grade: <?php echo number_format($grade, 2); ?>%</p>
                </div>
            <?php endif; ?>

            <div class="action-buttons">
                <a href="submit.html" class="btn btn-secondary">Submit Another Grade</a>
                <a href="display.php" class="btn btn-primary">View All Grades</a>
            </div>
        </main>

        <footer>
            <p>&copy; 2024 Kennesaw State University</p>
        </footer>
    </div>
</body>
</html>
