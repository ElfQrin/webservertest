<?php
$page_start_time=microtime(true);
# Web Server Test
# By Valerio Capello (Elf Qrin) - http://labs.geody.com/
$xprodver='v2.6 r2020-10-27'; # fr2016-10-01

# die(); # die unconditionately, locking out any access

/*
# direct IP based ban (blacklist / blocklist)
switch($_SERVER['REMOTE_ADDR']) {
case '192.0.2.0':
case '192.0.2.1':
die();
break;
default:
break;
}

# direct IP based ban (whitelist / passlist)
switch($_SERVER['REMOTE_ADDR']) {
case '192.0.2.0':
case '192.0.2.1':
break;
default:
die();
break;
}
*/

# if ($_GET['pwd']!='123'.'45') {die('unauthorized');} # Simple password protection



# Configuration

$xmpmode=false; # Example Mode / Test Mode. Note: it could also be invoked from a HTTP GET Request: mode=example

$oufmt=2; # Output: 1: Flat (No Tables), 2: Within Tables.

$tserver=true; # Test the Server
$tsts=array('host'=>true, 'ip'=>true, 'port'=>true, 'dateu'=>true, 'datel'=>true, 'lastboot'=>true, 'bootid'=>false, 'sysloadavg'=>true, 'os'=>true, 'webserversoft'=>true, 'php'=>true, 'php_gd'=>true, 'php_imagick'=>true, 'php_mbstring'=>true, 'php_sodium'=>true, 'php_mcrypt'=>false, 'db'=>true, 'ossl'=>true, 'osslphp'=>true, 'prot'=>true, 'memspace'=>true, 'memspacebar'=>true, 'swapspace'=>true, 'swapspacebar'=>true, 'diskspace'=>true, 'diskspacebar'=>true, 'file'=>true, 'chars'=>true, 'img'=>true, 'phpinfo'=>false, 'gentime'=>true); # Server: Test/Show Host Name, IP address, Port, Date (UTC), Date (Local), last boot / uptime, Boot ID, system load average, OS, Web Server Software, PHP, PHP GD, PHP Imagick (ImageMagick), PHP mbstring, PHP Sodium, PHP mcrypt [untested], DB (*SQL) server, OpenSSL, OpenSSL (PHP), protocol, memory space, memory space (bar graph), swap space, swap space (bar graph), disk space, disk space (bar graph), test file (create, write, read, delete), character test, image, PHPinfo, page generation time.
$shellex=true; # Enable tests that require the execution of a Linux shell command: Last Boot, Uptime, Boot ID, System Load Average, openssl [it doesn't affect PHP openssl extension], Memory, Swap.
$memsfmt=2; # Memory output format for the display (NOT for logs): 1: bytes, 2: human readable;
$barsiz=300; # Bars size (in pixels)

$tclient=true; # Test the Client
$tstc=array('ip'=>true, 'port'=>true, 'dateu'=>true, 'datel'=>true, 'os'=>true, 'browser'=>true, 'uagent'=>false); # Client: Test/Show IP address, Port, Date (UTC), Date (Local), OS, browser, User Agent. Note that information about the Client's OS and browser are gathered from the User Agent and may be forged.

$dbx="mysqli"; # MySQL, MySQLi, PostgreSQL
$db_host='localhost'; $db_user=''; $db_pwd=''; # DataBase Host, User name and Password
$db_name=''; # You can leave this empty

$maxsysloadavx1=30; # Maximum acceptable system load average over the last 1 minute
$maxsysloadavx2=20; # Maximum acceptable system load average over the last 5 minutes
$maxsysloadavx3=15; # Maximum acceptable system load average over the last 15 minutes
$mxcstimediff=60; # Maximum acceptable time difference (in seconds) between client and server
$mxtimepgen=.9; # Maximum acceptable time (in seconds) to generate the page
$ldf=200000; # Low disk free space (in bytes)

$tfile='/var/www/html/webservertest.txt'; # Path and name of the test file. The destination directory must be owned or enabled to be read and written by www-data:www-data
$tfilec='Webserver test file - THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG the quick brown fox jumps over the lazy dog 0123456789 - labs.geody.com'; # Data to be written into the test file (up to 1024 bytes)
$ttxtplain='Encryption test string - THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG the quick brown fox jumps over the lazy dog 0123456789 - labs.geody.com'; # String for the encryption test

$logen=false; # Enable logging // it can be scripted using  wget -q -O- http://www.example.com/webservertest.php >/dev/null  or  lynx -dump http://www.example.com/webservertest.php >/dev/null
$logfile='/var/log/webservertest/webservertest_'.gmdate('Y').'.log'; # Path and name of the log file. You can have yearly logs with '/var/log/webservertest/webservertest_'.gmdate('Y').'.log'; the destination directory must be owned or enabled to be read and written by www-data:www-data
$logem=array('shost'=>true, 'sip'=>true, 'sport'=>false, 'sdateu'=>true, 'sdatel'=>true, 'slastboot'=>true, 'sbootid'=>false, 'ssysloadavg'=>true, 'sos'=>true, 'swebserversoft'=>true, 'sphp'=>true, 'sdb'=>true, 'sossl'=>true, 'sosslphp'=>true, 'sprot'=>true, 'smemt'=>true, 'smemu'=>true, 'smema'=>true, 'smemf'=>true, 'sswpt'=>true, 'sswpu'=>true, 'sswpf'=>true, 'sdiskt'=>true, 'sdisku'=>true, 'sdiskf'=>true, 'sfile'=>true, 'cip'=>true, 'cport'=>false, 'cos'=>true, 'cbrowser'=>true, 'cuagent'=>false, 'xprobs'=>true); # Information to include in the log file: Server IP, Server Port, Server Hostname, Server UTC Date, Server Local Date, Server OS, Server Webserver Software, PHP Version, DB (*SQL) Version, OpenSSL, OpenSSL (PHP), protocol, Total Memory, Used Memory, Available Memory, Free Memory, Total Swap Space, Used Swap Space, Free Swap Space, Total Disk Space, Used Disk Space, Free Disk Space, Test File Status, Client IP, Client Port, Client OS, Client Browser, Client User Agent, Problems found.
$memsfmtl=1; # Memory output format for logs (NOT for the display): 1: bytes, 2: human readable;
$logitmsep=', '; # Separates log items
$logqs1='"'; # Precedes (prefix) a log item
$logqs2='"'; # Follows (suffix) a log item
$logentst=''; # Precedes (prefix) a log entry
$logenten="\n"; # Follows (suffix) a log entry

