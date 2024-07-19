<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mariadb';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'db';
$CFG->dbname    = 'moodle';
$CFG->dbuser    = 'moodle';
$CFG->dbpass    = 'm00dl3ing';
$CFG->prefix    = 'm_';
$CFG->dboptions = ['dbcollation' => 'utf8mb4_unicode_ci'];

//echo "dbtype...";
//print_r($CFG->dbtype);
phpinfo();

if (empty($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'localhost';
    echo 'http host empty';
}

$protocol = getenv('TUGBOAT_DEFAULT_SERVICE_URL_PROTOCOL');
$host = getenv('TUGBOAT_DEFAULT_SERVICE_URL_HOST');

//$CFG->wwwroot = getenv('TUGBOAT_SERVICE_URL');
$CFG->wwwroot = $protocol . '://' . $host;
$CFG->dataroot  = '/var/www/moodledata';
$CFG->admin     = 'admin';
$CFG->directorypermissions = 0777;
$CFG->smtphosts = 'mailpit:1025';
$CFG->noreplyaddress = 'noreply@example.com';

// Debug options - possible to be controlled by flag in future..
$CFG->debug = (E_ALL | E_STRICT); // DEBUG_DEVELOPER
$CFG->debugdisplay = 1;
$CFG->debugstringids = 1; // Add strings=1 to url to get string ids.
$CFG->perfdebug = 15;
$CFG->debugpageinfo = 1;
$CFG->allowthemechangeonurl = 1;
$CFG->passwordpolicy = 0;
$CFG->cronclionly = 0;
$CFG->pathtophp = '/usr/local/bin/php';

$CFG->phpunit_dataroot  = '/var/www/phpunitdata';
$CFG->phpunit_prefix = 't_';

$CFG->behat_wwwroot   = 'http://webserver';
$CFG->behat_dataroot  = '/var/www/behatdata';
$CFG->behat_prefix = 'b_';
$CFG->behat_profiles = array(
    'default' => array(
        'browser' => getenv('MOODLE_DOCKER_BROWSER'),
        'wd_host' => 'http://selenium:4444/wd/hub',
    ),
);
$CFG->behat_faildump_path = '/var/www/behatfaildumps';

define('PHPUNIT_LONGTEST', true);

require_once(__DIR__ . '/lib/setup.php');
