<?php

// HTN.LAN by Schnitzel
// Changelog ranking.php
// 1. Text ver?ndert

define('IN_HTN',1);
$FILE_REQUIRES_PC=FALSE;
include('ingame.php');

$action=$_REQUEST['page'];
if($action=='') $action=$_REQUEST['mode'];
if($action=='') $action=$_REQUEST['action'];
if($action=='') $action=$_REQUEST['a'];
if($action=='') $action=$_REQUEST['m'];

#simple_message('Leider ist die Rangliste ATM kaputt ... Wir arbeiten aber dran!', 'error');
#exit;

switch($action) {

case 'ranking': // ----------------------------------- Ranking --------------------------------

$type=$_REQUEST['type'];
if($type!='user' & $type!='cluster') $type='user';

$javascript='<script type="text/javascript">'."\n";
if($type=='user') {
  $javascript.='function fill(s) { document.forms[0].user.value=s; }';
} else {
  $javascript.='function sorttype_go(obj,id) { location.href=\'ranking.php?a=ranking&sid='.$sid.'&type=cluster&sorttype=\'+obj.selectedIndex+\'&id=\'+id+\'#rank-table\'; }';
}
$javascript.="\n".'</script>';

createlayout_top('HackTheNet - Rangliste');

$updtime=nicetime((int)@file_get('data/calc-time.dat'));
echo '<div class="content" id="ranking">
<h2>Rangliste</h2>
<div class="submenu">
<p><a href="ranking.php?m=ranking&amp;sid='.$sid.'&amp;type=user">Spieler</a> | <a href="ranking.php?m=ranking&amp;sid='.$sid.'&amp;type=cluster">Cluster</a></p>
</div>
<div class="important">
<h3>Wichtig</h3>
<p>Die Punktest&auml;nde und die Rangliste werden immer wieder neu berechnet!<br /> <!-- 1. ge?ndert -->
Das n&auml;chste Mal passiert das um '.$updtime.'.</p>
</div>
';

if($type=='user') {
    echo '<div id="ranking-user">
<h3>User-Rangliste</h3>
<form action="ranking.php?m=search&amp;sid='.$sid.'&amp;type='.$type.'" method="post">
<table id="rank-table">
<tr>
<th class="number">Platz</th>
<th class="name">Name</th>
<th class="cluster">Cluster</th>
<th class="points">Punkte</th>
<th class="online">Status</th>
</tr>
';

    $start=(int)$_REQUEST['start'];

    $uid=(int)$_REQUEST['id'];
    if($uid==0) { $uid=$usrid; $udat=$usr; } else  $udat=getuser($uid);

    $rank=$udat['rank'];
    if($start==0) $start=$rank-4;
    if($start<=0) $start=1;

    #$total=(int)file_get('data/_server'.$server.'/rank-user-count.dat");
    $info=gettableinfo('rank_users',dbname($server));
    $total=$info[Rows];
    $r=db_query('SELECT * FROM rank_users WHERE platz>='.mysql_escape_string($start).' AND platz<'.mysql_escape_string($start).'+10;');

    $cluster_cache;
    while($data=mysql_fetch_assoc($r)):
      $ranking_sid=mysql_fetch_assoc(mysql_query('SELECT sid FROM users WHERE id='.$data['id']));
      $platz=$data['platz'];
      $uid=$data['id'];
      $uname=$data['name'];
      $c=$data['cluster'];
      $points=number_format($data['points'], 0, ',', '.');;
      if(file_exists('data/login/'.$ranking_sid['sid'].'.txt')==true) { $online='<font color="green">Online</font>'; }
      else { $online='<font color="red">Offline</font>'; }
      if(isset($cluster_cache[$c])==false) {
        $c=getcluster($c);
        $cluster_cache[$c['id']]=$c;
      } elseif($c!='') $c=$cluster_cache[$c];
      else $c=false;
      $ccode=$c['code'];

      if($uid==$udat['id']) {
        $platz='<strong>'.$platz.'</strong>';
        $uname='<strong>'.$uname.'</strong>';
        $points='<strong>'.$points.'</strong>';
        $cinfo='<strong>'.$cinfo.'</strong>';
	$online='<strong>'.$online.'</strong>';
      }

      if($c!==false) {
        $cclass='cluster';
        $cinfo='<a href="cluster.php?a=info&amp;sid='.$sid.'&amp;cluster='.$c['id'].'">'.$c['code'].'</a>';
      } else { $cinfo='&nbsp;'; $cclass=''; }
echo '<tr>
<td class="number">'.$platz.'</td>
<td class="name"><a href="user.php?a=info&amp;user='.$uid.'&amp;sid='.$sid.'">'.$uname.'</a></td>
<td class="'.$cclass.'">'.$cinfo.'</td>
<td class="points">'.$points.'</td>
<td class="online">'.$online.'</td>
</tr>
';

    endwhile;
    #&lsaquo; &rsaquo;

    if($start-10 > 0) $x=$start-10; elseif($start<=1) $x=0; else $x=1;
    if($x>0) $forwards='<a href="ranking.php?a=ranking&amp;start=1&amp;sid='.$sid.'#rank-table">&laquo; Platz 1</a> | <a href="ranking.php?a=ranking&amp;start='.$x.'&amp;sid='.$sid.'#rank-table">&lsaquo; Besser</a>';
    if($start+10 <= $total) $x=$start+10; elseif($start+10>$total) $x=$total+1; else $start=$total;
    if($x<=$total) $backwards='<a href="ranking.php?a=ranking&amp;start='.$x.'&amp;sid='.$sid.'#rank-table">Schlechter &rsaquo;</a> | <a href="ranking.php?a=ranking&amp;start='.($total-5).'&amp;sid='.$sid.'#rank-table">Letzter Platz &raquo;</a>';
    if($forwards!='' AND $backwards!='') $forwards.=' | ';

    echo '<tr id="ranking-user-navigation">'.LF.'<td colspan="5">'.$forwards.$backwards.'</td>'.LF.'</tr>'."\n";

} else {

    $sorttype=(int)$_REQUEST['sorttype'];
    $sts=array('Punkte','Punkte pro Mitglied','PCs aller Mitglieder','PCs pro Mitglied','Angriffs-Erfolgsrate','Mitglieder');
    echo '
<div id="ranking-cluster">
<h3>Cluster-Rangliste</h3>
<form action="ranking.php?m=search&amp;sid='.$sid.'&amp;type='.$type.'" method="post">
<table id="rank-table">
<tr>
<th>Platz:</th>
<th>Name:</th>
<th>'.$sts[$sorttype].':</th>
</tr>
';

    $start=(int)$_REQUEST['start'];
    $cid=(int)$_REQUEST['id'];
    if($cid==0) $cid=(int)$usr['cluster'];
    
    $sts=array('points','av_points','pcs','av_pcs','success_rate','members');
    $prec=array(0,      2,          0,    2,      1                ,0);
    $r=db_query('SELECT * FROM `rank_clusters` ORDER BY `'.mysql_escape_string($sts[$sorttype]).'` DESC;');
    $total=mysql_num_rows($r);
    #echo $cid;
    
    if ($total == 0)
    {
      echo '</table>';
      createlayout_bottom();
      exit;
    }
    
    if($start < 1) {
      $i=0;
      while(true):
        $dat=mysql_result($r,$i,'cluster');
        #echo $dat.' ';
        if($dat==$cid) { $start=$i-4; break; }
        $i++;
        if($i>=$total) break;
      endwhile;
    }
    if($start<1) $start=1;
    
    $i=$start-1;
    mysql_data_seek($r,$start-1);
    while($dat=mysql_fetch_assoc($r)):
      $c=getcluster($dat['cluster']);
      if($c!==false) {
        $zeile[0]=$dat['cluster'];
        $zeile[1]=htmlspecialchars($c['code']);
        $zeile[2]=number_format($dat[$sts[$sorttype]], $prec[$sorttype], ',' ,'.');
        if($sorttype==4) $zeile[2].=' %';
        $zeile[3]=htmlspecialchars($c['name']);
        $platz=$i+1;
        if($c[id]==$cid) {
          $platz='<strong>'.$platz.'</strong>';
          $zeile[1]='<strong>'.$zeile[1].'</strong>';
          $zeile[2]='<strong>'.$zeile[2].'</strong>';
          $zeile[3]='<strong>'.$zeile[3].'</strong>';
        }
        echo '<tr>
<td class="number">'.$platz.'</td>
<td class="name"><a href="cluster.php?a=info&amp;cluster='.$zeile[0].'&amp;sid='.$sid.'">'.$zeile[1].'</a> ('.$zeile[3].')</td>
<td class="points">'.$zeile[2].'</td>
</tr>
';
      }
      $i++;
      if($i>=$start+9) break;
    endwhile;

    if($start-10 > 0) $x=$start-10; elseif($start<=1) $x=0; else $x=1;
    if($x>0) $forwards='<a href="ranking.php?a=ranking&amp;type=cluster&amp;start=1&amp;sid='.$sid.'&amp;sorttype='.$sorttype.'#rank-table">Platz 1</a> | <a href="ranking.php?a=ranking&amp;type=cluster&amp;start='.$x.'&amp;sid='.$sid.'&amp;sorttype='.$sorttype.'&amp;id='.$cid.'#rank-table">&laquo; Besser</a>';
    if($start+10 <= $total) $x=$start+10; elseif($start+10>$total) $x=$total+1; else $start=$total;
    if($x<=$total) $backwards='<a href="ranking.php?a=ranking&amp;type=cluster&amp;start='.$x.'&amp;sid='.$sid.'&amp;sorttype='.$sorttype.'&amp;id='.$cid.'#rank-table">Schlechter &raquo;</a> | <a href="ranking.php?a=ranking&amp;type=cluster&amp;start='.($total-5).'&amp;sid='.$sid.'&amp;sorttype='.$sorttype.'#rank-table">Letzter Platz</a>';
    if($forwards!='' AND $backwards!='') $forwards.=' | ';

    $max=$total-10;
    echo '<tr><td colspan="4" class="navigation">'.$forwards.$backwards.'</td></tr>';

}

if($type=='user') {
  $st='Benutzer';
  if($usr['bigacc']=='yes') $xxx=' <a href="javascript:show_abook(\'user\')">Adressbuch</a> ';
} else {
  $st='Cluster';
  eval('$ch'.$sorttype.'=\' selected="selected"\';');
  $xxx='<select onchange="sorttype_go(this,\''.$cid.'\')"><option'.$ch0.'>Punkte</option><option'.$ch1.'>Durchschnittspunkte pro Mitglied</option><option'.$ch2.'>Anzahl von PCs aller Mitglieder</option><option'.$ch3.'>Durchschnittliche Anzahl PCs pro Mitglied</option><option'.$ch4.'>Angriffs-Erfolgsrate</option><option'.$ch5.'>Mitglieder</option></select>';
  $hf='<input type="hidden" value="'.$sorttype.'" name="sorttype" />';
}

echo '<tr>
<td colspan="5" id="ranking-'.$type.'-search">'.$st.' suchen: '.$hf.'<input name="'.$type.'" value="'.$_REQUEST[$type].'" maxlength="50" /> <input type="submit" value="OK" /></td>
</tr>
<tr>
<td colspan="5">'.$xxx.'</td>
</tr>
</table>
</form>
</div>
</div>
';

createlayout_bottom();

break;

case 'search':
switch($_REQUEST['type']) {

case 'user':
  $u=getUser(trim($_REQUEST['user']),'name');

  if($u!==false) {
    header('Location: ranking.php?a=ranking&sid='.$sid.'&type=user&id='.$u['id']);
  } else {
    simple_message('Einen Benutzer mit diesem Name gibt es nicht!');
  }
break;

case 'cluster':
  $c=getcluster(trim($_REQUEST['cluster']),'code');

  $st=(int)$_REQUEST['sorttype'];
  if($c!==false) {
    header('Location: ranking.php?a=ranking&sid='.$sid.'&type=cluster&id='.$c['id'].'&sorttype='.$st);
  } else {
    simple_message('Einen Cluster mit diesem Code gibt es nicht!');
  }
break;

}
break;

}

/*if(!headers_sent()) no_(1); FIXME */

?>
