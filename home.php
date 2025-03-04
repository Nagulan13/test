<?php

include 'db_conn.php';

session_start();

if(!isset($_SESSION['email'])) {
    echo "<script>alert('Please Login Again...'); window.location.href = 'index.php';</script>";
    exit();
}

?> 

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width= device-width, initial-scale=1.0">
        <title>Dashboard</title>

        <link href="styles.css" rel="stylesheet">

    </head>
    <body>
        <div class="wrapper">
            <div class="container">
                <div class="navbar">
                    <a href="chart_basic_column.php">Basic Column</a>
                    <a href="chart_stacked_column.php">Stacked Column</a>
                    <a href="chart_multiple_axes.php">Multiple axes</a>
                    <a href="chart_dual_axes_line_column.php">Dual axes</a>
                    <a href="chart_column_with_drill_down.php">Drilldown</a>

                    <?php
                        if (isset($_GET['logout'])) { // Check if logout is clicked
                            echo "<script>
                                if(confirm('Are you sure you want to logout?')) {
                                    window.location.href = 'logout.php'; // Redirect to logout.php to destroy session
                                } else {
                                    window.history.back(); // Stay on the page if cancel
                                }
                            </script>";
                        }
                    ?>
                    <a href="?logout=true">Logout</a>
                </div>
                <div class="content">
                    <figure class="highcharts-figure">
                        <div id="container"></div>
                        <p class="highcharts-description">
                        </p>
                    </figure>


                </div>
            </div>
        </div>
    </body>
</html>
