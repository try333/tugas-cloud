<?php 
error_reporting(0);
require_once("config.php");
require_once("auth.php"); 
if(isset($_GET["pdfx"])){
$file = urldecode($_GET["pdfx"]); // Decode URL-encoded string
    $filepath = "pdf_files/" . $file;
    
    // Process download
    if(file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush(); // Flush system output buffer
        readfile($filepath);
        exit;
    }
}
if(isset($_GET['delpdf'])){
    $uid=$_SESSION["user"]["id"];
    // menyiapkan query
    $sql = "DELETE FROM filepdf WHERE user_id='$uid' AND namafile='$_GET[delpdf]' ";
    $stmt = $db->prepare($sql);
    // bind parameter ke query

    // eksekusi query untuk menyimpan ke database
    $saved = $stmt->execute();
    unlink('pdf_files/'.$_GET['delpdf']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload File</title>
    <link href="dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>
<body class="bg-light">
<?php
include('header.php');
?>
<div class="container mt-5">
<button class="btn btn-primary btn-lg" name="upldoc" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-plus"></span>Upload Dokumen</button>

<div>
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Upload Dokumen.</h4>
        </div>
        <div class="modal-body">
          <p>Silahkan upload dokumen anda.</p>
          <form action="upload.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
                <label for="name">Judul Dokumen</label>
                <input class="form-control" type="text" name="judul" placeholder="Judul" />
            </div>
            <div class="form-group">
                <input type="file" name="fileToUpload" id="fileToUpload">
            </div>
            <input type="submit" class="btn btn-success btn-lg" name="btn_upload" value="Upload" />
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-3">
  <form class="navbar-form" role="search" method="GET">
    <div class="input-group add-on">
      <input class="form-control" placeholder="Cari" name="srchpdf" id="srch-term" type="text">
      <div class="input-group-btn">
        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
      </div>
    </div>
  </form>
    </div>
    <?php
    if(isset($_GET['srchpdf'])){
        $tableheader = false;
    $uid=$_SESSION["user"]["id"];
    $query = "SELECT * FROM filepdf WHERE user_id='$uid' AND judul LIKE '%".$_GET['srchpdf']."%' ORDER BY id DESC";
    $sth = $db->prepare($query);

    if(!$sth->execute()) {
        die('Error');
    }

    echo "<table width='100%' class='table table-hover'>";
        echo "<tr>
        <thead>
        <th>#</th>
        <th width='60%'>JUDUL</th>
        <th>ACTION</th>
        </thead>
        </tr><tbody>";
    $i=1;
    while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
        
        echo "<tr><td>".$i."</td>
            <td>".$row['judul']."</td><td><a href='?pdfx=".$row['namafile']."'><span class='btn btn-success'>Download</span></a>
                <a target='_blank' href='viewpdf.php?vwpdf=".$row['namafile']."'><span class='btn btn-primary'><span class=''>View</span></span></a>
                <a href='?delpdf=".$row['namafile']."'><span class='btn btn-danger'>Delete</span>

            </td>
        </tr>";
        $i++;
    }
    echo "</tbody></table>";
}else{
    $tableheader = false;
    $uid=$_SESSION["user"]["id"];
    $query = "SELECT * FROM filepdf WHERE user_id='$uid' ORDER BY id DESC";
    $sth = $db->prepare($query);

    if(!$sth->execute()) {
        die('Error');
    }

    echo "<table width='100%' class='table table-hover'>";
        echo "<tr>
        <thead>
        <th>#</th>
        <th width='60%'>JUDUL</th>
        <th>ACTION</th>
        </thead>
        </tr><tbody>";
    $i=1;
    while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
        
        echo "<tr><td>".$i."</td>
            <td>".$row['judul']."</td><td><a href='?pdfx=".$row['namafile']."'><span class='btn btn-success'>Download</span></a>
                <a target='_blank' href='viewpdf.php?vwpdf=".$row['namafile']."'><span class='btn btn-primary'><span class=''>View</span></span></a>
                <a href='?delpdf=".$row['namafile']."'><span class='btn btn-danger'>Delete</span>

            </td>
        </tr>";
        $i++;
    }
    echo "</tbody></table>";
}    ?>
</div>
</div>

</div>
            <!--?php for($i=0; $i < 6; $i++){ ?>
            <div class="card mb-3">
                <div class="card-body">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis veritatis nemo ad recusandae labore nihil iure qui eum consequatur, officiis facere quis sunt tempora impedit ullam reprehenderit facilis ex amet!
                </div>
            </div>
            <--?php } ?>-->
            

</div>

<script src="dist/js/bootstrap.min.js"></script>
</body>
</html>