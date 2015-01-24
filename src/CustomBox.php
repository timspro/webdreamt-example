<?php
use WebDreamt\Box;

class CustomBox extends Box {

	function __construct() {
		parent::__construct();
		$this->DatabaseUsername = 'root';
		$this->DatabasePassword = '';
		$this->DatabaseName = 'webdreamt_blog';
		$this->DatabaseHost = 'localhost';
	}

	function javascript() {
		return '<script src="' . $this->root() . '/dist/client/build.js"></script>';
	}

	function css() {
		return '<link href="' . $this->root() . '/dist/client/build.min.css" rel="stylesheet">';
	}

}
