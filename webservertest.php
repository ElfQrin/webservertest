<?
$page_start_time=microtime(true);
# Web Server Test
# By Valerio Capello ( http://labs.geody.com/ )
# v1.2 r2016-11-03 fr2016-10-01

# if ($_GET['pwd']!='123'.'45') {die('unauthorized');} # Simple password protection

# Configuration

$oufmt=2; # Output: 1: Flat (No Tables), 2: Within Tables.

$tserver=true; # Test the Server
$tsts=array('host'=>true, 'ip'=>true, 'dateu'=>true, 'datel'=>true, 'os'=>true, 'webserversoft'=>true, 'php'=>true, 'db'=>true, 'diskspace'=>true, 'diskspacebar'=>false, 'file'=>true, 'img'=>true, 'phpinfo'=>false, 'gentime'=>true); # Server: Test/Show Host Name, IP address, Date (UTC), Date (Local), OS, Web Server Software, PHP, PHPinfo, DB (*SQL) server, disk space, disk space (bar graph), test file (create, write, read, delete), image, PHPinfo, page generation time.
$dsfmt=2; # Disk Space format: 1: bytes, 2: human readable;

$tclient=true; # Test the Client
$tstc=array('ip'=>true, 'dateu'=>true, 'datel'=>true, 'os'=>true, 'browser'=>true, 'uagent'=>false); # Client: Test/Show IP address, Date (UTC), Date (Local), OS, browser. Note that information about the Client's OS and browser are gathered from the User Agent and may be forged.

$dbx="mysqli"; # MySQL, MySQLi, PostgreSQL
$db_host='localhost'; $db_user=''; $db_pwd=''; # DataBase Host, User name and Password
$db_name=''; # You can leave this empty

$mxcstimediff=60; # Maximum acceptable time difference (in seconds) between client and server

$ldf=200000; # Low disk free space (in bytes)

$tfile='/var/www/html/webservertest.txt'; # Path and name of the test file. The destination directory must be owned or enabled to be read and written by www-data:www-data
$tfilec='Webserver test file - THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG the quick brown fox jumps over the lazy dog 0123456789 - http://labs.geody.com/'; # Data to be written into the test file (up to 1024 bytes)

$logen=true; # Enable logging // it can be scripted using  wget -q -O- http://www.example.com/webservertest.php >/dev/null  or  lynx -dump http://www.example.com/webservertest.php >/dev/null
$logfile='/var/log/webservertest/webservertest.log'; # Path and name of the log file. You can have yearly logs with '/var/log/webservertest/webservertest_'.gmdate('Y').'log'; the destination directory must be owned or enabled to be read and written by www-data:www-data
$logem=array('shost'=>true, 'sip'=>true, 'sdateu'=>true, 'sdatel'=>true, 'sos'=>true, 'swebserversoft'=>true, 'sphp'=>true, 'sdb'=>true, 'sdiskt'=>true, 'sdiskf'=>true, 'sdisku'=>true, 'sfile'=>true, 'cip'=>true, 'cos'=>true, 'cbrowser'=>true, 'cuagent'=>false); # Information to include in the log file: Server IP, Server Hostname, Server UTC Date, Server Local Date, Server OS, Server Webserver Software, PHP Version, MySQL Version, Total Disk Space, Free Disk Space, Used Disk Space, Test File Status, Client IP, Client OS, Client Browser, Client User Agent.
$dsfmtl=1; # Disk Space format for logs: 1: bytes, 2: human readable;
$logitmsep=', '; # Separates log items
$logqs1='"'; # Precedes a log item
$logqs2='"'; # Follows a log item
$logentst=''; # Precedes a log entry
$logenten="\n"; # Follows a log entry

