<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once "src/request.php";

final class RequestTest extends TestCase
{
    public function testConfiguredUserOverridesRequestUser(): void
    {
        $this->assertSame(
            "alvinlucillo",
            resolveStreakUser(
                ["user" => "someone-else", "theme" => "tokyonight"],
                [
                    "STREAK_USER" => "alvinlucillo",
                ],
            ),
        );
    }

    public function testRequestUserIsUsedWithoutConfiguredUser(): void
    {
        $this->assertSame("octocat", resolveStreakUser(["user" => "octocat"], []));
    }

    public function testMissingUserReturnsNull(): void
    {
        $this->assertNull(resolveStreakUser(["theme" => "tokyonight"], []));
    }

    public function testCacheIsDisabledOnlyForTrueValue(): void
    {
        $this->assertTrue(isStreakCacheDisabled(["DISABLE_CACHE" => "true"]));
        $this->assertTrue(isStreakCacheDisabled(["DISABLE_CACHE" => " TRUE "]));
        $this->assertFalse(isStreakCacheDisabled([]));
        $this->assertFalse(isStreakCacheDisabled(["DISABLE_CACHE" => "false"]));
    }
}
