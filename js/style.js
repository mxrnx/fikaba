function changeStyle(title) {
	var links = document.getElementsByTagName('link');
	for (var i = links.length - 1; i >= 0; i--) {

		if (links[i].getAttribute('rel').indexOf('style')> -1 && links[i].getAttribute('title')) {
			links[i].disabled = true;
			if (links[i].getAttribute('title') == title || links[i].getAttribute('title') == 'global') links[i].disabled = false;
		}
	}
}
