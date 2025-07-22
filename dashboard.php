<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';
include __DIR__ . '/includes/sidebar.php';
require_once 'db.php';

// Totals
$total_students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
$total_males = $conn->query("SELECT COUNT(*) AS total FROM students WHERE gender = 'Male'")->fetch_assoc()['total'];
$total_females = $conn->query("SELECT COUNT(*) AS total FROM students WHERE gender = 'Female'")->fetch_assoc()['total'];

// Fetch class-wise gender data
$class_data = [];
$male_data = [];
$female_data = [];

$classes = $conn->query("SELECT class_id, name FROM classes ORDER BY name ASC");

while ($class = $classes->fetch_assoc()) {
    $class_name = $class['name'];
    $class_data[] = $class_name;

    $stmt_m = $conn->prepare("SELECT COUNT(*) AS total FROM students WHERE class_id = ? AND gender = 'Male'");
    $stmt_m->bind_param("i", $class['class_id']);
    $stmt_m->execute();
    $male_data[] = $stmt_m->get_result()->fetch_assoc()['total'];

    $stmt_f = $conn->prepare("SELECT COUNT(*) AS total FROM students WHERE class_id = ? AND gender = 'Female'");
    $stmt_f->bind_param("i", $class['class_id']);
    $stmt_f->execute();
    $female_data[] = $stmt_f->get_result()->fetch_assoc()['total'];
}
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f6f9;
}
.summary-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 20px;
    justify-content: center;
}
.summary-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 20px;
    width: 250px;
    text-align: center;
    transition: 0.3s ease;
}
.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.summary-card h2 {
    margin: 0;
    font-size: 40px;
    color: #2c3e50;
}
.summary-card p {
    margin: 10px 0 0;
    font-size: 16px;
    color: #555;
}
.chart-container {
    padding: 20px;
    max-width: 1000px;
    margin: 0 auto;
    height: 500px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
canvas {
    width: 100% !important;
    height: 100% !important;
}
</style>

<!-- Summary Cards -->
<div class="summary-container">
    <div class="summary-card">
        <h2><?= $total_students ?></h2>
        <p>Total Students</p>
    </div>
    <div class="summary-card">
        <h2><?= $total_males ?></h2>
        <p>Male Students</p>
    </div>
    <div class="summary-card">
        <h2><?= $total_females ?></h2>
        <p>Female Students</p>
    </div>
</div>

<!-- Chart -->
<div class="chart-container">
    <canvas id="genderChart"></canvas>
</div>

<script>
const ctx = document.getElementById('genderChart').getContext('2d');

const classLabels = <?= json_encode($class_data) ?>;
const maleCounts = <?= json_encode($male_data) ?>;
const femaleCounts = <?= json_encode($female_data) ?>;

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: classLabels,
        datasets: [
            {
                label: 'Male',
                data: maleCounts,
                backgroundColor: '#3498db',
                borderRadius: 0
            },
            {
                label: 'Female',
                data: femaleCounts,
                backgroundColor: '#e74c3c',
                borderRadius: 0
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: 20
        },
        plugins: {
            title: {
                display: true,
                text: 'Population overview',
                font: {
                    size: 20,
                    weight: 'bold'
                },
                color: '#2c3e50',
                padding: { top: 10, bottom: 20 }
            },
            tooltip: {
                mode: 'index',
                intersect: false,
                backgroundColor: '#2c3e50',
                titleColor: '#fff',
                bodyColor: '#fff',
                cornerRadius: 8,
                padding: 10
            },
            legend: {
                labels: {
                    color: '#2c3e50',
                    font: {
                        size: 14
                    }
                }
            }
        },
        scales: {
            x: {
                stacked: true,
                ticks: {
                    color: '#2c3e50',
                    font: { size: 14 }
                },
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                stacked: true,
                ticks: {
                    stepSize: 1,
                    color: '#2c3e50',
                    font: { size: 14 }
                },
                grid: {
                    color: 'rgba(0,0,0,0.05)'
                }
            }
        }
    }
});
</script>

<?php include 'footer.php'; ?>
