<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    $sql = "INSERT INTO students (name, email, age) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $email, $age]);

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="form.css">
    <title>Add New Student</title>
</head>
<body>
    <div class="container">
        <form method="POST">
            <h1>Add New Student</h1>
            <label>Name</label>
            <input type="text" name="name" required><br>
            <label>Email</label>
            <input type="email" name="email" required><br>
            <label>Age</label>
            <input type="number" name="age" required><br>
            <button type="submit">Add Student</button>
        </form>
    </div>
</body>
</html>
