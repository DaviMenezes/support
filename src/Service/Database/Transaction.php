<?php

namespace Dvi\Support\Service\Database;

use Adianti\Base\Lib\Database\TTransaction;
use Adianti\Base\Lib\Widget\Dialog\TMessage;

/**
 * Transaction
 * Manage Transactions
 */
class Transaction
{
    protected static $connection;

    public static function open($conn = 'default')
    {
        self::$connection = TTransaction::get();
        if (!self::$connection) {
            self::$connection = TTransaction::open($conn);
        }
        return self::$connection;
    }

    public static function close()
    {
        if (TTransaction::get()) {
            TTransaction::close();
        }
    }

    public static function rollback()
    {
        if (TTransaction::get()) {
            TTransaction::rollback();
        }
    }

    public static function db(\Closure $closure)
    {
        try {
            self::open();
            $closure();
            self::close();
        } catch (\Exception $e) {
            self::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    public static function get()
    {
        $conn = TTransaction::get();
        if (!$conn) {
            self::$connection = TTransaction::get();
        }
        return self::$connection;
    }
}
