<?php

declare(strict_types=1);

/**
 * Resolve the GitHub username for a request.
 *
 * A configured STREAK_USER locks the service to one account while preserving
 * request parameters such as theme. Otherwise, use the request's user value.
 *
 * @param array<string,mixed> $request
 * @param array<string,mixed> $server
 */
function resolveStreakUser(array $request, array $server): ?string
{
    $configuredUser = trim(strval($server["STREAK_USER"] ?? ""));
    if ($configuredUser !== "") {
        return $configuredUser;
    }

    if (!isset($request["user"])) {
        return null;
    }

    $requestedUser = trim(strval($request["user"]));
    return $requestedUser === "" ? null : $requestedUser;
}

/**
 * Check whether contribution and HTTP caching are disabled.
 *
 * @param array<string,mixed> $server
 */
function isStreakCacheDisabled(array $server): bool
{
    return strtolower(trim(strval($server["DISABLE_CACHE"] ?? ""))) === "true";
}
