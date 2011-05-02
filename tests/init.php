<?php

ini_set('display_errors', 1);
require __DIR__ . '/test-config.php';

/**
 * @return \PDO
 */
function scrl_get_PDO() {
    $db = scrl_get_db_creds();
    $pdo = new PDO("mysql:host=$db->host;dbname=$db->dbname", $db->username, $db->password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    return $pdo;
}

/**
 * @return \Zend_Db_Adapter_Mysqli
 */
function scrl_get_ZendDb() {
    return new Zend_Db_Adapter_Mysqli((array) scrl_get_db_creds());
}


function scrl_rebuild_tables() {
    $pdo = scrl_get_PDO();
    $mgr = new ScopedRole\Util_TableManager($pdo);
    $mgr->dropTables();
    $mgr->createTables();
}


/**
 * pTest - PHP Unit Tester
 * @param mixed $test Condition to test, evaluated as boolean
 * @param string $message Descriptive message to output upon test
 * @url http://www.sitepoint.com/blogs/2007/08/13/ptest-php-unit-tester-in-9-lines-of-code/
 */
function assertTrue($test, $message)
{
    static $count;
    if (!isset($count)) $count = array('pass'=>0, 'fail'=>0, 'total'=>0);

    $mode = $test ? 'pass' : 'fail';
    $outMode = $test ? 'PASS' : '!FAIL';
    printf("%s: %s (%d of %d tests run so far have %sed)\n",
            $outMode, $message, ++$count[$mode], ++$count['total'], $mode);

    return (bool)$test;
}

scrl_setup_autoloading();
