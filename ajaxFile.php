<?php
// Count total files
$countfiles = count($_FILES['files']['name']);
$idMolde = strval($_GET['idMolde']);

// Upload Location ================================================
$upload_location = "pedidos/".$idMolde."/";

//varificando se a pasta existe (por que as vezes buga :'(')=======
if (!file_exists($upload_location)) {
    mkdir($upload_location, 0777, true);
}



// To store uploaded files path
$files_arr = array();

// Loop all files
for($index = 0;$index < $countfiles;$index++){
     if(isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != ''){
           // File name
           $filename = $_FILES['files']['name'][$index];

           // Get extension
           $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

           // Valid image extension
           $valid_ext = array("png","jpeg","jpg","pdf");

           // Check extension
           if(in_array($ext, $valid_ext)){

                // File path
                $path = $upload_location.$filename;

                // Upload file
                if(move_uploaded_file($_FILES['files']['tmp_name'][$index],$path)){
                      $files_arr[] = $path;
                }
           }
     }
}
echo json_encode($files_arr);
die;
?>