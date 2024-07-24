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
       
       echo '<div class="alert alert-warning d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
              File is not an image
                    </div>';
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
    echo '<div class="alert alert-warning d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
            Sorry, your file is too large
                    </div>';
     //echo "Sorry, your file is too large.";
     $uploadOk = 0;
   }
   
   // Allow certain file formats
   if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
   && $imageFileType != "gif" ) {
     //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
     echo '<div class="alert alert-warning d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
        Sorry, only JPG, JPEG, PNG & GIF files are allowed
                    </div>';
     $uploadOk = 0;
   }
   
   // Check if $uploadOk is set to 0 by an error
   if ($uploadOk == 0) {
     //echo "Sorry, your file was not uploaded.";
     echo '<div class="alert alert-warning d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
              Sorry, your file was not uploaded
                    </div>';
   // if everything is ok, try to upload file
   } else {
     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
       //echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
       echo '<div class="alert alert-success d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                        Your file has been uploaded
                    </div>';
     } else {
       //echo "Sorry, there was an error uploading your file.";
       echo '<div class="alert alert-warning d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
        Sorry, there was an error uploading your file
                    </div>';
     }
   }
echo '<script>
        setTimeout(function() {
            document.getElementById("alert").remove();
        }, 4000);
      </script>';