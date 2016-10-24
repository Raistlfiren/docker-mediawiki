<?php

// @see https://www.mediawiki.org/wiki/Manual:Configuration_settings

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
    exit;
}

if (getenv('MEDIAWIKI_SITENAME') != '') {
    $wgSitename = getenv('MEDIAWIKI_SITENAME');
}

if (getenv('MEDIAWIKI_META_NAMESPACE') != '') {
    $wgMetaNamespace = getenv('MEDIAWIKI_META_NAMESPACE');
}

# Short URLs
$wgScriptPath = "";
$wgArticlePath = "/$1";
$wgUsePathInfo = true;
$wgScriptExtension = ".php";

if (getenv('MEDIAWIKI_SERVER') == '') {
    throw new Exception('Missing environment variable MEDIAWIKI_SERVER');
} else {
    $wgServer = getenv('MEDIAWIKI_SERVER');
}

$wgResourceBasePath = $wgScriptPath;

$wgLogo = "$wgResourceBasePath/resources/assets/wiki.png";

if (getenv('MEDIAWIKI_EMERGENCY_CONTACT') != '') {
    $wgEmergencyContact = getenv('MEDIAWIKI_EMERGENCY_CONTACT');
}

if (getenv('MEDIAWIKI_PASSWORD_SENDER') != '') {
    $wgPasswordSender = getenv('MEDIAWIKI_PASSWORD_SENDER');
}

$wgEnotifUserTalk = false;
$wgEnotifWatchlist = false;
$wgEmailAuthentication = true;

if (getenv('MEDIAWIKI_DB_TYPE') != '') {
    $wgDBtype = getenv('MEDIAWIKI_DB_TYPE');
}

if (getenv('MEDIAWIKI_DB_HOST') != '' || getenv('MEDIAWIKI_DB_PORT') != '') {
    $hostname = ((getenv('MEDIAWIKI_DB_HOST') != '') ? getenv('MEDIAWIKI_DB_HOST') : '127.0.0.1');
    $port = ((getenv('MEDIAWIKI_DB_PORT') != '') ? getenv('MEDIAWIKI_DB_PORT') : '3306');
    $wgDBserver = $hostname.':'.$port;
}

unset($hostname, $port);

if (getenv('MEDIAWIKI_DB_NAME') != '') {
    $wgDBname = getenv('MEDIAWIKI_DB_NAME');
}

if (getenv('MEDIAWIKI_DB_USER') != '') {
    $wgDBuser = getenv('MEDIAWIKI_DB_USER');
}

if (getenv('MEDIAWIKI_DB_PASSWORD') != '') {
    $wgDBpassword = getenv('MEDIAWIKI_DB_PASSWORD');
}

# MySQL specific settings
if (getenv('MEDIAWIKI_DB_PREFIX') != '') {
    $wgDBprefix = getenv('MEDIAWIKI_DB_PREFIX');
}

if (getenv('MEDIAWIKI_DB_TABLE_OPTIONS') != '') {
    $wgDBTableOptions = getenv('MEDIAWIKI_DB_TABLE_OPTIONS');
}

$wgDBmysql5 = false;

# SQLite specific settings
$wgSQLiteDataDir = '/data';

if (getenv('MEDIAWIKI_DB_TYPE') == 'sqlite') {
    $wgObjectCaches[CACHE_DB] = [
        'class' => 'SqlBagOStuff',
        'loggroup' => 'SQLBagOStuff',
        'server' => [
            'type' => 'sqlite',
            'dbname' => 'wikicache',
            'tablePrefix' => '',
            'flags' => 0
        ]
    ];
}

$wgMainCacheType = CACHE_ACCEL;
$wgMemCachedServers = [];

$wgEnableUploads = false;
if (getenv('MEDIAWIKI_ENABLE_UPLOADS') == '1') {
    $wgEnableUploads = true;
}

if (getenv('MEDIAWIKI_FILE_EXTENSIONS') != '') {
    foreach (explode(', ', getenv('MEDIAWIKI_FILE_EXTENSIONS')) as $extension) {
        $wgFileExtensions[] = trim($extension);
    }
}

