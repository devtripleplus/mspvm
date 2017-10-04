<?php


//Database Details
define('DBHOST','localhost');
define('DBUSER','root');
define('DBPASS','');
define('DBNAME','mspvm');

define('SALTSTAFF','rSHM5J8I0QkF7UtZA;x3M8XdvsH-vE9d472gs:C0U6m58xob4462OlHa48oo3|2.J1XDuK.8A%0d4k9=9Gq7EL:!|%001TI77+7282Jm9n=aU%vVYq847gu1C5o7Pazv'); //DO NOT CHANGE THIS. THERE WILL BE NO WAY TO RESET ADMIN PASSWORDS. 

define('SALTSITE',':|!-!F_.:|4~^.%_!60_i^P:;qi^!-^^!!R|ki;|hEb38w.Q+.;%||a=y%i^!+c7!*7|;~9NUU*9C|%.-0G38Tc~.G0B;~-^oq-hO_--x.h0PFiP:9!~2%;la1b*_gWw'); //DO NOT CHANGE THIS. All users will have to reset their passwords. 

/* DONT CHANGE ANYTHING BELOW THIS LINE */

//Session Starts and Shutdown function is registred
sessionManager::sessionStart('MSPVM');

ob_start();
function endOfScript()
{
	ob_end_flush();
}
register_shutdown_function('endOfScript');

$db =  new ezSQL_mysqli(DBUSER,DBPASS,DBNAME,DBHOST);

define('SETTINGS', 'settings');
define('USERS', 'users');
define('SESSIONS', 'sessions');

$timezone = $db->get_row("SELECT * FROM ".SETTINGS." WHERE name='timezone'"); //todo make a general function
$timezone = empty($timezone) ? 'America/Chicago' : $timezone->value;

date_default_timezone_set($timezone);

?>