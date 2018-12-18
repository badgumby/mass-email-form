<html>
<head>
  <title>Delete Files</title>
  <link rel="stylesheet" type="text/css" href="resources/styles.css">
</head>
<body class="mass-notify">
  <script type="text/javascript">
  function close_window() {
    if (confirm("Close window?")) {
      close();
    }
  }
  </script>
<div class="mainDiv">
<?php
echo '<pre>';
$files = $_POST['files'];

foreach ($files as $file) {
  try {
    unlink($file);
    echo $file . " has been deleted.<br>";
  } catch (Exception $e) {
    echo "File delete failed. <br> $e <br>";
  }
}

?>
<br>
<a href="file-mgmt.php">Delete more...</a><br>
<a href="javascript:close_window();">Close this window</a>
</div>
</body>
</html>
