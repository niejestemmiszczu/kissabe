<?php require_once("functions.php"); ?>

<form action="create.php" method="post" onsubmit="return checkform(this);">
<input class="textbox" maxlength="2000" size="55" id="content_data" name="content_data" value="" type="text">
<input value="1" name="status" type="hidden">
<input class="button" value="<?= T_("shorten this")?>" type="submit">
</form>
<div id="urlcount"><?= sprintf(T_("<b>%s</b> url address shorted"), get_url_count(true)) ?><br/></div>
<br/>
