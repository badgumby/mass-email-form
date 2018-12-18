<?php
// Load config
include('config.php');
require_once 'vendor/autoload.php';

// Handle post
if(isset($_POST['attachments'])) {
  $attachArray = $_POST['attachments'];
  $attachments = explode("|", $attachArray);
} else {
  // Do nothing
}
$emailFile = $_POST['emailFile'];
$fullSubject = $_POST['fullSubject'];

// Convert HTML file to message body
$html = file_get_contents($emailFile);

// Find between tags
function getContents($str, $startDelimiter, $endDelimiter) {
  $contents = array();
  $startDelimiterLength = strlen($startDelimiter);
  $endDelimiterLength = strlen($endDelimiter);
  $startFrom = $contentStart = $contentEnd = 0;
  while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
    $contentStart += $startDelimiterLength;
    $contentEnd = strpos($str, $endDelimiter, $contentStart);
    if (false === $contentEnd) {
      break;
    }
    $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
    $startFrom = $contentEnd + $endDelimiterLength;
  }

  return $contents;
}

// Create the Transport
$transport = (new Swift_SmtpTransport($smtpAddr, $smtpPort));
// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);
// Create a message
$message = (new Swift_Message($fullSubject))
  ->setFrom([$smtpFrom => $smtpFromDisplay]);

// Add To address(es)
$i = 0;
foreach ($smtpTo as $toAddr) {
  $message->addTo($toAddr);
}

// Add CC address(es)
if (!$smtpCc) {
  // Do nothing
} else {
  $i = 0;
  foreach ($smtpCc as $ccAddr) {
    $message->addCc($ccAddr);
  }
}

// Add inline images
$images = getContents($html, "<img src='", "'>");
if (!$images) {
  // Do nothing
} else {
  $i = 0;
  foreach ($images as $image) {
    if ($image == "none-selected") {
      // Skip
    } else {
      // Create new inline attachment
      $attachment = Swift_Attachment::fromPath("." . $image)->setDisposition('inline');
      // Generate cid headers
      $attachment->getHeaders()->addTextHeader('Content-ID', "<SOMEID1$i>");
      $attachment->getHeaders()->addTextHeader('X-Attachment-Id', "SOMEID1$i");
      // Attach to message
      $cid = $message->embed($attachment);
      $img = "<img src='cid:SOMEID1$i'/>";
      // Replace actual path, with newly generated cid path
      $html = str_replace("<img src='$image'>", $img, $html);
      $i++;
    }
  }
}

// Add attachments
if (!$attachments) {
  // Do nothing
} else {
  $i = 0;
  foreach ($attachments as $attachThis) {
    if ($attachThis == "none-selected") {
      // Skip
    } else {
      $message->attach(Swift_Attachment::fromPath($attachThis));
      $i++;
    }
  }
}

// Add HTML body to message
$message->setBody($html, 'text/html');

// Send the message
$result = $mailer->send($message);

// Display results of message send
if($result = 1) {
  // Mail sent successfully
  ?>
  <html>
  <head>
    <title>Email sent</title>
    <link rel='stylesheet' type='text/css' href='resources/styles.css'>
  </head>
  <body class='mass-notify'>
    <div class='mainDiv'>
      <br><br>
      <b>Email sent successfully.</b>
      <br><br>
      <input type='button' onclick="window.location='index.php';" value='Back to form'>
      <br><br>
    </div>
  </body>
  </html>
  <?php
} else {
  // Mail failed to send
  ?>
  <html>
  <head>
    <title>Email failed</title>
    <link rel='stylesheet' type='text/css' href='resources/styles.css'>
  </head>
  <body class='mass-notify'>
    <div class='mainDiv'>
      <br><br>
      <script>
      function goBack() {
          window.history.back();
        }
      </script>
      <div class='mainDiv'>
        <br><br>
        <b><font color="red">Email failed to send.</font></b>
        <br><br>
        <input type='button' onclick='goBack()' value='Go Back'>
        <br><br>
      </div>
    </body>
  </html>
<?php
  }

 ?>
