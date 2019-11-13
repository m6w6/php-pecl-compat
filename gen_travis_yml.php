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
$php = ["7.1", "7.3", "7.4", "master"];
$env = $gen([
	"PHP" => $php,
	"PECL" => ["apcu", "krakjoe/apcu:apcu:master"],
	"enable_pcntl" => "yes",
	"enable_session" => "yes",
], [
	"PHP" => $php,
	"PECL" => "geoip",
	"TESTS" => "\"'$(PECL_DIR)/tests/{0[^1]?,01[^9]}.phpt'\"",
], [
	"PHP" => $php,
	"PECL" => ["mailparse", "php/pecl-mail-mailparse:mailparse:master"],
	"enable_mbstring" => "yes",
	"with_zlib" => "yes",
], [
	"PHP" => $php,
	"PECL" => ["memcached", "php-memcached-dev/php-memcached:memcached:master"],
	"TESTS" => "\"'$(PECL_DIR)/tests/*.phpt'\"",
	"enable_json" => "yes",
	"enable_session" => "yes",
], [
	"PHP" => $php,
	"PECL" => ["msgpack", "msgpack/msgpack-php:msgpack:master"],
	"TESTS" => "\"'$(PECL_DIR)/tests/{[A-z],[0-9][0-35-9],04[^0]}*'\"",
	"enable_session" => "yes",
], [
	"PHP" => $php,
	"PECL" => ["oauth", "php/pecl-web_services-oauth:oauth:master"],
	"with_curl" => "yes",
	"enable_pcntl" => "yes",
	"enable_posix" => "yes",
	"with_openssl" => "yes",
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
