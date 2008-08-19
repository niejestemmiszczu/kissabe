<?php
	require_once("functions.php");

	global $page_title;
	$page_title = "Talimatnâne";
	include("header.php");
?>
<h2>nedir?</h2>
<p><i>kissa.be!</i> ticari bir kaygı düşüncesi yoktur. Geliştirici ekibin tatilde tinyurl.com'u görüp can sıkıntısı da üzerine binince "ne var canım bu serviste?" demesi ve "bizim olsun daha da güzeli olsun" düşüncesiyle çooooooook uzun web adreslerini maksimum seviyede kısaltmayı amaç edinen bir hededir. <p/>
<b>Örneğin;</b><p/>
<div style="padding-left:30px">
<b>http://maps.google.com/maps?f=q&hl=en&q=Be%C5%9Fikta%C5%9F,+Be%C5%9Fikta%C5%9F,+Turkey&jsv=121&sll=37.0625,-95.677068&sspn=31.839416,79.013672&ie=UTF8&oi=georefine&ct=clnk&cd=2&geocode=0,41.071553,29.023276</b><p/>
gibi karışık bir adresi aşağıda görünen hale dönüştürür.<p/>
<b>http://kissa.be/1</b><p/>
</div>
</p>

<h2>ne işe yarar?</h2>
	<p>kissa.be'nin birkaç kullanım şekli aşağıda sıralanmıştır!<p/>
	<ul>
		<li>sms, wap-push vb cep telefonu için kıssa web adreslerine dönüştürür.</li>
		<li>blog, forum ve sosyal ağlarda adresleri rahatlıkla paylaşmanıza sebep olur</li>
		<li>web adreslerinizi spam botlarından korur.</b>
		<li><i>mailto:u.name@example.com</i> şeklinde girilen eposta adreslerinizi SPAM botlarından saklamanız için idealdir.</li>
		<li>temiz url adresleri ve twitter, del.icio.us gibi sitelerdeki karakter sınırlamaları içindir</li>
	</ul>
</p>
<hr>
<h2>nasıl çalışır?</h2>

<a name="preview"></a>
<h3>kullanımı</h3>
<p>
kissa.be! sitesinin anasayfasından uzun web adresinizi girin ve kıssalt butonuna basın. karşınıza gelen sayfada http://kissa.be/1 gibi bir url göreceksiniz. Bu url tarafımızdan yönlendirilir.<p/> 
<ul>
	<li>kissa.be! url kod tanımları tekildir. Yani aynısından birdaha başka bir web adresi için oluşturulmaz.</li>
	<li>ayrıca bu url bilgisi hiçbir zaman silinmeme garantisi tarafımızdan verilmiştir.</li>
	<li>url bellirlenen algoritmaya göre basamak sayısını arttırmaktadır. Maksimum basamak sayısının 8 olması hedeflenmiştir.</li>
	<li>hem web adresi hem de, eposta adresi girilebilir</li>
</ul>
<h3>kod'dan web adresine dönüş</h3>
<p>Evet bu mümkündür. http://kissa.be/1 adresini internet gezginin adres çubuğuna yazdığınızda adrese yönlendirilme yapılır. Ama url kodunun sonuna '-' işareti koyduğunuzda size uzun adresin görüntüler.
<p>Örneğin:
http://kissa.be/1 adresinin sorgulama istediniz. Bu adresin sonuna http://kissa.be/1- şeklinde '-' işaretini koyduğunuzda url kod önbakış sayfasına gidersiniz.</p></p>
<h3>bookmark</h3>
<p>Eğer site içeriğinizi bookmarklamak isteyenler için sitenizde link vermek isterseniz, aşağıdaki javascript link ile bunu yapabilirsiniz.<br/> <a href="javascript:void(location.href='http://kissa.be/create.php?url='+location.href)">adresi kıssalt bakim</a>.
</p>
<h3>api desteği</h3>
<p>Eğer çeşitli eklentilerde ve projelerde bu servisten faydalanmak isterseniz api desteğimizden faydalanabilirsiniz.
<p>
Örneğin;<br/>
<b>http://kissa.be/api.php?url=http://sizin.uzun.web/adresiniz?url=olarak&burada=kullanildi</b></p>
şeklinde çağrı yaptığınızda; sadece kıssaltılmış web adresinden oluşan sayfa karşınıza çıkacaktır.
</p>
<h3>firefox plugin</h3>
<p>Firefox plugini Gökhan Goralı tarafından hazırlandı. Kendisine teşekür ederiz.</p>
<p>Firefox pluginini <a href="kissa.be-v01.xpi">buradan</a> indirebilirsiniz.</p>
<p>Alternatif bir firefox pluginini <a href="kissa.be_creator-0.2.1-fx.xpi">buradan</a> indirebilirsiniz<p/>

<?php  include("footer.php"); ?>
