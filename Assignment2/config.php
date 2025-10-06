<?php
$db_file = 'grades.db';
$pdo = null;

try {
    $pdo = new PDO("sqlite:$db_file");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $create_table_sql = "
        CREATE TABLE IF NOT EXISTS grades (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            student_id VARCHAR(20) NOT NULL,
            grade DECIMAL(5,2) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ";
    
    $pdo->exec($create_table_sql);
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
