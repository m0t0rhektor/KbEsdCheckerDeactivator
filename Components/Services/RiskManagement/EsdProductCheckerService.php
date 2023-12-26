<?php

namespace KbEsdCheckerDeactivator\Components\Services\RiskManagement;

use SwagPaymentPayPalUnified\Components\Services\RiskManagement\EsdProductCheckerInterface;
use Doctrine\DBAL\Connection;

class EsdProductCheckerService implements EsdProductCheckerInterface
{
    /**
     * @var Connection
     */
    private $connection;

    // public function __construct(Connection $connection)
    // {
    //     $this->connection = $connection;
    // }

    public function checkForEsdProducts(array $productIds)
    {
        return false;
    }

    public function getEsdProductNumbers($categoryId)
    {
        return array();
    }
}
