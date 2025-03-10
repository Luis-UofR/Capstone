<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart of Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts.js" defer></script>
</head>

<body class="container mt-4">

    <!-- Chart of Accounts title and back button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-center flex-grow-1">Chart of Accounts</h1>
        <button onclick="location.href='add_account.html'" class="btn btn-success">New</button>
        <a href="home.html" class="btn btn-secondary ms-2">Back</a>
    </div>

    <!-- Searching for account -->
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <div>
            <input type="text" id="filter-name" class="form-control d-inline w-auto" placeholder="Filter by name" oninput="filterAccounts()">
            <select id="filter-type" class="form-select d-inline w-auto" onchange="filterAccounts()">
                <option value="">All</option>
                <option value="Asset">Asset</option>
                <option value="Liability">Liability</option>
                <option value="Equity">Equity</option>
                <option value="Revenue">Revenue</option>
                <option value="Expense">Expense</option>
            </select>
        </div>
    </div>

    <!-- List of all chart of accounts -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Account Type</th>
                <th>Balance</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="accounts-table">
            <?php include 'fetch_accounts_table.php'; ?>
        </tbody>
    </table>

    <script>
        function deleteAccount(accountId) {
            if (confirm('Are you sure you want to delete this account?')) {
                fetch('delete_account.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: accountId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Account deleted successfully.');
                        location.reload();
                    } else {
                        alert('Error deleting account: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>

</html>