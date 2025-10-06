<?php
require_once 'config.php';

try {
    $stmt = $pdo->query("SELECT student_id, grade, created_at FROM grades ORDER BY created_at DESC");
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $total_grades = count($grades);
    $average_grade = 0;
    
    if ($total_grades > 0) {
        $sum = array_sum(array_column($grades, 'grade'));
        $average_grade = $sum / $total_grades;
    }
    
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
    $grades = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSU Grade Calculator - View All Grades</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>KSU Grade Calculator</h1>
            <p>All Student Grades</p>
        </header>

        <main>
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <h3>Error:</h3>
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <div class="stats-summary">
                <div class="stat-card">
                    <h3>Total Students</h3>
                    <div class="stat-number"><?php echo $total_grades; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Average Grade</h3>
                    <div class="stat-number"><?php echo $total_grades > 0 ? number_format($average_grade, 2) . '%' : 'N/A'; ?></div>
                </div>
            </div>

            <?php if ($total_grades > 0): ?>
                <div class="grades-table-container">
                    <h3>Student Grades</h3>
                    <table class="grades-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Grade</th>
                                <th>Letter Grade</th>
                                <th>Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grades as $grade): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($grade['student_id']); ?></td>
                                    <td><?php echo number_format($grade['grade'], 2); ?>%</td>
                                    <td>
                                        <?php
                                        $letter_grade = '';
                                        $numeric_grade = $grade['grade'];
                                        if ($numeric_grade >= 90) $letter_grade = 'A';
                                        elseif ($numeric_grade >= 80) $letter_grade = 'B';
                                        elseif ($numeric_grade >= 70) $letter_grade = 'C';
                                        elseif ($numeric_grade >= 60) $letter_grade = 'D';
                                        else $letter_grade = 'F';
                                        echo $letter_grade;
                                        ?>
                                    </td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($grade['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data-message">
                    <h3>No grades submitted yet</h3>
                    <p>Be the first to submit a grade!</p>
                </div>
            <?php endif; ?>

            <div class="action-buttons">
                <a href="submit.html" class="btn btn-primary">Submit New Grade</a>
            </div>
        </main>

        <footer>
            <p>&copy; 2024 Kennesaw State University</p>
        </footer>
    </div>
</body>
</html>
