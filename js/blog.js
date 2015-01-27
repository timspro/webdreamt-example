(function () {
	$(window).load(function () {
		CKEDITOR.config.extraPlugins = "base64image";
		if ($('.ckeditor').length > 0) {
			CKEDITOR.replace('.ckeditor');
		}
	});
})();