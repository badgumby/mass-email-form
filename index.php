<?php
// load config
include('config.php');

// Uploads directory
$dir = "uploads/";

// List only support image formats
$imageList = preg_grep('~\.(jpeg|jpg|png|gif)$~', scandir($dir));
// Create array of images from directory (exclude all except, jpeg,jpg,png, and gif)
$images = array_diff($imageList, array('..','.','.gitignore'));
// Create array of files from directory (exclude .., ., and .gitignore)
$files = array_diff(scandir($dir, 2), array('..','.','.gitignore'));
 ?>

<html>
<head>
  <title>IT Mass Notification Form</title>
  <link rel="stylesheet" type="text/css" href="resources/styles.css">
</head>
<body class="mass-notify">
  <script type="text/javascript">
  function otherSelected() {
    $otherBox = document.getElementById("otherBox");
    $typeSelect = document.getElementById("type");
    if($typeSelect.value == "Other") {
      $otherBox.disabled = false;
      $otherBox.required = true;
    } else {
      $otherBox.disabled = true;
      $otherBox.required = false;
    }
  }
  </script>
  <div class="mainDiv">
    <h3>IT Mass Notification Form</h3>
    Do you need to attach screenshots, or additional files? If so, <a href="file-mgmt.php" target="popup"
    onclick="window.open('file-mgmt.php','popup','width=600,height=600'); return false;"><div class="tooltip">click here<span class="tooltiptext2">Remember to refresh this page after uploading any files.</span></div></a>.<br>

  <div class="tooltip">You can also use variables in the form.
    <span class="tooltiptext">
      <i>Wrap variable in braces</i> <b>{}</b> <i>as shown below (with examples):</i><br>
      {$date} - Friday, November 30, 2018<br>
      {$time} - 1:00 AM Central Time<br>
      {$datetime} - Friday, November 30, 2018 at 1:00 AM Central Time<br>
      {$type} - SAP System<br>
      {$team} - IT Infrastructure Team
    </span>
  </div> <br><br>

    <form id="create-email" action="view-email.php" method="post">
      <table>
        <tr>
          <td>Subject:<br><i>IT Notification: is<br>included by default</i></td>
          <td>
            <input type="text" id="subjectTitle" name="subjectTitle" required="required">
          </td>
        </tr>
      <tr>
        <td>
          <label>Date of event:</label>
        </td>
        <td>
          <input type="datetime-local" id="changeDate" name="changeDate" required="required">
          <select name="timezone" id="timezone" required="required">
            <option value="Eastern Time">Eastern Time</option>
            <option value="Central Time" selected>Central Time</option>
            <option value="Mountain Time">Mountain Time</option>
            <option value="Pacific Time">Pacific Time</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>
          <label>System Type:</label>
        </td>
          <td><select name="type" id="type" required="required" onchange="otherSelected();">
            <?php foreach ($systems as $val) {
              echo "<option value='$val'>$val</option>";
            } ?>
          </select> <input placeholder="Other..." style="max-width:60%;" type="text" name="otherBox" id="otherBox" disabled></td>
      </tr>
      <tr>
        <td><label>Summary of Change:</label></td>
        <td><textarea rows="8" id="soc" name="soc" required="required"><?php echo $sumofchangeText ;?></textarea>
      </tr>
      <tr>
        <td>Inline Screenshots<br>(PNG, JPG, or GIF):</td>
        <td><select name="socInline[]" id="socInline" class="input" type="text" multiple>
          <option value="none-selected"selected>No screenshot</option>
          <?php
          foreach($images as $img) {
            echo "<option value='" . $dir . $img . "'>" . $img . "</option>";
          }
          ?>
        </select></td>
      </tr>
      <tr>
        <td><label>Details:</label></td>
        <td><textarea rows="8" id="details" name="details" required="required"><?php echo $detailsText ;?></textarea>
      </tr>
      <tr>
        <td>Inline Screenshots<br>(PNG, JPG, or GIF):</td>
        <td><select name="detailsInline[]" id="detailsInline" class="input" type="text" multiple>
          <option value="none-selected" selected>No screenshot</option>
          <?php
          foreach($images as $img) {
            echo "<option value='" . $dir . $img . "'>" . $img . "</option>";
          }
          ?>
        </select></td>
      </tr>
      <tr>
        <td><label>What to Expect:</label></td>
        <td><textarea rows="8" id="whatToExpect" name="whatToExpect" required="required"><?php echo $whatToExpectText ;?></textarea>
      </tr>
      <tr>
        <td>Inline Screenshots<br>(PNG, JPG, or GIF):</td>
        <td><select name="wteInline[]" id="wteInline" class="input" type="text" multiple>
          <option value="none-selected" selected>No screenshot</option>
          <?php
          foreach($images as $img) {
            echo "<option value='" . $dir . $img . "'>" . $img . "</option>";
          }
          ?>
        </select></td>
      </tr>
      <tr>
        <td>Additional Attachments<br>(All formats):</td>
        <td><select name="attachments[]" id="attachments" class="input" type="text" multiple>
          <option value="none-selected" selected>No attachments</option>
          <?php
          foreach($files as $file) {
            echo "<option value='" . $dir . $file . "'>" . $file . "</option>";
          }
          ?>
        </select></td>
      </tr>
      <tr>
        <td><label>Team Signature:</label></td>
        <td><select name="teamName" id="teamName" required="required">
          <?php foreach ($teams as $val) {
            echo "<option value='$val->value'>$val->name</option>";
          } ?>
        </select></td>
      </tr>
      <tr>
        <td></td>
        <td><input type="submit" id="submit" value="Submit"></td>
      </tr>
    </table>
    </form>
  </div>
</body>
</html>
