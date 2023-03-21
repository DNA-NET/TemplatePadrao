<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>InnovaStudio Asset Manager 2.0</title>
    
    <script src="jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="xprofile_lang.js" type="text/javascript"></script>
    <script src="xprofiledialog.js" type="text/javascript"></script>
    
    <link rel="stylesheet" href="css/xprofiledialog.css" />
    
    <style>
        
        body {
            font-family: sans-serif, helvetica, arial;
            font-size: 1em;
            width: 900px;
            margin: 10px auto;
        }
        
        input {
            padding: 6px 8px;
        }
        
        h1 { text-transform: uppercase; }
        
        h3 { margin-top: 40px; margin-bottom:30px; text-transform: uppercase;}
        hr {
            border: none;
            border-bottom: #d8d8d8 1px solid;            
        }
        
        pre {
            border: #d8d8d8 1px solid;
            background-color: #fefefe;
            display: block;
            padding: 20px;
            font-size: 12px;
            font-family: courier;
        }
        
        .linkbutton {
            padding: 15px 30px;
            border-radius: 6px;
            display: inline-block;
            background-color: #32a3a7;
            color: #ffffff;
            text-decoration: none;            
        }
    </style>
    
    <script>
        jQuery(document).ready(function() {
            
            jQuery(".file-select-button").xproFileDialog( {} );
            
            jQuery("#selectFile").xproFileDialog({
                onSelect: function(data) {
                    jQuery("#selFileName").html(data.url);
                }
            });
            
        });
        
    </script>
</head>

