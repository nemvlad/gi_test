<?php

use App\config\db\Gift;
use App\models\GiftModel;

class ValidateGiftJob
{
    const GIFTS_TABLE_NAME = 'gifts';

    const VALIDATION_TIME = 10080; // 60 * 24 * 7

    /** @var \Slim\PDO\Database $pdo */
    private $db = null;

    public function __construct(Slim\PDO\Database $db)
    {
        $this->db = $db;
    }

    public function run()
    {
        $pdo = $this->db;

        $tableName = self::GIFTS_TABLE_NAME;

        $curTime = (new \DateTime())->getTimestamp();

        $selectStatement = $pdo
            ->select()
            ->from($tableName)
            ->where(Gift::isTaken, '=', GiftModel::STATUS_NOT_TAKEN)
            ->where(Gift::donationTime, '>', $curTime - self::VALIDATION_TIME);

        $stmt = $selectStatement->execute();
        $objects = $stmt->fetchAll();

        if(empty($objects))
            return;

        $giftIds = [];
        foreach ($objects as $object) {
            $giftIds[] = $object[Gift::id];
        }

        $deleteStatement = $pdo
            ->delete()
            ->from($tableName)
            ->whereIn(Gift::id, $giftIds);

        $deleteStatement->execute();
    }
}


try {

    $c = require __DIR__ . '/../settings.php';

    $dsn = 'mysql:host='.$c['settings']['db']['host'].';dbname='.$c['settings']['db']['database'].';charset=utf8';
    $usr = $c['settings']['db']['username'];
    $pwd = $c['settings']['db']['password'];
    $pdo = new \Slim\PDO\Database($dsn, $usr, $pwd);

    $job = new ValidateGiftJob($pdo);
    $job->run();

} catch (Exception $ex) {

}
