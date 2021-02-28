+++
title = "Cleaning Up After Deleted Apps on MacOS"
summary = "Clean up app remnants after deleting the app itself."
date = "2021-02-28"
tags = ["mac"]
toc = false
+++

After deleting apps on MacOS, they tend leave remnants behind in the form of caches and configs throughout the system. This is how I find and remove those leftover files. I'll be completely removing Skype from my system to demonstrate files and directories left behind after an app is deleted.

## Searching for Remnants

To find directories and files related to Skype, I ran a system-wide search using the following command:

```
sudo find / -name '*skype*' -not -path '/System/Volumes/Data/*' > ~/Desktop/findings.txt
```

This command finds all files and directories containing the word "snitch", while excluding those in `/System/Volumes/Data/`, and saves the results to a textfile. The reason `/System/Volumes/Data/` is being excluded is because the `find` command will give us duplicate results with `/System/Volumes/Data/` prepended. This is because MacOS has an additional read-only system volume. You can [read more about it here](https://support.apple.com/en-us/HT210650), and for a more technical dive into this additional volume you can read [this forensics blog post](http://www.swiftforensics.com/2019/10/macos-1015-volumes-firmlink-magic.html).

The command will take a few minutes to complete, so give it some time. Additionally, your terminal will fill up with error messages stating `Operation not permitted`. For example:

```
find: /private/var/db/appinstalld: Operation not permitted
find: /private/var/db/fpsd/dvp: Operation not permitted
find: /private/var/db/installcoordinationd: Operation not permitted
find: /private/var/db/oah: Operation not permitted
```

This is normal, just wait for the command to finish.

## Results

Once the command is finished, the output textfile will contain paths of Skype-related files and directories. Here are a few examples of those to demonstrate:

```
/usr/local/Homebrew/Library/Taps/homebrew/homebrew-cask-versions/Casks/skype7.rb
/usr/local/Homebrew/Library/Taps/homebrew/homebrew-cask-versions/Casks/skype-preview.rb
/usr/local/Cellar/nmap/7.91/share/nmap/scripts/skypev2-version.nse
/Users/nelson/Library/Preferences/com.skype.skype.plist
/Users/nelson/Library/Caches/com.skype.skype
/Users/nelson/Library/Caches/com.skype.skype.ShipIt
```

From here, we can see that we can ignore some of the results that were clearly not left behind by Skpe, such as the results under the `/usr/local/Homebrew/` directory. In this case, we want to delete files and directories with `com.skype.skype` in their name.

## Searching by Company

You can also try searching by company name. For example, I ran the `find` command and searched for `*microsoft*` and found that there were several directories in my system that were left by Microsoft applications:

```
sudo find / -name '*microsoft*' -not -path '/System/Volumes/Data/*'
```

A few examples of the results:

```
/private/var/db/receipts/com.microsoft.package.Microsoft_Excel.app.plist
/private/var/db/receipts/com.microsoft.package.Microsoft_Outlook.app.plist
/private/var/db/receipts/com.microsoft.package.Microsoft_PowerPoint.app.bom
/Users/nelson/Library/Application Scripts/com.microsoft.Powerpoint
/Users/nelson/Library/Application Scripts/com.microsoft.Word
/Users/nelson/Library/Application Scripts/com.microsoft.Excel
/Users/nelson/Library/Mobile Documents/iCloud~com~microsoft~onenote
/Users/nelson/Library/Mobile Documents/iCloud~com~microsoft~skype~teams
/Users/nelson/Library/Cookies/com.microsoft.OneDriveStandaloneUpdater.binarycookies
```

It's worth trying both searching by app and searching by company. If we had only searched for Powerpoint, we would've missed the results for Word and Excel.

Try this with apps you've deleted in the past. Chances are there's at least one file left behind by an app.