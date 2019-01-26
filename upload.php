<?php
require_once("config.php");
require_once("auth.php");

    $uid=$_SESSION["user"]["id"];
    $jdl=$_POST['judul'];
    $sql = "SELECT count(*) FROM filepdf WHERE user_id='$uid' AND judul='$jdl' "; 
    $result = $db->prepare($sql); 
    $result->execute(); 
    $number_of_rows = $result->fetchColumn(); 
    if($number_of_rows!=0){
        echo "<script>alert('Judul tidak boleh sama.');window.location='uppdf.php'</script>";
    }
    else{

$target_dir = "pdf_files/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($target_file)) {
    echo "<script>alert('File sudah ada.');window.location='uppdf.php'</script>";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "pdf") {
    echo "<script>alert('Format file tidak didukung.');window.location='uppdf.php'</script>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<script>alert('Upload Gagal');window.location='uppdf.php'</script>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $_SESSION["user"]["id"].$_POST['judul'].".".strtolower(pathinfo($target_file,PATHINFO_EXTENSION)))) {
        // filter data yang diinputkan
    $judul = filter_input(INPUT_POST, 'judul', FILTER_SANITIZE_STRING);
    $namafile = $_SESSION["user"]["id"].$_POST['judul'].".".strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $uid = $_SESSION["user"]["id"];


    // menyiapkan query
    $sql = "INSERT INTO filepdf (judul, namafile, user_id) 
            VALUES (:judul, :namafile, :user_id)";
    $stmt = $db->prepare($sql);

    // bind parameter ke query
    $params = array(
        ":judul" => $judul,
        ":namafile" => $namafile,
        ":user_id" => $uid
    );

    // eksekusi query untuk menyimpan ke database
    $saved = $stmt->execute($params);
        rename($_SESSION["user"]["id"].$_POST['judul'].".".strtolower(pathinfo($target_file,PATHINFO_EXTENSION)), 'pdf_files/'.$_SESSION["user"]["id"].$_POST['judul'].".".strtolower(pathinfo($target_file,PATHINFO_EXTENSION)));
        echo "<script>alert('Upload Berhasil');window.location='uppdf.php'</script>";
    } else {
        echo "<script>alert('Upload Gagal');window.location='uppdf.php'</script>";
        
    }
}
}
?>