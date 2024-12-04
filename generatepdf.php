<?php
include 'connection1.php';
require_once('vendor/autoload.php'); // Include DOMPDF's autoload file, adjust the path as needed

use Dompdf\Dompdf;
use Dompdf\Options;

date_default_timezone_set('Asia/Kolkata');

// Escape inputs to prevent SQL injection
$user_id = $con->real_escape_string($_POST["user_id"]);

// Retrieve all medical records for the user
$fetchQuery = "SELECT record_id, record_type, record_date, images 
               FROM medical_records 
               WHERE user_id = '$user_id'";

$result = $con->query($fetchQuery);

if ($result->num_rows > 0) {
    // Initialize DOMPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $dompdf = new Dompdf($options);

    // Prepare the HTML content for the PDF
    $htmlContent = '<h1>User Medical Reports</h1>';
    $htmlContent .= '<p>Generated on: ' . date('Y-m-d H:i:s') . '</p>';

    // Add content for each medical record
    while ($row = $result->fetch_assoc()) {
        $htmlContent .= "<h2>Record ID: {$row['record_id']}</h2>";
        $htmlContent .= "<p>Record Type: {$row['record_type']}</p>";
        $htmlContent .= "<p>Record Date: {$row['record_date']}</p>";

        // Attach images (if available)
        $images = json_decode($row['images'], true);
        if (!empty($images)) {
            foreach ($images as $image) {
                $htmlContent .= "<img src='$image' style='width:100px;height:75px;' />"; // Adjust size as needed
            }
        }

        $htmlContent .= '<hr>';
    }

    // Load the HTML content into DOMPDF
    $dompdf->loadHtml($htmlContent);

    // (Optional) Set paper size
    $dompdf->setPaper('A4', 'portrait');

    // Render the PDF (first pass)
    $dompdf->render();

    // Save PDF to server
    $pdfDirectory = 'pdf_reports/';
    if (!is_dir($pdfDirectory)) {
        mkdir($pdfDirectory, 0777, true);
    }
    $pdfFileName = "medical_reports_user_id = $user_id" . time() . ".pdf";
    $pdfFilePath = $pdfDirectory . $pdfFileName;
    file_put_contents($pdfFilePath, $dompdf->output());

    // Return the PDF download link
    echo json_encode(array(
        "status" => "true",
        "message" => "PDF generated successfully",
        "pdf_url" => "http://yourdomain.com/" . $pdfFilePath
    ));
} else {
    echo json_encode(array("status" => "false", "message" => "No records found for the user"));
}

$con->close();
?>