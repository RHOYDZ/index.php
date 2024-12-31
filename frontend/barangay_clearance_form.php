<?php
// Assuming user data comes from a session or database
session_start();

// Example user data (replace with actual database queries)
$user_data = [
    'name' => 'Juan Dela Cruz',
    'address' => '123 Purok 1, Barangay Mabuhay',
    'purpose' => 'Job Application',
    'issued_date' => date('F d, Y'),
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Clearance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .clearance-container {
            width: 8.5in;
            height: 11in;
            padding: 40px;
            margin: 0 auto;
            border: 1px solid #000;
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            width: 100px;
            height: auto;
        }
        .header h1 {
            margin: 10px 0 5px;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        .content {
            margin-top: 20px;
            font-size: 16px;
            line-height: 1.8;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .footer p {
            margin: 0;
        }
        .print-button {
            display: none;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="clearance-container">
        <div class="header">
            <!-- Updated logo path -->
            <img src="../pic/logo2.png" alt="Barangay Logo">
            <h1>Barangay Mabuhay</h1>
            <p>Purok 1, Barangay Mabuhay, Lungsod ng Tagumpay</p>
        </div>
        <div class="content">
            <p><strong>TO WHOM IT MAY CONCERN:</strong></p>
            <p>
                This is to certify that <strong><?php echo $user_data['name']; ?></strong>, 
                residing at <strong><?php echo $user_data['address']; ?></strong>, is a bona fide resident of this Barangay.
            </p>
            <p>
                This certification is issued upon the request of the above-named person for 
                <strong><?php echo $user_data['purpose']; ?></strong>.
            </p>
            <p>Issued this <strong><?php echo $user_data['issued_date']; ?></strong>.</p>
        </div>
        <div class="footer">
            <p>__________________________</p>
            <p>Barangay Captain</p>
        </div>
    </div>
    <!-- Print button positioned at the bottom -->
    <button class="print-button" onclick="window.print()" style="margin: 20px auto; display: block; padding: 10px 20px; background-color: #0077b6; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">Print</button>
</body>

</html>
