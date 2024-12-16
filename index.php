<?php
include 'config.php';

// Get search and pagination parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Build the SQL query with search and pagination
$sql = "SELECT * FROM students WHERE name LIKE :search OR email LIKE :search LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll();

// Get total number of records for pagination
$total_sql = "SELECT COUNT(*) FROM students WHERE name LIKE :search OR email LIKE :search";
$total_stmt = $conn->prepare($total_sql);
$total_stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$total_stmt->execute();
$total_records = $total_stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    
</head>
<body>
    <h1>Student Records</h1>

    <!-- Controls for "Add New Student" and Search Form -->
    <div class="controls">
        <a href="create.php" class="btn btn-primary">Add New Student</a>

        <!-- Search Form -->
        <form method="GET" action="" class="mb-4">
            <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Search by name or email" class="form-control" style="width: 200px; display: inline-block;">
            <button type="submit" class="btn btn-secondary">Search</button>
        </form>
    </div>

    <table id="example" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($students) > 0): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= $student['id']; ?></td>
                        <td><?= htmlspecialchars($student['name']); ?></td>
                        <td><?= htmlspecialchars($student['email']); ?></td>
                        <td><?= $student['age']; ?></td>
                        <td>
                            <a href="edit.php?id=<?= $student['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete.php?id=<?= $student['id']; ?>" onclick="return confirm('Are you sure?');" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No records found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="pagination-container">
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li><a href="?page=1&search=<?= urlencode($search); ?>" class="page-link">First</a></li>
                <li><a href="?page=<?= $page - 1; ?>&search=<?= urlencode($search); ?>" class="page-link">Prev</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                    <a href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>" class="page-link"><?= $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li><a href="?page=<?= $page + 1; ?>&search=<?= urlencode($search); ?>" class="page-link">Next</a></li>
                <li><a href="?page=<?= $total_pages; ?>&search=<?= urlencode($search); ?>" class="page-link">Last</a></li>
            <?php endif; ?>
        </ul>
    </div>

</body>
</html>

<script src='https://code.jquery.com/jquery-3.7.0.js'></script>
<script src='https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js'></script>
<script src='https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js'></script>
<script src='https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js'></script>
