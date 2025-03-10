<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Statement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h2>Income Statement</h2>
            <a href="home.html" class="btn btn-secondary">Back</a>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Income</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Database connection
                                include 'db_connection.php';

                                // Fetch income accounts
                                $result = $conn->query("SELECT name, balance FROM accounts WHERE type='Revenue'");
                                $total_income = 0;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>{$row['name']}</td><td>\${$row['balance']}</td></tr>";
                                    $total_income += $row['balance'];
                                }
                                ?>
                            </tbody>
                        </table>
                        <h5>Total Income: $<?php echo $total_income; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Expenses</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch expense accounts
                                $result = $conn->query("SELECT name, balance FROM accounts WHERE type='Expense'");
                                $total_expenses = 0;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>{$row['name']}</td><td>\${$row['balance']}</td></tr>";
                                    $total_expenses += $row['balance'];
                                }
                                ?>
                            </tbody>
                        </table>
                        <h5>Total Expenses: $<?php echo $total_expenses; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Net Income: $<?php echo $total_income - $total_expenses; ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>