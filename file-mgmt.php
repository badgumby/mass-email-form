<?php
// Uploads directory
$dir = "uploads/";

// Create array of files from directory (exclude .., ., and .gitignore)
$files = array_diff(scandir($dir, 2), array('..','.','.gitignore'));

?>
<html>
<head>
  <title>Manage Files</title>
  <link rel="stylesheet" type="text/css" href="resources/styles.css">
</head>
<body style="background-color:#498cd0;">
  <div class="viewDiv">
  <div class="fileDiv">
    <script type="text/javascript">
      function close_window() {
        if (confirm("Don't upload/delete files and close window?")) {
          close();
        }
      }
    </script>
    <h3><u>Upload Files</u></h3>
    Select file(s) to upload: <br><br>
    <form action="upload.php" method="post" enctype="multipart/form-data">
      <input type="file" multiple="multiple" name="upload[]" required="required">
      <br><br>
          <input type="submit" value="Upload" name="submit"></td>
    </form>
  </div>
  <div class="fileDiv">
    <h3><u>Delete Files</u></h3>
    Select file(s) to delete: <br><br>
    <form action="delete.php" method="post">
      <select name="files[]" id="files" class="input" type="text" multiple>
        <?php
        foreach($files as $file) {
          echo "<option value='" . $dir . $file . "'>" . $file . "</option>";
        }
        ?>
      </select>
      <br><br>
          <input type="submit" value="Delete" name="delete"></td>
    </form>
  </div>
  <a href="javascript:close_window();">Close this window</a>
</div>
</body>
</html>
