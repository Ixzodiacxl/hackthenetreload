<?php

/**
 * Adressbuch-Verwaltung f?r Extended Account
 **/


define('IN_HTN',1);
$FILE_REQUIRES_PC=FALSE;
include('ingame.php');

if($usr['bigacc']!='yes') { simple_message('Nur f&uuml;r User mit Extended Account!'); exit; }

$action=$_REQUEST['page'];
if($action=='') $action=$_REQUEST['mode'];
if($action=='') $action=$_REQUEST['action'];
if($action=='') $action=$_REQUEST['a'];
if($action=='') $action=$_REQUEST['m'];

switch($action) {
case 'selpage': //------------------------- SELECT PAGE -------------------------------

#if($usrid==1) write_pc_list(2382);

switch($_REQUEST['type']) { # Fucking Hackers ;-)
case 'user': $type='user'; break;
default: $type='pc'; break;
}
$javascript='<script language="JavaScript" type="text/javascript">
function choose(s) {
  window.opener.fill(s);
  self.close();
}
</script>';
basicheader('HackTheNet - Adressbuch',true,false);

echo '<body>
<div id="abook-selpage">
<h2>Adressbuch</h2>
<form name="formular">';

function list_items($g) {
global $STYLESHEET, $REMOTE_FILES_DIR, $DATADIR, $b,$sid,$type;
echo '<table>
<tr>';
$a=explode(',',$g);
if($type=='user') {
    echo '<th>Benutzer</th><th>W&auml;hlen</th></tr>';
    foreach($a as $item):
      $name=@mysql_result(db_query('SELECT name FROM users WHERE id=\''.$item.'\' LIMIT 1'),0,'name');
      if($name=='') continue;
      echo '<tr><td><a href="user.php?a=info&sid='.$sid.'&user='.$item.'">'.$name.'</a></td>';
      echo '<td><input type="button" value="W&auml;hlen" onclick="choose(\''.$name.'\')"></td></tr>';
    endforeach;
} else {
    echo '<th>Benutzer</th><th>PCs</th></tr>';
    foreach($a as $item):
      $user=getuser($item);
      if($user===false) continue;
      echo '<tr><td><a href="user.php?a=info&sid='.$sid.'&user='.$item.'">'.$user['name'].'</a></td>';
      echo '<td><table class="nomargin">';
      $pcs=explode(',',$user['pcs']);
      foreach($pcs as $xpc) {
        $xpc=getpc($xpc);
        echo '<tr><td><b>10.47.'.$xpc['ip'].'</b></td><td>'.$xpc['name'].'</td>';
        echo '<td>&nbsp;</td><td><input type="button" value="W&auml;hlen" onclick="choose(\''.$xpc['ip'].'\')"></td></tr>';
      }
      echo '</table></td></tr>';
    endforeach;
}
echo '</table></form>';
}

$b=@mysql_fetch_assoc(db_query('SELECT * FROM abooks WHERE user=\''.$usrid.'\''));

echo "\n".'<h3>Gruppe: Allgemein</h3>'; list_items($b['set1']);
echo "\n".'<h3>Gruppe: Cluster</h3>'; list_items($b['set2']);
echo "\n".'<h3>Gruppe: Freunde</h3>'; list_items($b['set3']);
echo "\n".'<h3>Gruppe: Andere</h3>'; list_items($b['set4']);
echo "\n".'</div>';
basicfooter();

break;

case 'add': //------------------------- ADD -------------------------------
$ix=(int)$_REQUEST[user];
if($ix==0) $u=getuser($_REQUEST[user],'name'); else $u=getuser($ix);
if($u!==false) {
$g=(int)$_REQUEST[group];
if($g<1 || $g>4) $g=1;
$r=db_query('SELECT * FROM abooks WHERE user=\''.$usrid.'\'');

$field=@mysql_fetch_array($r);
if(!$field) { db_query('INSERT INTO abooks VALUES(\''.$usrid.'\',\'\',\'\',\'\',\'\')');
  $r=db_query('SELECT * FROM abooks WHERE user=\''.$usrid.'\'');
  $field=@mysql_fetch_array($r); 
}
$field=trim($field,',').','.$u['id'];

$a=explode(',',$field);
settype($c,'array');
foreach($a as $item) {
  $u=getuser($item);
  if($u!==false) {
    $name=$u[name];
    $c[$name]=$item;
  }
}
ksort($c);
$a=array_values($c);
$field=joinex($a,',',true,true);

db_query('UPDATE abooks SET set'.mysql_escape_string($g).'=\''.mysql_escape_string($field).'\' WHERE user='.mysql_escape_string($usrid));

header('Location: abook.php?sid='.mysql_escape_string($sid).'&m=admin&saved=1');
} else simple_message('Benutzer inexistent!');
break;

case 'admin': //------------------------- ADMIN -------------------------------
createlayout_top('HackTheNet - Adressbuch');

if($_REQUEST[saved]==1) $xxx='<div class="ok"><h3>OK</h3><p>Die &Auml;nderungen wurden &uuml;bernommen!</p></div><br />'."\n";
echo '<div id="abook-administration" class="content">
<h2>Adressbuch</h2>
<h3>Adressbuch verwalten</h3>'.$xxx.'
<form action="abook.php?action=add&amp;sid='.$sid.'" method="post">
<table>
<tr><th colspan="2">Benutzer hinzuf&uuml;gen</th></tr>
<tr><th>Benutzername:</th><td><input name="user" size="20" maxlength="20" /></td></tr>
<tr><th>Gruppe:</th><td><select name="group"><option value="1">Allgemein</option><option value=2>Cluster</option><option value=3>Freunde</option><option value=4>Andere</option></select></td></tr>
<tr><td colspan="2" align="right"><input type="submit" value="Hinzuf&uuml;gen" /></td></tr>
</table></form><br />';

function admin_list($ix) {
  global $STYLESHEET, $REMOTE_FILES_DIR, $DATADIR, $usrid,$sid;
  eval('$ch'.$ix.'=\' selected\';');
  $s=@mysql_result(db_query('SELECT `set'.mysql_escape_string($ix).'` FROM abooks WHERE user='.mysql_escape_string($usrid)),'set'.mysql_escape_string($ix));
  if($s!='') {
  $a=explode(',',$s);
  if(! (count($a)<=1 && $a[0]=='')) {
  echo '<form action="abook.php?sid='.$sid.'&amp;m=saveadmin&amp;group='.$ix.'" method="post">
<table>
<tr><th>User</th><th>Gruppe</th><th>L&ouml;schen?</th></tr>';
  foreach($a as $item) {
    $u=getuser($item);
    if($u!==false) {
      $name=$u['name'];
      echo '<tr><td width="100"><a href="user.php?a=info&sid='.$sid.'&user='.$item.'">'.$name.'</a></td>';
      echo '<td><select name="group'.$item.'"><option value=1'.$ch1.'>Allgemein</option><option value=2'.$ch2.'>Cluster</option><option value=3'.$ch3.'>Freunde</option><option value=4'.$ch4.'>Andere</option></select></td>';
      echo '<td><input type="checkbox" value="yes" name="u'.$item.'" /></td>';
      echo '</tr>';
    }
  }
echo '<tr><td colspan="3" align="right"><input type="submit" value="Ausf&uuml;hren" /></td></tr></table></form>';
}
}
}
echo '<h3>Gruppe: Allgemein</h3>'; admin_list(1);
echo '<h3>Gruppe: Cluster</h3>'; admin_list(2);
echo '<h3>Gruppe: Freunde</h3>'; admin_list(3);
echo '<h3>Gruppe: Andere</h3>'; admin_list(4);
echo '</div>';
createlayout_bottom();
break;

case 'saveadmin': //------------------------- SAVE ADMIN -------------------------------

  $b=explode(';',$usr[abook]);
  $g=(int)$_REQUEST[group];
  if($g>4 OR $g<1) $g=1;
  $b=mysql_fetch_assoc(db_query('SELECT * FROM abooks WHERE user=\''.mysql_escape_string($usrid).'\''));

  $a=explode(',',$b['set'.$g]);
  for($i=0;$i<count($a);$i++) {
    $item=$a[$i];
    $u=getuser($item);
    if($u===false || $_REQUEST['u'.$item]=='yes') {
      $a[$i]='';
    } elseif((int)$_REQUEST['group'.$item]!=$g) {
      $aa=(int)$_REQUEST['group'.$item];
      if($aa>4 OR $aa<1) $aa=1;
      $b['set'.$aa].=','.$item;
      $a[$i]='';
    }
  }
  $b['set'.$g]=joinex($a,',',true,true);
  $b['set1']=joinex(explode(',',$b['set1']),',',true,true);
  $b['set2']=joinex(explode(',',$b['set2']),',',true,true);
  $b['set3']=joinex(explode(',',$b['set3']),',',true,true);
  $b['set4']=joinex(explode(',',$b['set4']),',',true,true);

  db_query('UPDATE abooks SET set1=\''.mysql_escape_string($b['set1']).'\', set2=\''.mysql_escape_string($b['set2']).'\', set3=\''.mysql_escape_string($b['set3']).'\', set4=\''.mysql_escape_string($b['set4']).'\' WHERE user='.mysql_escape_string($usrid));

  header('Location: abook.php?sid='.$sid.'&m=admin&saved=1');
break;

}

?>
