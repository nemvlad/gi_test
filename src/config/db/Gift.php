<?php

namespace App\config\db;

class Gift extends Generic
{
    const id = 'id';
    const object = 'object';
    const giver = 'giver';
    const recipient = 'recipient';
    const donationTime = 'donation_time';
    const isTaken = 'is_taken';

    static function getTableName()
    {
        return 'gifts';
    }
}