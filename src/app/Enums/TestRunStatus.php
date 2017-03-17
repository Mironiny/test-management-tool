<?php

namespace App\Enums;

/**
 * Enum for Test Run status.
 *
 */
abstract class TestRunStatus
{
    const RUNNING = "Running";
    const FINISHED = "Finished";
    const ARCHIVED = "Archived";
}
