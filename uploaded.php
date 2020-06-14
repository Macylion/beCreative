<?php
  ob_start();
  session_start();
  if(!isset($_SESSION['isLoged'])){
    header('Location: index.php');
    die();
  }
  $title = $_POST['title'];
  $desc = $_POST['desc'];
  //Valid data
  if(!isset($_POST['title'])){
    header('Location: upload.php?e=1');
    die();
  }
  else{
    if(strlen($_POST['title']) < 1){
      header('Location: upload.php?e=1');
      die();
    }
    $title = str_replace('"', "&quot;", $title);
    $title = str_replace("'", "&apos;", $title);
    $title = str_replace("`", "&#180;", $title);
    $title = str_replace("/", "&#47;", $title);
    $title = str_replace("\\", "&#92;", $title);
    $title = str_replace("<", "&lt;", $title);
    $title = str_replace(">", "&gt;", $title);
    $desc = str_replace('"', "&quot;", $desc);
    $desc = str_replace("'", "&apos;", $desc);
    $desc = str_replace("`", "&#180;", $desc);
    $desc = str_replace("/", "&#47;", $desc);
    $desc = str_replace("\\", "&#92;", $desc);
    $desc = str_replace("<", "&lt;", $desc);
    $desc = str_replace(">", "&gt;", $desc);
  }

//File
$add_str = date("ymdHiss").$_SESSION['yourID']; //additional_string
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "image/PNG"))
&& ($_FILES["file"]["size"] < 20000000)
&& in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

    if (file_exists("C:/xampp/htdocs/img/".$add_str . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "img/".$add_str . $_FILES["file"]["name"]);
      echo "Stored in: " . "C:/xampp/htdocs/img/".$add_str . $_FILES["file"]["name"];
        //Add to db
        require_once("conn.php");
        require_once("lib.php");
        $sql = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
        $sql->query('SET CHARACTER SET utf8');
        //Put data
        $sql->query("INSERT INTO `images` VALUES (NULL,0,'".$title."','".$desc."','".$_SESSION["yourID"]."','".date("Y-m-d H:i:s")."','"."C:/xampp/htdocs/img/".$add_str.$_FILES["file"]["name"]."',0,0)");
        $sql->close();
        //Get data
        $sql = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
        $sql->query('SET CHARACTER SET utf8');
        $res = $sql->query("SELECT `id` FROM `images` WHERE `img_url` = 'C:/xampp/htdocs/img/".$add_str.$_FILES["file"]["name"]."'");
        $r = $res->fetch_row();
        header('Location: img.php?id='.$r[0]);
        $sql->close();
      }
    }
  }
else
  {
  header('Location: error.php?err=Invalid+file');
  }
?>