<?php
require('vendor/autoload.php');
/*
 * Settings
 *
 * player needs to be online.
 *
 * Check readme.md
 */
$host = '0.0.0.0';
$port = 25575;
$password = 'myRconPassword';
$username = "minecraftUsername";
$OnlyBackup = false;
$restoreBackup = '';

/*
 * Code
 */
use Thedudeguy\Rcon;
$rcon = new Rcon($host, $port, $password, 3); //timeout 3

if ($rcon->connect())
{
    /*
     * Collect tags
     */
    $tags = $rcon->sendCommand("scoreboard players tag $username list"); //send rcon
    $tags = TagListToArray($tags);

    $backup = $username.'_tags.json';
    if(file_exists($backup)) die("Backup $backup already exists, please remove.");
    file_put_contents($backup, json_encode($tags, JSON_PRETTY_PRINT));

    print("> $username has these tags:".PHP_EOL);
    print(implode(", ",$tags).PHP_EOL);
    print("> A backup was saved to $backup".PHP_EOL);
    sleep(2); // rcon cooldown


    /*
     * Stop script if were only here to backup.
     */
    if($OnlyBackup) exit;


    /*
     * Teleport player to spawn
     */
    print("> Teleporting $username to spawn".PHP_EOL);
    $tp = $rcon->sendCommand("tp $username -780 64 -245");
    print("> ".$tp.PHP_EOL);
    sleep(2); // rcon cooldown


    /*
     * Remove EonEncounterSuccess tag.
     */
    print("> Removing EonEncounterSuccess tag".PHP_EOL);
    $eon = $rcon->sendCommand("scoreboard players tag $username remove EonEncounterSuccess");
    print("> ".$eon.PHP_EOL);
    sleep(2); // rcon cooldown


    /*
     * Wait for user to talk to the professor.
     */
    $waitForProfessor = true;
    print("> Waiting for $username to finish talking to the professor".PHP_EOL."# ");
    while($waitForProfessor) {
        print(".");
        $wTags = $rcon->sendCommand("scoreboard players tag $username list"); //send rcon
        $wTags = TagListToArray($wTags);

        if (in_array('Dialogue1', $wTags)) {
            $waitForProfessor = false;
            print(PHP_EOL);
            print("> $username has spoken to the professor".PHP_EOL);
        }
        sleep(2); // dont spam server
    }


    /*
     * Use backup file.
     */
    if(strlen($restoreBackup) > 1) {
        print("> Loading backup file $restoreBackup" . PHP_EOL);
        $file = file_get_contents("$restoreBackup");
        if(!$file) die('Can not find backup file');
        $tags = json_decode($file);
        if(!$tags || count($tags) == 0) die('Backup file seems empty');
    }


    /*
     * Give player tags
     */
    print("> Giving tags back".PHP_EOL);
    foreach($tags as $tag) {
        usleep((0.25 * 1000000)); // wait quarter second.
        $cmd = $rcon->sendCommand("scoreboard players tag $username add $tag");
        print("# ".$cmd.PHP_EOL);

    }


    /*
     * Were done
     */
    print("> Done.".PHP_EOL);
}
else {
    echo '# RCON: Failed to connect to RCON, check your server.properties.';
}


function TagListToArray($tags) {
    $tags = explode(":", $tags)[1]; //strip first text
    $tags = explode(", ", $tags); //split the tags
    $last_tag = count($tags) -1; //get the last tag
    $tags2 = explode(" and ", $tags[$last_tag]); //last tag is separated by ' and '
    $tags[$last_tag] = $tags2[0];
    $last_tag++; //increase last tag by 1.
    $tags[$last_tag] = $tags2[1];
    $tags[$last_tag] = str_replace("\u0000\u0000", "", $tags[$last_tag]); //weird minecraft bug.

    return $tags;
}