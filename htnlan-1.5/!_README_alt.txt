 _    _ _______ _   _       _               _   _   
| |  | |__   __| \ | |     | |        /\   | \ | | 
| |__| |  | |  |  \| |     | |       /  \  |  \| | 
|  __  |  | |  | . ` |     | |      / /\ \ | . ` |
| |  | |  | |  | |\  |  _  | |____ / ____ \| |\  |
|_|  |_|  |_|  |_| \_| (_) |______/_/    \_\_| \_|
				      by Schnitzel

Um die HTN.LAN Version auf eurem Server laufen zu lassen 
m�sst ihr zuerst die 'DATABASE.DUMP.SQL' z.B. in
PHPmyAdmin starten.

========================================================================

Danach m�sst ihr in der 'config.php' diese Daten anpassen:

 - $database_prefix='htn_server';
   Die Datenbank auf dem ihr die Daten eingelesen habt, ohne 
   Zahl! Standartm�ssig ist die Datenbank 'htn_server1', also
   'htn_server' eintragen 

 - $db_use_this_values=true;
   Wenn ihr bei eurem MySQL Server ein Username / Passwort eingegeben 
   habt, m�sst ihr hier true eingeben, ist es Login-Frei muss hier
   'false' rein. 

 - $db_host='localhost';
   Hier k�nnt ihr den MySQL Server eintragen, standartm�ssig
   'localhost' 

 - $db_username='root';
   Wenn ihr bei $db_use_this_values 'true' eingegeben habt,
   dann k�nnt ihr hier den Username eingeben.

 - $db_password='';
   Wenn ihr bei $db_use_this_values 'true' eingegeben habt,
   dann k�nnt ihr hier das Passwort f�r den Username eingeben.

========================================================================

Nun hat es in der HTN.LAN Version in der config.php eine spezielle
Varable: 'define('geschwindigkeit',10,false);' 
Hier k�nnt ihr die Geschwindigkeits-Zahl einstellen:

'1'   - normale/originale HTN Geschwindigkeit
'10'  - 10x schneller als original
'100' - 100x schneller als original

========================================================================

Speziell in dieser Version habe ich die 'fillcountry' Funktion von erazorlll
eingef�gt, damit kann man die Einzelnen Subnetze mit 0-, 1024- oder 
Zufalls-Punkte PC's f�llen oder Herrenlose PC's l�schen. Die Funktion 
findet man als Administrator 'stat <= 100' im Men� unter dem Men�punkt: 
'Subnetze f�llen / leeren'. 

========================================================================

Update von 1.1.9b oder tiefer:

Wenn ihr von der Version 1.1.9b oder einer tieferen updatet, dann m�sst
ihr im PHPmyAdmin die update.sql anzeigen (fixt einen Fehler in der DB)

========================================================================

Viel Spass bei der HTN.LAN Version!

========================================================================

Changelog 1.2.2:
- Anzahl Sekunden in denen kein Geld �berwiesen und der Clusterbeitrag 
  nicht erh�ht werden kann, nach der neuen Runde, eingef�gt 
  (config.php, game.htn, cluster.htn)
- PC Suche und Notiz verbessern (game.htn)


Changelog 1.2.1:
- MaxPlayers bei Cluster auf 32 erh�ht (ingame.php)
- PC's nach Geld sortieren (game.htn)
- Neue Angriffsformel (battle.htn, distrattack.htn, ingame.php)
- layout.php auf neue Version angepasst (layout.php)

Changelog 1.2.0:
- Beschr�nkung bei Geld�berweisung entfernt
- Bei einem HJ wird das Attribut "lmupd" neu reingeschrieben (so haben die 
  PC's nicht so viel Geld)
- Notizblok eingef�gt
- Sortierung der PC's wird gespeichert
- PC Suche eingef�gt (PC's k�nnen nach bestimmen Kriterien gesucht werden)
- Angriffsst�rke bei einem Hijack vermindert (SDK z�hlt nicht mehr)
- Fehler bei abook.htn gefixt
- Erweiterte Statistiken eingef�gt
- Member k�nnen sich aus dem Cluster selber geld schicken
- Der Server kann �ber die Datei /data/serverstop.txt f�r eine bestimmte Zeit
  (muss per UnixTimestamp eingegeben werden) gesperrt werden.

****WICHTIG****
- Wenn ihr von der Version 1.1.9b oder einer tieferen updatet, dann m�sst
  ihr im PHPmyAdmin die update.sql auf�hren!!

Changelog 1.1.9b:
- Riesen-Fehler mit der config.php gefixt (min_user_points war nicht definiert)

Changelog 1.1.9:
- Fehler bei DistributeAttack gel�st

Changelog 1.1.8:
- Der Server kann nun auf eine bestimmte Zeit gesperrt werden 
  (Zeit kann in der /data/newround.txt per UnixTimeStamp angeben werden,
  Administratoren k�nnen sich trozdem einloggen!) 
  (gres.php, startseiten.php, login.htn)
- neue Fillcountry Version eingef�hgt, gibt jetzt keine Fehler mehr

Changelog 1.1.7:
- Bei Passwort �nderung wird kein Mail mehr verschickt
- Bei Accountl�schung wird auch kein Mail verschickt
- Angriffsformel auf DistributeAttack erweitert
- Passwort zusenden deaktiviert
- Neue DATABASE.DUMP.SQL eingef�ght (Countrys gefixt)
- Update.SQL f�r Updates von alten DB's

Changelog 1.1.6b:
- Fehler in der neuen Angriffsformel gefixt

Changelog 1.1.6:
- Neue Version von Fillcountry 0.4 eingef�gt
- Neue Angriffformel nachzulesen: http://www.htn-lan.com/forum/thread.php?threadid=99
- Startgeld der neuen Pcs eingef�hrt, kann in der config.php eingegeben werden.
- Berechnungsfehler-h�nger gefixt

Changelog 1.1.5:
- Cokkie-Sicherheitleck in login.htn ge�ndert
- Einige kleine echo Fehler gefixt

Changelog 1.1.4:
- Fehler in fillcountry mit last-menu-update-Timestamp gefixt
- Fehler bei HTN nur alle 2 Tage gefixt 

Changelog 1.1.3:
- Fehler bei zu grossen Speed mit Money gefixt
- Kapazit�t des BB * Geschwindigkeit erh�ht

Changelog 1.1.2:

- Fehler bei der Geschwindigkeit beim Ausbau von IDS gefixt (ingame.php)
- Fehler bei HJ ohne Cluster gefixt  (battle.htn)

Changelog 1.1.1:

- Fehler dass jeder die Subnetze f�llen kann gefixt.

========================================================================

Bei Fragen oder Problemen:

ICQ:	77501874
Mail:	michael@x-page.ch
MSN:	schnitzel@comireel.ch