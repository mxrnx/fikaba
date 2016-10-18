function changeStyle(title) {
	var links = document.getElementsByTagName('link');
	for (var i = links.length - 1; i >= 0; i--) {

		if (links[i].getAttribute('rel').indexOf('style')> -1 && links[i].getAttribute('title')) {
			links[i].disabled = true;
			if (links[i].getAttribute('title') == title) {
				links[i].disabled = false;
				var d = new Date();
				d.setTime(d.getTime() + (24*60*60*1000));
				var expires = "expires="+ d.toUTCString();
				document.cookie = "fikabastyle="+links[i].getAttribute('title')+";"+expires+"; path=/";
			}
		}
	}
}

function getCookie(cname) { /* http://www.w3schools.com/js/js_cookies.asp */
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i = 0; i <ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length,c.length);
		}
	}
	return false;
} 

function getUserFav(defstyle) {
	if (getCookie('fikabastyle')){
		changeStyle(getCookie('fikabastyle'));
	} else {
		changeStyle(defstyle);
	}
}
