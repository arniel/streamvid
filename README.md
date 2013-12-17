StreamVid Version 1.1 Readme
=======================================================
 * AUTHOR: Jon Hall
 * REVISION: 3
 * DATE: 2013/12/16

What is StreamVid?
------------------------------------------------
StreamVid is a LAMP server application that allows users, through the use of tv database service SickBeard (http://sickbeard.com/) and StreamVid's own movie database, to stream their own digital content while on the go. Complete with a user authentication system so you can add your family members to the site (where they will have their own preferences, favorites, etc.), StreamVid caters to the person who doesn't have time to watch movies or tv while at home, but finds time while traveling or otherwise to enjoy the odd tv show or movie they may have intended to watch at home but never got around to.* Whether at home on another device or outside of your home network, StreamVid enables all media content rendered in MP4 format to play via any device (PC, Mac, Unix, PS3/PS4, Xbox 360, WiiU and PS Vita have all been tested and work).

*StreamVid does not supply any video content of any kind. It is up to the end-user to create and curate their own video libraries. StreamVid is not responsible for any unintended consequences that arise from exposing a user's home video content via the web.

Screenshots
------------------------------------------------
* [Home] (http://i.imgur.com/r9ziy7Q.jpg)
* [TV Shows, Alphabetized] (http://i.imgur.com/r9ziy7Q.jpg)
* [TV Shows, Latest] (http://i.imgur.com/r9ziy7Q.jpg)
* [Movies] (http://i.imgur.com/r9ziy7Q.jpg)
* [User Panel] (http://i.imgur.com/AFZs05D.png)


System Requirements
-------------------------------------------------
 * Ubuntu Linux (12.10+ supported)
 * Local SickBeard installation
 * Apache2, with Mod-Auth-Token (https://code.google.com/p/mod-auth-token/) and mod-h264-streaming (http://h264.code-shop.com/trac/wiki/Mod-H264-Streaming-Apache-Version2) installed
 * PHP5 (PHP4 may work, not tested)
 * MySQL version 5.5.32 or higher
 * All video files must be encoded as x264 with .mp4 extensions, no other files will work with StreamVid

Installation Steps
--------------------------------------------------
Note: These steps may vary slightly per install. The generics will be used in place of any local data when necessary, they will be in ALL CAPS. Support for custom setups will be limited and vary on a case-by-case basis. This guide also assumes you know about general unix permissions and how to make things writeable/readable is StreamVid is having troubles (it shouldn't in most cases).

1. Include the mod-h264-streaming and mod-auth-token apache modules in your apache2.conf file. Here's a sample of what it should look like:
<pre>
    Load h264 streaming module
    LoadModule h264_streaming_module /usr/lib/apache2/modules/mod_h264_streaming.so
    AddHandler h264-streaming.extensions .mp4
    #Load mod-auth token
    LoadModule auth_token_module /usr/lib/apache2/modules/mod_auth_token.so
    <Directory /var/www/tvcontent>
        AllowOverride None
        allow from all
    </Directory>
    ScriptAlias /tvcontent/ /PATH/TO/PUBLICWWW/FOLDER/tvcontent/
    <Location /tvcontent/>
        AuthTokenSecret         "PASSWORD"
        AuthTokenPrefix         /tvcontent/
        AuthTokenTimeout        10800
    </Location>
</pre>

2. Create a movies folder. If you have one that you already want to use, skip this step.
3. Create a symbolic link to your movies folder. Navigate to your apache public (www) folder, and issue the following command:
		ln -s /PATH/TO/MOVIES/FOLDER moviecontent
4. Create a movie images folder. If you have one that you already want to use, skip this step.
5. Create a symbolic link to your movie images folder. Navigate to your apache public (www) folder, and issue the following command:
		ln -s /PATH/TO/MOVIEIMAGES/FOLDER movie_images
6. Create a tv folder. If you have one that SickBeard is already using, skip this step. Otherwise, create the folder (and make sure that SickBeard knows about it, this is where you'll put all your tv episodes from now on)
7. Create a symbolic link to your tv folder. Navigate to your apache public (www) folder, and issue the following command:
		ln -s /PATH/TO/TV/FOLDER/ tvcontent
8. Create a symbolic link to SickBeard's show images folder. If you've been using SickBeard for a while, you may know where this is or how to access it in SickBeard's settings. In a default configuration, with the .sickbeard folder located in a user's home directory, the path should be something like /home/USER/.sickbeard/cache/images. Use that path in the following command:
		ln -s /home/USER/.sickbeard/cache/images show_images
9. Copy the contents of the StreamVid archive's "www" folder into your apache public (www) folder.
8. Create a folder for the program above the apache's public folder, so that it can't be reached by web traffic (I put mine at /var/StreamVid and my public folder is /var/www).
10. In that folder, place the contents of the StreamVid folder.
11. Open index.php, located at the root level of your apache public folder. Edit the line with the placeholder at the top of the file, and give it the absolute path of the config.php file (which should be located at the root level of the StreamVid folder you just created)
12. Open create_user_table.php, located at the root level of your apache public folder. Edit the line with the placeholder at the top of the file, and give it the absolute path of the config.php file (which should be located at the root level of the StreamVid folder you just created)
13. Open the config.php file in the StreamVid folder. You will edit this file with the specifics of your system configuration. Not all lines need to be edited, but in case you do, below are explanations for each line in the config file:
<pre>
$config['StreamVidLoc']				Location of the StreamVid program folder. VERY important.
$config['SessionSavePath']			The path to sessions folder which will save individual users' sessions for later use (necessary for the login system to work properly).
$config['LoginAuth'] 					The path to the login authorization php file, which will authorize individual users on every pageview. The full filepath is necessary.
$config['MYSQL_server'] 			Name of the mysql server to use.
$config['MYSQL_user'] 				The name of the mysql user to use to retrieve/edit rows.
$config['MYSQL_pass'] 				The password for the mysql user.
$config['MYSQL_userDB']			Name of the user database used by the user system for any and everything the user system needs.
$config['MYSQL_movieDB']			Name of the user database used by the movie system for any and everything the movie system needs.
$config['LogFileLoc'] 					Location of the log file StreamVid will use to keep track of user interaction.
$config['debug']							Determines whether, when there are failures in the procedural generation of the website, if PHP outputs those errors.
$config['sickbeardDB'] 				Absolute path of the sickbeard database to be used by StreamVid. VERY important.
$config['AuthTokenSecret']			The secret shared with mod-auth-token to be used to generate video file URIs.
$config['AuthTokenPrefix']			The prefix shared with mod-auth-token to be used to generate video file URIs. This is generally the same as the symbolic link to the tv video files, and should be the same as AuthTokenPrefix in apache2.conf.
$config['BaseTVDir']					The absolute path to the tv directory (not using the SymLink).
$config['TMDbAPIKey']				TheMovieDatabase API key, used to retrieve metadata for movies. (Apply for a key here: http://docs.themoviedb.apiary.io/)
$config['MovieImageLoc']			The absolute path to the movie image directory (not using the SymLink).
</pre>

14. Open your browser, and navigate to your server's URL : WWW.SERVERURL.COM/create_user_table.php. Assuming you setup your config file correctly, this file should create the users and movies databases necessary for StreamVid to properly run. This file will also have created a basic admin user with username "default" and password "default" (or is it "password"? I honestly don't remember).
15. Delete or move create_user_table.php from your server's public (www) folder. This file is to be used on first time initialization only. Any subsequent use could remove user or movie entries in the database, or possible corrupt everything.
16. In your browser, navigate to your server's base URL. You should see a login page. Login with the user/pass test/test.
17. Go to the admin page and create your own ADMIN account, delete test user after logging in as new admin account. Make sure your account type is admin before deleting test.
18. You're done. Have fun!

Images
------------------------------------
The original version of the site was called "AB3", an inside joke among friends. As such, the site is branded as "AB3.0" throughout. All that needs to be changed is a few HTML titles and some image files. To that end, I've left the layered photoshop files for your own use. If you want to call your website something else, go for it, I really don't care. They're all in the public apache folder in /images (which in hindsight isn't the best place for them, but whatever).

Stats/Analytics
---------------------------------------
On the admin page you'll see a box called "Stats JSON". This is for the stats.json file located in the StreamVid folder, which outputs relevant statistical information about the StreamVid server's use. This file is generated by stats.py, a script that is custom to the original machine it was written on. If you want Stats JSON to work for your server, you need to go into stats.py and edit the filepaths to point at the correct locations.
