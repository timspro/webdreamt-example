(function () {
	$(window).load(function () {
		CKEDITOR.config.extraPlugins = "base64image,codesnippet";
		if ($('.ckeditor').length > 0) {
			CKEDITOR.replace('.ckeditor');
		}
	});
})();