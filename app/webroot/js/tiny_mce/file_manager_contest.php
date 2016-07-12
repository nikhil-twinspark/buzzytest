<?php
/*  	
S3 file_manager for TinyMCE
Put together by David Lippman 2008
Adapted from work by TadejK
	http://sourceforge.net/tracker/index.php?func=detail&aid=1698519&group_id=103281&atid=738747
Uses S3 library  (c) 2007-2008, Donovan Schonknecht
*/

//SPECIFY THESE
$AWSkey = "AKIAJTJWV5FVP63JVX6A"; 		//your AWS key
$AWSsecret = "1uzKJd28RE4WREY6c0iM6cqehT5bvjD9bvcrIPRX";  	//your AWS secret
$AWSbucket = "integrateortho_prod";	//your AWS bucket to use for files

//PUT YOUR OWN CODE HERE FOR USERNAME LOOKUP
//or leave alone for single user systems
//stores file in bucket as filebase/userid/filename
$userid = 1;  			//userid - for multiple user systems
$filebase = "Contest";  	//base directory


//No need to change after this, unless you want to
require("S3.php");
function storeuploadedfile($id,$key,$sec="private") {
	if ($sec=="public" || $sec=="public-read") {
		$sec = "public-read";
	} else {
		$sec = "private";
	}
	if (is_uploaded_file($_FILES[$id]['tmp_name'])) {
		$s3 = new S3($GLOBALS['AWSkey'],$GLOBALS['AWSsecret']);
         
                
                
                $promotion_image = $_FILES['uploaded_file'];
                $rootpath=explode('/js',$_SERVER['SCRIPT_FILENAME']);

                $uploadPath = $rootpath[0].'/img/Contest';
                
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                    chmod($uploadPath, 0777);
                }
                
               
                            //image file name
                            $date = strtotime(date('m/d/Y h:i:s a', time()));
							
										   
                            $img_dir=$uploadPath;
                            $img = explode('.', $promotion_image["name"]);

                            $image_filePath=$promotion_image['tmp_name'];
                            $img_fileName=$date.".".$img[1];
							
                            $challenge_header_image_name = $img_fileName;
                            //check if file exists in upload folder
                            if (file_exists($uploadPath . '/' . $challenge_header_image_name)) {
                                //create full filename with timestamp
                                unlink($uploadPath . '/' . $challenge_header_image_name);
                            }
                            $key='img/Contest/'.$challenge_header_image_name;
                            //create full path with image name
                            $challenge_header_image_full_image_path = $uploadPath . '/' . $challenge_header_image_name;
                           
                            //upload image to upload folder
                            
                            if (move_uploaded_file($promotion_image['tmp_name'], $challenge_header_image_full_image_path)) {
                              
               
		if ($s3->putObjectFile($challenge_header_image_full_image_path,$GLOBALS['AWSbucket'],$key,$sec)) {
			return true;
		} else {
			return false;
		}
                            } 

                
	} else {
		return false;
	}
}
function getuserfiles($uid,$img=false) {
	global $filebase;
	$s3 = new S3($GLOBALS['AWSkey'],$GLOBALS['AWSsecret']);
	$arr = $s3->getBucket($GLOBALS['AWSbucket'],"img/$filebase/");
	if ($arr!=false) {
		if ($img) {
			$imgext = array(".gif",".jpg",".png",".jpeg");
			foreach ($arr as $k=>$v) {
				if (!in_array(strtolower(strrchr($arr[$k]['name'],".")),$imgext)) {
					unset($arr[$k]);
				}
			}
		}
		return $arr;
	} else {
		return array();
	}
}
function deleteuserfile($uid,$file) {
	global $filebase;
	$s3 = new S3($GLOBALS['AWSkey'],$GLOBALS['AWSsecret']);
	$s3object = "img/$filebase/$file";
	if($s3->deleteObject($GLOBALS['AWSbucket'],$s3object)) {
		return true;
	}else {
		return false;
	}
}
function getuserfileurl($key) {
	return "http://s3.amazonaws.com/{$GLOBALS['AWSbucket']}/$key";
}

@set_time_limit(0);
ini_set("max_input_time", "600");
ini_set("max_execution_time", "600");
ini_set("memory_limit", "104857600");
ini_set("upload_max_filesize", "10485760");
ini_set("post_max_size", "10485760");
//which language file to use
include("file_manager/lang/lang_eng.php");

