<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/23753737e8.js" crossorigin="anonymous"></script>

    <title>Dashboard</title>

    <style type="text/css">
        .wrapper {
            margin: 0 auto;
        }

        table tr td:last-child a {
            margin-right: 8px;
        }
    </style>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header mt-5">
                        <h2 class="pull-left"><i class='fas fa-fw fa-user-graduate'></i> Students Details</h2>
                        <a href="create.php" class="btn btn-primary mb-3 mt-3">Add New Record</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";

                    // Attempt select query execution
                    $sql = "SELECT * FROM students";
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo "<table class='table table-striped table-hover'>";
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>Name</th>";
                            echo "<th>Student ID</th>";
                            echo "<th>Major</th>";
                            echo "<th>Action</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";

                            $number = 1;
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>" . $number . "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['student_id'] . "</td>";
                                echo "<td>" . $row['major'] . "</td>";
                                echo "<td>";
                                echo "<a class='btn btn-secondary p-0' href='read.php?id=" . $row['id'] . "' title='View Record' data-toggle='tooltip'><i class='fas fa-fw fa-eye'></i></a>";
                                echo "<a class='btn btn-secondary p-0' href='edit.php?id=" . $row['id'] . "' title='Edit Record' data-toggle='tooltip'><i class='fas fa-fw fa-pencil-alt'></i></a>";
                                echo "<a class='btn btn-secondary p-0' href='delete.php?id=" . $row['id'] . "' title='Delete Record' data-toggle='tooltip'><i class='fas fa-fw fa-trash-alt'></i></a>";
                                echo "</td>";
                                echo "</tr>";
                                ++$number;
                            }
                            echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else {
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else {
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }

                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>