# Webdav access to TYPO3 CMS 6.1+ including FAL

![Build Status](https://api.travis-ci.org/kaystrobach/TYPO3.webdav.svg)

This extension allow you to access the TYPO3 FS via Webdav.
Other virtual filesystems can also be added.

![Screenshot](Documentation/Images/webdav.png)

# Goal

This extension offers you the possibility to access your TYPO3 Installation via WebDav.

The WebDav URL is **http://<domain>/index.php/dav**

After opening the URI you're asked to input your user credentials. Just input your TYPO3 CMS user credentials. The extension webdav supports saltedpasswords so it should work in all environments.

# Configuration Options

All Configuration is done globally in the extensionmanager. There currently three Options:
* add cyberduck bookmarkfile in filelistmodule
* add webdav shortcur in filelist
* dav only hostname: 
while this option enables the server to completely capture the entered hostname for dav usage, it won't be possible to render other output via this hostname, this option requires .htaccess to redirect every call to index.php like it's needed for simulate static or realurl.

# Non windows users

On linux, bsd and OSX you can use the native connect to server method to connect to your TYPO3.

# Windows users

It's really possible to use webdav on Windows nativly, from my point if view I do not recommend it!
There are several better and faster clients:

* Cyberduck (free)
 * http://code.google.com/p/sabredav/wiki/Cyberduck
* Totalcommander mit Webdav-Plugin (commercial)
 * http://ghisler.com/download.htm
 * http://ghisler.com/plugins.htm#filesys  
* netdrive (commercial)
 * http://www.netdrive.net/

## Adjusting your system settings

If you still want to use Windows, you may need to adjust some settings in your registry.
As this may damage your system i do not provide any help an how to do this.

```
Errors copying files?
HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Services\WebClient\Parameters\SupportLocking = 0
```

```
Problems with Basic Auth?
HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Services\WebClient\Parameters\BasicAuthLevel = 2
```

You know, what to do afterwards? - Reboot ... and connect!

```cmd
Connect with a share:
net use <CHAR>: https://<HOST>/index.php/dav /user:<BE_USER> [PASSWORD]
net use B: https://your.host.tld/index.php/dav /user:beuser bepassword
```
