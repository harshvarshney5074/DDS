<?php
session_start();
include("dbcon.php");
include("config.php");

// Load Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['send1'])) {

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';    // Your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;            // SMTP username from config.php
        $mail->Password   = SMTP_PASS;            // SMTP password from config.php
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // From address and name
        $mail->setFrom(SMTP_USER, 'Library Services');

        // To address
        $to = trim($_POST['To']);
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid To email address.");
        }
        $mail->addAddress($to);

        // Cc addresses
        // Cc addresses
        // Fix CC
        if (!empty($_POST['Cc']) && is_array($_POST['Cc'])) {
            $ccInput = $_POST['Cc'];
            $ccEmails = [];

            foreach ($ccInput as $item) {
                $parts = explode(',', $item);
                foreach ($parts as $cc) {
                    $cc = trim($cc);
                    if (filter_var($cc, FILTER_VALIDATE_EMAIL)) {
                        $mail->addCC($cc);
                    }
                }
            }
        }

        // Fix BCC
        if (!empty($_POST['Bcc']) && is_array($_POST['Bcc'])) {
            $bccInput = $_POST['Bcc'];
            $bccEmails = [];

            foreach ($bccInput as $item) {
                $parts = explode(',', $item);
                foreach ($parts as $bcc) {
                    $bcc = trim($bcc);
                    if (filter_var($bcc, FILTER_VALIDATE_EMAIL)) {
                        $mail->addBCC($bcc);
                    }
                }
            }
        }

        // Subject and body
        $mail->Subject = $_POST['sub'] ?? '(No Subject)';
        $mail->isHTML(true);
        $mail->Body = $_POST['Body'] ?? '';

        // Attachments from attachments table
        $result = mysqli_query($conn, "SELECT * FROM attachments");
        while ($row = mysqli_fetch_assoc($result)) {
            $filePath = $row['File_path'];
            $fileName = $row['File_name'];

            if (file_exists($filePath)) {
                $mail->addAttachment($filePath, $fileName);
            } else {
                error_log("Attachment file not found: $filePath");
            }
        }


        // Send the email
        $mail->send();
		if (isset($_POST['order_no'])) {
			$order_id = intval($_POST['order_no']);
			$update = mysqli_prepare($conn, "UPDATE orders SET sent = 1 WHERE order_id = ?");
			mysqli_stmt_bind_param($update, "i", $order_id);
			mysqli_stmt_execute($update);
			mysqli_stmt_close($update);
		}

        if (!empty($_POST['entry_ids'])) {
            $entryIdsRaw = $_POST['entry_ids'];
            $entryIds = array_filter(array_map('intval', explode(',', $entryIdsRaw)));

            if (!empty($entryIds)) {
                $idsList = implode(',', $entryIds); // e.g., "23,25,29"
                $today = date('Y-m-d');
                $updateSentDate = "UPDATE entry SET Sent_date = '$today' WHERE Sr_no IN ($idsList)";
                mysqli_query($conn, $updateSentDate);
                $updateStatus = "UPDATE entry SET Status = 'Complete' WHERE Sr_no IN ($idsList)";
                mysqli_query($conn, $updateStatus);

            }
        }

        // Clear attachments table after sending
        mysqli_query($conn, "TRUNCATE TABLE attachments");

        // Success HTML
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
          <meta charset="UTF-8" />
          <title>Send Mail - Success</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        </head>
        <body>
          <div class="container mt-5">
            <h2 class="text-success">Mail sent successfully!</h2>
            <a href="orders.php" class="btn btn-primary">Go to Orders</a>
          </div>
        </body>
        </html>
        <?php
    } catch (Exception $e) {
        // Failure HTML
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
          <meta charset="UTF-8" />
          <title>Send Mail - Error</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        </head>
        <body>
          <div class="container mt-5">
            <h2 class="text-danger">Mail sending failed!</h2>
            <p><strong>Error:</strong> <?= htmlspecialchars($mail->ErrorInfo) ?></p>
            <a href="show3.php" class="btn btn-secondary">Back</a>
          </div>
        </body>
        </html>
        <?php
    }
} else {
    // If the form wasn't submitted properly
    header("Location: show.php");
    exit;
}
?>
