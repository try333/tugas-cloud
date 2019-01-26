<?php  
include("config.php");
if(isset($_POST['chgconfirm'])){
    session_start();
    $name = $_POST['name'];
    $email = $_POST['email'];

    $uidx=$_SESSION["user"]["id"];
     $sql2 = "UPDATE users SET name=:name, email=:email WHERE id='$uidx' ";
    $stmt2 = $db->prepare($sql2);
    $params2 = array(
        ":name" => $name,
        ":email" => $email
    );   
    // bind parameter ke query

    $stmt2->execute($params2);

    $sql = "SELECT * FROM users WHERE id='$uidx'";
    $stmt = $db->prepare($sql);
    
    // bind parameter ke query
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION["user"] = $user;
if($_FILES['pic']['size'] != 0){
        $target_dir = "img/";
$target_file = $target_dir .basename($_FILES["pic"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

/*$check = getimagesize($_FILES["pic"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
*/
// Check file size
if ($_FILES["pic"]["size"] > 500000) {
    echo "<script>alert('Foto tidak boleh di atas 5MB');window.location='dashboard.php'</script>";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
   echo "<script>alert('Format file harus jpg, jpeg, png');window.location='dashboard.php'</script>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<script>alert('Maaf file gagal diupload');window.location='dashboard.php'</script>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["pic"]["tmp_name"], $uidx."-".$_FILES["pic"]["name"])) {
        unlink('img/'.$_SESSION['user']['photo']);
        $pic = $uidx."-".$_FILES["pic"]["name"];
        rename($uidx."-".$_FILES["pic"]["name"], 'img/'.$uidx."-".$_FILES["pic"]["name"]);

        $idxx=$_SESSION["user"]["id"];
     $qpic = "UPDATE users SET photo='$pic' WHERE id='$idxx' ";
    $spic = $db->prepare($qpic);
    // bind parameter ke query
    $spic->execute();

    $sqlp = "SELECT * FROM users WHERE id='$idxx'";
    $stmtp = $db->prepare($sqlp);
    
    // bind parameter ke query
    $stmtp->execute();
    $userp = $stmtp->fetch(PDO::FETCH_ASSOC);
    $_SESSION["user"] = $userp;
    echo "<script>alert('Foto berhasil diupload');window.location='dashboard.php'</script>";
    } else {
        echo "<script>alert('Upload foto gagal!');window.location='dashboard.php'</script>";
    }
}}

}else{
require_once("auth.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body class="bg-light">
<?php
include('header.php');
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">

            <div class="panel panel-default">
                <div class="panel-body" style="text-align: center;">
                    <img class="img img-circle" width="160px" src="img/<?php echo $_SESSION['user']['photo'] ?>" />
                    <input id="profile-image-upload" class="hidden" type="file">
                    <hr>
                    <h3><?php echo  $_SESSION["user"]["name"] ?></h3>
                    <p><?php echo $_SESSION["user"]["email"] ?></p>

                    <p><a href="logout.php">Logout</a></p>
                </div>
            </div>

            
        </div>

        <div class="col-md-8">
            <div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div>
                            <u><font size="5">Nama</font></u>
                        </div>
                        <div>
                            <b><font size="5"><?php echo  $_SESSION["user"]["name"] ?></font></b>
                        </div>
                        <hr>
                        <div>
                            <u><font size="5">Username</font></u>
                        </div>
                        <div>
                            <b><font size="5"><?php echo  $_SESSION["user"]["username"] ?></font></b>
                        </div>
                        <hr>
                        <div>
                            <u><font size="5">Email</font></u>
                        </div>
                        <div>
                            <b><font size="5"><?php echo  $_SESSION["user"]["email"] ?></font></b>
                        </div>
                        <hr>
                            <button type="button" class="btn" data-toggle="modal" data-target="#myModal">Ubah Profil</button>
                    </div>
                </div>
                 <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Ubah Profil</h4>
      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
    <label for="name">Nama</label>
    <input type="text" name="name" class="form-control" id="name" value="<?php echo  $_SESSION['user']['name'] ?>">
    </div>
    <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" name="email" class="form-control" id="email" value="<?php echo  $_SESSION['user']['email'] ?>">
    </div>
    <div class="form-group">
    <label for="pic">Foto:</label>
    <input type="file" name="pic" class="form-control" id="pic">
    </div>
    <input type="submit" name="chgconfirm" class="btn btn-default" value="Konfirmasi">
    </form>
      </div>
    </div>

  </div>
</div>
            </div>

        </div>
    
    </div>
</div>

</body>
</html>