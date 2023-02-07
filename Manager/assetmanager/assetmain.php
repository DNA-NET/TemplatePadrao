<?php include("config.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    
    <script src="jquery-1.9.1.min.js" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="css/xprofile.css" />
    <link rel="stylesheet" type="text/css" href="css/xproupload.css" />
    
    <script src="xprofile_lang.js" type="text/javascript"></script>
    <script src="xprofile.js" type="text/javascript"></script>
    <script src="xproupload.js" type="text/javascript"></script>

    <script src="external/caman/dist/caman.full.js" type="text/javascript"></script>
    
    <script>
        jQuery(document).ready(function() {

            //set readonly, readonly mode, must be called before iniFileManager();
            XPROFile.readonly = <?php print (XF_READ_ONLY==true?"true":"false"); ?>;

            XPROFile.allowDelete = <?php print (XF_ALLOW_DELETE==true?"true":"false"); ?>;
            
            XPROFile.allowRename = <?php print (XF_ALLOW_RENAME==true?"true":"false"); ?>;

            XPROFile.allowEdit = <?php print (XF_ALLOW_EDIT==true?"true":"false"); ?>;

            //set allowed file types, below are default.
            //
            //XPROFile.allowedTypes = ["jpg", "png", "gif", "txt", "jpeg", "zip", "pdf", "doc", "docx"];
            
            //set max upload size, 0 is unlimited, default is 0.
            //This is client limit, upload file size also restricted by 
            //PHP configuration (upload_max_filesize and post_max_size directive in PHP.ini)
            //            
            //XPROFile.maxSize = 0;
        
            XPROFile.iniFileManager();
            
            
        });
    </script>
    
</head>

<body>
    
</body>

</html>
