<?php

class Box extends \WebDreamt\Box {

	function __construct() {
		parent::__construct();
		$this->DatabaseUsername = 'root';
		$this->DatabasePassword = '';
		$this->DatabaseName = 'webdreamt_blog';
		$this->DatabaseHost = 'localhost';
		$this->enable();
		$this->csrfToken();
		$this->server()->automate();
	}

	function javascript() {
		$js = '<script src="' . $this->root() . '/dist/ckeditor/ckeditor.js"></script>' .
				'<script src="' . $this->root() . '/dist/client/build.js"></script>' .
				'<script src="' . $this->root() . '/dist/ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>' .
				'<script>hljs.initHighlightingOnLoad();</script>';
		return $js;
	}

	function css() {
		return '<link href="' . $this->root() . '/dist/client/build.min.css" rel="stylesheet">' .
				'<link href="' . $this->root() . '/dist/ckeditor/plugins/codesnippet/lib/highlight/styles/default.css" rel="stylesheet">';
	}

}
