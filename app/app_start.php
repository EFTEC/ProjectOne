<?php /** @noinspection PhpUnusedAliasInspection */
/** @noinspection UnknownInspectionInspection */
/** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpUnused */

use eftec\bladeone\BladeOne;
use eftec\bladeonehtml\BladeOneHtml;
use eftec\CacheOne;
use eftec\IPdoOneCache;
use eftec\PdoOne;
use eftec\routeone\RouteOne;
include __DIR__."/../vendor/autoload.php";

// general
define('DEBUGMODE',true);

// BLADEONE
define('WEBROOTFRONT','http://localhost');

// DATABASE
define('DATABASE_TYPE','mysql');
define('DATABASE_SERVER','127.0.0.1'); // for mysql it could be 127.0.0.1 or 127.0.0.1:3306
define('DATABASE_USER','web');
define('DATABASE_PASSWORD','');
define('DATABASE_SCHEMA','example'); // our database/schema/USER

// cache (optional)
define('CACHE_TYPE','redis');
define('CACHE_SERVER','127.0.0.1');
define('CACHE_SCHEMA','projectone');
define('CACHE_PORT',6379);


class BladeAll extends  BladeOne {
    use BladeOneHtml;
}

/**
 * @return BladeAll
 */
function blade() {
    global $blade;
    if($blade===null) {
        $blade=new BladeAll();
        $blade->setMode(DEBUGMODE? BladeOne::MODE_AUTO : BladeOne::MODE_DEBUG );
        $blade->setBaseUrl(WEBROOTFRONT);
    }
    return $blade;
}



function cache() {
    global $cache;
    if($cache===null) {
        $cache=new CacheOne(CACHE_TYPE,CACHE_SERVER,CACHE_SCHEMA,CACHE_PORT);
    }
    return $cache;
}

/**
 * Wrapper of getDB() returns a singleton of PdoOne
 * @return PdoOne
 */
function pdoOne() {
    return getDb();
}

/**
 * returns a singleton of PdoOne
 * @return PdoOne
 */
function getDb() {
    global $db;
    if($db===null) {

        $db=new PdoOne(DATABASE_TYPE,DATABASE_SERVER,DATABASE_USER,DATABASE_PASSWORD,DATABASE_SCHEMA);
        if(CACHE_TYPE!==null) {
            $cache=cache();
            $db->setCacheService($cache);
        }
        $db->logLevel=DEBUGMODE?3:0;
        try {
            $db->Connect();
        } catch (Exception $e) {
            echo "database is gone<br>";
            die(1);
        }
    }
    return $db;
}



