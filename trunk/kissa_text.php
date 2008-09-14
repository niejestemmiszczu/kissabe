<?php  

	require_once("functions.php"); 
?>
<script type="text/javascript" src="jquery.js"></script>
<form action="create.php" method="post">
<textarea class="textbox" id="content_data" name="content_data" rows="8" cols="60"></textarea>
<input value="3" name="status" type="hidden">
<input class="button" value="<?= T_("shorten this")?>" type="submit">
</form>
<div id="urlcount"><?= sprintf(T_("<b>%s</b> text shorted"), get_text_count(true)) ?> <br/></div>
<br/>