$msgstwarn='<span style="color: #ee0000;"><font color="#ee0000">'; # Start Warning Message
$msgenwarn='</font></span>'; # End Warning Message


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
$browser_array = array('/msie/i' => 'Internet Explorer', '/firefox/i' => 'Firefox', '/safari/i' => 'Safari', '/chrome/i' => 'Chrome', '/edge/i' => 'Edge', '/opera/i' => 'Opera', '/netscape/i' => 'Netscape', '/maxthon/i' => 'Maxthon', '/konqueror/i' => 'Konqueror', '/lynx/i' => 'Lynx', '/wget/i' => 'Wget');
foreach ($browser_array as $regex => $value) {
if (preg_match($regex, $user_agent)) {
$browser = $value; break;
}
}
if (preg_match('/mobile/i', $user_agent)) {$browser.=' ('.'Mobile'.')';}
return $browser;
}

function StringProgressBarLine($s1='',$s2='',$s3='',$valx=50,$valmax=100,$valwarn=10,$sl=300,$coltot='#888888',$colused='#448eeb',$colfree='#00ff00',$colnwarn='#efae25',$colwarn='#ff1111') {
$xpos1=round($valx*$sl/$valmax);
$xpos2=$sl-$xpos1;
if ($xpos1<2) {$xpos1=2;}
if ($xpos2<2) {$xpos2=2;}
if ($valx<$valwarn) {
$colusedd=$colwarn;
} elseif ($valx<$valwarn*2) {
$colusedd=$colnwarn;
} else {
$colusedd=$colfree;
}
if ($s1=='' && $s2=='' && $s3=='') {
/*
$xpc=round($valx*100/$valmax);
$s1='Tot.: '.'100'.'%';
$s2='Used: '.(100-$xpc).'%';
$s3='Free: '.$xpc.'%';
$notxt=false; $seth='';
*/
$notxt=true; $seth='height: 3px; ';
} else {$notxt=false; $seth='';}
$in=1;
if (${'s'.$in}=='' && !$notxt) {$strp=' ';} else {$strp=${'s'.$in};}
echo '<div style="border-bottom: 2px '.$coltot.' solid; '.$seth.'width: '.$sl.'px; overflow:visible;"><nobr>'.$strp.'</nobr></div>';
$in++;
if (${'s'.$in}=='' && !$notxt) {$strp=' ';} else {$strp=${'s'.$in};}
echo '<div style="border-bottom: 2px '.$colused.' solid; '.$seth.'width: '.$xpos2.'px; overflow:visible;"><nobr>'.$strp.'</nobr></div>';
$in++;
if (${'s'.$in}=='' && !$notxt) {$strp=' ';} else {$strp=${'s'.$in};}
echo '<div style="border-bottom: 2px '.$colusedd.' solid; '.$seth.'width: '.$xpos1.'px; overflow:visible;"><nobr>'.$strp.'</nobr></div>';
}

# Set default, failproof, values
$dbn=''; $dbxinfo='Untested'; $tfiler='Untested';

$user_os = getRemoteOS($_SERVER['HTTP_USER_AGENT']);
$user_browser = getRemoteBrowser($_SERVER['HTTP_USER_AGENT']);

