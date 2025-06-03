<?php
require 'PHPMailer-master/PHPMailerAutoload.php';
include("dbcon.php");
require 'config.php';

if (isset($_POST['send_inst'])) {
    // Sanitize and fetch input values
    $body = trim($_POST['Body']);
    $Cc = array_map('trim', explode(',', $_POST['Cc'] ?? ''));
	$Bcc = array_map('trim', explode(',', $_POST['Bcc'] ?? ''));
    $sub = htmlspecialchars(trim($_POST['sub']));
    $institutes = $_POST["framework1"] ?? [];

    // Proceed only if at least one institute is selected
    if (!empty($institutes)) {
        $get_inst = mysqli_query($conn, "SELECT * FROM send_instentry");

        // For each entry in send_instentry, insert institute list and update status
        while ($row = mysqli_fetch_assoc($get_inst)) {
            $entry_id = $row['entry_id'];
            $request_date = $row['req_date'];

            foreach ($institutes as $inst_id) {
                $inst_id = intval($inst_id);
                mysqli_query($conn, "INSERT INTO institute_list (institute_name, entry_id, req_date) 
                                     VALUES ('$inst_id', '$entry_id', '$request_date')");
            }

            mysqli_query($conn, "UPDATE entry SET Status='Approached' WHERE Sr_no='$entry_id'");
        }

        // Send email to each selected institute
        foreach ($institutes as $inst_id) {
            $inst_id = intval($inst_id);
            $result = mysqli_query($conn, "SELECT email FROM institutions WHERE Sr_no='$inst_id'");
            $row = mysqli_fetch_assoc($result);

            if ($row && filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                sendEmail($row['email'], $body, $Cc, $Bcc, $sub);
            }
        }

        // Redirect on success
        header("Location: index.php?status=sent");
        exit();
    } else {
        echo "<script>alert('No institute selected.'); window.history.back();</script>";
    }
}

// Send Email Function
function sendEmail($email, $body, $Cc, $Bcc, $sub) {
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom(SMTP_USER, 'Document Delivery Service');
    $mail->addAddress($email);

	// CC handling
	if (!empty($_POST['Cc'])) {
		$ccs = explode(',', $_POST['Cc']);
		foreach ($ccs as $cc) {
			$cc = trim($cc);
			if (filter_var($cc, FILTER_VALIDATE_EMAIL)) {
				$mail->addCC($cc);
			}
		}
	}

	// BCC handling
	if (!empty($_POST['Bcc'])) {
		$bccs = explode(',', $_POST['Bcc']);
		foreach ($bccs as $bcc) {
			$bcc = trim($bcc);
			if (filter_var($bcc, FILTER_VALIDATE_EMAIL)) {
				$mail->addBCC($bcc);
			}
		}
	}

    $mail->isHTML(true);
    $mail->Subject = $sub;
    $mail->Body = $body;

    if (!$mail->send()) {
        file_put_contents('mail_errors.log', date('Y-m-d H:i:s') . ' - ' . $email . ' - ' . $mail->ErrorInfo . "\n", FILE_APPEND);
    }
}
?>
