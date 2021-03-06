<?php

// HTN.LAN by Schnitzel
// Changelog user.php
// 1. jeder kann Passwort ?ndern
// 2. md5 Fehler beim neuen Passwort behoben
// 3. ext. Account User-Info-Bild entfernt da das nicht funktioniert
// 4. Subnetze f?llen eingef?ght
// 5. Multi Suche eingef?gt


define('IN_HTN',1);
$FILE_REQUIRES_PC=FALSE;
include('ingame.php');

$action=$_REQUEST['page'];
if($action=='') $action=$_REQUEST['mode'];
if($action=='') $action=$_REQUEST['action'];
if($action=='') $action=$_REQUEST['a'];
if($action=='') $action=$_REQUEST['m'];

switch($action) {
case 'config': //------------------------- CONFIG -------------------------------

createlayout_top('HackTheNet - Optionen');

echo '<div class="content" id="settings">
<h2>Optionen</h2>';

echo '<br /><br />';

while(list($bez,$val)=each($usr)) {
$usr[$bez]=safeentities(html_entity_decode($val));
}

$m=''; $w=''; $x='';
if($usr['gender']=='x') $x=' checked="checked"';
elseif($usr['gender']=='m') $m=' checked="checked"';
elseif($usr['gender']=='w') $w=' checked="checked"';

$dd=explode('.',$usr['birthday']);
if($dd[0]==0) $xx=' selected'; else $xx='';
$days='<option'.$xx.' value="0">?</option>'; 
for($i=1;$i<32;$i++) { 
  if((int)$dd[0]==$i) 
    $xx=' selected="selected"'; 
  else 
    $xx=''; 
  $days.='<option'.$xx.' value="'.$i.'">'.$i.'</option>'; 
}
if($dd[1]==0) 
  $xx=' selected'; 
else 
  $xx='';
$months='<option value="0">?</option>'; 
for($i=1;$i<13;$i++) { 
  if((int)$dd[1]==$i) 
    $xx=' selected="selected"'; 
  else 
    $xx=''; 
  $months.='<option'.$xx.' value="'.$i.'">'.$i.'</option>'; 
}
if($dd[2]==0) 
  $xx=' selected'; 
else 
  $xx='';
$years='<option value="0">?</option>'; 
for($i=1900;$i<2001;$i++) { 
  if((int)$dd[2]==$i) 
    $xx=' selected="selected"'; 
  else 
    $xx=''; 
  $years.='<option'.$xx.' value="'.$i.'">'.$i.'</option>'; 
}

if($usr['stat']>1) 
  $statx='<tr>'.LF.'<th>Dein Status:</th>'.LF.'<td>privilegiert<br />F&uuml;r die Sonderfunktionen rufe die Info-Seite eines Users auf!</td>'.LF.'</tr>'."\n";
if($usr['stat']==1000) 
  $statx='<tr>'.LF.'<th>Dein Status:</th>'.LF.'<td>King</td>'.LF.'</tr>'."\n";

if($usr['bigacc']=='yes') 
  $account='Extended Account (werbefrei und Adressbuch)';
elseif($usr['ads']=='no') 
  $account='werbefrei'; 
else 
  $account='normal';


if(eregi('http://.*/.*',$usr['avatar'])!==false) $avatar='<br />'.LF.'<img src="'.$usr['avatar'].'" alt="Avatar" />';

$xsinfo='';
$styles='';
reset($stylesheets);
foreach($stylesheets As $data) {
  if(($data['bigacc']=='yes' && $usr['bigacc']=='yes') || $data['bigacc']=='no') {
    $styles.='<option value="'.$data['id'].'"';
    if($usr['stylesheet']==$data['id']) $styles.=' selected';
    $styles.='>'.$data['name'].' (by '.$data['author'].')</option>';
  } else {
    $xsinfo.='<em>'.$data['name'].'</em>, ';
  }
}
if($xsinfo!='') $xsinfo='<br />Weitere Stylesheets in Extended Accounts: '.substr($xsinfo,0,strlen($xsinfo)-2);

$ipcheck=($usr['noipcheck']=='yes' ? '' : 'checked="checked" ');
$danotice=($usr['danotice']=='no' ? '' : 'checked="checked" ');
/*
if($usr['bigacc']=='yes') {
  #$usessl=($usr['usessl']=='yes' ? 'checked="checked" ' : 'no');
  #$usessl='<input type="checkbox" value="yes" name="usessl" '.$usessl.'/>';
  $usessl='<em>Diese Funktion steht in K&uuml;rze f&uuml;r alle Extended Account-User zur Verf&uuml;gung</em>';
} else {
  $usessl='<em>Diese Funktion steht nur in Extended Accounts zur Verf&uuml;gung</em>';
}
$usessl.="\n";*/

echo '<div class="submenu">
<p>';

	if($usr['bigacc']=='yes') echo '<a href="abook.php?mode=admin&amp;sid='.$sid.'">Adressbuch verwalten</a><br>';
#else echo '<a href="pub.php?d=extacc">Extended Account bestellen</a>'; # la la la

if($usr['bigacc']=='yes') 
{
	$dirname=dirname($_SERVER['PHP_SELF']);
	$dirname=(strlen($dirname)>0 ? $dirname.'/' : $dirname);
	$url='http://'.$_SERVER['HTTP_HOST'].$dirname.'usrimg.php/'.$server.'-'.$usrid.'.png';
	$usrimg=($usr['enable_usrimg']!='yes' ? '' : 'checked="checked" ');
	$usrimg='<input type="checkbox" value="yes" name="enable_usrimg" '.$usrimg.'/>URL des Bildes: <a href="'.$url.'">'.$url.'</a>';
	$send_sysmsg='<input type="checkbox" value="yes" name="send_sysmsg" '.($usr['send_sysmsg']!='yes' ? '' : 'checked="checked" ').'/>';
} else  {
	$send_sysmsg='<em>Diese Funktion steht nur in Extended Accounts zur Verf&uuml;gung!</em>';
	$usrimg='<em>Diese Funktion steht nur in Extended Accounts zur Verf&uuml;gung!</em>';
}

echo '</p>
</div>
'.$notif.'<div id="settings-settings">
<h3>'.$usr['name'].'</h3>
<form action="user.php?a=saveconfig&amp;sid='.$sid.'" method="post">
<table>
<tr id="settings-settings-account">
<th>Account-Typ:</th>
<td>'.$account.'</td>
</tr>
<tr id="settings-settings-gender">
<th>Geschlecht:</th>
<td><input type="radio" name="sex" value="m" id="sm"'.$m.' />M&auml;nnlich <input type="radio" name="sex" value="w" id="sw"'.$w.' />Weiblich <input type="radio" name="sex" value="x" id="sx"'.$x.' />Keine Angabe</td>
</tr>
<tr id="settings-settings-date-of-birth">
<th>Geburtsdatum:</th>
<td><select name="bday">'.$days.'</select>. <select name="bmonth">'.$months.'</select>. <select name="byear">'.$years.'</select></td>
</tr>
<tr id="settings-settings-style">
<th>HackTheNet-Style:</th>
<td><select name="style">'.$styles.'</select>'.$xsinfo.'</td>
</tr>
<tr id="settings-settings-homepage">
<th>Deine Homepage:</th>
<td><input type="text" name="homepage" value="'.$usr['homepage'].'" maxlength="100" /></td>
</tr>
<tr id="settings-settings-city">
<th>Wohnort:</th>
<td><input type="text" name="ort" value="'.$usr['wohnort'].'" /></td>
</tr>
<tr id="settings-settings-description">
<th>Beschreibung (max. 2048 Zeichen):<br />BB-Code: Eingeschaltet. <a href="game.php?m=kb&amp;sid='.$sid.'#messages-compose" target="_blank">BB-Codes</a></th>
<td><textarea name="aboutme" rows="5" cols="50">'.$usr['infotext'].'</textarea></td>
</tr>
<tr id="settings-settings-avatar">
<th>Avatar-Bild (http://&nbsp;...):</th>
<td><input type="text" name="avatar" value="'.$usr['avatar'].'" />'.$avatar.'</td>
</tr>
<tr id="settings-settings-mail-signature">
<th>Signatur f&uuml;r Mails (max. 255 Zeichen):</th>
<td><textarea name="sig_mails" rows="4" cols="30">'.$usr['sig_mails'].'</textarea></td>
</tr>
<tr id="settings-settings-board-signature">
<th>Signatur f&uuml;r Cluster-Board (max. 255 Zeichen):</th>
<td><textarea name="sig_board" rows="4" cols="30">'.$usr['sig_board'].'</textarea></td>
</tr>
<tr id="settings-settings-mail-maximum">
<th>&raquo;Posteingang voll&laquo;-Nachricht:</th>
<td><input type="text" value="'.$usr['inbox_full'].'" name="inbox_full" maxlength="250" /><br />
Wenn dein Posteingang voll ist, erh&auml;lt ein User, der dir eine Nachricht schicken will, diese Meldung</td>
</tr>
<tr id="settings-settings-ipcheck">
<th>Session an IP-Adresse binden:</th>
<td><input type="checkbox" value="yes" name="ipcheck" '.$ipcheck.'/></td>
</tr>
<tr id="settings-settings-da">
<th>DA-Meldungen anzeigen:</th>
<td><input type="checkbox" name="danotice" '.$danotice.'/></td>
</tr>
<tr id="settings-settings-userimg">
<th>Versenden von System-Nachrichten per Mail aktivieren:</th>
<td>'.$send_sysmsg.'</td>
</tr>
<tr id="settings-settings-usrimg">
<th>Benutzerinfo-Bild aktivieren:</th>
<td>'.$usrimg.'</td>
</tr>
';

/*<!--<tr id="settings-settings-usessl">
<th>SSL-Verschl&uuml;sselte Verbindung:</th>
<td>'.$usessl.'</td>
</tr>-->*/

$usrimg_fmt='';
$fmts=array('points', 'ranking', 'points ranking', 'cluster points', 'cluster ranking', 'cluster points ranking');
$fmtnms=array('Punkte', 'Ranglisten-Platz', 'Punkte + Platz', 'Cluster + Punkte', 'Cluster + Platz', 'Cluster + Platz + Punkte');
for($i=0;$i<count($fmts);$i++) {
  $usrimg_fmt.='<option value="'.$fmts[$i].'"';
  if($usr['usrimg_fmt']==$fmts[$i]) $usrimg_fmt.=' selected="selected"';
  $usrimg_fmt.='>'.$fmtnms[$i].'</option>'."\n";
}

if($usr['bigacc']=='yes') {
echo '<tr id="settings-settings-usrimg">
<th>Format des Benutzerinfo-Bildes:</th>
<td><select name="usrimg_fmt">
'.$usrimg_fmt.'
</select></td>
</tr>';
}
echo '<tr id="settings-settings-delete-account">
<th>Account l&ouml;schen:</th>
<td><input type="checkbox" value="yes" name="delete_account" /></td>
</tr>
'.$statx.'
<tr id="settings-settings-confirm">
<td colspan="2"><input type="submit" value="Speichern" /></td>
</tr>
</table>
</form>
</div>

<div id="settings-mail">
<h3>Email-Adresse &auml;ndern</h3>
<form action="user.php?a=setmailaddy&amp;sid='.$sid.'" method="post">
<table>
<tr id="settings-mail-address">
<th>Deine Email-Adresse:</th>
<td><input type="text" name="email" value="'.$usr['email'].'" /><br />
Die Email-Adresse ist f&uuml;r andere Benutzer nicht sichtbar</td>
</tr>
<tr id="settings-mail-password">
<th>Dein Account-Passwort:</th>
<td><input name="pwd" type="password" /><br />
Bitte zur Best&auml;tigung eingeben.</td>
</tr>
<tr id="settings-mail-confirm">
<td colspan="2"><input type="submit" value="Speichern" /></td>
</tr>
</table>
</form>
</div>';

echo '<div id="settings-password">
<form action="user.php?a=newpwd&amp;sid='.$sid.'" method="post">
<h3>Passwort &auml;ndern</h3> <!-- 3. Text "Sonderfunktion" gel?scht  -->
<table>
<tr id="settings-password-password">
<th>Neues Passwort:</th>
<td><input name="pwd" type="password" maxlength="16" /></td>
</tr>
<tr id="settings-password-confirm">
<td colspan="2"><input type="submit" value="Speichern" /></td>
</tr>
</table>
</form>
</div>
';
echo '</div>'."\n";
createlayout_bottom();
break;

case 'saveconfig': //------------------------- SAVE CONFIG -------------------------------

if($_POST['delete_account']=='yes') {
$code=randomx(16);

$body='Schade, dass du deinen Account bei HTN.LAN l?schen m?chtest!'.LF."\n";
$body.='Wenn du dir ganz sicher bist, klicke bitte ';
$body.='<a href="http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/pub.php?a=deleteaccount&code='.$code.'">HIER</a>';

echo nl2br($body);

file_put('data/regtmp/del_account_'.$code.'.txt',$usrid.'|'.$server);
@unlink('data/login/'.$sid.'.txt');
} else { # Nicht Account l?schen sondern Settings speichern

$g=$_POST['sex'];
if($g=='') $g='x';
$birthday=$_POST['bday'].'.'.$_POST['bmonth'].'.'.$_POST['byear'];
$hp=trim($_POST['homepage']);
$ort=trim($_POST['ort']);
$text=trim($_POST['aboutme']);
$sig_mails=trim($_POST['sig_mails']);
$sig_board=trim($_POST['sig_board']);
$inbox_full=trim($_POST['inbox_full']);
$avatar=trim($_POST['avatar']);
$style=$_POST['style'];
$danotice=($_POST['danotice']=='' ? 'no' : 'yes');
$noipcheck=($_POST['ipcheck']!='yes' ? 'yes' : 'no');

$usessl=($_POST['usessl']=='yes' ? 'yes' : 'no');
if($usr['bigacc']!='yes') $usessl='no';

$enable_usrimg=($_POST['enable_usrimg']=='yes' ? 'yes' : 'no');
if($usr['bigacc']!='yes') $enable_usrimg='no';
$send_sysmsg=($_POST['send_sysmsg']=='yes' ? 'yes' : 'no');
if($usr['bigacc']!='yes') $send_sysmsg='no';

$usrimg_fmt=$_POST['usrimg_fmt'];

$pcs=explode(',',$usr['pcs']);

$e=false;
$error='';

if(eregi('http://.*',$hp)==false) { $hp=''; }
if(eregi('http://.*/.*',$avatar)==false) { $avatar=''; }
if(strlen($ort)<3) $ort='';
if(strlen($text)>2048) { $e=true; $error.='Die Beschreibung darf maximal 2048 Zeichen haben!'; }
if(strlen($sig_mails)>255) { $e=true; $error.='Die Signatur f&uuml;r Mails darf maximal 255 Zeichen haben!'; }
if(strlen($sig_board)>255) { $e=true; $error.='Die Signatur f&uuml;rs Cluster-Board darf maximal 255 Zeichen haben!'; }
if(strlen($inbox_full)>255) { $e=true; $error.='Die Nachricht bei vollem Posteingang darf maximal 255 Zeichen haben!'; }

if($e==false) {

while(list($bez,$val)=each($_POST)) $_POST[$bez]=html_entity_decode($val);

$usr['gender']=$g;
$usr['birthday']=$birthday;
$usr['homepage']=safeentities($hp);
$usr['infotext']=safeentities($text);
$usr['wohnort']=safeentities($ort);
$usr['sig_mails']=safeentities($sig_mails);
$usr['sig_board']=safeentities($sig_board);
$usr['inbox_full']=safeentities($inbox_full);
$usr['avatar']=safeentities($avatar);
if($stylesheets[$style]['bigacc']=='yes' && $usr['bigacc']=='no') $style=$standard_stylesheet;
$usr['stylesheet']=$style;
$usr['noipcheck']=$noipcheck;
$usr['danotice']=$danotice;
$usr['usessl']=$usessl;
if($usr['usrimg_fmt']!=$usrimg_fmt || $usr['enable_usrimg']!=$enable_usrimg) {
  @unlink('data/_server'.$server.'/usrimgs/'.$usrid.'.png');
}
$usr['enable_usrimg']=$enable_usrimg;
$usr['send_sysmsg']=$send_sysmsg;
$usr['usrimg_fmt']=$usrimg_fmt;
saveuserdata();
header('Location: user.php?a=config&sid='.$sid.'&ok='.urlencode('Die &Auml;nderungen wurden gespeichert.'));
} else {
site_header('Optionen'); body_start(); echo '<h2>Optionen</h2>';
echo '<div class=error>FEHLER:<br />'.$msg.'<br /><br />';
echo 'Aufgrund dieser Fehler wurden die &Auml;nderungen <i>nicht</i> &uuml;bernommen!</div>';
echo '</div>'; site_footer(); }

}

break;

case 'setmailaddy': //------------------------- SET MAIL ADDY -------------------------------
$email=trim($_POST['email']);
if(!check_email($email)) {
  simple_message('Bitte eine g&uuml;ltige Email-Adresse im Format xxx@yyy.zz angeben!');
} else {
$pwd=trim($_POST['pwd']);
$real_pwd=$usr['password'];

if($pwd==$real_pwd || md5($pwd)==$real_pwd) {
  db_query('UPDATE users SET email=\''.mysql_escape_string($email).'\' WHERE id=\''.mysql_escape_string($usrid).'\'');
  echo mysql_error();
  header('Location: user.php?a=config&sid='.$sid.'&saved=1');
} else {
  simple_message('Falsches Passwort!');
}
}
break;

case 'info': //------------------------- INFO -------------------------------
$index=$_REQUEST['user'];
$a=getuser($index);
if($a!=false) {

$u_points=$a['points'];
createlayout_top('HackTheNet - Benutzerprofil');
if($a['gender']=='x')
  $geschl='';
elseif($a['gender']=='m')
  $geschl='M&auml;nnlich';
elseif($a['gender']=='w')
  $geschl='Weiblich';
if($geschl!='')
  $geschl='<tr>'.LF.'<th>Geschlecht:</th>'.LF.'<td>'.$geschl.'</td>'.LF.'</tr>'."\n";
if($a['wohnort']!='') $ort='<tr>'.LF.'<th>Wohnort:</th><td>'.$a['wohnort'].'</td>'.LF.'</tr>'."\n";

if($a['locked']!='') $locked='<tr id="account-locked">'.LF.'<th>Besonderheiten:</th>'.LF.'<td>Account gesperrt ('.$a['locked'].')</td>'.LF.'</tr>'."\n";

if($a['verwarnung']>=1) $verwarnungen='<tr id="account-warn">'.LF.'<th>Verwarnungen:</th>'.LF.'<td>'.$a['verwarnung'].'</td>'.LF.'</tr>'."\n";

if($a['birthday']!='0.0.0') {
list($bday,$bmonth,$byear)=explode('.',$a['birthday']);
$years=date('Y')-$byear;
if($bmonth>date('m')) $years--;
if($bmonth==date('m') AND $bday>date('d')) $years--;
if($years<=104) { $alter=$years.' Jahre';
$gb='<tr>'.LF.'<th>Alter</th>'.LF.'<td>'.$alter.'</td>'.LF.'</tr>'."\n";
}}
if(eregi('http://.*',$a['homepage'])!=false) {
  $hp=dereferurl($a['homepage']);
  $hp=safeentities($hp);
  $hp='<tr>'.LF.'<th>Homepage:</th><td><a href="'.$hp.'">'.safeentities($a['homepage']).'</a></td>'.LF.'</tr>'."\n";
}
$descr=nl2br($a['infotext']);
$descr=text_replace($descr);
$c=$a['cluster'];
if($c!=false) {
  $c=getcluster($c);
  $scluster='<a href="cluster.php?a=info&amp;cluster='.$a['cluster'].'&amp;sid='.$sid.'">'.$c['name'].'</a> '.$c['code'];
} else $scluster='keiner';

$spcs='';
$sql=db_query('SELECT * FROM pcs WHERE owner='.mysql_escape_string($a['id']).' ORDER BY name ASC;');
$pccnt=mysql_num_rows($sql);
#$attackallowed=false;
while($xpc=mysql_fetch_assoc($sql)):
  $country=GetCountry('id',$xpc['country']);
  $xpc['name']=safeentities($xpc['name']);
  if((int)$usr['stat']>=100) $extras=' <a href="secret.php?sid='.$sid.'&amp;m=file&amp;type=pc&amp;id='.$xpc['id'].'">Extras</a>'; else $extras='';
  $spcs.='<li>'.$xpc['name'].' (10.47.'.$xpc['ip'].', <a href="game.php?m=subnet&amp;sid='.$sid.'&amp;subnet='.subnetfromip($xpc['ip']).'">'.$country['name'].'</a>, '.$xpc['points'].' Punkte)'.$extras.'</li>';
  #$xdefence=$xpc['fw'] + $xpc['av'] + $xpc['ids']/2;
  #if($xdefence >= MIN_ATTACK_XDEFENCE OR isavailh('scan',$xpc)==true) $attackallowed=true;
endwhile;
if(file_exists('data/login/'.$a['sid'].'.txt')==true) 
  $online='<span style="color:green;">Online</span>';
else 
  $online='<span style="color:red;">Offline</span>';

if($usr['stat']>=100) $descr.='</td>'.LF.'</tr>'.LF.'<tr>'.LF.'<th>Sonder-Funktionen:</th>'.LF.'<td><a href="secret.php?sid='.$sid.'&amp;m=file&amp;type=user&amp;id='.$a['id'].'">'.($usr['stat']==1000 ? 'Bearbeiten' : 'Daten ansehen').'</a><br>';

if($usr['stat']==1000) $descr.='<a href="secret.php?a=lockacc&amp;sid='.$sid.'&amp;user='.$a['id'].'">Account sperren</a> | <a href="secret.php?a=delacc1&amp;sid='.$sid.'&amp;user='.$a['id'].'">Account l&ouml;schen</a> | <a href="secret.php?a=warn&amp;sid='.$sid.'&amp;user='.$a['id'].'">Account verwarnen</a><br>';
if($usr['stat']==1000) $descr.='<a href="secret.php?a=delockacc&amp;sid='.$sid.'&amp;user='.$a['id'].'">Account entsperren</a> | <a href="secret.php?a=dewarn&amp;sid='.$sid.'&amp;user='.$a['id'].'">Account entwarnen</a>';

if($usr['bigacc']=='yes') $bigacc='| <a href="abook.php?sid='.$sid.'&amp;action=add&amp;user='.$index.'">User zum Adressbuch hinzuf&uuml;gen</a>';

/*
$rhx=true;
if( $a['points'] <= ($usr['points'] * (25/100)) ) {
  $r=db_query('SELECT * FROM `attacks` WHERE `from_usr`=\''.mysql_escape_string($a['id']).'\' AND `to_usr`=\''.mysql_escape_string($usrid).'\' AND `type`<>\'scan\';');
  if(mysql_num_rows($r)==0) {
    $rhx=false;
  }
}

if($attackallowed!==true && $a['login_time']+MIN_INACTIVE_TIME>time() )
  $attack='Dieser User kann nicht angegriffen werden, weil er noch zu schwach ist.';
elseif(is_noranKINGuser($index))
  $attack='Dieser User kann nicht angegriffen werden, weil er ein Administrator ist.';
elseif($rhx==false)
  $attack='M&ouml;glich, allerdings kein Remote Hijack.';
elseif(isattackallowed($dummy,$dummy2)==false)
  $attack='Dieser User k&ouml;nnte angegriffen werden, aber du kannst momentan nicht angreifen.';
else
  $attack='Sofort m&ouml;glich';
*/
$attack='(keine Info)';
$attack='Letztes Login: <i>'.nicetime3($a['login_time']).'</i><br />Angriff: <i>'.$attack.'</i>';

if(eregi('http://.*/.*',$a['avatar'])!==false) {
  if($usr['sid_ip']!='noip') {
    $avatar=$a['avatar'];
    $avatar='<tr><td colspan="2"><center><img src="'.$avatar.'" alt="'.$a['name'].'" /></center></td></tr>';
  }
}

$lastlogin=nicetime3($a['login_time']);

echo '<div class="content" id="user-profile">
<h2>Benutzer-Profil</h2>
<div class="submenu">
<p><a href="mail.php?m=newmailform&amp;sid='.$sid.'&amp;recip='.$a['name'].'">Mail an User</a> |
<a href="ranking.php?m=ranking&amp;sid='.$sid.'&amp;type=user&amp;id='.$a['id'].'">User in Rangliste</a>
'.$bigacc.'</p>
</div>
<div id="user-profile-profile">
<h3>'.$a['name'].'</h3>
<table>
'.$avatar.'
<tr>
<th>Punkte</th><td>'.$a['points'].'</td>
</tr>
'.$geschl.$gb.$ort.$hp.$locked.$verwarnungen.'
<tr>
<th>Cluster</th><td>'.$scluster.'</td></tr>
<tr>
<th>Computer ('.$pccnt.')</th>
<td><ul>'.$spcs.'</ul>'.$pchw.'</td>
</tr>';
/*
<tr>
<th>Angriff?</th>
<td>'.$attack.'</td>
</tr>
*/
echo '<tr>
<th>Online?</th>
<td>'.$online.'</td>
</tr>
<tr>
<th>Letzter Login</th>
<td>'.$lastlogin.'</td>
</tr>
';
if ($descr!='') {
$descr=preg_replace('/script/i','$cr!p7',$descr);
echo '<tr>
<th>Beschreibung:</th>
<td>'.$descr.'</td>
</tr>
';
}
echo '</table>
</div>
</div>
';
createlayout_bottom();
} else simple_message('Diesen Benutzer gibt es nicht!');
break;

case 'newpwd': //------------------------- NEW PWD -------------------------------
#if($usr['stat']<10) { simple_message('No!'); exit; } // 1. Auskommentiert damit jeder Passwort ?ndern kann
$pwd=$_POST['pwd'];
$usrname=strtolower($usr['name']);
if(substr_count($pwd,';')==0) {
  db_query('UPDATE users SET password=\''.mysql_escape_string(md5($pwd)).'\' WHERE id=\''.mysql_escape_string($usrid).'\';'); //2. md5 Fehler korrigiert
  simple_message('Passwort ge&auml;ndert auf <i>'.$pwd.'</i>');
} else simple_message('Passwort ung&uuml;ltig!');
break;

case 'adminaufgaben': //4. Subnetze f?llen code
    if ($usr['stat']>100) { 
    	createlayout_top('HackTheNet - Subnetze f?llen');
    	echo "<div class='content' id='settings'><h2>Subnetze f?llen / leeren</h2>";
    	include('fillcountry.php');
    	echo "</div>";
    } else {
    	simple_message('Wir wollen doch nicht hacken?!?');
    }
break;

case 'multi': //5. Multis suchen code
    if ($usr['stat']>100) { 
    	createlayout_top('HackTheNet - Multis suchen');
    	echo "<div class='content' id='settings'><h2>Multis suchen</h2>";
    	include('multi.php');
    	echo "</div>";
    } else {
    	simple_message('Wir wollen doch nicht hacken?!?');
    }
break;

case 'bbcode': //6. BB Code
    	createlayout_top('HackTheNet - BB Code');
    	echo "<div class='content' id='bbcode'><h2>BB Code</h2>";
    	include('./data/pubtxt/bbcode.php');
    	echo "</div>";
break;

case 'calc': //5. Calc Points
    if ($usr['stat']>100) { 
    	createlayout_top('HackTheNet - Calc Points');
    	echo "<div class='content' id='settings'><h2>Calc Points</h2>";
    	include('calc_points.php');
    	echo "</div>";
    } else {
    	simple_message('Wir wollen doch nicht hacken?!?');
    }
break;

}

?>