$msgstok='<span class="isok">'; # Start OK Message
$msgenok='</span>'; # End OK Message
$msgstwarn='<span class="warn">'; # Start Warning Message
$msgenwarn='</span>'; # End Warning Message


# Functions

function hrsize($bytes=0,$base=1024) {
$si_prefix=array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
$class=min((int)log($bytes , $base),count($si_prefix)-1);
return sprintf('%1.2f',$bytes/pow($base,$class)).' '.$si_prefix[$class];
}

function getRemoteOS($user_agent) {
$os_platform = "Unknown";
$os_array = array('/windows nt 10/i' => 'Windows 10', '/windows nt 6.3/i' => 'Windows 8.1', '/windows nt 6.2/i' => 'Windows 8', '/windows nt 6.1/i' => 'Windows 7', '/windows nt 6.0/i' => 'Windows Vista', '/windows nt 5.2/i' => 'Windows Server 2003/XP x64', '/windows nt 5.1/i' => 'Windows XP', '/windows xp/i' => 'Windows XP', '/windows nt 5.0/i' => 'Windows 2000', '/windows me/i' => 'Windows ME', '/win98/i' => 'Windows 98', '/win95/i' => 'Windows 95', '/win16/i' => 'Windows 3.11', '/macintosh|mac os x/i' => 'Mac OS X', '/mac_powerpc/i' => 'Mac OS 9', '/linux/i' => 'Linux', '/ubuntu/i' => 'Ubuntu', '/iphone/i' => 'iPhone', '/ipod/i' => 'iPod', '/ipad/i' => 'iPad', '/android/i' => 'Android', '/blackberry/i' => 'BlackBerry', '/webos/i' => 'webOS');
foreach ($os_array as $regex => $value) {
if (preg_match($regex, $user_agent)) {
$os_platform = $value; break;
}
}
return $os_platform;
}

function getRemoteBrowser($user_agent) {
$browser = "Unknown";
$browser_array = array('/msie/i' => 'Internet Explorer', '/edg/i' => 'Edge', '/chrome/i' => 'Chrome', '/firefox/i' => 'Firefox', '/opera/i' => 'Opera', '/netscape/i' => 'Netscape', '/maxthon/i' => 'Maxthon', '/konqueror/i' => 'Konqueror', '/lynx/i' => 'Lynx', '/wget/i' => 'Wget', '/safari/i' => 'Safari');
foreach ($browser_array as $regex => $value) {
if (preg_match($regex, $user_agent)) {
$browser = $value; break;
}
}
if (preg_match('/mobile/i', $user_agent)) {$browser.=' ('.'Mobile'.')';}
return $browser;
}

function progbar($tag='div',$sl=200,$ph=5,$bord=1,$pc=array(100),$col=array("#ee0000","#00ee00","#0000ee"),$colbord="#111111") {
$pcn=count($pc); $coln=count($col);
if ($pcn>0) {
if ($coln<=0) {$col=array("#ee0000","#00ee00","#0000ee"); $coln=count($col);}
echo '<'.$tag.' style="height:'.$ph.'px; width:'.$sl.'px; border:'.$bord.'px solid '.$colbord.'; background:linear-gradient(to right,';
$i2=0;
for ($i1=0; $i1<=$pcn; ++$i1) {
if ($i1==0) {$pos1=0;} else {$pos1=$pc[$i1-1];}
if ($i1>=$pcn) {$pos2=100;} else {$pos2=$pc[$i1];}
echo ' '.$col[$i2].' '.$pos1.'%, '.$col[$i2].' '.$pos2.'%';
if ($i1<$pcn) {echo ',';}
++$i2; if ($i2>=$coln) {$i2=0;}
}
echo ');">'.'</'.$tag.'>';
}
}

function xcrypt($cipher='sodium',$mode=0,$msg,$key) {
$cipher=strtolower(trim($cipher));
$r='';
switch ($cipher) {
case 'clear':
case 'cleartext':
case 'cleartxt':
$r=$msg;
return $r;
break;
default:
case 'sodium':
if ($mode==0) {
$nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
$r = base64_encode($nonce.sodium_crypto_secretbox($msg, $nonce, $key));
sodium_memzero($msg); sodium_memzero($key);
return $r;
} else {
$decoded = base64_decode($msg);
if ($decoded === false) {
$r='*** ERROR: Encoding failed';
sodium_memzero($msg); sodium_memzero($key);
# throw new Exception($r);
return $r;
}
if (mb_strlen($decoded, '8bit') < (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES)) {
$r='*** ERROR: Truncated message';
sodium_memzero($msg); sodium_memzero($key);
# throw new Exception($r);
return $r;
}
$nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
$decodedcipher = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
$r = sodium_crypto_secretbox_open($decodedcipher, $nonce, $key);
if ($r === false) {
$r='*** ERROR: The message was tampered with in transit';
sodium_memzero($msg); sodium_memzero($decodedcipher); sodium_memzero($key);
# throw new Exception($r);
return $r;
}
sodium_memzero($msg); sodium_memzero($decodedcipher); sodium_memzero($key);
# $r='*** ERROR: General Failure';
return $r;
}
break;
case 'openssl':
$cipher='aes-256-cbc';
$ivlen = openssl_cipher_iv_length($cipher);
# $iv = openssl_random_pseudo_bytes($ivlen);
$ivsec = '1234567890123456789012345678901234567890123456789012345678901234';
$iv = substr(hash('sha256', $ivsec), 0, $ivlen);
if ($mode==0) {
$r = base64_encode(openssl_encrypt($msg, $cipher, $key, 0, $iv));
return $r;
} else {
$r = openssl_decrypt(base64_decode($msg), $cipher, $key, 0, $iv);
return $r;
}
break;
case 'opensslshell':
$cipher='aes-256-cbc';
if ($mode==0) {
$r = shell_exec('echo -n "'.$msg.'"|openssl '.$cipher.' -pbkdf2 -salt -pass pass:'.$key.'|base64');
return $r;
} else {
$r = shell_exec('echo -n "'.$msg.'"|base64 -d|openssl '.$cipher.' -d -pbkdf2 -pass pass:'.$key);
return $r;
}
break;
case 'mcrypt':
if ($mode==0) {
$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
$crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $msg, MCRYPT_MODE_CBC, $iv);
$combo = $iv.$crypt;
$r = base64_encode($iv.$crypt);
return $r;
} else {
$combo = base64_decode($msg);
$iv = substr($combo, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));
$crypt = substr($combo, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), strlen($combo));
$r = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $crypt, MCRYPT_MODE_CBC, $iv);
return $r;
}
break;
}
return $r;
}


