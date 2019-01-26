<?php 
require_once("config.php");
require_once("auth.php"); 

$uid=$_SESSION["user"]["id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <link href="dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>
<body>
<?php include('header.php');?>
<div class="container">
<div class="col-sm-12">
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
</div>
<hr class="col-sm-12">
<?php
if(isset($_GET['srchpdf'])){
        $limit = 24;
$query = "SELECT * FROM filepdf WHERE user_id!='$uid' AND judul LIKE '%".$_GET['srchpdf']."%' ";

$s = $db->prepare($query);
$s->execute();
$total_results = $s->rowCount();
$total_pages = ceil($total_results/$limit);

if (!isset($_GET['page'])) {
    $page = 1;
} else{
    $page = $_GET['page'];
}



$starting_limit = ($page-1)*$limit;
$show  = "SELECT * FROM filepdf WHERE user_id!='$uid' AND judul LIKE '%".$_GET['srchpdf']."%' ORDER BY id DESC LIMIT $starting_limit, $limit";

$r = $db->prepare($show);
$r->execute();

while($res = $r->fetch(PDO::FETCH_ASSOC)):

//QUERY SELECT NAMA
    $sqln="SELECT name FROM users WHERE users.id='$res[user_id]' ";
    $rn=$db->prepare($sqln);

    $rn->execute();

    $nnn = $rn->fetch(PDO::FETCH_ASSOC);
?>

<div class="col-sm-3">
<div class="panel panel-default">
    <div class="panel-body">
<a target="_blank" href="viewpdf.php?vwpdf=<?php echo $res['namafile']; ?>"><h3><?php echo "<b>".$res['judul']."</b>";?></h3></a>
<p><?php echo "by : <i>". $nnn['name']."</i>";?></p>
</div>
</div>
</div>
<?php
endwhile;

echo "<div style='text-align: center;' class='col-sm-12'><hr>";
for ($page=1; $page <= $total_pages ; $page++):?>

<a href='<?php echo "?page=$page&srchpdf=$_GET[srchpdf]"; ?>' class="btn btn-primary"><?php  echo $page; ?>
 </a>

<?php endfor; ?>

<?php
}else{

    $limit = 24;
$query = "SELECT * FROM filepdf WHERE user_id!='$uid'";

$s = $db->prepare($query);
$s->execute();
$total_results = $s->rowCount();
$total_pages = ceil($total_results/$limit);

if (!isset($_GET['page'])) {
    $page = 1;
} else{
    $page = $_GET['page'];
}



$starting_limit = ($page-1)*$limit;
$show  = "SELECT * FROM filepdf WHERE user_id!='$uid' ORDER BY id DESC LIMIT $starting_limit, $limit";

$r = $db->prepare($show);
$r->execute();

while($res = $r->fetch(PDO::FETCH_ASSOC)):

//QUERY SELECT NAMA
    $sqln="SELECT name FROM users WHERE users.id='$res[user_id]' ";
    $rn=$db->prepare($sqln);

    $rn->execute();

    $nnn = $rn->fetch(PDO::FETCH_ASSOC);
?>


<div class="col-sm-3">
<div class="panel panel-default">
    <div class="panel-body">
<a target="_blank" href="viewpdf.php?vwpdf=<?php echo $res['namafile']; ?>"><h3><?php echo "<b>".$res['judul']."</b>";?></h3></a>
<p><?php echo "by : <i>". $nnn['name']."</i>";?></p>
</div>
</div>
</div>
<?php
endwhile;

echo "<div style='text-align: center;' class='col-sm-12'><hr>";
for ($page=1; $page <= $total_pages ; $page++):?>

<a href='<?php echo "?page=$page"; ?>' class="btn btn-primary"><?php  echo $page; ?>
 </a>

<?php endfor; 
}?>
</div>
</div>

<script src="dist/js/bootstrap.min.js"></script>
</body>
</html>