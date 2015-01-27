(function () {
	$(window).load(function () {
		CKEDITOR.config.extraPlugins = "base64image";
		CKEDITOR.replace('.ckeditor');
	});
})();