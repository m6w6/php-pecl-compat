#!/usr/bin/env php
# autogenerated file; do not edit
language: c
dist: bionic

addons:
 apt:
  packages:
   - php-cli
   - pkg-config
   - libc-ares-dev
   - libcurl4-openssl-dev
   - libevent-dev
   - libgeoip-dev
   - libicu-dev
   - libmemcached-dev
   - libmsgpack-dev
   - libpcre3-dev
   - libsqlite3-dev
   - libssl-dev
   - libzip-dev
   - zlib1g-dev


env:
<?php

$gen = include "./travis/pecl/gen-matrix.php";
$env = $gen([
	"PHP" => ["7.1", "7.3", "7.4", "master"],
	"PECL" => [
		"apcu", "apcu::master", 
		"geoip", 
		"mailparse", "mailparse::master", 
		"memcached", "memcached::master", 
		"msgpack", "msgpack::master",
		"oauth", "oauth::master"
	],
	"enable_mbstring" => "yes",
	"enable_session" => "yes",
]);

foreach ($env as $grp) foreach ($grp as $e) {
	printf(" - %s\n", $e);
}
?>

before_script:
 - test $PECL = apcu::master		&& git clone https://github.com/krakjoe/apcu
 - test $PECL = mailparse::master	&& git clone https://git.php.net/repository/pecl/mail/mailparse.git
 - test $PECL = memcached::master	&& git clone https://github.com/php-memcached-dev/php-memcached
 - test $PECL = msgpack::master		&& git clone https://github.com/msgpack/msgpack-php
 - test $PECL = oauth::master		&& git clone https://github.com/php/pecl-web_services-oauth

script:
 - make -f travis/pecl/Makefile pecl
 - make -f travis/pecl/Makefile pecl-test
