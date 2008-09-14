<?php  

	require_once("functions.php"); 
?>
<script type="text/javascript" src="jquery.js"></script>
<form action="create.php" method="post" enctype="multipart/form-data">
<input type="file" id="content_data" name="content_data" class="textbox" size="25"/>
<input value="4" name="status" type="hidden">
<input class="button" value="<?= T_("shorten this")?>" type="submit">
</form>
<div id="urlcount"><?= sprintf(T_("<b>%s</b> image shorted"), get_image_count(true)) ?> <br/></div>
<br/>

