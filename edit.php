<?php
include 'config.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    // Update student record
    $sql = "UPDATE students SET name = ?, email = ?, age = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $email, $age, $id]);

    header("Location: index.php");
    exit;
}

// Fetch current student data
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$student = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="form.css">
    <title>Edit Student</title>
</head>
<body>
    <div class="container">
        
        <form method="POST">
        <h1>Edit Student</h1>
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($student['name']); ?>" required><br>
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($student['email']); ?>" required><br>
            <label>Age</label>
            <input type="number" name="age" value="<?= htmlspecialchars($student['age']); ?>" required><br>
            <button type="submit">Update Student</button>
        </form>
    </div>
</body>
</html>
