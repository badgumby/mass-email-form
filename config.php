<?php

// To address(es)
$smtpTo = array(
  "Main.Person@example.com"
);

// CC address(es)
$smtpCc = array(
  "Person.One@example.com",
  "Person.Two@example.com"
);

// From address
$smtpFrom = "IT.Notifications@example.com";
// From display name
$smtpFromDisplay = "IT Notifications";

// SMTP server and port
$smtpAddr = "smtp-relay.example.com";
$smtpPort = "25";

################################################
################################################
// Email subject prefix
$subjectPrefix = "IT Notification: ";

// Section 1 (Planned changed needs to be modified in code, due to additional variables)
// Section Header (Details)
$section2 = "Details About the Planned System Change";

// Section Headers (What to Expect)
$section3 = "What to Expect";

// Email footer
$footerMsg = "If you have any questions or should experience any issues as a result of the system change, please submit a helpdesk ticket, and we will treat your incident as high priority.";

################################################
################################################

// Load JSON variables
$filename = "resources/options.json";
$loadJSON = file_get_contents($filename);
$jsonDecode = json_decode($loadJSON);
// Explode file into variables
$systems = $jsonDecode->systems;
$teams = $jsonDecode->teams;
$sumofchangeText = $jsonDecode->summaryOfChange;
$detailsText = $jsonDecode->details;
$whatToExpectText = $jsonDecode->whatToExpect;


 ?>