# Set default, failproof, values
$sysok=0; $dbn=''; $dbxinfo='Untested'; $tfiler='Untested';

$user_os = getRemoteOS(addslashes($_SERVER['HTTP_USER_AGENT']));
$user_browser = getRemoteBrowser(addslashes($_SERVER['HTTP_USER_AGENT']));

$dival="left"; if ($oufmt==2) {$dival='center';}

if ($xmpmode===true || strtolower(trim($_REQUEST['mode']))=='example') {
# Example mode / Test Mode
$xmp=true;
$logen=false; # Keep logging disabled in example mode to prevent fake information to be logged
} else {$xmp=false;}

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
?>
<!DOCTYPE html>
<html>
<head>
<title>Web Server Test<?php echo ' @ '.$_SERVER['HTTP_HOST'].' ('.$_SERVER['SERVER_ADDR'].')'; ?></title>
<meta name="Author" content="Valerio Capello - http://labs.geody.com/" />
<meta name="Description" content="Test if the webserver is up and running" />
<meta name="Generator" content="Handwritten using EditPlus" />
<meta name="Keywords" content="checkup test server webserver WAMP LAMP Windows Linux Apache PHP MySQL MySQLi PostgreSQL HTML JavaScript" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="Robots" content="NOINDEX,NOFOLLOW,NOARCHIVE" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<style type="text/css">
/* Light Theme */
/*
body {background-color: #ffffff; color: #222222; font-family: Arial, Helvetica, sans-serif;}
table.t1 {border-collapse: collapse; border: 1px solid #ddddcc; box-shadow: 1px 2px 3px #ccccaa; font-size: 85%; text-align: center; background-color: #ffffff;}
table.t2 {border-collapse: collapse; border: 1px solid #ddddcc; box-shadow: 1px 2px 3px #ccccaa; font-size: 85%; text-align: left; background-color: #ffffff;}
.isok {color: #00ee00;}
.warn {color: #ee0000;}
*/

/* Dark Theme */

