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
    <title>Drilldown Charts</title>
    <link href="styles.css" rel="stylesheet">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
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
        <h2 style="text-align: center;">Drilldown Column Chart</h2>
        <figure class="highcharts-figure">
            <div id="container"></div>
            <p class="highcharts-description">
                Chart showing Team Transfer Spending with Drilldown feature.
            </p>

        </figure>
    </div>
</div>

<?php
$teams = [];
$players = [];
$net_spend = [];

// Fetch Teams and Players
$sqlTeams = "SELECT DISTINCT team_id FROM transfers ORDER BY team_id ASC;";
$resultTeams = mysqli_query($conn, $sqlTeams);

if (mysqli_num_rows($resultTeams) > 0) {
    while ($row = mysqli_fetch_assoc($resultTeams)) {
        $teams[] = $row['team_id'];
    }
}

$sqlSpend = "SELECT team_id, SUM(transfer_fee) AS net_spend FROM transfers GROUP BY team_id;";
$resultSpend = mysqli_query($conn, $sqlSpend);

if (mysqli_num_rows($resultSpend) > 0) {
    while ($row = mysqli_fetch_assoc($resultSpend)) {
        $net_spend[] = (int)$row['net_spend'];
    }
}

$sqlPlayers = "SELECT team_id, player_name, transfer_fee FROM transfers ORDER BY team_id ASC;";
$resultPlayers = mysqli_query($conn, $sqlPlayers);

if (mysqli_num_rows($resultPlayers) > 0) {
    while ($row = mysqli_fetch_assoc($resultPlayers)) {
        $players[$row['team_id']][] = [$row['player_name'], (int)$row['transfer_fee']];
    }
}
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Team Transfer Spending'
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Net Spend (in USD)'
        }
    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },
    tooltip: {
        pointFormat: '<b>{point.y}</b>'
    },
    series: [{
        name: 'Teams',
        colorByPoint: true,
        data: [
            <?php
            foreach ($teams as $index => $team) {
                echo "{ name: '$team', y: {$net_spend[$index]}, drilldown: '$team' },";
            }
            ?>
        ]
    }],
    drilldown: {
        series: [
            <?php
            foreach ($teams as $team) {
                if (isset($players[$team])) {
                    echo "{ name: '$team', id: '$team', data: " . json_encode($players[$team]) . " },";
                }
            }
            ?>
        ]
    }
});
</script>


</body>
</html>
