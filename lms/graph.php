<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2024 Term Test I Marks</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            width: 60%;
            margin: auto;
            background-color: #fff;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>2024 Term Test I Marks</h2>
        <h2>Student 1</h2>
        <canvas id="marksChart" width="400" height="200"></canvas>
    </div>
    <script>
        // Fetching the marks data
        fetch('get_marks.php?studentID=1')  // Replace '1' with dynamic student ID
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.subName);  // Use subName for labels
                const marks = data.map(item => item.mark);  // Ensure correct field name

                // Creating the bar chart
                const ctx = document.getElementById('marksChart').getContext('2d');
                const marksChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Marks',
                            data: marks,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching the marks data:', error));
    </script>
</body>
</html>