body {background-color: #0e0e0e; color: #efefef; font-family: Arial, Helvetica, sans-serif;}
table.t1 {border-collapse: collapse; border: 1px solid #333322; box-shadow: 1px 2px 3px #444466; font-size: 85%; text-align: center; background-color: #121212;}
table.t2 {border-collapse: collapse; border: 1px solid #333322; box-shadow: 1px 2px 3px #444466; font-size: 85%; text-align: left; background-color: #121212;}
.isok {color: #55ee55;}
.warn {color: #ee5555;}


/* More */

table.th1 { border-collapse: collapse; }
h1 {display: block; font-size: 1.1em; margin-top: 1%; margin-bottom: 1%; margin-left: 0; margin-right: 0; font-weight: bold;}
h2 {display: block; font-size: 1.05em; margin-top: 1%; margin-bottom: 1%; margin-left: 0; margin-right: 0; font-weight: bold;}
.helem { font-weight: bold; }
.helem2 { font-weight: bold; font-style: italic; }
.txtsml {font-size: 70%;}
.prodver {font-size: 70%;}
.prodlink {font-size: 70%; text-decoration: none;}
img.im1 {float: none; border: 0; padding: 5px 1px 8px 1px;}

</style>
</head>
<body bgcolor="#FFFFFF" text="#222222">
<script language="JavaScript" type="text/javascript">
<!--

if (!Date.now) {
Date.now = function() { return new Date().getTime(); }
}

var ctmst=Math.floor(Date.now()/1000);
var stmst=<?php echo time(); ?>;
if (Math.abs(ctmst-stmst)><?php echo $mxcstimediff; ?>) { var wrntst='<?php echo $msgstwarn; ?>'; var wrnten='<?php echo $msgenwarn; ?>'; } else { var wrntst=''; var wrnten=''; }

// -->
</script>
<?php
$jswarnsttime='
<script language="JavaScript" type="text/javascript">
<!--
document.write(wrntst);
// -->
</script>
';
$jswarnentime='
<script language="JavaScript" type="text/javascript">
<!--
document.write(wrnten);
// -->
</script>
';
?>
<div name="main" id="main" align="<?php echo $dival; ?>" style="text-align: <?php echo $dival; ?>;">
<?php
if ($oufmt==2) {
echo '<table border="1" cellpadding="5" cellspacing="0" class="t1"><tr><td align="center">'."\n";
}

echo '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="th1"><tr>';

echo '<td align="left" valign="top" width="29%"></td>';

echo '<td align="center" valign="top" width="42%">';
echo '<h1>'.'Web Server Test'.'</h1>';

if ($tsts['img']) {
?>
<img src="webservertestimg.png" alt="Test image NOT loaded" title="Test" border="0" class="im1" /><br />
<?php
}

echo '</td>';

echo '<td align="right" valign="top" width="29%">';
echo '<span class="prodver">'.$xprodver.'</span>'."<br />";
echo '<a href="http://labs.geody.com/" target="_blank" class="prodlink">'.'by Geody Labs'.'</a>';
echo '</td>';

echo '</tr></table>';

if ($oufmt==2 && ($tserver || $tclient)) {
echo '<table border="1" cellpadding="5" cellspacing="0" class="t2"><tr><td>'."\n";
}

# Requires functions_db.php (if $tsts['db']==true), webservertestimg.png (if $tsts['img']==true)

# echo '['.$msgstwarn.'WARNING TEST'.$msgenwarn.']'."<br /><br />\n"; # ++$sysok;

if ($tserver) {

echo '<h2>'.'Server'.'</h2>';

if ($tsts['host']) {
echo '<span class="helem">'.'Host Name'.'</span>'.': ';
if (!$xmp) {
echo $_SERVER['HTTP_HOST'];
} else {
echo 'www.example.com';
}
echo "<br />\n";
}
if ($tsts['ip'] || $tsts['port']) {
if ($tsts['ip']) {
echo '<span class="helem">'.'IP address'.'</span>'.': ';
if (!$xmp) {
echo $_SERVER['SERVER_ADDR'];
} else {
echo '192.0.2.50';
}
}
if ($tsts['port']) {if ($tsts['ip']) {echo ' ';}; echo '<span class="helem">'.'Port'.'</span>'.': '.$_SERVER['SERVER_PORT'];}
echo "<br />\n";
}

if ($tsts['prot']) {
echo '<span class="helem">'.'Protocol'.'</span>'.': '.$_SERVER['SERVER_PROTOCOL'];
echo ' - ';
if ($_SERVER['HTTPS']) {echo 'Secure (HTTPS)';} else {echo 'NOT Secure';}
echo "<br />\n";
}

if ($tsts['dateu']) {echo '<span class="helem">'.'Date'.'</span>'.': '.$jswarnsttime.gmdate('D d-M-Y H:i:s').' '.'UTC'.$jswarnentime."<br />\n";}
if ($tsts['datel']) {
$ntzl=date('Z'); if ($ntzl>0) {$ntzsl="+";} else {$ntzsl="";}
echo '<span class="helem">'.'Date'.'</span>'.': '.$jswarnsttime.date('D d-M-Y H:i:s').' '.'UTC'.$ntzsl.($ntzl/3600).' (local)'.$jswarnentime."<br />\n";
}

if ($shellex) {

if ($tsts['lastboot'] || $logem['slastboot']) {
$upt_date=trim(shell_exec('uptime -s'));
}
if ($tsts['lastboot']) {
$upt_now=trim(shell_exec('uptime -p'));
echo '<span class="helem">'.'Last Boot'.'</span>'.': '.$upt_date.' ('.$upt_now.')'."<br />\n";
}

if ($tsts['bootid'] || $logem['sbootid']) {
if (!$xmp) {
$bootid=shell_exec('cat /proc/sys/kernel/random/boot_id');
$bootid=preg_replace('~[\r\n]+~','',$bootid);
} else {
$bootid='6c3a8c81-7c0a-4083-8f9a-28b5ee236577';
}
}
if ($tsts['bootid']) {
echo '<span class="helem">'.'Boot ID'.'</span>'.': '.$bootid."<br />\n";
}

if ($tsts['sysloadavg'] || $logem['ssysloadavg']) {
if (!$xmp) {
$sysloadav=shell_exec('uptime | awk -F\'[a-z]:\' \'{print $2}\''); # System Load Average over the last 1, 5, 15 minutes.
} else {
$sysloadav=' 0.45, 1.12, 1.26';
}
$sysloadava=explode(',',$sysloadav);
for ($i=0; $i<count($sysloadava); ++$i) {$sysloadava[$i]=(float)$sysloadava[$i];}
}

if ($tsts['sysloadavg']) {
if (!$xmp) {
$cpucoresn=trim(shell_exec('grep -c \'processor\' /proc/cpuinfo')); $cpucoresn=(int)$cpucoresn;
} else {
$cpucoresn=1;
}
echo '<span class="helem">'.'Load Avg'.'</span>';
echo ' ('.'<span class="helem2">'.'Cores'.'</span>'.': '.$cpucoresn.')';
echo ': ';
echo '<span class="helem2">'.'1 m'.'</span>'.': ';
if ($sysloadava[0]<=$maxsysloadavx1) {echo $sysloadava[0];} else {echo $msgstwarn.$sysloadava[0].$msgenwarn; ++$sysok;}
echo ', ';
echo '<span class="helem2">'.'5 m'.'</span>'.': ';
if ($sysloadava[1]<=$maxsysloadavx2) {echo $sysloadava[1];} else {echo $msgstwarn.$sysloadava[1].$msgenwarn; ++$sysok;}
echo ', ';
echo '<span class="helem2">'.'15 m'.'</span>'.': ';
if ($sysloadava[2]<=$maxsysloadavx3) {echo $sysloadava[2];} else {echo $msgstwarn.$sysloadava[2].$msgenwarn; ++$sysok;}
echo '.'."<br />\n";
}

}

# if ($tsts['os']) {echo 'Web Server OS'.': '.php_uname('s').' '.php_uname('r').' '.php_uname('v').' ('.php_uname('m').')'."<br />\n";}
if ($tsts['os']) {echo '<span class="helem">'.'Web Server OS'.'</span>'.': '.php_uname('s').' '.php_uname('v').' ('.php_uname('m').')'."<br />\n";}
if ($tsts['webserversoft']) {echo '<span class="helem">'.'Web Server Software'.'</span>'.': '.$_SERVER['SERVER_SOFTWARE']."<br />\n";}
if ($tsts['php']) {
echo '<span class="helem">'.'PHP'.'</span>'.': '.'Running';
echo '. '.'Version'.': '.PHP_VERSION;
echo "<br />\n";

if ($tsts['php_gd']) {
echo '<span class="helem">'.'PHP'.'</span>'.': '.'<span class="helem2">'.'GD'.'</span>'.': ';
if (extension_loaded('gd')) {echo 'Loaded'.'. '.'Version'.': '.gd_info()['GD Version'].' '.'<img src="webservertestimg_gd.php" height="10" width="10" alt="PHP GD Test Image" title="PHP GD Test Image" border="0" />';} else {echo $msgstwarn.'NOT loaded'.$msgenwarn; ++$sysok;}
echo "<br />\n";
}

if ($tsts['php_imagick']) {
echo '<span class="helem">'.'PHP'.'</span>'.': '.'<span class="helem2">'.'Imagick'.'</span>'.': ';
if (extension_loaded('imagick')) {
$phpimagickv=Imagick::getVersion();
echo 'Loaded'.'. '.'Version'.': <!-- '.$phpimagickv['versionString'].' --> '.$phpimagickv['versionNumber'].' '.'<img src="webservertestimg_imagick.php" height="10" width="10" alt="PHP Imagick Test Image" title="PHP Imagick Test Image" border="0" />';
} else {echo $msgstwarn.'NOT loaded'.$msgenwarn; ++$sysok;}
echo "<br />\n";
}

if ($tsts['php_mbstring']) {
echo '<span class="helem">'.'PHP'.'</span>'.': '.'<span class="helem2">'.'mbstring'.'</span>'.': ';
if (extension_loaded('mbstring')) {echo 'Loaded';} else {echo $msgstwarn.'NOT loaded'.$msgenwarn; ++$sysok;}
echo '.'."<br />\n";
}

if ($tsts['php_sodium']) {
echo '<span class="helem">'.'PHP'.'</span>'.': '.'<span class="helem2">'.'Sodium'.'</span>'.': ';
if (extension_loaded('sodium')) {
echo 'Loaded'.'. ';
if (extension_loaded('mbstring')) {
$txtplain=$ttxtplain;
$keyenc=random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
# echo ' ('.'Key (Hex)'.': '.bin2hex($keyenc).') '; # hex2bin(bin2hex($keyenc))
$txtenc=xcrypt('sodium',0,$txtplain,$keyenc);
# echo ' ('.'Sodium (ENC): '.$txtenc.') ';
$txtdec=xcrypt('sodium',1,$txtenc,$keyenc);
# echo $txtdec.' ';
if ($txtdec===$txtplain) {
echo $msgstok.'Encryption OK!'.$msgenok;
} else {
echo $msgstwarn.'Encryption FAILED';
if (substr($txtdec,0,11)=='*** ERROR: ') {echo ': '.substr($txtdec,11);}
echo $msgenwarn;
++$sysok;
}
} else {
echo ', '.'but mbstring extension (required for the test) is missing.';
}
} else {echo $msgstwarn.'NOT loaded'.$msgenwarn; ++$sysok;}
echo "<br />\n";
}

if ($tsts['php_mcrypt']) {
echo '<span class="helem">'.'PHP'.'</span>'.': '.'<span class="helem2">'.'mcrypt'.'</span>'.': ';
if (extension_loaded('mcrypt')) {
echo 'Loaded'.'. ';
$txtplain=$ttxtplain;
$keyenc='ABCDEabcde1234567890';
# echo ' ('.'Key'.': '.$keyenc.') ';
$txtenc=xcrypt('mcrypt',0,$txtplain,$keyenc);
# echo ' ('.'mcrypt (ENC): '.$txtenc.') ';
$txtdec=xcrypt('mcrypt',1,$txtenc,$keyenc);
# echo $txtdec.' ';
if ($txtdec===$txtplain) {echo $msgstok.'Encryption OK!'.$msgenok;} else {echo $msgstwarn.'Encryption FAILED'.$msgenwarn; ++$sysok;}
} else {echo $msgstwarn.'NOT loaded'.$msgenwarn; ++$sysok;}
echo "<br />\n";
}

}

if ($tsts['osslphp']) {
$osslphpv=OPENSSL_VERSION_TEXT.' '.'('.OPENSSL_VERSION_NUMBER.')';
if (strtolower(substr($osslphpv,0,8))==="openssl ") {$osslphpv=substr($osslphpv,strpos($osslphpv," ")+1);}
echo '<span class="helem">'.'PHP'.'</span>'.': '.'<span class="helem2">'.'OpenSSL'.'</span>'.': ';
echo $osslphpv.'. ';
$txtplain=$ttxtplain;
$keyenc='ABCDEabcde1234567890';
# echo ' ('.'Key'.': '.$keyenc.') ';
$txtenc=xcrypt('openssl',0,$txtplain,$keyenc);
# echo ' ('.'openssl php (ENC): '.$txtenc.') ';
$txtdec=xcrypt('openssl',1,$txtenc,$keyenc);
# echo $txtdec.' ';
if ($txtdec===$txtplain) {echo $msgstok.'Encryption OK!'.$msgenok;} else {echo $msgstwarn.'Encryption FAILED'.$msgenwarn; ++$sysok;}
echo "<br />\n";
}

if ($shellex && $tsts['ossl']) {
$osslv=shell_exec('openssl version -v'); $osslv=preg_replace('~[\r\n]+~','',$osslv);
if (strtolower(substr($osslv,0,8))==="openssl ") {$osslv=substr($osslv,strpos($osslv," ")+1);}
echo '<span class="helem">'.'OpenSSL'.'</span>'.' ('.'<span class="helem2">'.'Shell'.'</span>'.')'.': ';
echo $osslv.'. ';

$txtplain=$ttxtplain;
$keyenc='ABCDEabcde1234567890';
# echo ' ('.'Key'.': '.$keyenc.') ';
$txtenc=xcrypt('opensslshell',0,$txtplain,$keyenc);
# echo ' ('.'openssl (ENC): '.$txtenc.') ';
$txtdec=xcrypt('opensslshell',1,$txtenc,$keyenc);
# echo $txtdec.' ';
if ($txtdec===$txtplain) {echo $msgstok.'Encryption OK!'.$msgenok;} else {echo $msgstwarn.'Encryption FAILED'.$msgenwarn; ++$sysok;}
echo "<br />\n";
}


if ($tsts['db']) {
include('functions_db.php');

echo '<span class="helem">'.'DB'.'</span>'.': ';
switch (strtolower($dbx)) {
case 'mysql':
case 'mysqli':
$dbn='MySQL';
break;
case 'postgresql':
$dbn='PostgreSQL';
break;
default:
$dbn='DataBase';
break;
}
if ($dbxcon=dbx_connect($dbx,$db_host,$db_user,$db_pwd,$db_name)) {
$dbxinfo=dbx_server_info($dbx,$dbxcon);
echo '<span class="helem2">'.$dbn.'</span>'.' server is running';
echo '. '.'Version'.': '.$dbxinfo;
dbx_close($dbx,$dbxcon);
echo "<br />\n";
} else {
$dbxinfo='';
echo $msgstwarn.$dbn.' server is NOT running or NOT connected'.$msgenwarn; ++$sysok;
echo "<br />\n";
}
}

if ($shellex) {

if ($tsts['memspace'] || $tsts['memspacebar'] || $logem['smemt'] || $logem['smemu'] || $logem['smema'] || $logem['smemf']) {
$memspc=shell_exec('free -w | xargs | awk \'{print $9","$10","$11","$12","$13","$14","$15}\''); # Memory: Total, Used, Free, Shared, Buffers, Cache, Available
$memspca=explode(',',$memspc);
for ($i=0; $i<count($memspca); ++$i) {$memspca[$i]=(int)$memspca[$i];}
# $memspca[4]=$memspca[0]-($memspca[1]+$memspca[2]+$memspca[3]); # Other (cache / buffers)

if ($memspca[0]!=0) {
$memusdp=sprintf('%1.2f',$memspca[1]*100/$memspca[0]); $memfrep=sprintf('%1.2f',$memspca[2]*100/$memspca[0]); $membufp=sprintf('%1.2f',$memspca[4]*100/$memspca[0]); $memcacp=sprintf('%1.2f',$memspca[5]*100/$memspca[0]);
$memavlp=sprintf('%1.2f',$memspca[6]*100/$memspca[0]); $memunavlp=sprintf('%1.2f',($memspca[0]-$memspca[6])*100/$memspca[0]);
} else {
$memusdp=0; $memfrep=0; $membufp=0; $memcacp=0; $memavlp=0; $memunavlp=0;
}

}

if ($memsfmt==2) {
$memtot=hrsize($memspca[0]*1024); $memusd=hrsize($memspca[1]*1024); $memfre=hrsize($memspca[2]*1024); $memsha=hrsize($memspca[3]*1024); $membuf=hrsize($memspca[4]*1024); $memcac=hrsize($memspca[5]*1024); $memavl=hrsize($memspca[6]*1024); $memunavl=hrsize($memspca[0]*1024-$memspca[6]*1024);
} else {
$memtot=$memspca[0]*1024; $memusd=$memspca[1]*1024; $memfre=$memspca[2]*1024; $memsha=$memspca[3]*1024; $membuf=$memspca[4]*1024; $memcac=$memspca[5]*1024; $memavl=$memspca[6]*1024; $memunavl=$memspca[0]*1024-$memspca[6]*1024;
}

if ($tsts['memspace']) {
echo '<span class="helem">'.'Memory'.'</span>'.': ';
echo '<span class="helem2">'.'Tot'.'</span>'.': '.$memtot.', ';
echo '<span class="helem2">'.'Used'.'</span>'.': '.$memusd.' ('.$memusdp.'%'.')'.', '; echo "<br />";
echo '<span class="helem2">'.'Buffers'.'</span>'.': '.$membuf.' ('.$membufp.'%'.')'.', ';
echo '<span class="helem2">'.'Cache'.'</span>'.': '.$memcac.' ('.$memcacp.'%'.')'.', ';
echo '<span class="helem2">'.'Free'.'</span>'.': '.$memfre.' ('.$memfrep.'%'.')';
echo '<!-- ';
echo '; ';
echo '<span class="helem2">'.'Shared'.'</span>'.': '.$memsha;
echo ' -->';
echo '.';
echo "<br />\n";
}

if ($tsts['memspacebar']) {
progbar('div',$barsiz,3,1,array(floor($memusdp),floor($memusdp+$membufp),floor($memusdp+$membufp+$memcacp)),array("#ee55aa","#bb7711","#dd9933","#00ff00"),"#111111");
# echo "<br />\n";
}

if ($tsts['memspace']) {
echo '<span class="helem">'.'Memory'.'</span>'.': ';
echo '<span class="helem2">'.'Tot'.'</span>'.': '.$memtot.', ';
echo '<span class="helem2">'.'Unavail'.'</span>'.': '.$memunavl.' ('.$memunavlp.'%'.')'.', ';
echo '<span class="helem2">'.'Avail'.'</span>'.': '.$memavl.' ('.$memavlp.'%'.')'.'.';
echo "<br />\n";
}

if ($tsts['memspacebar']) {
progbar('div',$barsiz,3,1,array(floor(100-$memavlp)),array("#ee55aa","#00ff00"),"#111111");
# echo "<br />\n";
}

if ($tsts['swapspace'] || $tsts['swapspacebar'] || $logem['sswpt'] || $logem['sswpu'] || $logem['sswpf']) {
$swpspc=shell_exec('swapon --show --noheadings --raw --bytes | xargs | awk \'{print $1","$3","$4}\''); # Swap: Name, Total, Used
$swpspca=explode(',',$swpspc);
$swpspca[2]=preg_replace('~[\r\n]+~','',$swpspca[2]);
for ($i=0; $i<count($swpspca); ++$i) {$swpspca[$i]=(int)$swpspca[$i];}

$swpnam=$swpspca[0];
if ($memspca[1]!=0) {
$swpusdp=sprintf('%1.2f',$swpspca[2]*100/$swpspca[1]); $swpfrep=100-$swpusdp;
} else {
$swpusdp=0; $swpfrep=0;
}
}

if ($memsfmt==2) {
$swptot=hrsize($swpspca[1]); $swpusd=hrsize($swpspca[2]); $swpfre=hrsize($swpspca[1]-$swpspca[2]);
} else {
$swptot=$swpspca[1]; $swpusd=$swpspca[2]; $swpfre=$swpspca[1]-$swpspca[2];
}

if ($tsts['swapspace']) {
echo '<span class="helem">'.'Swap'.'</span>'.': ';
# echo '<span class="helem2">'.'Name'.'</span>'.': '.$swpnam.', ';
echo '<span class="helem2">'.'Tot'.'</span>'.': '.$swptot.', ';
echo '<span class="helem2">'.'Used'.'</span>'.': '.$swpusd.' ('.$swpusdp.'%'.')'.', ';
echo '<span class="helem2">'.'Free'.'</span>'.': '.$swpfre.' ('.$swpfrep.'%'.')'.'.';
echo "<br />\n";
}

if ($tsts['swapspacebar']) {
progbar('div',$barsiz,3,1,array(floor($swpusdp)),array("#ee55aa","#00ff00"),"#111111");
# echo "<br />\n";
}

}

if ($tsts['diskspace'] || $tsts['diskspacebar'] || $logem['sdiskt'] || $logem['sdisku'] || $logem['sdiskf']) {
if (!$xmp) {
$ds=disk_total_space('/'); 
$df=disk_free_space('/');
} else {
$ds=113.3*1024*1024;
$df=88.56*1024*1024;
}
$dso=$ds; $dfo=$df;
if ($ds!=0) {
$dfp=sprintf('%1.2f',$df*100/$ds); $dup=100-$dfp;
} else {
$dfp=0; $dup=0;
}
$du=$ds-$df; $duo=$du;
if ($memsfmt==2) {$ds=hrsize($ds); $df=hrsize($df); $du=hrsize($du);}
}

if ($tsts['diskspace']) {
echo '<span class="helem">'.'Disk'.'</span>'.': ';
echo '<span class="helem2">'.'Tot'.'</span>'.': '.$ds.', ';
echo '<span class="helem2">'.'Used'.'</span>'.': '.$du.' ('.$dup.'%'.')'.', ';
if ($dfo<$ldf) {echo $msgstwarn;}
echo '<span class="helem2">'.'Free'.'</span>'.': '.$df.' ('.$dfp.'%'.')';
if ($dfo<$ldf) {echo $msgenwarn; ++$sysok;}
echo '.';
echo "<br />\n";
}

if ($tsts['diskspacebar']) {
progbar('div',$barsiz,3,1,array(floor($dup)),array("#ee55aa","#00ff00"),"#111111");
# echo "<br />\n";
}

if ($tsts['file']) {
# test file (create, write, read, delete)
echo '<span class="helem">'.'Test File'.'</span>'.': ';
$do=true;
$fob=fopen($tfile,'wb'); if (!$fob) {echo $msgstwarn.'Cannot create test file'.$msgenwarn.'. '; $do=false; ++$sysok;} else {echo 'Created'.', ';}
if ($do) {$fow=fwrite($fob,$tfilec); if (!$fow) {echo $msgstwarn.'Cannot write on test file'.$msgenwarn.'. '; $do=false; ++$sysok;} else {echo 'Written'.', ';}}
fclose($fob);
if ($do) {$foa=fopen($tfile,'rb'); if (!$foa) {echo $msgstwarn.'Cannot open test file'.$msgenwarn.'. '; $do=false; ++$sysok;} else {echo 'Opened'.', ';}}
if ($do) {$data=fread($foa,1024); if (!$data) {echo $msgstwarn.'Cannot read test file'.$msgenwarn.'. '; $do=false; ++$sysok;} else {echo 'Read'.', '; if ($tfilec!=$data) {echo $msgstwarn.'Data read from the file does not match the written data'.$msgenwarn.', '; $rmatch=false; ++$sysok;} else {echo 'Verified'.', '; $rmatch=true;}}}
fclose($foa);
if ($do) {$foad=unlink($tfile); if (!$foad) {echo $msgstwarn.'Cannot remove test file'.$msgenwarn.'. '; $do=false; ++$sysok;} else {echo 'Removed'.'. ';}}
if ($do && $rmatch) {echo $msgstok.'Success!'.$msgenok; $tfiler='OK';} else {echo $msgstwarn.'FAILED!'.$msgenwarn; $tfiler='FAILED'; ++$sysok;}
echo "<br />\n";
}

if ($tsts['chars']) {
echo '<span class="helem">'.'Characters'.'</span>'.': '.'<span title="Numbers: 0123456789">0-9</span> <span title="Letters (lower case): abcdefghijklmonpqrstuvwxyz">a-z</span> <span title="Letters (Upper Case): ABCDEFGHIJKLMONPQRSTUVWXYZ">A-Z</a></a> | <span title="Accented Characters (Diacritic)">àçðñøšüÿž ÀÇÐÑØŠÜŸŽ</span><!-- | <span title="Porportional Test">WWW iii</span> | <span title="Disambiguation Test (Visually Similar Characters)">B83 bG64 1lIi oO0 gq9 sS5 uvUV zZ2</span> -->';
}

echo "<br />\n";
}

if ($tclient) {
if ($tserver) {echo "<br />\n";}

echo '<h2>'.'Client'.'</h2>';

if ($tstc['ip'] || $tstc['port']) {
if ($tstc['ip']) {
echo '<span class="helem">'.'IP address'.'</span>'.': ';
if (!$xmp) {
echo $_SERVER['REMOTE_ADDR'];
} else {
echo '192.0.2.'.rand(60,200);
}
}
if ($tstc['port']) {if ($tstc['ip']) {echo ' ';}; echo '<span class="helem">'.'Port'.'</span>'.': '.$_SERVER['REMOTE_PORT'];}
echo "<br />\n";
}
?>
<script language="JavaScript" type="text/javascript">
<!--

function npadf2(num,size) {
var r="0"+num;
return r.substr(r.length-size);
}

var dwds=new Array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
var dmms=new Array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
var ndateObj=new Date();

<?php if ($tstc['dateu']) { ?>
var nddu=ndateObj.getUTCDate();
var nmmu=ndateObj.getUTCMonth();
var nyyu=ndateObj.getUTCFullYear();
var ndwu=ndateObj.getUTCDay();
var nhhu=ndateObj.getUTCHours();
var nmnu=ndateObj.getUTCMinutes();
var nssu=ndateObj.getUTCSeconds();
document.writeln('<span class="helem">'+"Date"+'</span>'+": "+wrntst+dwds[ndwu]+" "+npadf2(nddu,2)+"-"+dmms[nmmu]+"-"+nyyu+" "+npadf2(nhhu,2)+":"+npadf2(nmnu,2)+":"+npadf2(nssu,2)+" "+"UTC"+wrnten+"<br />");
<?php } ?>

<?php if ($tstc['datel']) { ?>
var nddl=ndateObj.getDate();
var nmml=ndateObj.getMonth();
var nyyl=ndateObj.getFullYear();
var ndwl=ndateObj.getDay();
var nhhl=ndateObj.getHours();
var nmnl=ndateObj.getMinutes();
var nssl=ndateObj.getSeconds();
var ntzl=ndateObj.getTimezoneOffset(); ntzl*=-1;
if (ntzl>0) {var ntzsl="+";} else {var ntzsl="";}
document.writeln('<span class="helem">'+"Date"+'</span>'+": "+wrntst+dwds[ndwl]+" "+npadf2(nddl,2)+"-"+dmms[nmml]+"-"+nyyl+" "+npadf2(nhhl,2)+":"+npadf2(nmnl,2)+":"+npadf2(nssl,2)+" UTC"+ntzsl+""+(ntzl/60)+" "+"("+"local"+")"+wrnten+"<br />");
<?php } ?>

<?php if ($tstc['os']) { ?>document.writeln('<span class="helem">'+"Client OS"+'</span>'+": "+"<?php echo $user_os; ?>"+" "+"("+navigator.platform+")"+"<br />");<?php } ?>
<?php if ($tstc['browser']) { ?>document.writeln('<span class="helem">'+"Browser"+'</span>'+": "+"<?php echo $user_browser; ?>"+"<br />");<?php } ?>
<?php if ($tstc['uagent']) { ?>document.writeln('<span class="helem">'+"User Agent"+'</span>'+": "+"<?php echo addslashes($_SERVER['HTTP_USER_AGENT']); ?>"+"<br />");<?php } ?>

document.writeln('<span class="helem">'+"JavaScript"+'</span>'+": "+"Enabled"+"<br />");
// -->
</script>
<noscript>
<span class="helem">JavaScript</span>: <?php echo $msgstwarn; ?>DISABLED<?php echo $msgenwarn; ?>
</noscript>
<?php
}

if ($oufmt==2 && ($tserver || $tclient)) {
echo '</td></tr></table>'."\n";
}

if ($oufmt==2) {
echo '</td></tr></table>'."\n";
}

if ($logen) {
$oul=$logentst; $itm=0;
if ($logem['shost']) {$oul.=$logqs1.addslashes('HSTS'.' '.$_SERVER['HTTP_HOST']).$logqs2; $itm++;}
if ($logem['sip']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('IPS'.' '.$_SERVER['SERVER_ADDR']).$logqs2; $itm++;}
if ($logem['sport']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('PTS'.' '.$_SERVER['SERVER_PORT']).$logqs2; $itm++;}
if ($logem['sdateu']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('DATEU'.' '.gmdate('Y-m-d H:i:s')).' '.'UTC'.$logqs2; $itm++;}
if ($logem['sdatel']) {if ($itm>0) {$oul.=$logitmsep;}; $ntzl=date('Z'); if ($ntzl>0) {$ntzsl="+";} else {$ntzsl="";}; $oul.=$logqs1.addslashes('DATEL'.' '.date('Y-m-d H:i:s')).' '.'UTC'.$ntzsl.($ntzl/3600).$logqs2; $itm++;}
if ($logem['slastboot'] && $shellex) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('LBOOT'.' '.$upt_date).$logqs2; $itm++;}
if ($logem['sbootid'] && $shellex) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('BOOTID'.' '.$bootid).$logqs2; $itm++;}
if ($logem['ssysloadavg'] && $shellex) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('SLDAVG'.' '.$sysloadava[0].';'.$sysloadava[1].';'.$sysloadava[2]).$logqs2; $itm++;}
# if ($logem['sos']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('OS'.' '.php_uname('s').' '.php_uname('r').' '.php_uname('v').' ('.php_uname('m').')').$logqs2; $itm++;}
if ($logem['sos']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('OS'.' '.php_uname('s').' '.php_uname('v').' ('.php_uname('m').')').$logqs2; $itm++;}
if ($logem['swebserversoft']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('WSRV'.' '.$_SERVER['SERVER_SOFTWARE']).$logqs2; $itm++;}
if ($logem['sphp']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('PHP'.' '.PHP_VERSION).$logqs2; $itm++;}
if ($logem['sdb']) {if ($itm>0) {$oul.=$logitmsep;}; if ($dbxinfo=='') {$dbxinfo='FAILED';}; $oul.=$logqs1.addslashes('DB'.' '.$dbn.' '.$dbxinfo).$logqs2; $itm++;}
if ($logem['sossl'] && $shellex) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('OSSL'.' '.$osslv).$logqs2; $itm++;}
if ($logem['sosslphp']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('OSSL-PHP'.' '.$osslphpv).$logqs2; $itm++;}
if ($logem['sprot']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('PROT'.' '.$_SERVER['SERVER_PROTOCOL']); if ($_SERVER['HTTPS']) {$oul.=addslashes(' (HTTPS)');} else {$oul.=addslashes(' (HTTP)');}; $oul.=$logqs2; $itm++;}
if (($logem['smemt'] || $logem['smemu'] || $logem['smema'] || $logem['smemf']) && $shellex) {
if ($memsfmtl==2) {
$memtot=hrsize($memspca[0]*1024); $memusd=hrsize($memspca[1]*1024); $memfre=hrsize($memspca[2]*1024); $memavl=hrsize($memspca[3]*1024);
} else {
$memtot=$memspca[0]*1024; $memusd=$memspca[1]*1024; $memfre=$memspca[2]*1024; $memavl=$memspca[3]*1024;
}
if ($logem['smemt']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('MET'.' '.$memtot).$logqs2; $itm++;}
if ($logem['smemu']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('MEU'.' '.$memusd).$logqs2; $itm++;}
if ($logem['smema']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('MEA'.' '.$memfre).$logqs2; $itm++;}
if ($logem['smemf']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('MEF'.' '.$memavl).$logqs2; $itm++;}
}
if (($logem['sswpt'] || $logem['sswpu'] || $logem['sswpf']) && $shellex) {
if ($memsfmtl==2) {
$swptot=hrsize($swpspca[1]); $swpusd=hrsize($swpspca[2]); $swpfre=hrsize($swpspca[1]-$swpspca[2]);
} else {
$swptot=$swpspca[1]; $swpusd=$swpspca[2]; $swpfre=$swpspca[1]-$swpspca[2];
}
if ($logem['sswpt']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('SWT'.' '.$swptot).$logqs2; $itm++;}
if ($logem['sswpu']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('SWU'.' '.$swpusd).$logqs2; $itm++;}
if ($logem['sswpf']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('SWF'.' '.$swpfre).$logqs2; $itm++;}
}
if ($logem['sdiskt'] || $logem['sdisku'] || $logem['sdiskf']) {
if ($memsfmtl==2) {$ds=hrsize($dso); $df=hrsize($dfo); $du=hrsize($duo);} else {$ds=$dso; $df=$dfo; $du=$duo;}
if ($logem['sdiskt']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('DKT'.' '.$ds).$logqs2; $itm++;}
if ($logem['sdisku']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('DKU'.' '.$du).$logqs2; $itm++;}
if ($logem['sdiskf']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('DKF'.' '.$df).$logqs2; $itm++;}
}
if ($logem['sfile']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('FILE'.' '.$tfiler).$logqs2; $itm++;}
if ($logem['cip']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('IPC'.' '.$_SERVER['REMOTE_ADDR']).$logqs2; $itm++;}
if ($logem['cport']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('PTC'.' '.$_SERVER['REMOTE_PORT']).$logqs2; $itm++;}
if ($logem['cos']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('OSC'.' '.$user_os).$logqs2; $itm++;}
if ($logem['cbrowser']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('BRWSC'.' '.$user_browser).$logqs2; $itm++;}
if ($logem['cuagent']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('UAGC'.' '.$_SERVER['HTTP_USER_AGENT']).$logqs2; $itm++;}
if ($logem['xprobs']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('PROBLEMS'.' '.$sysok).$logqs2; $itm++;}
$oul.=$logenten;
# echo '['.$oul.']';
$do=true;
$fob=fopen($logfile,'a'); if (!$fob) {echo $msgstwarn.'Cannot create/open log file for writing'.$msgenwarn.'. '; $do=false; ++$sysok;}
if ($do) {
$fow=fwrite($fob,$oul); if (!$fow) {echo $msgstwarn.'Cannot write on log'.$msgenwarn.'. '; $do=false; ++$sysok;}
fclose($fob);
}
}

?>
</div>
<?php
if ($tsts['phpinfo']) {
echo '<div name="extra" id="extra">';
phpinfo();
echo '</div>';
}

if ($tsts['gentime']) {
$page_end_time=microtime(true);
$page_time_gen=round($page_end_time-$page_start_time,5);

echo '<span class="txtsml">';
if ($page_time_gen>$mxtimepgen) {echo $msgstwarn;}
echo 'Page generated in'.' '.$page_time_gen.' '.'seconds'.'.';
if ($page_time_gen>$mxtimepgen) {echo $msgenwarn; ++$sysok;}
echo '</span>';
echo "<br />";
echo '<span class="txtsml">';
# Note that some problems may remain undetected, like missing images.
if ($sysok===0) {echo $msgstok.'All Systems Go!'.$msgenok;} else {
echo $msgstwarn.'WARNING'.': '.$msgenwarn;
if ($sysok==1) {
echo $msgstwarn.$sysok.' '.'problem encountered during testing.'.$msgenwarn;
} else {
echo $msgstwarn.$sysok.' '.'problems encountered during testing.'.$msgenwarn;
}
}
echo '</span>';
}
?>
</body>
</html>