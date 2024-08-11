<?php
include 'song_tree.php';

// get details of the mp3 file
$fileTempPath = $_FILES['mp3_file']['tmp_name'];
$fileName = $_FILES['mp3_file']['name'];

$fileNameSplit = explode(".", $fileName);
$fileExtension = strtolower(end($fileNameSplit));

if ($fileExtension === 'mp3') {
    
    $fileStoreDirectry = 'uploads/mp3/';
    $destination_path = $fileStoreDirectry . $fileName;

    move_uploaded_file($fileTempPath, $destination_path);

    $conn = new mysqli('localhost', 'root', '', 'song_db');
        
    if ($conn->connect_error) {
        die("Database Connection Failed");
    }

    $insertQuery = $conn->prepare("INSERT INTO songs (file_name, file_path, preference, date_added) VALUES (?, ?, ?, ?)");
    $insertQuery->bind_param("ssis", $fileName, $destination_path, $preference, $dateAdded);

    $preference = $_POST['preference'];
    $dateAdded = date("Y-m-d H:i:s");
    $insertQuery->execute();
    $insertQuery->close();
    $conn->close();

    $bst = new SongBST();
    $bst->insert($fileName, $preference, $dateAdded, $destination_path);
    
} else {
    echo 'Uploaded file is not a mp3 file. Check Again!';
}

?>
