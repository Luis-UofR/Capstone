<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Entry Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts.js" defer></script>
</head>

<body class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-center flex-grow-1">Journal Entry Form</h1>
        <button type="button" class="btn btn-primary me-2" onclick="location.reload();">New Entry</button>
        <a href="home.html" class="btn btn-secondary">Back</a>
    </div>

    <form id="journal-entry-form" action="process_journal_entry.php" method="post">
        <div class="mb-3">
            <label for="journal-date" class="form-label">Date:</label>
            <input type="date" id="journal-date" name="journal_date" class="form-control" required>
        </div>

        <table class="table table-bordered" id="journal-table">
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < 2; $i++): ?>
                    <tr>
                        <td>
                            <select name="account[]" class="form-select">
                                <!-- Loading because of the script that fetches the accounts -->
                                <option value="">Loading...</option>
                            </select>
                        </td>
                        <td><input type="number" name="debit[]" step="0.01" class="form-control" onchange="validateEntries();" oninput="toggleRowInputs(this)"></td>
                        <td><input type="number" name="credit[]" step="0.01" class="form-control" onchange="validateEntries();" oninput="toggleRowInputs(this)"></td>
                        <td><input type="text" name="description[]" class="form-control"></td>
                        <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <button type="button" class="btn btn-success mb-3" onclick="addRow()">Add Row</button>
        <div id="error" class="text-danger"></div>
        <br>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <div id="success-message" class="text-success mt-3"></div>

    <script>
        // Set today's date as the default date
        document.getElementById('journal-date').valueAsDate = new Date();

        // Function to add a new row to the table
        function addRow() {
            const table = document.getElementById('journal-table').getElementsByTagName('tbody')[0];
            const newRow = table.insertRow();
            newRow.innerHTML = `
                <td>
                    <select name="account[]" class="form-select">
                        <!-- Loading because of the script that fetches the accounts -->
                        <option value="">Loading...</option>
                    </select>
                </td>
                <td><input type="number" name="debit[]" step="0.01" class="form-control" onchange="validateEntries();" oninput="toggleRowInputs(this)"></td>
                <td><input type="number" name="credit[]" step="0.01" class="form-control" onchange="validateEntries();" oninput="toggleRowInputs(this)"></td>
                <td><input type="text" name="description[]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
            `;
        }

        // Function to remove a row from the table
        function removeRow(button) {
            const row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }

        // Function to validate entries
        function validateEntries() {
            // Add your validation logic here
        }

        // Function to toggle row inputs
        function toggleRowInputs(input) {
            // Add your toggle logic here
        }

        // Reset the form after submission
        document.getElementById('journal-entry-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            const form = event.target;

            fetch(form.action, {
                method: form.method,
                body: new FormData(form)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('success-message').textContent = 'Journal entry submitted successfully.';
                    form.reset(); // Reset the form
                    document.getElementById('journal-date').valueAsDate = new Date(); // Reset the date to today's date
                } else {
                    document.getElementById('error').textContent = 'Error submitting journal entry: ' + data.message;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>