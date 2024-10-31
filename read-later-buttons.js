jQuery(function() {

	var iOS = ( navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false );
	function supportsSVG() {
		return !!document.createElementNS && !!document.createElementNS('http://www.w3.org/2000/svg', "svg").createSVGRect;
	}

	// Upgrade images to SVG if supported
	if(supportsSVG()) {
		jQuery('.read_later_service').find('img').each(function() {
			this.src = this.src.replace(/.png$/, '.svg');
		});
	}
});