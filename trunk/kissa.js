/*<![CDATA[*/
/* SnipURL util functions */

function cSn(sn, el) 
{ 
		if (window.clipboardData) 
		{ 
				window.clipboardData.setData("Text",sn); 
		} 
		else 
		{ 
				var flashcopier = 'flashcopier'; 
				
				if(!document.getElementById(flashcopier)) 
				{ 
						var divholder = document.createElement('div'); 
						divholder.id = flashcopier; 
						document.body.appendChild(divholder); 
				} 
				document.getElementById(flashcopier).innerHTML = ''; 
				var divinfo = '<embed src="flashcopier.swf" FlashVars="clipboard='+escape(sn)+'" width="0" height="0" type="application/x-shockwave-flash"></embed>'; 
				document.getElementById(flashcopier).innerHTML = divinfo; 
		} 

		var e=document.getElementById(el); 
		var coors= findPos(e); 
		var em=document.getElementById("blip"); 
		em.style.left = coors[0]+"px"; 
		em.style.top = (coors[1]+25)+"px"; 
		em.style.display=""; 
		setTimeout('hB()', 2000);
}

function hB() { var em=document.getElementById('blip');em.style.display="none"; }

function findPos(obj) { var curleft = curtop = 0;if (obj.offsetParent) { curleft = obj.offsetLeft; curtop = obj.offsetTop; while (obj = obj.offsetParent) { curleft += obj.offsetLeft; curtop += obj.offsetTop; } } return [curleft,curtop];}

/*]]>*/
