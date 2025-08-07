+++
title = "Cleaning Up Residual Files on macOS After Deleting Apps"
summary = "Clean up residual files and directories after deleting macOS apps."
date = "2021-02-28"
lastmod = "2024-10-13"
categories = ["macOS", "Shell"]
keywords = ["macOS cleanup", "delete app files", "residual files", "macOS maintenance", "find command", "Pearcleaner", "app uninstaller", "macOS tips", "system cleanup", "leftover files"]
+++

After deleting apps on macOS, they tend leave behind residual files and directories throughout the system. You can use the `find` command to find these files after an app has been deleted. I'll be deleting the LastPass app and removing its residual files as an example.

**2024-10-13 Update**: I recently discovered [Pearcleaner](https://github.com/alienator88/Pearcleaner). You can use this app to delete other apps along with all the extra files and folders they create. I recommend you use this first and then continue reading this post if you want to look more deeply.

You can download Pearcleaner from the link above or install it with [Homebrew](https://formulae.brew.sh/cask/pearcleaner):

```shell
brew install --cask pearcleaner
```

## Searching for Residual Files and Directories

To find directories and files related to LastPass, I ran a system-wide search using `find`. I exlcluded directories such as `/System/Volumes/Data` since those result in errors like "Operation not permitted". I also excluded Homebrew directories that don't need to be cleaned up. You can add more directories as needed, just make sure to not add a trailing slash to the filepaths!

```shell
find / \
-not \( -path /System/Volumes/Data -prune \) \
-not \( -path /usr/local/Homebrew -prune \) \
-not \( -path /usr/local/Cellar -prune \) \
-not \( -path /usr/local/Caskroom -prune \) \
-name \*lastpass\* 2>&1 | grep -v -E 'Operation not permitted|Permission denied|Not a directory'
```

The command may take some time to complete depending on your machine specs and amount of files you have. It took around ~10 minutes for me on an 2019 Macbook Pro with an i7 Intel CPU.


Here's the output I got from the command above. I can now go through each of these files and folders and decide if I want to delete them manually:

```shell
/private/var/folders/m9/_7bg6tbn3636m1zzlzq33bwh0000gn/T/com.lastpass.lastpassmacdesktop
/private/var/folders/m9/_7bg6tbn3636m1zzlzq33bwh0000gn/C/com.lastpass.lastpassmacdesktop
/Users/nelson/Library/Application Support/com.lastpass.lastpassmacdesktop
/Users/nelson/Library/WebKit/com.lastpass.lastpassmacdesktop
/Users/nelson/Library/Preferences/com.lastpass.lastpassmacdesktop.plist
/Users/nelson/Library/Application Scripts/N24REP3BMN.lmi.lastpass.group
/Users/nelson/Library/Application Scripts/com.lastpass.lastpassmacdesktop.safariext
/Users/nelson/Library/HTTPStorages/com.lastpass.lastpassmacdesktop.binarycookies
/Users/nelson/Library/HTTPStorages/com.lastpass.lastpassmacdesktop
/Users/nelson/Library/Group Containers/N24REP3BMN.lmi.lastpass.group
/Users/nelson/Library/Group Containers/N24REP3BMN.lmi.lastpass.group/Library/Preferences/N24REP3BMN.lmi.lastpass.group.plist
/Users/nelson/Library/Group Containers/N24REP3BMN.lmi.lastpass.group/Library/Application Scripts/N24REP3BMN.lmi.lastpass.group
/Users/nelson/Library/Containers/com.lastpass.LastPass
/Users/nelson/Library/Containers/com.lastpass.lastpassmacdesktop.safariext
/Users/nelson/Library/Containers/com.lastpass.lastpassmacdesktop.safariext/Data/Library/Application Scripts/com.lastpass.lastpassmacdesktop.safariext
/Users/nelson/Library/Caches/com.crashlytics.data/com.lastpass.lastpassmacdesktop
/Users/nelson/Library/Caches/Homebrew/Cask/lastpass--4.116.0.dmg
/Users/nelson/Library/Caches/com.lastpass.lastpassmacdesktop
```

This could probably be automated all the way through the deletion step, but I prefer to double check what is actually going to be deleted.

Also, this *may* help to free up space on macOS, but I mainly did it because I like keeping my system tidy.

Try this out with whatever apps you've deleted in the past. You can also try finding files and folders be specifying a company name. For example, replace `\*lastpass\*` with `\*microsoft\*` and see what you get!

## References
- https://stackoverflow.com/questions/4210042/how-do-i-exclude-a-directory-when-using-find
