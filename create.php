<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $student_id = $major = "";
$name_err = $student_id_err = $major_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    $input_name = trim($_POST["name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $name_err = "Please enter a valid name.";
    } else {
        $name = $input_name;
    }

    // Validate student_id
    $input_student_id = trim($_POST["student_id"]);
    if (empty($input_student_id)) {
        $student_id_err = "Please enter an student ID.";
    } elseif (!ctype_digit($input_student_id)) {
        $student_id_err = "Please enter a positive integer value.";
    } else {
        $student_id = $input_student_id;
    }

    // Validate major
    $input_major = trim($_POST["major"]);
    if (empty($input_major)) {
        $major_err = "Please enter the major.";
    } else {
        $major = $input_major;
    }

    // Check input errors before inserting in database
    if (empty($name_err) && empty($student_id_err) && empty($major_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO students (name, student_id, major) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_student_id, $param_major);

            // Set parameters
            $param_name = $name;
            $param_student_id = $student_id;
            $param_major = $major;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/23753737e8.js" crossorigin="anonymous"></script>

    <title>Create Record</title>

    <style type="text/css">
        .wrapper {
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header mt-5">
                        <h2><i class="fas fa-fw fa-user"></i> Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($student_id_err)) ? 'has-error' : ''; ?>">
                            <label class="mt-3">Student ID</label>
                            <input name="student_id" class="form-control" value="<?php echo $student_id; ?>">
                            <span class="help-block"><?php echo $student_id_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($major_err)) ? 'has-error' : ''; ?>">
                            <label class="mt-3">Major</label>
                            <input type="text" name="major" class="form-control" value="<?php echo $major; ?>">
                            <span class="help-block"><?php echo $major_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary mt-3" value="Submit">
                        <a href="index.php" class="btn btn-outline-secondary mt-3">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>