<?

# Functions

function explodex($s,$nx='.',$ord=0,$mod=false) {
$rt=array(false,false);
switch ($ord) {
default:
case 0:
# truncate the string keeping characters up to the last X matching character (excluded: will be removed)
$r=strrpos($s,$nx); if ($r!==false) {$rt[0]=substr($s,0,$r);}
# truncate the string keeping characters from the last X matching character (excluded: will be removed)
$rn=strlen($s); $r=strrpos($s,$nx); if ($r!==false && $r<$rn) {$rt[1]=substr($s,$r+1,$rn);}
break;
case 1:
# truncate the string keeping characters up to the first X matching character (excluded: will be removed)
$r=strpos($s,$nx,0); if ($r!==false) {$rt[0]=substr($s,0,$r);}
# truncate the string keeping characters from the first X matching character (excluded: will be removed)
$rn=strlen($s); $r=strpos($s,$nx,0); if ($r!==false && $r<$rn) {$rt[1]=substr($s,$r+1,$rn);}
break;
}
if ($mod && $rt[0]===false && $rt[1]===false) {$rt[0]=$s;}
return $rt;
}


# DB Functions

# Requires function explodex()
function dbx_connect($dbx,$db_host,$db_user,$db_pwd,$db_name='') {
$dbx=strtolower(trim($dbx));
$r=false;
switch ($dbx) {
case 'mysql':
$r=mysql_connect($db_host,$db_user,$db_pwd);
break;
case 'mysqli':
$db_host_1=explodex($db_host,':',0,true);
if ($db_host_1[1]!==false) {$r=mysqli_connect($db_host,$db_user,$db_pwd,$db_name,$db_host_1[1]);} else {$r=mysqli_connect($db_host,$db_user,$db_pwd,$db_name);}
break;
case 'postgresql':
# $r=pg_connect($db_host1, $db_port, , , $db_name); # deprecated
$db_host_1=explodex($db_host,':',0,true);
if ($db_host_1[1]!==false) {$db_portz=' port='.$db_host_1[1].' ';} else {$db_portz=' ';}
$r=pg_connect("host=".$db_host_1[0].$db_portz."dbname=".$db_name." user=".$db_user." password=".$db_pwd);
break;
}
return $r;
}

function dbx_connect_pt($dbx,$db_host,$db_port,$db_user,$db_pwd,$db_name='') {
$dbx=strtolower(trim($dbx));
if ($db_port!==false) {
$r=dbx_connect($dbx,$db_host.':'.$db_port,$db_user,$db_pwd,$db_name);
} else {
$r=dbx_connect($dbx,$db_host,$db_user,$db_pwd,$db_name);
}
return $r;
}

function dbx_close($dbx,$dbxcon) {
$dbx=strtolower(trim($dbx));
switch ($dbx) {
case 'mysql':
mysql_close($dbxcon);
break;
case 'mysqli':
mysqli_close($dbxcon);
break;
case 'postgresql':
pg_close($dbxcon);
break;
}
}

function dbx_query($dbx,$dbxcon,$dbq,$db_name='') {
$dbx=strtolower(trim($dbx));
$r=false;
switch ($dbx) {
case 'mysql':
$r=mysql_db_query($db_name,$dbq,$dbxcon);
break;
case 'mysqli':
$r=mysqli_query($dbxcon,$dbq);
break;
case 'postgresql':
# $dbxcon=pg_connect("dbname=".$db_name);
$r=pg_query($dbxcon,$dbq);
break;
}
return $r;
}

function dbx_num_rows($dbx,$dbo) {
$dbx=strtolower(trim($dbx));
$r=false;
switch ($dbx) {
case 'mysql':
$r=mysql_num_rows($dbo);
break;
case 'mysqli':
$r=mysqli_num_rows($dbo);
break;
case 'postgresql':
$r=pg_num_rows($dbo);
break;
}
return $r;
}

function dbx_fetch_array($dbx,$dbo) {
$dbx=strtolower(trim($dbx));
$r=false;
switch ($dbx) {
case 'mysql':
$r=mysql_fetch_array($dbo);
break;
case 'mysqli':
$r=mysqli_fetch_array($dbo);
break;
case 'postgresql':
$r=pg_fetch_array($dbo);
break;
}
return $r;
}

function dbx_fetch_object($dbx,$dbo) {
$dbx=strtolower(trim($dbx));
$r=false;
switch ($dbx) {
case 'mysql':
$r=mysql_fetch_object($dbo);
break;
case 'mysqli':
$r=mysqli_fetch_object($dbo);
break;
case 'postgresql':
$r=pg_fetch_object($dbo);
break;
}
return $r;
}

function dbx_last_error($dbx,$dbxcon) {
$dbx=strtolower(trim($dbx));
switch ($dbx) {
case 'mysql':
$r=mysql_error($dbxcon);
break;
case 'mysqli':
$r=mysqli_error($dbxcon);
break;
case 'postgresql':
$r=pg_last_error($dbxcon);
break;
}
return $r;
}

function dbx_server_info($dbx,$dbxcon) {
$dbx=strtolower(trim($dbx));
switch ($dbx) {
case 'mysql':
$r=mysql_get_server_info($dbxcon);
break;
case 'mysqli':
$r=mysqli_get_server_info($dbxcon);
break;
case 'postgresql':
$r=pg_version($dbxcon);
break;
}
return $r;
}

# Connect to the Database: $dbxcon=dbx_connect($dbx,$db_host,$db_user,$db_pwd,$db_name);

?>