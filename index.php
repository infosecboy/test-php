<?php
// Connection details
$serverName = "tcp:public-db-cloud-assignment.database.windows.net,1433";
$connectionOptions = array(
    "Database" => "publicdb",
    "Uid" => "mysq",
    "PWD" => "HPpavilion15$"
);

// Create connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check if the connection is successful
if ($conn === false) {
    echo "Error connecting to SQL Server.";
    die(print_r(sqlsrv_errors(), true));
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_employee'])) {
        // Add employee to the table
        $name = $_POST['name'];
        $age = $_POST['age'];
        $department = $_POST['department'];

        $query = "INSERT INTO employee (name, age, department) VALUES (?, ?, ?)";
        $params = array($name, $age, $department);
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            echo "Error adding employee: " . print_r(sqlsrv_errors(), true);
        } else {
            echo "Employee added successfully!";
        }
    } elseif (isset($_POST['remove_employee'])) {
        // Remove employee from the table
        $employeeId = $_POST['employee_id'];

        $query = "DELETE FROM employee WHERE id = ?";
        $params = array($employeeId);
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            echo "Error removing employee: " . print_r(sqlsrv_errors(), true);
        } else {
            echo "Employee removed successfully!";
        }
    }
}

// Retrieve employee data from the table
$query = "SELECT * FROM employee";
$stmt = sqlsrv_query($conn, $query);

if ($stmt === false) {
    echo "Error retrieving employees: " . print_r(sqlsrv_errors(), true);
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Management</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        form {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Employee Management</h1>

    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required>
        <label for="department">Department:</label>
        <input type="text" id="department" name="department" required>
        <button type="submit" name="add_employee">Add Employee</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Department</th>
            <th>Action</th>
        </tr>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['department']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="employee_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="remove_employee">Remove</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
<?php
}

// Close the connection
sqlsrv_close($conn);
?>
