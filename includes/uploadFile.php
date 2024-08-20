<?php
$target_dir = "uploads/";
   //$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
   $filename = basename($_FILES["fileToUpload"]["name"]);
  $target_file = $target_dir. $filename;
   if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
  }
   $uploadOk = 1;
   $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
   
   // Check if image file is a actual image or fake image
   if(isset($_POST["submit"])) {
     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
     if($check !== false) {
       //echo "File is an image - " . $check["mime"] . ".";
       $uploadOk = 1;
     } else {
       //echo "File is not an image.";
       create_Alert('warning', 'File is not an image');
       $uploadOk = 0;
     }
   }
   
    // Check if the file already exists
    if (file_exists($target_file)) {
      // If the file exists, modify the filename to make it unique
      $filename_parts = explode('.', $filename);
      $filename_base = $filename_parts[0];
      $filename_ext = $filename_parts[1];
      $counter = 1;
      $new_filename = $filename_base. '_'. $counter. '.'. $filename_ext;
      $new_target_file = $target_dir. $new_filename;
      while (file_exists($new_target_file)) {
          $counter++;
          $new_filename = $filename_base. '_'. $counter. '.'. $filename_ext;
          $new_target_file = $target_dir. $new_filename;
      }
      $target_file = $new_target_file;
    } else {
      // If the file doesn't exist, we can proceed with the upload
      $uploadOk = 1;
    }

   // Check file size
   if ($_FILES["fileToUpload"]["size"] > 5000000) {
    create_Alert('warning', 'File is too large');
     //echo "Sorry, your file is too large.";
     $uploadOk = 0;
   }
   
   // Allow certain file formats
   if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
   && $imageFileType != "gif" ) {
     //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
     create_Alert('warning', 'Only JPG, JPEG, PNG & GIF files are allowed');
     $uploadOk = 0;
   }
   
   // Check if $uploadOk is set to 0 by an error
   if ($uploadOk == 0) {
     //echo "Sorry, your file was not uploaded.";
     create_Alert('warning', 'Sorry, your file was not uploaded');
   // if everything is ok, try to upload file
   } else {
     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
       //inform user that the image file has been uploaded
       create_Alert('success', 'Your file has been uploaded');
     } else {
       //echo "Sorry, there was an error uploading your file.";
       create_Alert('warning', 'Sorry, there was an error uploading your file');
     }
   }