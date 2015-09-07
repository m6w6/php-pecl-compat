#!/usr/bin/env php
<?php

const ALL_RELEASES = "http://pecl.php.net/rest/r/%s/allreleases.xml";
const ONE_RELEASE  = "http://pecl.php.net/rest/r/%s/package.%s.xml";

$stability = "devel";
switch ($argc) {
case 4:
	$stability = $argv[3];
	/* no break */
case 3:
	$package = strtolower($argv[2]);
	$version = strcmp($argv[1], "master") ? $argv[1] : "7.0";
	break;
default:
	fprintf(STDERR, "Usage: %s <php_version> <package_name> [stability]\n", $argv[0]);
	exit(1);
}

$stabilities = [
	"stable" => ["stable"],
	"beta" => ["stable", "beta"],
	"alpha" => ["stable", "beta", "alpha"],
	"devel" => ["stable", "beta", "alpha", "devel"],
];

$cache = "$package.$version.$stability.cache";
if (is_file($cache)) {
	readfile($cache);
	exit(0);
}
ob_start(function($s) use($cache) {
	file_put_contents($cache, $s, FILE_APPEND);
	return $s;
});

$count = 0;
foreach (@simplexml_load_file(sprintf(ALL_RELEASES, $package))->r as $r) {
	if (++$count >= 10) {
		fprintf(STDERR, "Max release check count reached\n");
		// only check the last 10 releases
		break;
	}
	fprintf(STDERR, "Checking whether stability '%s' satisfies '%s'\n", $r->s, $stability);
	if (!in_array($r->s, $stabilities[$stability])) {
		continue;
	}
	fprintf(STDERR, "Loading %s/package.%s.xml\n", $package, $r->v);
	if (!$inf = @simplexml_load_file(sprintf(ONE_RELEASE, $package, $r->v))) {
		break;
	}
	fprintf(STDERR, "Checking providesextension: %s\n", $inf->providesextension);
	if (!$inf->providesextension) {
		break;
	}
	fprintf(STDERR, "Checking PHP dependencies\n");
	if (!$php = $inf->dependencies->required->php) {
		break;
	}
	fprintf(STDERR, "Checking whether %s > %s\n", $version, $php->min);
	if (isset($php->min) && version_compare($version, $php->min, "<")) {
		continue;
	}
	fprintf(STDERR, "Checking whether %s < %s\n", $version, $php->max);
	if (isset($php->max) && version_compare($version, $php->max, ">")) {
		continue;
	}
	foreach ($php->exclude as $ex) {
		fprintf(STDERR, "Checking whether %s != %s\n");
		if (version_compare($version, $ex, "==")) {
			continue;
		}
	}
	
	printf("%s:%s:%s\n", $package, $inf->providesextension, $r->v);
	exit(0);
}

fprintf(STDERR, "ERROR: no suitable PECL package '%s' version found for PHP %s\n", 
	"$package-$stability", $version);
exit(2);
