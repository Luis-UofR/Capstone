<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['journal_date'];    
    $accounts = $_POST['account'];
    $descriptions = $_POST['description'];
    $debits = $_POST['debit'];
    $credits = $_POST['credit'];

    // Check if arrays are properly received
    if (!empty($accounts) && is_array($accounts)) {
        $total_revenue = 0;
        $total_expenses = 0;

        foreach ($accounts as $key => $account_id) {
            // Ensure the array keys exist before accessing them
            $account_id = isset($accounts[$key]) ? $conn->real_escape_string($accounts[$key]) : null;
            $description = isset($descriptions[$key]) ? $conn->real_escape_string($descriptions[$key]) : "";
            $debit = isset($debits[$key]) ? floatval($debits[$key]) : 0.00;
            $credit = isset($credits[$key]) ? floatval($credits[$key]) : 0.00;

            // Check if account_id is valid
            if ($account_id !== null) {
                // Insert journal entry into database
                $sql = "INSERT INTO journal_entries (date, account_id, description, debit, credit) 
                        VALUES ('$date', '$account_id', '$description', $debit, $credit)";

                if (!$conn->query($sql)) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                // Fetch account type
                $account_sql = "SELECT type FROM accounts WHERE id = '$account_id'";
                $account_result = $conn->query($account_sql);
                $account_row = $account_result->fetch_assoc();
                $account_type = $account_row['type'];

                // Calculate balance change
                if ($account_type == 'Asset' || $account_type == 'Expense') {
                    $balance_change = $debit - $credit;
                } else if ($account_type == 'Liability' || $account_type == 'Equity' || $account_type == 'Revenue') {
                    $balance_change = $credit - $debit;
                }

                // Update account balance
                $update_sql = "UPDATE accounts SET balance = balance + $balance_change WHERE id = '$account_id'";
                if (!$conn->query($update_sql)) {
                    echo "Error: " . $update_sql . "<br>" . $conn->error;
                }

                // Track total revenue and expenses
                if ($account_type == 'Revenue') {
                    $total_revenue += $credit - $debit;
                } else if ($account_type == 'Expense') {
                    $total_expenses += $debit - $credit;
                }
            }
        }

        // Calculate net income
        $net_income = $total_revenue - $total_expenses;

        // DOUBLE CHECK IF STILL WORKS ON CLOSING ENTRIES!!!!!!!!!!!!!!!

        // Check for "Retained Earnings" account
        $retained_earnings_sql = "SELECT id FROM accounts WHERE name = 'Retained Earnings'";
        $retained_earnings_result = $conn->query($retained_earnings_sql);
        if ($retained_earnings_result->num_rows > 0) {
            $retained_earnings_row = $retained_earnings_result->fetch_assoc();
            $retained_earnings_id = $retained_earnings_row['id'];

            // Update "Retained Earnings" account
            if ($net_income != 0) {
                $retained_earnings_debit = $net_income < 0 ? abs($net_income) : 0;
                $retained_earnings_credit = $net_income > 0 ? $net_income : 0;

                $retained_earnings_entry_sql = "INSERT INTO journal_entries (date, account_id, description, debit, credit) 
                                                VALUES ('$date', '$retained_earnings_id', 'Retained Earnings Adjustment', $retained_earnings_debit, $retained_earnings_credit)";
                if (!$conn->query($retained_earnings_entry_sql)) {
                    echo "Error: " . $retained_earnings_entry_sql . "<br>" . $conn->error;
                }

                $update_retained_earnings_sql = "UPDATE accounts SET balance = balance + ($retained_earnings_credit - $retained_earnings_debit) WHERE id = '$retained_earnings_id'";
                if (!$conn->query($update_retained_earnings_sql)) {
                    echo "Error: " . $update_retained_earnings_sql . "<br>" . $conn->error;
                }
            }
        }

        // Return a JSON response
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Journal entry submitted successfully.']);
    } else {
        echo "No data received.";
    }
}

$conn->close();
?>
