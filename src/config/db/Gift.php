<?php

use App\config\db\Generic;

class Gift extends Generic
{
    const id = 'id';
    const object = 'object';
    const giver = 'giver';
    const recipient = 'recipient';
    const donationTime = 'donationTime';
    const isTaken = 'isTaken';

    static function getTableName()
    {
        return 'gift';
    }
}