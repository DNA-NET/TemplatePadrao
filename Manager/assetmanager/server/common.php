<?php

function is_image($ext) {
    global $__IMAGE_FILES;
    return in_array($ext, $__IMAGE_FILES);
}

function get_thumbnail($folder, $f) {
    
    //check if thumbnail exists
    $base_folder = XF_ASSET_BASE . $folder;
    $thm_folder = $base_folder . "/" . XF_AUTO_THUMB_NAME;
    
    if(file_exists("$thm_folder/$f")) {
        return XF_ASSET_BASE_VIRTUAL . "$folder/" . XF_AUTO_THUMB_NAME . "/$f";
    } else {
        if(XF_AUTO_THUMB_CREATION === true) {
            //auto create thumb
            if(!file_exists($thm_folder)) {
                if(mkdir($thm_folder, 0777)) chmod($thm_folder, 0777);
            }
            $thumbnail = create_thumbnail_image($folder, $f);
        } else {
            $thumbnail = XF_ASSET_BASE_VIRTUAL . "/$folder/$f";
        }
        return $thumbnail;
    }
      
}

function create_thumbnail_image($folder, $f) {
    
    $base_folder = XF_ASSET_BASE . $folder;
    $thm_folder = $base_folder . "/" . XF_AUTO_THUMB_NAME;
    
    $imgResize = new XFImageResize($base_folder . "/$f");
    $imgResize->resize(XF_AUTO_THUMB_MAX_WIDTH, XF_AUTO_THUMB_MAX_HEIGHT);
    $imgResize->save($thm_folder . "/$f");
    
    $thumbnail = XF_ASSET_BASE_VIRTUAL . "$folder/" . XF_AUTO_THUMB_NAME . "/$f";
    
    return $thumbnail;
}

class XFImageResize {
    
    var $width;
    var $height;
    var $resizedImg;
    var $image;
    
    function __construct($fname) {
        
        $this->width = 0;
        $this->height = 0;
        $this->image = $this->open($fname);
        
    }
    
    function open($fname) {
        
        $info = pathinfo($fname);
        
        $ext = "";
		
		$ty = exif_imagetype($fname);
		if($ty===false) {
			$ext = strtolower(isset($info["extension"]) ? $info["extension"] : "");
		} else {
			if($ty == IMAGETYPE_JPEG) {
				$ext = "jpg";
			} else if($ty == IMAGETYPE_GIF) {
				$ext = "gif";
			} else if($ty == IMAGETYPE_PNG) {
				$ext = "png";
			}
		}
        
        $img = null;
        
        switch($ext) {
            case 'jpg':
            case 'jpeg':
                $img = @imagecreatefromjpeg($fname);
                break;
            case 'gif':
                $img = @imagecreatefromgif($fname);
                break;
            case 'png':
                $img = @imagecreatefrompng($fname);
                break;
            default:
                $img = false;
                break;
        }
        
        if($img) {
            $this->width = imagesx($img);
            $this->height = imagesy($img);
        }
        
        return $img;
    }
    
    function resize($maxWidth, $maxHeight) {
        
        if(!$this->image) return false;
            
        $ratio = $this->width/$this->height;
        
        if($ratio<=1) {
            //portrait
            $newWidth = $maxWidth;
            $newHeight = $newWidth/$ratio;
        } else {
            //landscape
            $newHeight = $maxHeight;
            $newWidth = $newHeight*$ratio;
        }
        
        $this->resizedImg = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($this->resizedImg, false);
        imagesavealpha($this->resizedImg, true);

		imagecopyresampled($this->resizedImg, $this->image, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);
        
        return true;
    }
    
    function save($fname) {
        
        if(!$this->image) return false;
        
        $quality = XF_AUTO_THUMB_QUALITY;
        $info = pathinfo($fname);
        $ext = strtolower(isset($info["extension"]) ? $info["extension"] : "");

        switch($ext) {
            case 'jpg':
            case 'jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->resizedImg, $fname, $quality);
                }
                break;

            case 'gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->resizedImg, $fname);
                }
                break;

            case 'png':

                $pngQuality = 9 - round(($quality/100) * 9);

                if (imagetypes() & IMG_PNG) {
                     imagepng($this->resizedImg, $fname, $pngQuality);
                }
                break;

            default:

                break;
        }

        imagedestroy($this->resizedImg);
        
        return true;
    }
    
}

?>