$wgUseImageMagick = true;
$wgImageMagickConvertCommand = "/usr/bin/convert";
$wgUseInstantCommons = true;
$wgShellLocale = "en_US.UTF-8";

if (getenv('MEDIAWIKI_LANGUAGE_CODE') != '') {
    $wgLanguageCode = getenv('MEDIAWIKI_LANGUAGE_CODE');
}

if (getenv('MEDIAWIKI_SECRET_KEY') != '') {
    $wgSecretKey = getenv('MEDIAWIKI_SECRET_KEY');
}

if (getenv('MEDIAWIKI_UPGRADE_KEY') != '') {
    $wgUpgradeKey = getenv('MEDIAWIKI_UPGRADE_KEY');
}

$wgAuthenticationTokenVersion = "1";

$wgDiff3 = "/usr/bin/diff3";

$wgGroupPermissions['*']['edit'] = false;
$wgShowIPinHeader = false;

$wgDefaultSkin = "vector";
if (getenv('MEDIAWIKI_DEFAULT_SKIN') != '') {
    $wgDefaultSkin = getenv('MEDIAWIKI_DEFAULT_SKIN');
}

# Enabled skins
wfLoadSkin( 'CologneBlue' );
wfLoadSkin( 'Modern' );
wfLoadSkin( 'MonoBook' );
wfLoadSkin( 'Vector' );

# Debug
if (getenv('MEDIAWIKI_DEBUG') == '1') {
    $wgShowExceptionDetails = true;
    $wgShowSQLErrors = true;
    $wgDebugDumpSql = true;
    $wgDebugLogFile = "/tmp/wiki-debug.log";
}

# SMTP E-Mail
if (getenv('MEDIAWIKI_SMTP') == '1') {
    $wgEnableEmail = true;
    $wgEnableUserEmail = true;
    $wgSMTP = array(
        'host'     => getenv('MEDIAWIKI_SMTP_HOST'), // could also be an IP address. Where the SMTP server is located
        'IDHost'   => getenv('MEDIAWIKI_SMTP_IDHOST'), // Generally this will be the domain name of your website (aka mywiki.org)
        'port'     => getenv('MEDIAWIKI_SMTP_PORT'), // Port to use when connecting to the SMTP server
        'auth'     => (getenv('MEDIAWIKI_SMTP_AUTH') == '1'), // Should we use SMTP authentication (true or false)
        'username' => getenv('MEDIAWIKI_SMTP_USERNAME'), // Username to use for SMTP authentication (if being used)
        'password' => getenv('MEDIAWIKI_SMTP_PASSWORD') // Password to use for SMTP authentication (if being used)
    );
}

# VisualEditor
if (getenv('MEDIAWIKI_ENABLE_VISUAL_EDITOR') == '' || getenv('MEDIAWIKI_ENABLE_VISUAL_EDITOR') == '1') {
    wfLoadExtension('VisualEditor');
    $wgDefaultUserOptions['visualeditor-enable'] = 1;
    $wgVirtualRestConfig['modules']['parsoid'] = array(
        'url' => 'http://localhost:8142',
        'domain' => 'localhost',
        'prefix' => ''
    );
    $wgSessionsInObjectCache = true;
    $wgVirtualRestConfig['modules']['parsoid']['forwardCookies'] = true;
}

# WikiEditor
wfLoadExtension( 'WikiEditor' );

# InputBox
wfLoadExtension( 'InputBox' );

# Math
wfLoadExtension( 'Math' );

# CodeEditor
wfLoadExtension( 'CodeEditor' );

# SyntaxHighlight
wfLoadExtension( 'SyntaxHighlight_GeSHi' );

# SpamBlacklist
wfLoadExtension( 'SpamBlacklist' );

# Parser Functions
wfLoadExtension( 'ParserFunctions' );

# Mobile Frontend
require_once "$IP/extensions/MobileFrontend/MobileFrontend.php";

# Scribunto
require_once "$IP/extensions/Scribunto/Scribunto.php";

# Load extra settings
require 'ExtraLocalSettings.php';
