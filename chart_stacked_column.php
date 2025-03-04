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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Stacked Column Chart</title>
        <link href="styles.css" rel="stylesheet">
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <style>
            #container {
                height: 400px;
            }

            .highcharts-figure,
            .highcharts-data-table table {
                min-width: 310px;
                max-width: 800px;
                margin: 1em auto;
            }

            .highcharts-data-table table {
                font-family: Verdana, sans-serif;
                border-collapse: collapse;
                border: 1px solid #ebebeb;
                margin: 10px auto;
                text-align: center;
                width: 100%;
                max-width: 500px;
            }

            .highcharts-data-table caption {
                padding: 1em 0;
                font-size: 1.2em;
                color: #555;
            }

            .highcharts-data-table th {
                font-weight: 600;
                padding: 0.5em;
            }

            .highcharts-data-table td,
            .highcharts-data-table th,
            .highcharts-data-table caption {
                padding: 0.5em;
            }

            .highcharts-data-table thead tr,
            .highcharts-data-table tr:nth-child(even) {
                background: #f8f8f8;
            }

            .highcharts-data-table tr:hover {
                background: #f1f7ff;
            }

            .highcharts-description {
                margin: 0.3rem 10px;
            }  

        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="navbar">
                <a href="chart_basic_column.php">Basic Column</a>
                <a href="chart_stacked_column.php">Stacked Column</a>
                <a href="chart_multiple_axes.php">Multiple Axes</a>
                <a href="chart_dual_axes_line_column.php">Dual Axes</a>
                <a href="chart_column_with_drill_down.php">Downdrill</a>
                
                <?php
                    if (isset($_GET['logout'])) {
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
                <h2 style="text-align: center;">Stacked Column Chart</h2>
                <figure class="highcharts-figure">
                    <div id="container"></div>
                    <p class="highcharts-description">
                        Chart showing Total Wins, Draws, and Losses for 5 Seasons for 3 Teams.
                    </p>
                </figure>
            </div>
        </div>

        <?php

        $teams = [];
        $wins = [];
        $draws = [];
        $losses = [];
        $sqlTeams = "SELECT team_id, SUM(home_wins + away_wins) AS total_wins, SUM(draws) AS total_draws, SUM(losses) AS total_losses FROM match_results GROUP BY team_id ORDER BY team_id ASC;";
        $resultTeams = mysqli_query($conn, $sqlTeams);

        if (mysqli_num_rows($resultTeams) > 0) {
            while ($row = mysqli_fetch_assoc($resultTeams)) {
                $teams[] = $row['team_id'];
                $wins[] = (int)$row['total_wins'];
                $draws[] = (int)$row['total_draws'];
                $losses[] = (int)$row['total_losses'];
            }
        }

        ?>

        <script>
            Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Total Wins, Draws, and Losses',
                    align: 'left'
                },
                xAxis: {
                    categories: <?php echo json_encode($teams); ?>
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Count'
                    },
                    stackLabels: {
                        enabled: true
                    }
                },
                legend: {
                    align: 'left',
                    x: 470,
                    verticalAlign: 'top',
                    y: -7,
                    floating: true,
                    backgroundColor:
                        Highcharts.defaultOptions.legend.backgroundColor || 'white',
                    borderColor: '#CCC',
                    borderWidth: 1,
                    shadow: false
                },
                tooltip: {
                    headerFormat: '<b>{category}</b><br/>',
                    pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: [{
                    name: 'Wins',
                    data: <?php echo json_encode($wins); ?>
                }, {
                    name: 'Draws',
                    data: <?php echo json_encode($draws); ?>
                }, {
                    name: 'Losses',
                    data: <?php echo json_encode($losses); ?>
                }]
            });
        </script>

    </body>
    
</html>