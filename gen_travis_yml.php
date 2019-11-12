#!/usr/bin/env php
# autogenerated file; do not edit
language: c
dist: bionic

addons:
 apt:
  packages:
   - geoip-database
   - php-cli
   - pkg-config
   - libc-ares-dev
   - libcurl4-openssl-dev
   - libevent-dev
   - libgeoip-dev
   - libicu-dev
   - libmemcached-dev
   - libmsgpack-dev
   - libonig-dev
   - libpcre3-dev
   - libsqlite3-dev
   - libssl-dev
   - libzip-dev
   - zlib1g-dev

services:
 - memcached

env:
<?php

$gen = include "./travis/pecl/gen-matrix.php";
$env = $gen([
	"PHP" => ["7.1", "7.3", "7.4", "master"],
	"PECL" => [
		"apcu",			"krakjoe/apcu:apcu:master",
		"geoip", 
		"mailparse",	"php/pecl-mail-mailparse:mailparse:master",
		"memcached",	"php-memcached-dev/php-memcached:memcached:master",
		"msgpack",		"msgpack/msgpack-php:msgpack:master",
		"oauth",		"php/pecl-web_services-oauth:oauth:master"
	],
	"enable_mbstring" => "yes",
	"enable_session" => "yes",
	"enable_pcntl" => "yes",
	"enable_json" => "yes",
	"with_curl" => "yes",
]);

foreach ($env as $grp) foreach ($grp as $e) {
	printf(" - %s\n", $e);
}
?>

before_script:
 - sudo ln -s /usr/include/{x86_64-linux-gnu/,}curl
 - make -f travis/pecl/Makefile php

script:
 - make -f travis/pecl/Makefile pecl
 - make -f travis/pecl/Makefile pecl-test
