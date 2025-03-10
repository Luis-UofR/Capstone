<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h2>Balance Sheet</h2>
            <a href="home.html" class="btn btn-secondary">Back</a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Assets</h5>
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

                                // Fetch asset accounts
                                $result = $conn->query("SELECT name, balance FROM accounts WHERE type='Asset'");
                                $total_assets = 0;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>{$row['name']}</td><td>\${$row['balance']}</td></tr>";
                                    $total_assets += $row['balance'];
                                }
                                ?>
                            </tbody>
                        </table>
                        <h5>Total Assets: $<?php echo $total_assets; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Liabilities</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch liability accounts
                                $result = $conn->query("SELECT name, balance FROM accounts WHERE type='Liability'");
                                $total_liabilities = 0;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>{$row['name']}</td><td>\${$row['balance']}</td></tr>";
                                    $total_liabilities += $row['balance'];
                                }
                                ?>
                            </tbody>
                        </table>
                        <h5>Total Liabilities: $<?php echo $total_liabilities; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Equity</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch equity accounts
                                $result = $conn->query("SELECT name, balance FROM accounts WHERE type='Equity'");
                                $total_equity = 0;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>{$row['name']}</td><td>\${$row['balance']}</td></tr>";
                                    $total_equity += $row['balance'];
                                }
                                ?>
                            </tbody>
                        </table>
                        <h5>Total Equity: $<?php echo $total_equity; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Total Liabilities and Equity: $<?php echo $total_liabilities + $total_equity; ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>