<?php
if (!empty($_FILES['fileToUpload'])) {
    $target_dir = "import/";
    $target_file = $target_dir . "import.sql"; // Always use the same filename
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allow only SQL file format
    if ($imageFileType != "sql") {
        echo "Nur SQL erlaubt.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Datei wurde nicht hochgeladen.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            ?>
            <meta http-equiv="refresh" content="0; url=import2.php" />
            
            <?php
        } else {
            echo "Datei wurde nicht hochgeladen.";
        }
    }
} else {
    // Display the file upload form
    ?>
    <div class="container">
        <div class="row mt-3">
            <h1>Servicepreise importieren</h1>
        </div>

        <div class="row text-center">
            
            <h4>SQL-Datei auswählen</h4>
            
        </div>

        

        <div class="row text-center">
        
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
            <form action="" method="post" enctype="multipart/form-data">
            
            <input class="mt-3" type="file" name="fileToUpload" id="fileToUpload">
            
            
            <input type="submit" class="btn btn-primary mt-3" value="Importieren" name="submit">
            </div>
            <div class="col-md-4">
            </div>
        </form>
        </div>
    </div>
    <?php
}
?>

