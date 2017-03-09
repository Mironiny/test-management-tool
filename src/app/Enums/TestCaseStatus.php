<?php

namespace App\Enums;

/**
 * Enum for Test Run status.
 *
 */
abstract class TestCaseStatus
{
    const NOT_TESTED = "Not tested";
    const PASS = "Pass";
    const FAIL = "Fail";
    const BLOCKED = "Blocked";

}
