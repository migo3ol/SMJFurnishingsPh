<?php
include 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user details from users_admin
$user_query = "SELECT firstname, middlename, surname FROM users_admin WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $userId);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

// Construct full name
$full_name = trim($user['firstname'] . ' ' . ($user['middlename'] ? $user['middlename'] . ' ' : '') . $user['surname']);

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $selected_month = $_GET['month'] ?? date('Y-m');
    $search_po_no = $_GET['po_no'] ?? '';

    if (isset($_GET['total_sales']) && $_GET['total_sales'] == '1') {
        // Return only the total sales value
        $total_sales_query = "SELECT SUM(total_amount) AS total_sales 
                              FROM sales_records 
                              WHERE user_id = ? 
                              AND DATE_FORMAT(date, '%Y-%m') = ? 
                              AND status = 'Delivered'";
        $params = [$userId, $selected_month];
        $types = "is";

        if (!empty($search_po_no)) {
            $total_sales_query .= " AND po_no LIKE ?";
            $params[] = '%' . $search_po_no . '%';
            $types .= "s";
        }

        $total_sales_stmt = $conn->prepare($total_sales_query);
        $total_sales_stmt->bind_param($types, ...$params);
        $total_sales_stmt->execute();
        $total_sales_result = $total_sales_stmt->get_result();
        $total_sales_row = $total_sales_result->fetch_assoc();
        $total_sales = $total_sales_row['total_sales'] ?? 0;

        echo number_format($total_sales, 2);
        exit();
    }

    // Fetch sales records
    $query = "SELECT * FROM sales_records WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
    $params = [$userId, $selected_month];
    $types = "is";

    if (!empty($search_po_no)) {
        $query .= " AND po_no LIKE ?";
        $params[] = '%' . $search_po_no . '%';
        $types .= "s";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . date('Y-m-d', strtotime($row['date'])) . "</td>
                <td>" . $row['po_no'] . "</td>
                <td>" . $row['client_name'] . "</td>
                <td>" . $row['project_name'] . "</td>
                <td>" . $row['product_classification'] . "</td>
                <td>" . $row['area'] . "</td>
                <td>₱" . number_format($row['total_amount'], 2) . "</td>
                <td>" . $row['or_no'] . "</td>
                <td>" . $row['sibi_no'] . "</td>
                <td>" . $row['dr_no'] . "</td>
                <td>" . $row['status'] . "</td>
                <td>
                    <a href='edit_sales.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='delete_sales.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                </td>
            </tr>";
    }
    exit();
}

// Regular Load
$selected_month = $_GET['month'] ?? date('Y-m');
$search_po_no = $_GET['po_no'] ?? '';

// Total Sales
$total_sales_query = "SELECT SUM(total_amount) AS total_sales 
                      FROM sales_records 
                      WHERE user_id = ? 
                      AND DATE_FORMAT(date, '%Y-%m') = ? 
                      AND status = 'Delivered'";
$total_sales_stmt = $conn->prepare($total_sales_query);
$total_sales_stmt->bind_param("is", $userId, $selected_month);
$total_sales_stmt->execute();
$total_sales_result = $total_sales_stmt->get_result();
$total_sales_row = $total_sales_result->fetch_assoc();
$total_sales = $total_sales_row['total_sales'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Records | SMJ Furnishings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .container { margin-top: 50px; }
        .table { box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="col-md-2">
        <?php include 'side_navbar.php'; ?>
    </div>
    <div class="container col-md-9 ms-auto">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="fw-bold">Sales Records</h1>
            <div>
                <button class="btn btn-success" onclick="window.location.href='add_sales.php'">Add Sales</button>
                <button class="btn btn-primary" id="generatePdf">Generate PDF</button>
            </div>
        </div>
        <form method="GET" action="sales.php" class="mb-4" onsubmit="return false;">
            <div class="row">
                <div class="col-md-4">
                    <label for="month" class="form-label">Select Month</label>
                    <input type="month" id="month" name="month" class="form-control" value="<?= $selected_month ?>">
                </div>
                <div class="col-md-4">
                    <label for="po_no" class="form-label">Search PO No</label>
                    <input type="text" id="po_no" name="po_no" class="form-control" placeholder="Enter PO No" value="<?= $search_po_no ?>">
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped" id="salesTable">
            <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>PO No</th>
                <th>Client Name</th>
                <th>Project Name</th>
                <th>Product Classification</th>
                <th>Area in sqm</th>
                <th>Total Amount</th>
                <th>OR</th>
                <th>SI/BI</th>
                <th>DR</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT * FROM sales_records WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
            $params = [$userId, $selected_month];
            $types = "is";

            if (!empty($search_po_no)) {
                $query .= " AND po_no LIKE ?";
                $params[] = '%' . $search_po_no . '%';
                $types .= "s";
            }

            $stmt = $conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= date('Y-m-d', strtotime($row['date'])) ?></td>
                    <td><?= $row['po_no'] ?></td>
                    <td><?= $row['client_name'] ?></td>
                    <td><?= $row['project_name'] ?></td>
                    <td><?= $row['product_classification'] ?></td>
                    <td><?= $row['area'] ?></td>
                    <td>₱<?= number_format($row['total_amount'], 2) ?></td>
                    <td><?= $row['or_no'] ?></td>
                    <td><?= $row['sibi_no'] ?></td>
                    <td><?= $row['dr_no'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <a href="edit_sales.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_sales.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="6" class="text-end">Total Sales:</th>
                <th colspan="5">₱<?= number_format($total_sales, 2) ?></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    const monthInput = document.getElementById('month');
    const poNoInput = document.getElementById('po_no');

    function fetchSalesData() {
        const poNo = poNoInput.value;
        const month = monthInput.value;

        const xhr = new XMLHttpRequest();
        xhr.open('GET', `sales.php?ajax=1&po_no=${encodeURIComponent(poNo)}&month=${encodeURIComponent(month)}`, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.querySelector('#salesTable tbody').innerHTML = xhr.responseText;

                // Fetch updated total sales
                fetchTotalSales(month);
            }
        };
        xhr.send();
    }

    function fetchTotalSales(month) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `sales.php?ajax=1&total_sales=1&month=${encodeURIComponent(month)}`, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.querySelector('#salesTable tfoot th[colspan="5"]').innerHTML = `₱${xhr.responseText}`;
            }
        };
        xhr.send();
    }

    poNoInput.addEventListener('input', fetchSalesData);
    monthInput.addEventListener('change', fetchSalesData);
