// Add a row to the journal form table
function addRow() {
    let table = document.getElementById("journal-table").getElementsByTagName('tbody')[0];
    let newRow = table.insertRow();

    newRow.innerHTML = `
        <td>
            <select name="account[]" class="form-select">
                <option value="">Loading...</option>
            </select>
        </td>
        <td><input type="number" name="debit[]" step="0.01" class="form-control" onchange="validateEntries();" oninput="toggleRowInputs(this)"></td>
        <td><input type="number" name="credit[]" step="0.01" class="form-control" onchange="validateEntries();" oninput="toggleRowInputs(this)"></td>
        <td><input type="text" name="description[]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
    `;

    fetchAccountOptions(newRow.cells[0].querySelector("select"));
}

// Remove a row from the journal form table
function removeRow(button) {
    let row = button.parentNode.parentNode;
    let table = document.getElementById("journal-table").getElementsByTagName('tbody')[0];
    if (table.rows.length > 2) {
        row.remove();
    } else {
        alert("At least two rows are required.");
    }
}

// Using AJAX to fetch account options without having to reload the page
function fetchAccountOptions(selectElement) {
    fetch('fetch_accounts_options.php')
        .then(response => response.text())
        .then(data => {
            selectElement.innerHTML = `<option value="">Select Account</option>` + data;
        })
        .catch(error => {
            console.error("Error fetching account options:", error);
        });
}

// Populate dropdowns in initial rows on page load
window.onload = function () {
    document.querySelectorAll('select[name="account[]"]').forEach(fetchAccountOptions);

    // Set today's date as the default date
    document.getElementById('journal-date').valueAsDate = new Date();
};

// Checking if debits equal credits, and prevent from debiting and crediting the same account
function validateEntries() {

    // checking if credits and debits are equal
    const debits = document.querySelectorAll('input[name="debit[]"]');
    const credits = document.querySelectorAll('input[name="credit[]"]');

    let totalDebits = 0, totalCredits = 0;

    debits.forEach(input => totalDebits += parseFloat(input.value) || 0);
    credits.forEach(input => totalCredits += parseFloat(input.value) || 0);

    const error = document.getElementById('error');
    if (totalDebits !== totalCredits) {
        error.textContent = `Debits (${totalDebits.toFixed(2)}) must equal Credits (${totalCredits.toFixed(2)}).`;
        return false;
    } else {
        error.textContent = '';
        return true;
    }
}

// Restricting input to 2 decimal places
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('input[name="debit[]"], input[name="credit[]"]').forEach(input => {
        input.addEventListener("input", function () {
            let value = this.value;

            // Ensure the value is a valid number
            if (!value.match(/^\d*(\.\d{0,2})?$/)) {
                this.value = value.slice(0, -1); // Remove last typed character
            }
        });
    });
});

// Preventing debiting and crediting in the same line (automatiacally a zero to not break mySQL) 
function toggleRowInputs(changedInput) {
    const row = changedInput.closest('tr');
    const debitInput = row.querySelector('input[name="debit[]"]');
    const creditInput = row.querySelector('input[name="credit[]"]');

    if (changedInput === debitInput && debitInput.value) {
        creditInput.readOnly = true; // Use readOnly instead of disabled
        creditInput.value = "0";
    } else if (changedInput === creditInput && creditInput.value) {
        debitInput.readOnly = true;
        debitInput.value = "0";
    } else {
        debitInput.readOnly = false;
        creditInput.readOnly = false;
    }
}

// Preventing form submission if debits don't equal credits (NOT TESTED)
function validateAndSubmit(event) {
    if (!validateEntries()) {
        event.preventDefault();
        alert('Debits must equal Credits before submission.');
    }
}

// Add function to prevent users from using the same account twice
// [Yet to type, would be nice to add]

// Tells user that the journal entry was successfully submitted
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('journal-entry-form');
    const successMessageDiv = document.getElementById('success-message');

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(form);

        fetch('process_journal_entry.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                successMessageDiv.textContent = data.message;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    filterAccounts();
});

function filterAccounts() {
    const filterName = document.getElementById('filter-name').value.toLowerCase();
    const filterType = document.getElementById('filter-type').value;

    const rows = document.querySelectorAll('#accounts-table tr');

    rows.forEach(row => {
        const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const type = row.querySelector('td:nth-child(2)').textContent;

        const matchesName = name.includes(filterName);
        const matchesType = filterType === '' || type === filterType;

        if (matchesName && matchesType) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
