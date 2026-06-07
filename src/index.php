<?php

declare(strict_types=1);

// load functions
require_once "../vendor/autoload.php";
require_once "stats.php";
require_once "card.php";
require_once "cache.php";
require_once "generator.php";
require_once "request.php";

// load .env
$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->safeLoad();

// if environment variables are not loaded, display error
if (!isset($_SERVER["TOKEN"])) {
    $message = file_exists(dirname(__DIR__ . "../.env", 1))
        ? "Missing token in config. Check Contributing.md for details."
        : ".env was not found. Check Contributing.md for details.";
    renderOutput($message, 500);
}

$cacheDisabled = isStreakCacheDisabled($_SERVER);
if ($cacheDisabled) {
    header("Cache-Control: no-store, max-age=0");
    header("Expires: 0");
} else {
    // set cache to refresh once per day (24 hours)
    $cacheSeconds = CACHE_DURATION;
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + $cacheSeconds) . " GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: public, max-age=$cacheSeconds");
}

$user = resolveStreakUser($_REQUEST, $_SERVER);

// redirect to demo site if neither STREAK_USER nor a request user is given
if ($user === null) {
    header("Location: demo/");
    exit();
}

try {
    $stats = generateStreakStats($user, $_REQUEST);
    renderOutput($stats);
} catch (InvalidArgumentException | AssertionError $error) {
    error_log("Error {$error->getCode()}: {$error->getMessage()}");
    if ($error->getCode() >= 500) {
        error_log($error->getTraceAsString());
    }
    renderOutput($error->getMessage(), $error->getCode());
}
