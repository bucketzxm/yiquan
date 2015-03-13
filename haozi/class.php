<?php
$data = file_get_contents ( realpath ( "b.jpg" ) );
/* Read the image */
$im = new Imagick ();

// $im->newImage();
$im->readImageBlob ( $data );
$im->setImageFormat ( 'jpg' );

// $im->thumbnailImage(100,0);
// $im->writeImageFile ( fopen ( "b.jpg", "w" ) );

/* Output the image*/
// header ( "Content-Type: image/jpg" );
var_dump ( base64_encode ( $im->getImagesBlob () ) );
?>