</script>
<script>
    document.getElementById('generatePdf').addEventListener('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });

        // Add logo (replace 'Uploads/logo.png' with actual path)
        const logoPath = 'Uploads/logo.png';
        try {
            doc.addImage(logoPath, 'PNG', 10, 10, 40, 20);
        } catch (e) {
            console.warn('Logo not found, skipping...');
        }

        // Add company details
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(18);
        doc.text('SMJ Furnishings', 148, 20, { align: 'center' });
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(10);
        doc.text('Sales Report', 148, 28, { align: 'center' });

        // Add report metadata
        const selectedMonth = document.getElementById('month').value || 'Current Month';
        const reportDate = new Date().toISOString().split('T')[0];
        doc.setFontSize(10);
        doc.text(`Month: ${selectedMonth}`, 148, 34, { align: 'center' });
        doc.text(`Generated on: ${reportDate}`, 148, 40, { align: 'center' });
        doc.text(`Generated by: <?php echo htmlspecialchars($full_name); ?>`, 148, 46, { align: 'center' });

        // Fetch table data
        const table = document.getElementById('salesTable');
        const headers = Array.from(table.querySelectorAll('thead th'))
            .map(th => th.innerText)
            .filter(header => header !== 'Actions');
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr => {
            return Array.from(tr.querySelectorAll('td'))
                .slice(0, -1)
                .map(td => td.innerText.replace('₱', 'P'));
        });

        // Generate table in PDF
        doc.autoTable({
            head: [headers],
            body: rows,
            startY: 55,
            theme: 'striped',
            headStyles: {
                fillColor: [44, 62, 80],
                textColor: [255, 255, 255],
                fontStyle: 'bold',
                fontSize: 10
            },
            bodyStyles: {
                font: 'helvetica',
                fontSize: 9,
                cellPadding: 3
            },
            alternateRowStyles: {
                fillColor: [240, 240, 240]
            },
            columnStyles: {
                0: { cellWidth: 25 },
                1: { cellWidth: 20 },
                2: { cellWidth: 30 },
                3: { cellWidth: 30 },
                4: { cellWidth: 30 },
                5: { cellWidth: 20 },
                6: { cellWidth: 25, halign: 'right' },
                7: { cellWidth: 20 },
                8: { cellWidth: 20 },
                9: { cellWidth: 20 },
                10: { cellWidth: 20 }
            },
            margin: { left: 10, right: 10 },
            didDrawPage: function (data) {
                const pageCount = doc.internal.getNumberOfPages();
                const pageSize = doc.internal.pageSize;
                const pageHeight = pageSize.height || pageSize.getHeight();
                doc.setFontSize(8);
                doc.setFont('helvetica', 'normal');
                doc.text(`Page ${data.pageNumber} of ${pageCount}`, 280, pageHeight - 10, { align: 'right' });
                doc.text('SMJ Furnishings', 10, pageHeight - 10);
            }
        });

        // Add total sales
        const totalSales = document.querySelector('#salesTable tfoot th[colspan="5"]').innerText.replace('₱', 'P');
        const finalY = doc.lastAutoTable.finalY + 10;
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(12);
        doc.text(`Total Sales: ${totalSales}`, 148, finalY, { align: 'center' });

        // Handle empty table
        if (rows.length === 0) {
            doc.setFontSize(12);
            doc.text('No sales records found for the selected criteria.', 148, 100, { align: 'center' });
        }

        // Save the PDF
        doc.save(`sales_report_${selectedMonth}.pdf`);
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

</body>
</html>