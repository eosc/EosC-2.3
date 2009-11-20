<script language="javascript" type="text/javascript" src="includes/javascript/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
  mode : "textareas",
  language: "en",
  theme : "advanced",
	plugins : "table,advhr,advimage,advlink,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu,paste,directionality,fullscreen,codeprotect, ibrowser",
	theme_advanced_buttons1_add_before : "",
	theme_advanced_buttons1_add : "fontselect,fontsizeselect",
	theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
	theme_advanced_buttons2_add : "separator,insertdate,inserttime,separator,forecolor,backcolor",
	theme_advanced_buttons3_add_before : "tablecontrols,separator",
	theme_advanced_buttons3_add : "iespell,flash,advhr,separator,print,separator,preview,separator,fullscreen, ibrowser",

  fullscreen_settings : {
  theme_advanced_path_location : "top"},
  document_base_url : "<?php echo HTTP_SERVER.DIR_WS_CATALOG ?>",
  relative_urls : false,
  remove_script_host : false,
  external_image_list_url : "tinymce_images.js.php",
  		width : "100%",
		height : "460",
		//theme_advanced_disable : "image",
  theme_advanced_toolbar_location : "top",
  theme_advanced_toolbar_align : "left",
  theme_advanced_path_location : "bottom",
  extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
});
</script>