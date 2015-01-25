<?php

class Box extends \WebDreamt\Box {

	function __construct() {
		parent::__construct();
		$this->DatabaseUsername = 'root';
		$this->DatabasePassword = '';
		$this->DatabaseName = 'webdreamt_blog';
		$this->DatabaseHost = 'localhost';
		$this->enable();
	}

	function javascript() {
		return '<script src="' . $this->root() . '/dist/client/build.js"></script>';
	}

	function css() {
		return '<link href="' . $this->root() . '/dist/client/build.min.css" rel="stylesheet">';
	}

}
