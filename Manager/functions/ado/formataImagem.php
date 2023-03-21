<?php



function formataImagem($imagem, $largura, $altura)
{

    // Get the details of "Institucional_imagem_pq"
    $filename = $_FILES[$imagem]['name'];
    $temporary_name = $_FILES[$imagem]['tmp_name'];
    $mimetype = $_FILES[$imagem]['type'];
    $filesize = $_FILES[$imagem]['size'];

    //Open the image using the imagecreatefrom..() command based on the MIME type.
    switch ($mimetype) {
        case "image/jpg":
        case "image/jpeg":
            $i = imagecreatefromjpeg($temporary_name);
            break;

        case "image/gif":
            $i = imagecreatefromgif($temporary_name);
            break;

        case "image/png":
            $i = imagecreatefrompng($temporary_name);
            break;
    }

    //Delete the uploaded file
    unlink($temporary_name);

    //Save a copy of the original
    //imagejpeg($i,"images/uploadedfile.jpg",80);

    //Specify the size of the thumbnail
    $dest_x = $largura;
    $dest_y = $altura;

    //Is the original bigger than the thumbnail dimensions?
    if (imagesx($i) > $dest_x or imagesy($i) > $dest_y) {
        //Is the width of the original bigger than the height?
        if (imagesx($i) >= imagesy($i)) {
            $thumb_x = $dest_x;
            $thumb_y = imagesy($i) * ($dest_x / imagesx($i));
        } else {
            $thumb_x = imagesx($i) * ($dest_y / imagesy($i));
            $thumb_y = $dest_y;
        }
    } else {
        //Using the original dimensions
        $thumb_x = imagesx($i);
        $thumb_y = imagesy($i);
    }

    //Generate a new image at the size of the thumbnail
    $thumb = imagecreatetruecolor($thumb_x, $thumb_y);

    //Copy the original image data to it using resampling
    imagecopyresampled($thumb, $i, 0, 0, 0, 0, $thumb_x, $thumb_y, imagesx($i), imagesy($i));

    //Save the thumbnail
    //imagejpeg($thumb, "images/thumbnail.jpg", 80);

    ob_start();
    imagejpeg($thumb, NULL, 80); //IF SECOND PARAMETER SET TO NULL, OUTPUTS TO THE BROWSER
    $contents = ob_get_contents(); //SAVE $content IN DATABASE
    // end capture
    ob_end_clean();

    return $contents;
}