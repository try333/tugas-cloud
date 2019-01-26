<?php 
require_once("config.php");
require_once("auth.php"); 

if(isset($_GET['vwpdf'])){
$vwpdf='pdf_files/'.$_GET['vwpdf'];
}
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
    <style type="text/css">
    	html,body{height:100%;}
.m100 {
    height:100%;
    min-height:100%;
}
    </style>
</head>
<body>
<?php include('header.php');

if(isset($_GET['vwpdf'])){
	
?>

<div class="panel panel-default m100">
	<div class="panel-body m100">
<embed src="<?php echo $vwpdf; ?>" width="100%" height="100%"></embed>
</div>
</div>
<?php } else{
echo "<h1>File tidak ditemukan.</h1>"; 
}?>

<script src="dist/js/bootstrap.min.js"></script>
</body>
</html>