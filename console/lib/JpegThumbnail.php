<?php
class JpegThumbnail
{
    public $width;  // maximum thumbnail width
    public $height; // maximum thumbnail height

    // intialize a new Thumbnail object
    public function __construct($width = 50, $height = 50)
    {
        $this->width = $width;
        $this->height = $height;
        
    }
    // accept a source file location and return an open image handle or
    // save to disk if destination provided 
    public function generate($src, $dest = '')
    {
        //print($src);
        // retrive image dimensions
        list($width, $height) = getimagesize($src);
        
        //print($width);
        // determine if resize is necessary
        if(($lowest = min($this->width / $width, $this->height / $height)) < 1)
        {
            $tmp = imagecreatefromjpeg($src);

            // resize
            $sm_width = floor($lowest * $width);
            $sm_height = floor($lowest * $height);
            $img = imagecreatetruecolor($sm_width, $sm_height);
            imagecopyresized($img, $tmp, 0,0, 0,0, $sm_width, $sm_height,
                $width, $height);
            imagedestroy($tmp);
            $finimg = new SaeImage();
            $finimg->setData($img);
        }
        // image is already thumbnail size and resize not necessary
        else
        {
            $img = imagecreatefromjpeg($src);   
            $finimg = new SaeImage();
            $finimg->setData($img);
        }
        
        // save to disk or return the open image handle
        if ($dest)
        {
            $s = new SaeStorage();
            $s->write('smallform',$dest,$finimg->exec());
                //imagejpeg($img, $dest, 100);
            imagedestroy($img);
        }
        else
        {
            return $img;
        }
    }
}
?>
