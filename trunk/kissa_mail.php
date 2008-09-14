<?php require_once("functions.php"); ?>

<form action="create.php" method="post" onsubmit="return checkform(this);">
<input class="textbox" maxlength="2000" size="55" id="content_data" name="content_data" value="" type="text">
<input value="2" name="status" type="hidden">

<input class="button" value="<?= T_("shorten this")?>" type="submit">

</form>
<div id="urlcount"><?= sprintf(T_("<b>%s</b> e-mail shorted"), get_mail_count(true)) ?><br/></div>
<br/>

