<html>
<head>
  <title>Upload Files</title>
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
$myFile = $_FILES['upload'];
$target_dir = "uploads/";

if(!empty($myFile))
{
    $myFile_desc = reArrayFiles($myFile);
    //print_r($myFile_desc);

    foreach($myFile_desc as $val)
    {
      $target_file = $target_dir . str_replace(' ', '-', $val['name']);
      if (file_exists($target_file)) {
        echo "Sorry, a file with that name already exists. <br>";
      } else {
        //$newname = date('YmdHis',time()).mt_rand().'.jpg';
        move_uploaded_file($val['tmp_name'],$target_file);
        echo "File " . $val['name'] . " uploaded successfully. <br>";
    }
  }
}

function reArrayFiles($file)
{
    $file_ary = array();
    $file_count = count($file['name']);
    $file_key = array_keys($file);

    for($i=0;$i<$file_count;$i++)
    {
        foreach($file_key as $val)
        {
            $file_ary[$i][$val] = $file[$val][$i];
        }
    }
    return $file_ary;
}
?>
<br>
<a href="file-mgmt.php">Upload more...</a><br>
<a href="javascript:close_window();">Close this window</a>
</div>
</body>
</html>
