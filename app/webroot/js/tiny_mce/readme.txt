A basic S3 file_manager for TinyMCE
Put together by David Lippman 2008 http://www.pierce.ctc.edu/dlippman
Adapted from work by TadejK
  http://sourceforge.net/tracker/index.php?func=detail&aid=1698519&group_id=103281&atid=738747
Uses S3 library  (c) 2007-2008, Donovan Schonknecht

This is a very very simple file/image browser for TinyMCE that
uses Amazon S3 for file storage.  You can add your own CMS code 
for multi-user systems so each user has their own file storage 
space.  

You will need an Amazon Web Services key and secret, and will need
to create a bucket with another utility.
	
To use
-------
1) Copy files to tinyMCE directory

2) Edit file_manager and put in your S3 key, secret, and bucket

3) In your HTML, add to tinyMCE.init:
	file_browser_callback : "fileBrowserCallBack"
		
4) In your HTML, add this function after tinyMCE.init:  
   (change connector path if needed)
	
	function fileBrowserCallBack(field_name, url, type, win) {
		var connector = "jscript/file_manager.php";
		my_field = field_name;
		my_win = win;
		switch (type) {
			case "image":
				connector += "?type=img";
				break;
			case "file":
				connector += "?type=files";
				break;
		}
		tinyMCE.activeEditor.windowManager.open({
			file : connector,
			title : 'File Manager',
			width : 350,  
			height : 450,
			resizable : "yes",
			inline : "yes",  
			close_previous : "no"
		    }, {
			window : win,
			input : field_name
		    });
	}