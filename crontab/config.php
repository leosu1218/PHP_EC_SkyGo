<?PHP
/********************************************
 *
 * 路徑定義
 *
 ********************************************/
define('ROOT', str_replace('\\', '/',dirname(dirname(__FILE__)) . '/' ));
define('CRONTAB_PATH', ROOT . 'crontab/');
define('FRAMEWORK_PATH', ROOT . 'framework/');
define('DB_NAME', 'lifecom_skygo');
define('DB_CONNECT_NAME', 'lifecom_skygo');
define('DB_LOGIN_USRE', 'lifecom_skygo');
define('DB_LOGIN_PASSWORD', 'TtF=Zk$@TRyG');
define('TRADE_LOG_PATH', ROOT . "../trade_logs/");
define('MAIL_API_PATH', 'http://' . $_SERVER['HTTP_HOST'] . '/api/mail');
define('REPORT', ROOT . 'reports/');
?>