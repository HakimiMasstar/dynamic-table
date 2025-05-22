<!DOCTYPE html>
<html>
<head>
    <title>Bookstore Input Form</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6fb;
            margin: 0;
            padding: 0;
        }
        h2 {
            color: #2d3e50;
            text-align: center;
        }
        form {
            background: #fff;
            max-width: 600px;
            margin: 30px auto;
            padding: 30px 40px 20px 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px 8px;
            text-align: center;
        }
        th {
            background: #2d3e50;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f0f2f7;
        }
        input[type="text"], input[type="number"] {
            width: 90%;
            padding: 6px;
            border: 1px solid #bfc9d4;
            border-radius: 4px;
        }
        input[type="submit"] {
            background: #2d3e50;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }
        input[type="submit"]:hover {
            background: #1a2533;
        }
        .summary-table {
            background: #fff;
            max-width: 600px;
            margin: 30px auto;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 20px 30px 30px 30px;
        }
        .summary-table table {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <h2>Enter Book Details</h2>
    <form onsubmit="return false;">
        <table id="bookTable">
            <tr>
                <th>Book Title</th>
                <th>Price (RM)</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            <tr>
                <td><input type='text' name='title[]' required oninput="updateSummary()"></td>
                <td><input type='number' name='price[]' step='0.01' min='0' required oninput="updateSummary()"></td>
                <td><input type='number' name='qty[]' min='0' required oninput="updateSummary()"></td>
                <td><button type="button" onclick="addRow()">Add Row</button></td>
            </tr>
        </table>
    </form>
    <div id="summaryContainer"></div>
    <script>
    function addRow() {
        const table = document.getElementById('bookTable');
        const row = table.insertRow(-1);
        row.innerHTML = `
            <td><input type='text' name='title[]' required oninput="updateSummary()"></td>
            <td><input type='number' name='price[]' step='0.01' min='0' required oninput="updateSummary()"></td>
            <td><input type='number' name='qty[]' min='0' required oninput="updateSummary()"></td>
            <td><button type="button" onclick="removeRow(this)">Remove</button></td>
        `;
        updateSummary();
    }
    function removeRow(btn) {
        const row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
        updateSummary();
    }
    function formatCurrency(amount) {
        return 'RM ' + Number(amount).toLocaleString('en-MY', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
    function updateSummary() {
        const table = document.getElementById('bookTable');
        const rows = Array.from(table.rows).slice(1); // skip header
        let summaryHTML = '';
        let grandSubtotal = 0;
        let hasValidRow = false;
        summaryHTML += `<div class='summary-table'><h2>Summary</h2><table>`;
        summaryHTML += `<tr><th>Book</th><th>Quantity</th><th>Unit Price</th><th>Subtotal</th></tr>`;
        rows.forEach(row => {
            const title = row.cells[0].querySelector('input').value;
            const price = parseFloat(row.cells[1].querySelector('input').value) || 0;
            const qty = parseInt(row.cells[2].querySelector('input').value) || 0;
            if (title && qty > 0) {
                const sub = price * qty;
                grandSubtotal += sub;
                hasValidRow = true;
                summaryHTML += `<tr><td>${escapeHTML(title)}</td><td>${qty}</td><td>${formatCurrency(price)}</td><td>${formatCurrency(sub)}</td></tr>`;
            }
        });
        const tax = grandSubtotal * 0.06;
        const total = grandSubtotal + tax;
        if (hasValidRow) {
            summaryHTML += `<tr><td colspan='3' align='right'><strong>Subtotal:</strong></td><td>${formatCurrency(grandSubtotal)}</td></tr>`;
            summaryHTML += `<tr><td colspan='3' align='right'><strong>Tax (6%):</strong></td><td>${formatCurrency(tax)}</td></tr>`;
            summaryHTML += `<tr><td colspan='3' align='right'><strong>Total:</strong></td><td>${formatCurrency(total)}</td></tr>`;
        }
        summaryHTML += `</table></div>`;
        document.getElementById('summaryContainer').innerHTML = summaryHTML;
    }
    function escapeHTML(str) {
        return str.replace(/[&<>'"]/g, function(tag) {
            const charsToReplace = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            };
            return charsToReplace[tag] || tag;
        });
    }
    // Initial summary
    updateSummary();
    </script>
</body>
</html>