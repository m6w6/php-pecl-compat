#!/usr/bin/env php
# autogenerated file; do not edit
language: c
sudo: false

addons:
 apt:
  packages:
   - php-cli
   - php-pear

env:
<?php

$pkg = simplexml_load_file("http://pecl.php.net/rest/p/packages.xml")->p;
$gen = include "./travis/pecl/gen-matrix.php";
$env = $gen([
	"PHP" => ["5.4", "5.5", "5.6", "master"],
	"EXT" => $pkg,
	"enable_all" => [""], // reset to default enabled extensions
]);

foreach ($env as $e) {
	printf(" - %s\n", $e);
}
?>

script:
 -  make -f travis/pecl/Makefile pecl PECL='$$(./peclfu.php $(PHP_VERSION) $(EXT))'
