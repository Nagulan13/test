<?php

include 'db_conn.php';

session_start();

if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please Login Again...'); window.location.href = 'index.php';</script>";
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charts</title>
    <link href="styles.css" rel="stylesheet">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 310px;
            max-width: 800px;
            margin: 1em auto;
        }

        #container {
            height: 400px;
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
        <h2 style="text-align: center;">Basic Column Chart</h2>
        <figure class="highcharts-figure">
            <div id="container"></div>
            <p class="highcharts-description">
                Chart showing Home and Away Wins for 5 Seasons for 3 Teams.
            </p>
        </figure>
    </div>
</div>

<?php
// Fetch Teams
$seasons = [];

$sqlSeason = "SELECT DISTINCT season_id FROM match_results ORDER BY season_id ASC;";
$stmt = mysqli_prepare($conn, $sqlSeason);
mysqli_stmt_execute($stmt);
$resultSeason = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultSeason) > 0) {
    while ($row = mysqli_fetch_assoc($resultSeason)) {
        $seasons[] = $row['season_id'];
    }
}

$win_percentage = [];

$sqlWP = "SELECT season_id, SUM(home_wins + away_wins) AS total_wins, SUM(home_wins + away_wins + draws + losses) AS total_matches 
          FROM match_results 
          GROUP BY season_id 
          ORDER BY season_id ASC;";

$resultWP= mysqli_query($conn, $sqlWP);

while ($row = mysqli_fetch_assoc($resultWP)) {
    $win_percentage[] = ($row['total_matches'] > 0) ? round(($row['total_wins'] / $row['total_matches']) * 100, 2) : 0;
}

$net_spend = [];

$sqlSpend = "SELECT season_id, SUM(transfer_fee) AS net_spend FROM transfers GROUP BY season_id ORDER BY season_id ASC;";
$resultSpend = mysqli_query($conn, $sqlSpend);

if (mysqli_num_rows($resultSpend) > 0) {
    while ($row = mysqli_fetch_assoc($resultSpend)) {
        $net_spend[] = (int)$row['net_spend'];
    }
}

?>

<script>
    Highcharts.chart('container', {
        chart: {
            zooming: {
                type: 'xy'
            }
        },
        title: {
            text: 'Manchester United Performance Based On Spending',
            align: 'left'
        },
        credits: {
            text: 'Daythree'
        },
        xAxis: [{
            categories: <?php echo json_encode($seasons); ?>,
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}%',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: 'Win Percentage',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: 'Net Spend',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value} USD',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            align: 'left',
            verticalAlign: 'top',
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },
        series: [{
            name: 'Net Spend',
            type: 'column',
            yAxis: 1,
            data: <?php echo json_encode($net_spend); ?>,
            tooltip: {
                valueSuffix: ' USD'
            }

        }, {
            name: 'Win Percentage',
            type: 'spline',
            data: <?php echo json_encode($win_percentage); ?>,
            tooltip: {
                valueSuffix: '%'
            }
        }]
    });
</script>

</body>
</html>