$dival="left"; if ($oufmt==2) {$dival='center';}

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
?>
<!DOCTYPE html>
<html>
<head>
<title>Web Server Test<? echo ' @ '.$_SERVER['HTTP_HOST'].' ('.$_SERVER['SERVER_ADDR'].')'; ?></title>
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
body {background-color: #ffffff; color: #222222; font-family: Arial, Helvetica, sans-serif;}
.txtsml {font-size: 70%;}
table.t1 {border-collapse: collapse; border: 1px solid #ddddcc; box-shadow: 1px 2px 3px #ccccaa; font-size: 90%; text-align: center; background-color: #ffffff;}
table.t2 {border-collapse: collapse; border: 1px solid #ddddcc; box-shadow: 1px 2px 3px #ccccaa; font-size: 90%; text-align: left; background-color: #ffffff;}
img.im1 {float: none; border: 0;}
</style>
</head>
<body bgcolor="#FFFFFF" text="#222222">
<script language="JavaScript" type="text/javascript">
<!--

if (!Date.now) {
Date.now = function() { return new Date().getTime(); }
}

var ctmst=Math.floor(Date.now()/1000);
var stmst=<? echo time(); ?>;
if (Math.abs(ctmst-stmst)><? echo $mxcstimediff; ?>) { var wrntst='<? echo $msgstwarn; ?>'; var wrnten='<? echo $msgenwarn; ?>'; } else { var wrntst=''; var wrnten=''; }

// -->
</script>
<?
$jswarnsttime='
<body bgcolor="#FFFFFF" text="#222222">
<script language="JavaScript" type="text/javascript">
<!--
document.write(wrntst);
// -->
</script>
';
$jswarnentime='
<body bgcolor="#FFFFFF" text="#222222">
<script language="JavaScript" type="text/javascript">
<!--
document.write(wrnten);
// -->
</script>
';
?>
<div name="main" id="main" align="<? echo $dival; ?>" style="text-align: <? echo $dival; ?>;">
<?
if ($oufmt==2) {
echo '<table border="1" cellpadding="5" cellspacing="0"><tr><td align="center" class="t1">'."\n";
}
?>
<strong>Web Server Test</strong><br /><br />
<?
if ($tsts['img']) {
?>
<img src="webservertestimg.png" alt="Test image not loaded" title="" border="0" class="im1" /><br /><br />
<?
}
if ($oufmt==2 && ($tserver || $tclient)) {
echo '<table border="1" cellpadding="5" cellspacing="0" class="t2"><tr><td>'."\n";
}

# Requires functions_db.php (if $tsts['db']==true), webservertestimg.png (if $tsts['img']==true)

if ($tserver) {

echo '<strong>'.'Server'.'</strong>'."<br />\n";

if ($tsts['host']) {echo 'Host Name'.': '.$_SERVER['HTTP_HOST']."<br />\n";}
if ($tsts['ip']) {echo 'IP address'.': '.$_SERVER['SERVER_ADDR']."<br />\n";}

if ($tsts['dateu']) {echo 'Date'.': '.$jswarnsttime.gmdate('D d-M-Y H:i:s').' '.'UTC'.$jswarnentime."<br />\n";}
if ($tsts['datel']) {
$ntzl=date('Z'); if ($ntzl>0) {$ntzsl="+";} else {$ntzsl="";}
echo 'Date'.': '.$jswarnsttime.date('D d-M-Y H:i:s').' '.'UTC'.$ntzsl.($ntzl/3600).' (local)'.$jswarnentime."<br />\n";
}

if ($tsts['os']) {echo 'Web Server OS'.': '.PHP_OS."<br />\n";}
if ($tsts['webserversoft']) {echo 'Web Server Software'.': '.$_SERVER['SERVER_SOFTWARE']."<br />\n";}
if ($tsts['php']) {
echo 'PHP'.': '.'Running';
echo '. '.'Version info'.': '.PHP_VERSION;
echo "<br />\n";
}

if ($tsts['db']) {
include('functions_db.php');

echo 'DB'.': ';
switch ($dbx) {
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
echo $dbn.' server is running';
echo '. '.'Version info'.': '.$dbxinfo;
echo "<br />\n";
} else {
$dbxinfo='';
echo $msgstwarn.$dbn.' server is NOT running or NOT connected'.$msgenwarn;
echo "<br />\n";
}
}

if ($tsts['diskspace'] || $tsts['diskspacebar']) {
$ds=disk_total_space('/'); $dso=$ds;
$df=disk_free_space('/'); $dfo=$df;
$dfp=sprintf('%1.2f',$df*100/$ds); $dup=100-$dfp;
$du=$ds-$df; $duo=$du;
if ($dsfmt==2) {$ds=hrsize($ds); $df=hrsize($df); $du=hrsize($du);}
}

if ($tsts['diskspace']) {
echo 'Disk Space'.': ';
echo 'Tot.'.': '.$ds.', ';
if ($dfo<$ldf) {echo $msgstwarn;}
echo 'Free'.': '.$df.' ('.$dfp.'%'.')';
if ($dfo<$ldf) {echo $msgenwarn;}
echo ', ';
echo 'Used'.': '.$du.' ('.$dup.'%'.')'.'.';
echo "<br />\n";
}

if ($tsts['diskspacebar']) {
StringProgressBarLine('','','',$dfo,$dso,$ldf);
# echo "<br />\n";
}

if ($tsts['file']) {
# test file (create, write, read, delete)
echo 'Test file'.': ';
$do=true;
$fob=fopen($tfile,'wb'); if (!$fob) {echo $msgstwarn.'Cannot create test file'.$msgenwarn.'. '; $do=false;} else {echo 'Created'.', ';}
if ($do) {$fow=fwrite($fob,$tfilec); if (!$fow) {echo $msgstwarn.'Cannot write on test file'.$msgenwarn.'. '; $do=false;} else {echo 'Written'.', ';}}
fclose($fob);
if ($do) {$foa=fopen($tfile,'rb'); if (!$foa) {echo $msgstwarn.'Cannot open test file'.$msgenwarn.'. '; $do=false;} else {echo 'Opened'.', ';}}
if ($do) {$data=fread($foa,1024); if ($tfilec!=$data) {echo $msgstwarn.'Data read from the file does not match the written data'.$msgenwarn.', '; $rmatch=false;} else {echo 'Read'.', '; $rmatch=true;}}
fclose($foa);
if ($do) {$foad=unlink($tfile); if (!$foad) {echo $msgstwarn.'Cannot remove test file'.$msgenwarn.'. '; $do=false;} else {echo 'Removed'.'. ';}}
if ($do && $rmatch) {echo 'Success!'; $tfiler='OK';} else {echo $msgstwarn.'FAILED!'.$msgenwarn; $tfiler='FAILED';}
}

echo "<br />\n";
}

if ($tclient) {
if ($tserver) {echo "<br />\n";}

echo '<strong>'.'Client'.'</strong>'."<br />\n";
if ($tstc['ip']) {echo 'IP address'.': '.$_SERVER['REMOTE_ADDR']."<br />\n";}
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

<? if ($tstc['dateu']) { ?>
var nddu=ndateObj.getUTCDate();
var nmmu=ndateObj.getUTCMonth();
var nyyu=ndateObj.getUTCFullYear();
var ndwu=ndateObj.getUTCDay();
var nhhu=ndateObj.getUTCHours();
var nmnu=ndateObj.getUTCMinutes();
var nssu=ndateObj.getUTCSeconds();
document.writeln("Date"+": "+wrntst+dwds[ndwu]+" "+npadf2(nddu,2)+"-"+dmms[nmmu]+"-"+nyyu+" "+npadf2(nhhu,2)+":"+npadf2(nmnu,2)+":"+npadf2(nssu,2)+" "+"UTC"+wrnten+"<br />");
<? } ?>

<? if ($tstc['datel']) { ?>
var nddl=ndateObj.getDate();
var nmml=ndateObj.getMonth();
var nyyl=ndateObj.getFullYear();
var ndwl=ndateObj.getDay();
var nhhl=ndateObj.getHours();
var nmnl=ndateObj.getMinutes();
var nssl=ndateObj.getSeconds();
var ntzl=ndateObj.getTimezoneOffset(); ntzl*=-1;
if (ntzl>0) {var ntzsl="+";} else {var ntzsl="";}
document.writeln("Date"+": "+wrntst+dwds[ndwl]+" "+npadf2(nddl,2)+"-"+dmms[nmml]+"-"+nyyl+" "+npadf2(nhhl,2)+":"+npadf2(nmnl,2)+":"+npadf2(nssl,2)+" UTC"+ntzsl+""+(ntzl/60)+" "+"("+"local"+")"+wrnten+"<br />");
<? } ?>

<? if ($tstc['os']) { ?>document.writeln("Client OS"+": "+"<? echo $user_os; ?>"+" "+"("+navigator.platform+")"+"<br />");<? } ?>
<? if ($tstc['browser']) { ?>document.writeln("Browser"+": "+"<? echo $user_browser; ?>"+"<br />");<? } ?>
<? if ($tstc['uagent']) { ?>document.writeln("User Agent"+": "+"<? echo $_SERVER['HTTP_USER_AGENT']; ?>"+"<br />");<? } ?>

document.writeln("JavaScript"+": "+"Enabled"+"<br />");
// -->
</script>
<noscript>
JavaScript: <? echo $msgstwarn; ?>DISABLED<? echo $msgenwarn; ?>
</noscript>
<?
}

if ($oufmt==2 && ($tserver || $tclient)) {
echo '</td></tr></table>'."\n";
}

if ($oufmt==2) {
echo '</td></tr></table>'."\n";
}

if ($logen) {
$oul=$logentst; $itm=0;
if ($logem['shost']) {$oul.=$logqs1.addslashes($_SERVER['HTTP_HOST']).$logqs2; $itm++;}
if ($logem['sip']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('IPS'.' '.$_SERVER['SERVER_ADDR']).$logqs2; $itm++;}
if ($logem['sdateu']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes(gmdate('Y-m-d H:i:s')).' '.'UTC'.$logqs2; $itm++;}
if ($logem['sdatel']) {if ($itm>0) {$oul.=$logitmsep;}; $ntzl=date('Z'); if ($ntzl>0) {$ntzsl="+";} else {$ntzsl="";}; $oul.=$logqs1.addslashes(date('Y-m-d H:i:s')).' '.'UTC'.$ntzsl.($ntzl/3600).$logqs2; $itm++;}
if ($logem['sos']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes(PHP_OS).$logqs2; $itm++;}
if ($logem['swebserversoft']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes($_SERVER['SERVER_SOFTWARE']).$logqs2; $itm++;}
if ($logem['sphp']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('PHP'.' '.PHP_VERSION).$logqs2; $itm++;}
if ($logem['sdb']) {if ($itm>0) {$oul.=$logitmsep;}; if ($dbxinfo=='') {$dbxinfo='FAILED';}; $oul.=$logqs1.addslashes('DB'.' '.$dbn.' '.$dbxinfo).$logqs2; $itm++;}
if ($logem['sdiskt'] || $logem['sdiskf'] || $logem['sdisku']) {
$ds=disk_total_space('/'); $df=disk_free_space('/'); $du=$ds-$df;
if ($dsfmtl==2) {$ds=hrsize($ds); $df=hrsize($df); $du=hrsize($du);}
if ($logem['sdiskt']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('DT'.' '.$ds).$logqs2; $itm++;}
if ($logem['sdiskf']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('DF'.' '.$df).$logqs2; $itm++;}
if ($logem['sdisku']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('DU'.' '.$du).$logqs2; $itm++;}
}
if ($logem['sfile']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('File'.' '.$tfiler).$logqs2; $itm++;}
if ($logem['cip']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes('IPC'.' '.$_SERVER['REMOTE_ADDR']).$logqs2; $itm++;}
if ($logem['cos']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes($user_os).$logqs2; $itm++;}
if ($logem['cbrowser']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes($user_browser).$logqs2; $itm++;}
if ($logem['cuagent']) {if ($itm>0) {$oul.=$logitmsep;}; $oul.=$logqs1.addslashes($_SERVER['HTTP_USER_AGENT']).$logqs2; $itm++;}
$oul.=$logenten;
# echo '['.$oul.']';
$do=true;
$fob=fopen($logfile,'a'); if (!$fob) {echo $msgstwarn.'Cannot create/open log file for writing'.$msgenwarn.'. '; $do=false;}
if ($do) {
$fow=fwrite($fob,$oul); if (!$fow) {echo $msgstwarn.'Cannot write on log'.$msgenwarn.'. '; $do=false;}
fclose($fob);
}
}

?>
</div>
<?
if ($tsts['phpinfo']) {
echo '<div name="extra" id="extra">';
phpinfo();
echo '</div>';
}

if ($tsts['gentime']) {
$page_end_time=microtime(true);
$page_time_gen=round($page_end_time-$page_start_time,5);

echo '<span class="txtsml">'.'Page generated in'.' '.$page_time_gen.' '.'seconds'.'.'.'</span>';
}
?>
</body>
</html>