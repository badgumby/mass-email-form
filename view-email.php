<?php
// load config
include('config.php');

// handle post objects
// Convert date
$getDate = $_POST['changeDate'];
$dateConvert = strtotime($getDate);
$changeDate = date("l, F j, Y", $dateConvert);
$changeTime = date("g:i A", $dateConvert);
$timezone = $_POST['timezone'];

$subjectTitle = htmlspecialchars($_POST['subjectTitle']);
$soc = htmlspecialchars($_POST['soc']);
$socInline = $_POST['socInline'];
$details = htmlspecialchars($_POST['details']);
$detailsInline = $_POST['detailsInline'];
$whatToExpect = htmlspecialchars($_POST['whatToExpect']);
$wteInline = $_POST['wteInline'];
$attachments = $_POST['attachments'];
$teamValue = htmlspecialchars($_POST['teamName']);

// Lookup additional team information from options.json
foreach ($teams as $val) {
  if ($val->value == $teamValue) {
    $teamSignature = $val->signature;
    $teamName = $val->name;
  } else {
    // Do nothing
  }
}

// Handle type options (selected or custom)
$type = $_POST['type'];
if ($type == "Other") {
  $type = htmlspecialchars($_POST['otherBox']);
} else {

}

$vars = array(
  '{$date}' => $changeDate,
  '{$time}' => $changeTime . " " . $timezone,
  '{$datetime}' => $changeDate . " at " . $changeTime . " " . $timezone,
  '{$type}' => $type,
  '{$team}' => $teamName
);

// Variables for Subject line
$subject = strtr($subjectTitle, $vars);
$emailDir = "emails/";
$emailFile = str_replace(' ', '-', $subject) . ".html";
$emailFullPath = $emailDir . $emailFile;

?>
<html>
<head>
  <title>View Email</title>
  <link rel="stylesheet" type="text/css" href="resources/styles.css">
</head>
<body class="mass-notify" style="color:#ffffff">
  <div class="viewDiv">
<?php


// Subject line of email
$fullSubject = $subjectPrefix . $subject;
echo "<font style='font-size:20px;font-weight:bold;color:#ffffff;'>Subject: </font> <font style='font-size:18px;color:#ffffff;'>" . $fullSubject . "</font><br>";

// Attempting write to file
try {
  $fh = fopen($emailFullPath, 'w') or die("Can't create file<br>");

########## To base64 encode files, use below in the foreach (not supported by many mail clients)
//$type = pathinfo($socFile, PATHINFO_EXTENSION);
//$data = file_get_contents($socFile);
//$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

  // Summary of Change section
  $socHeader = "<b><font style='font-size:14.0pt;color:#4472C4'>Planned $type Change: Beginning $changeDate at $changeTime $timezone</font></b><br>";
  fwrite($fh, $socHeader);
  fwrite($fh, strtr($soc, $vars));
  fwrite($fh, "<br><br>");
  // Summary of Change screenshots
  foreach($socInline as $socFile) {
    if ($socFile == "none-selected") {
      // Do nothing
    } else {
      // Write to file
      fwrite($fh, "<img src='/$socFile'><br>");
    }
  }
  fwrite($fh, "<br>");

  // Details section
  $detailsHeader = "<b><font style='font-size:14.0pt;color:#4472C4'>$section2</font></b><br>";
  fwrite($fh, $detailsHeader);
  fwrite($fh, strtr($details, $vars));
  fwrite($fh, "<br><br>");
  // Details screenshots
  foreach($detailsInline as $detailsFile) {
    if ($detailsFile == "none-selected") {
      // Do nothing
    } else {
      // Write to file
      fwrite($fh, "<img src='/$detailsFile'><br>");
    }
  }
  fwrite($fh, "<br>");

  // What to Expect section
  $wteHeader = "<b><font style='font-size:14.0pt;color:#4472C4'>$section3</font></b><br>";
  fwrite($fh, $wteHeader);
  fwrite($fh, strtr($whatToExpect, $vars));
  fwrite($fh, "<br><br>");
  // What to Expect screenshots
  foreach($wteInline as $wteFile) {
    if ($wteFile == "none-selected") {
      // Do nothing
    } else {
      // Write to file
      fwrite($fh, "<img src='/$wteFile'><br>");
    }
  }

  // Write footer message
  fwrite($fh, "<br>$footerMsg<br><br><br>");

  // Team signature
  fwrite($fh, $teamSignature . "<br>");

  // Add whitespace to bottom of message
  fwrite($fh, "<br><br><br>");
  fclose($fh);

  // Display the email in an iFrame
  ?>
  <iframe style="background:#ffffff;" src="<?php echo $emailFullPath ?>"></iframe>
  <br>

  <div class="attachments">
    <h3><u>Attachments</u></h3>
  <?php

  // Attachments
  foreach($attachments as $attachFile) {
    if ($attachFile == "none-selected") {
      echo "No files will be attached.<br>";
    } else {
      echo "<b>$attachFile</b> will be attached.<br>";
    }
  }
  ?>
  <br>
</div>
  <br>
  <script>
  function goBack() {
      window.history.back();
    }
  </script>
  <div id="box">
    <div class="item">
      <input type="button" onclick="goBack()" value="Go Back">
    </div>
    <div class="item">
        <form id="sendMessage" action="send-email.php" method="post">
          <input type="hidden" value="<?php echo implode('|', $attachments) ;?>" name="attachments">
          <input type="hidden" value="<?php echo $emailFullPath ;?>" name="emailFile">
          <input type="hidden" value="<?php echo $fullSubject ;?>" name="fullSubject">
          <input type="submit" id="submit" value="Send Email">
        </form>
    </div>
  </div>
</div>
  <?php
} catch (Exception $e) {
  echo "An error occurred. <br>". $e->getMessage();
}

 ?>

</body>
</html>