<body>
    <h1>InnovaStudio Asset Manager</h1>
    <hr />
    
    <h3>Install</h3>
    <p>Unzip files to your website. Requirements: </p>
    <ul>
        <li>PHP 5+</li>
        <li>Enable GD extension in php.ini</li>
    </ul>
    
    <h3>Configuration</h3>
    <p>Set asset base folder and asset base virtual in config.php.</p>
    
    <pre>
    //asset base - local path, ***NO TRAILING SLASH***
    define("XF_ASSET_BASE", 'D:\mywebsite\assets\images');

    //asset base - virtual path, for displaying on page or link
    //use relative to website root path (starts with slash - /).

    define("XF_ASSET_BASE_VIRTUAL", '/assets/images');
    </pre>
    
    <p>You can configure other options in config.php if needed.</p>

    <h3>Script Include</h3>
    
    <p>Include scripts files in your page:</p>
    <pre>
    &lt;script src="path-to/jquery-1.9.1.min.js" type="text/javascript"&gt;&lt;/script&gt;
    &lt;script src="path-to/xprofile_lang.js" type="text/javascript"&gt;&lt;/script&gt;
    &lt;script src="path-to/xprofiledialog.js" type="text/javascript"&gt;&lt;/script&gt;
    
    &lt;link rel="stylesheet" href="path-to/css/xprofiledialog.css" /&gt;    
    </pre>    
    
    <h3>Usage</h3>
    
    <h4>Usage #1: Return selected file into textbox</h4>
    <input id="filename" type="text" size="40" value="" />
    <input type="button" class='file-select-button' data-target-input='filename' value="Select" />
    
    <br /><br />
    <input id="filename2" type="text" size="40" value="" />
    <input type="button" class='file-select-button' data-target-input='filename2' value="Select" />

    <br /><br />
    <pre>    
    &lt;input id="filename" type="text" size="40" value="" /&gt;
    &lt;input type="button" class='file-select-button' data-target-input='filename' value="Select" /&gt;
    
    jQuery(document).ready(function() {
        jQuery(".file-select-button").xproFileDialog( {} );
    }); 
    </pre>
    
    <br />
    <h4>Usage #2. Integrate to ContentBuilder</h4>
    
    <p>Integrate InnovaStudio Asset Manager to ContentBuilder to select image or file from server (<strong>requires ContentBuilder v1.8.3 or newer</strong>). </p>
    <p><img src="demo-cb.jpg" /></p>
    
    <pre>
        $("#contentarea").contentbuilder({
            snippetFile: 'assets/default/snippets.html',

            onImageSelectClick: function(selEv) {
                dlg = new XPROFileDialog({
                    url: "pathto/assetmain.php",
                    onSelect: function(data) {					
                        var inp = jQuery(selEv.targetInput).val(data.url);
                    }
                });
                dlg.open();
            },

            onFileSelectClick: function(selEv) {
                dlg = new XPROFileDialog({
                    url: "pathto/assetmain.php",
                    onSelect: function(data) {							
                        var inp = jQuery(selEv.targetInput).val(data.url);
                    }
                });
                dlg.open();
            },

            toolbar: 'left'
        });        

    </pre>
    <!--<p><a href="ContentBuilder/contentbuilder-assetmanager.html" target="_blank" class="linkbutton">Show Me</a></p>-->
    
    <br />
    <h4>Usage #3. Get selected file and display in DIV</h4>
    <div id="selFileName" style="padding: 6px;">No file selected</div>
    <p><input type="button" id="selectFile" value="Select" /></p>
    
    <br />
    <pre>    
    &lt;div id="selFileName" style="padding: 6px;"&gt;No file selected&lt;/div&gt;
    &lt;p&gt;&lt;input type="button" id="selectFile" value="Select" /&gt;&lt;/p&gt;
    
    jQuery("#selectFile").xproFileDialog({
        url : "assetmain.php", //this is default (you can omit this)
        onSelect: function(data) {
            jQuery("#selFileName").html(data.url);
        }
    });    
    </pre>
    
    <br />
    <h4>Usage #4. Instantiate using Object Style</h4>
    <pre>
    var manager = new XPROFileDialog({
        url: "assetmain.php"
        onSelect: function(data) {
            alert(data.url);
        }
    });

    manager.open();
    </pre>
    
    <br />
    <h3>Other Options</h3>

    <h4>1. To set max upload size</h4>
    <p>In assetmain.php, asset manager initialization:</p>
    <pre>
    XPROFile.maxSize = 2000000; //in byte    
    </pre>
    
    <h4>2. To set allowed file type</h4>
    <p>In assetmain.php, asset manager initialization:</p>
    <pre>
    XPROFile.allowedTypes = ["jpg", "png", "gif", "txt", "jpeg", "zip", "pdf", "doc", "docx"];

    //to allow all: XPROFile.allowedTypes = ["*"];
    </pre>
    
    <h4>3. To set asset manager to readonly mode:</h4>
    <p>In assetmain.php, asset manager initialization</p>
    <pre>
    XPROFile.readonly = true;  
    </pre>
    
    <h4>4. To allow delete:</h4>
    <p>In assetmain.php, asset manager initialization</p>
    <pre>
    XPROFile.allowDelete = true;  
    </pre>
    
    <h4>5. To allow Rename:</h4>
    <p>In assetmain.php, asset manager initialization</p>
    <pre>
    XPROFile.allowRename = true;  
    </pre>
    
    <h4>6. To allow Edit (existing file on server):</h4>
    <p>In assetmain.php, asset manager initialization</p>
    <pre>
    XPROFile.allowEdit = true;  
    </pre>
    
    <br />
    <h3>Localization</h3>
    
    <h4>Server Side</h4>
    <p>Set language code in config.php, for example</p>
    <pre>
    define("XF_LANGUAGE", "dk-DK");
    </pre>
    <p>Copy the English version of language file located in <em>server/i18n-en-US.txt</em>, rename to <em>i18n-dk-DK.txt</em> and translate.</p>
    
    <h4>JavaScript Side</h4>
    <p>In <em>xprofile_lang.js</em>, create a new object and copy all the words from English version and translate.</p>
    
    <pre>
    var XPROFileI18N_DK = {
    ...
    ...
    };
    </pre>
    
    <p>Then set the object as second argument in the following line in <em>xprofile_lang.js</em>:</p>
    <pre>
    var _XFI18n = jQuery.extend(XPROFileI18n_EN, <strong>XPROFileI18N_DK</strong>);
    </pre>
    <br /><br />
</body>

</html>
