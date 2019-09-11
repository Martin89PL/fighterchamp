<?php

namespace Tests\Helper;

class DateFactory
{
    public static function createRandomDate(): \DateTime
    {
        return new \DateTime(
            mt_rand(1, 28).'-'.mt_rand(1, 12).'-'.mt_rand(1950, 2002)
        );
    }
}
