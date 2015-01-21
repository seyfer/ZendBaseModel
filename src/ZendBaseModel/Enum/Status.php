<?php

namespace ZendBaseModel\Enum;

/**
 * Description of Status
 *
 * @author seyfer
 */
abstract class Status extends BaseEnum
{

    const STATUS_NEW        = 0;
    const STATUS_IN_PROCESS = 1;
    const STATUS_OK         = 2;
    const STATUS_ERROR      = 3;
    const STATUS_FOR_UPDATE = 4;

}
