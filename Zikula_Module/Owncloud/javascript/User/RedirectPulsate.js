function Owncloud_RedirectPulsate() {
	jQuery('#Owncloud_redirectLogo').delay(900).fadeTo(300, 0.5, 'easeInOutQuad', function() {
		jQuery('#Owncloud_redirectLogo').fadeTo(250, 1, 'easeInOutQuad', function() {
			Owncloud_RedirectPulsate();
		})
	})
}

jQuery('document').ready(function() {
	Owncloud_RedirectPulsate();
})