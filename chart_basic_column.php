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
$teams = [];
$sqlTeams = "SELECT DISTINCT team_id FROM match_results ORDER BY team_id ASC;";
$resultTeams = mysqli_query($conn, $sqlTeams);

if (mysqli_num_rows($resultTeams) > 0) {
    while ($row = mysqli_fetch_assoc($resultTeams)) {
        $teams[] = $row['team_id'];
    }
}

// Prepare Data Series
$homeSeries = [
    'name' => 'Home Wins',
    'data' => []
];
$awaySeries = [
    'name' => 'Away Wins',
    'data' => []
];

foreach ($teams as $team) {
    $sqlHome = "SELECT SUM(home_wins) AS total_home FROM match_results WHERE team_id = '$team';";
    $resultHome = mysqli_query($conn, $sqlHome);
    $rowHome = mysqli_fetch_assoc($resultHome);
    $homeSeries['data'][] = (int)$rowHome['total_home'];

    $sqlAway = "SELECT SUM(away_wins) AS total_away FROM match_results WHERE team_id = '$team';";
    $resultAway = mysqli_query($conn, $sqlAway);
    $rowAway = mysqli_fetch_assoc($resultAway);
    $awaySeries['data'][] = (int)$rowAway['total_away'];
}

$series = [$homeSeries, $awaySeries];
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Home and Away Wins for Last 5 Seasons'
    },
    xAxis: {
        categories: <?php echo json_encode($teams); ?>,
        title: {
            text: 'Teams'
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Wins'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><br/>',
        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y} Wins</b><br/>'
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: <?php echo json_encode($series); ?>
});
</script>

</body>
</html>
