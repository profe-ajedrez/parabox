<?php /** @noinspection ALL */

/**
 * Test unitario para probar clase ConnectionPg.php
 */

use parabox\services\connection\ConnectionPg;

try {
    ConnectionPg::open($config["cdn"]);
} catch (Exception $e) {
}

