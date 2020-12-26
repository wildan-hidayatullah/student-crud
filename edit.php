<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $student_id = $major = "";
$name_err = $student_id_err = $major_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

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
        // Prepare an update statement
        $sql = "UPDATE students SET name=?, student_id=?, major=? WHERE id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_name, $param_student_id, $param_major, $param_id);

            // Set parameters
            $param_name = $name;
            $param_student_id = $student_id;
            $param_major = $major;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
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
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM students WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $name = $row["name"];
                    $student_id = $row["student_id"];
                    $major = $row["major"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/23753737e8.js" crossorigin="anonymous"></script>

    <title>Update Record</title>

    <style type="text/css">
        .wrapper {
            width: 80vw;
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
                        <h2><i class="fas fa-fw fa-edit"></i> Edit Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($student_id_err)) ? 'has-error' : ''; ?>">
                            <label class="mt-3">Student ID</label>
                            <input type="text" name="student_id" class="form-control" value="<?php echo $student_id; ?>">
                            <span class="help-block"><?php echo $student_id_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($major_err)) ? 'has-error' : ''; ?>">
                            <label class="mt-3">Major</label>
                            <input type="text" name="major" class="form-control" value="<?php echo $major; ?>">
                            <span class="help-block"><?php echo $major_err; ?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary mt-3" value="Submit">
                        <a href="index.php" class="btn btn-outline-secondary mt-3">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>