//which images to use
$delete_image 			= "file_manager/x.png";
$file_small_image 		= "file_manager/file_small.png";

//custom configuration from here on ..
//image browser configuration
$dir_width 		= "96px";
$file_width 	= "96px";
$pics_per_row 	= 2;

if (isset($_REQUEST["type"])) {
	$type = $_REQUEST["type"];
}
else {
	$type = -2;
}

if (isset($_REQUEST["action"]))
{
	if ($_REQUEST["action"] == "upload_file")
	{
		storeuploadedfile("uploaded_file","$filebase/". basename($_FILES["uploaded_file"]["name"]),"public");
	}
	else if ($_REQUEST["action"] == "delete_file")
	{
		deleteuserfile($userid,$_REQUEST["item_name"]);
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $strings["title"]; ?></title>


<script language="javascript" type="text/javascript" src="tiny_mce_popup.js"></script>
<link href="file_manager/styles.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
var FileBrowserDialogue = {
    init : function () {
        // Here goes your code for setting your custom things onLoad.
    },
    mySubmit : function (filename) {
        var win = tinyMCEPopup.getWindowArg("window");

        // insert information now
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = filename;

        // for image browsers: update image dimensions
	if (win.ImageDialog) {
		if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
		if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(URL);
	}

        // close popup window
        tinyMCEPopup.close();
    }
}

tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);

function switchDivs() {
	var fieldvalue = document.getElementById("uploaded_file").value;
	if (fieldvalue=='') {
		alert("No file selected");
		return false;
	}
<?php
if ($type=="img") {
?>
	
	extension = ['.png','.gif','.jpg','.jpeg'];
	isok = false;
	var thisext = fieldvalue.substr(fieldvalue.lastIndexOf('.')).toLowerCase();
	for(var i = 0; i < extension.length; i++) {
		if(thisext == extension[i]) { isok = true; }
	}
	if (!isok) {
		alert("File must be an image file: .png, .gif, .jpg");
		return false;
	}
<?php
}
?>

	document.getElementById("upload_div").style.display = "none";
	document.getElementById("uploading_div").style.display = "block";
	return true;
}
</script>
</head>
<body>
<div class="td_close">
<a class="close" href="javascript: tinyMCEPopup.close();"><?php echo $strings["close"]; ?></a>
</div>
<div class="td_main">
<?php
$files = getuserfiles($userid,$type=="img");
foreach ($files as $k=>$v) {
	//echo "<a href='#' onClick='delete_file(\"" . basename($v['name']) . "\")'>";
	//echo "<img border=0 src='" . getuserfileurl($v['name']) . "' height=100 width=100></a> ";
	//echo "<img src='" . $file_small_image . "'> ";
	echo "<a class='file' href='#' onClick='FileBrowserDialogue.mySubmit(\"" . getuserfileurl($v['name']) . "\");'><img border=0 src='" . getuserfileurl($v['name']) . "' height=100 width=100></a>\n";

}
?>
</div>
<div class="upload">

	<div id="upload_div" style="display: block; padding: 0px;">
	<form method="post" enctype="multipart/form-data" onSubmit="return switchDivs();">
		<?php echo $strings["upload_file"]; ?>
		<input type="hidden" name="action" value="upload_file">
		<input type="hidden" name="MAX_FILE_SIZE" value="10485760" /> <!-- ~10mb -->
		<input type="file" name="uploaded_file" id="uploaded_file">
		<input type="submit" value="<?php echo $strings["upload_file_submit"]; ?>">
	</form>
	</div>
	<div id="uploading_div" style="display: none; padding: 0px;">
	<?php echo $strings["sending"]; ?>
	</div>
</div>

<script>

function delete_file(file_name)
{
	document.getElementById("hidden_action").value = "delete_file";
	document.getElementById("hidden_item_name").value = file_name;
	document.getElementById("hidden_form").submit();
}
</script>
<div style="display: none;">
	<form method="post" id="hidden_form">
	<input type="hidden" name="action" id="hidden_action" value="">
	<input type="hidden" name="item_name" id="hidden_item_name" value="">
	</form>
</div>
</body>
</html>