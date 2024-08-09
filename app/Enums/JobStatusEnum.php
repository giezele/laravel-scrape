<?php

namespace App\Enums;

/**
 * Class JobStatusEnum
 *
 * @package App\Enums
 */
enum JobStatusEnum: string
{
    case FAILED = 'failed';
    case COMPLETED = 'completed';
}
