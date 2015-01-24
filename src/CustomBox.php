<?php
use WebDreamt\Box;

class CustomBox extends Box {

	function __construct() {
		parent::__construct();
		$this->DatabaseUsername = 'root';
		$this->DatabasePassword = '';
		$this->DatabaseName = 'blog';
		$this->DatabaseHost = 'localhost';
	}

	function header($css = true, $title = '', $custom = null) {
		parent::header($css, $title, $custom);
	}

	function javascript() {
		return '<script src="' . $this->root() . '/dist/client/webdreamt-build.js"></script>';
	}

	function css() {
		return '<link href="' . $this->root() . '/dist/client/webdreamt-build.min.css" rel="stylesheet">';
	}

}
