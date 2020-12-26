## **Player name change fix for Jond's Johto map**

In Jond's Johto-Kanto playthrough map several errors occur when a users has changed their minecraft name.
This tool will fix the newly changed player and restore his progress made on his old username.

 

#### Requirements:
`PHP 5+` `Minecraft server with RCON enabled.`



#### **Usage:**
Open `fix-player.php` and alter the settings listed under `settings`
```text
$host               The IP adress off the server, this does not support SRV records.
$port               The RCON port of the server, by default this is 25575 when RCON is enabled.
$password           The RCON password set in server.properties.
$timeout            The amount of times it should try to connect, basicly just
$username           The new username of the user you are trying to fix.
$OnlyBackup         Set to "true" if you only want to make a backup of the current players progression.
$restoreBackup      If you want to resture a previously created progression backup to a player.
```

Make sure the target player is currently online. Then run the command on your server
```bash 
php fix-player.php
```

During the execution of the script the player will be teleported back to the spawn, he should talk to the professor and then be teleported back to the house.
Once the player is teleported back his progression will be restored, based on how far the player has progressed this may take up to 2-3 minutes.


#### Final words
I guarantee nothing, but whatever it does it makes a backup before end.
For any support, Jond's discord is the best place: https://discord.com/invite/p5txVrp
