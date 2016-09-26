BlackEye CMS
============
The best DarkOrbit private server.
Current version: 1.1.0

CMS Structure
-------------
The core of the CMS (the installation of Alexya) is located in the directory *alexya* while the directory *public_html* contains the files interpreted by the browser (css, images, js...).

The CMS is build on top of the [Alexya Framework](https://github.com/AlexyaFramework/Alexya) so you should check it for knowing how it works.
There 4 pages (they're located on *Application/Page*):
 * Internal page
 * External page
 * Map page
 * API page

The external page simply prints external page and registers/logins users.
The internal page will load the requested module (they're located on *Application/Module*) and render it.
The map page contains the spacemap container.
The API page is used for querying the BlackEye database easily so you can build your own tools for this server (it's documented in `docs/API`).


Before continue you should go to the *alexya* directory and execute `composer update` for installing composer's dependencies.

ORM Classes
-----------
The ORM classes are under the *\Application\ORM* package and are used to translate the database tables to objects.

Between all the ORM classes, the  class *\Application\ORM\Account* is the most important since it's used everywhere. This class represents the user that is currently visitting the website so take a close look to it.

SocksWork
---------
*SocksWork* is the way the CMS will be linked to the emulator for keeping it updated about the things user does on the website (like bidding on auction, buying items...).

The *SocksWork* object is instanced in *index.php* and takes the configuration values from *config/alexya.php*, by default the client connects to *127.0.0.1:1207*.

The packets *SocksWork* sends are located in *Application/Packets* and their structure is on the documentation of each packet.

Localization
------------
When printing texts always use the *\Alexya\Localization\Locale::translate* function (*t* is a shortcut for the function) so the text can be translated whenever it's possible. Also use the functions *\Alexya\Localization\Locale::formatNumber* (*fNumber* as shortcut) and *\Alexya\Locale\Localization::formatDate* (*fDate* as shortcut) for formatting numbers and dates.
