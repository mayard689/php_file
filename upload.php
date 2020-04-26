<?php

    $maxFileSize=1048576;
    $acceptedTypes=["image/jpg", "image/gif", "image/png"];

    if ($_SERVER["REQUEST_METHOD"]=="POST") {

        if (!empty($_POST['action'])) {
            if (!empty($_POST['file'])) {
                if (file_exists($_POST['file'])) {
                    unlink($_POST['file']);
                }

            }
        }


        if (!empty($_FILES['myFiles'])) {

            for ($i=0; $i<count($_FILES['myFiles']['name']) ; $i++) {

                $fileName=$_FILES['myFiles']['name'][$i];
                $fileTmpName=$_FILES['myFiles']['tmp_name'][$i];
                $fileType=$_FILES['myFiles']['type'][$i];
                $fileSize=$_FILES['myFiles']['size'][$i];
                $fileError=$_FILES['myFiles']['error'][$i];

                if (0==$fileError) {
                    if ($fileSize>$maxFileSize) {
                        $errorMessage="Le fichier $fileName dépasse la taille maximale de $maxFileSize";
                        var_dump($errorMessage);
                    } elseif (!in_array($fileType, $acceptedTypes)) {
                        $errorMessage="Le type du fichier $fileType n'est pas dans la liste :".implode(",", $acceptedTypes) ;
                        var_dump($errorMessage);
                    } else {
                        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $fileName = uniqid() . '.' .$extension;
                        move_uploaded_file($fileTmpName, "upload/".$fileName);
                    }
                }
            }
        }
    }

?>



<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Inscrire un titre ici</title>
    <!-- On peut avoir d'autres méta-données ici -->
  </head>
  <body>



        <form action="" method="post" enctype="multipart/form-data" accept="image/jpg, image/gif, image/png ">

            <input type="hidden" name="MAX_FILE_SIZE" value="<?=$maxFileSize?>>" />

            <label for="imageUpload">Fichier à télécharger</label>
            <input type="file" name="myFiles[]" multiple="multiple" id="imageUpload" />

            <button>Envoyer</button>
        </form>




        <?php $path=__DIR__."/upload";
        $it = new FilesystemIterator($path);
        foreach ($it as $fileinfo) {
           $fileName="upload/".$fileinfo->getFilename();
             ?>

            <figure style="width:250px; text-align:center; border:thin solid pink">
                <img src="<?="/./".$fileName?>" alt="<?='image nommée '.$fileName?>" style="width:100px">
                <figcaption> <?=$fileName?> </figcaption>
                <form method="post" action="">
                    <input type="hidden" name="file" value="<?=$fileName?>">
                    <input type="submit" name="action" value="Supprimer">
                </form>
            </figure>
        <?php } ?>




  </body>
</html>