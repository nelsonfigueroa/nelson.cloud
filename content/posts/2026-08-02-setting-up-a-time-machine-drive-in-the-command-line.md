+++
title = "Setting Up a Time Machine Drive from the Command Line"
summary = "Steps to set up a Time Machine drive from the command line."
date = "2026-08-02"
categories = ["macOS"]
ShowToc = true
TocOpen = true
featured = false
+++

## Introduction

It's possible to set up Time Machine drives from the command line. This is way more convenient and becomes scriptable (there's one GUI checkbox at the end if you encrypt, so the drive can unlock itself). Also, based on my own personal experience, the Time Machine GUI can be unresponsive so the CLI is much better.

I'll be using a 1TB external SSD in this guide.

Here's how you set up a drive.

## Prerequisites

Some of these terminal commands require Full Disk Access. Specifically, the `tmutil` commands we'll be using later on.

Grant your preferred terminal app full disk access in System Settings -> Privacy & Security -> Full Disk Access.

## Identifying the Drive

Plug in your drive. Unlock it if you have to.

Run the following to get some information we'll need.

Here is what you'll see if the drive is currently being used for Time Machine:
```console
$ diskutil list external

/dev/disk4 (external, physical):
   #:                       TYPE NAME                    SIZE       IDENTIFIER
   0:      GUID_partition_scheme                        *1.0 TB     disk4
   1:                        EFI EFI                     209.7 MB   disk4s1
   2:                 Apple_APFS Container disk5         1000.0 GB  disk4s2

/dev/disk5 (synthesized):
   #:                       TYPE NAME                    SIZE       IDENTIFIER
   0:      APFS Container Scheme -                      +1000.0 GB  disk5
                                 Physical Store disk4s2
   1:                APFS Volume backup                  768.2 GB   disk5s1
```

Here is what you'll see if the drive is brand new:
```console
$ diskutil list external

/dev/disk4 (external, physical):
   #:                       TYPE NAME                    SIZE       IDENTIFIER
   0:     FDisk_partition_scheme                        *1.0 TB     disk4
   1:               Windows_NTFS Untitled                1.0 TB     disk4s1
```

Two identifiers matter here:

- **`disk4`** — the whole physical disk. We'll need this later on when running the `eraseDisk` command.
- **`disk5`** — the APFS container. This is what we'll need for the `addVolume` command. (A brand new drive won't have this one yet. It'll get created when we erase the disk in a later step.)

{{< admonition type="warning" >}}
`eraseDisk` destroys everything on whichever disk you name. Confirm you've got the right one before running it or you might nuke your local drive instead of your backup drive.
{{< /admonition >}}

Confirm that you have the correct disk with this command.

```console
$ diskutil info disk4 | grep -iE "device location|protocol|disk size"

Protocol:                  USB
Disk Size:                 1.0 TB (1000204140544 Bytes) (exactly 1953523712 512-Byte-Units)
Device Location:           External
```

We know this one is the external drive due to the `Device Location: External` line. On Apple silicon the internal drive shows `Protocol: Apple Fabric` and `Device Location: Internal`.

## Erasing the Disk

We'll need to erase the disk next. The steps vary slightly depending on whether the drive is brand new or is an existing Time Machine drive.

### Erasing a Brand New Drive

New drives usually ship as ExFAT with an MBR partition scheme, so there's no APFS container yet. We can erase and convert the disk with this command:

```console
$ diskutil eraseDisk APFS backup GPT disk4

Started erase on disk4
Unmounting disk
Creating the partition map
Waiting for partitions to activate
Formatting disk4s2 as APFS with name backup
Mounting disk
Finished erase on disk4
```

This results in a drive with a GPT scheme, an EFI partition, and an APFS container with one volume in it (read more on containers vs volumes in [APFS: Containers and Volumes](https://eclecticlight.co/2024/04/02/apfs-containers-and-volumes/)). It does not encrypt the volume, enable ownership, or set the Time Machine role, which are all things we need. So we'll delete this newly created volume and create a proper one later.

Run this again to figure out the identifier:

```console
$ diskutil list external

/dev/disk4 (external, physical):
   #:                       TYPE NAME                    SIZE       IDENTIFIER
   0:      GUID_partition_scheme                        *1.0 TB     disk4
   1:                        EFI EFI                     209.7 MB   disk4s1
   2:                 Apple_APFS Container disk5         1000.0 GB  disk4s2

/dev/disk5 (synthesized):
   #:                       TYPE NAME                    SIZE       IDENTIFIER
   0:      APFS Container Scheme -                      +1000.0 GB  disk5
                                 Physical Store disk4s2
   1:                APFS Volume backup                  806.9 KB   disk5s1
```

The identifier is `disk5s1` in this case.

Now use that identifier to delete the volume that was created in the `eraseDisk` step:

```console
$ diskutil apfs deleteVolume disk5s1

Started APFS operation
Deleting APFS Volume from its APFS Container
Unmounting disk5s1
Erasing any xART session referenced by 40D7443F-46DA-4DA5-9F61-41AEEBEAA1CF
Deleting Volume
Removing any Preboot and Recovery Directories
Finished APFS operation
```

That's it for this section. Skip ahead to the "Create the Volume" section.

### Erasing a Drive Already Used for Time Machine

If the drive is already being used for Time Machine we need to remove the destination (the disk entry in the Time Machine GUI).

First, figure out the destination UUID:

```console
$ tmutil destinationinfo

====================================================
Name          : backup
Kind          : Local
Mount Point   : /Volumes/backup
ID            : E10FD749-891E-4051-9135-3457E80F90E7
```

Then remove the old destination. We're using `sudo` so it'll prompt you for your machine's password.

```console
$ sudo tmutil removedestination E10FD749-891E-4051-9135-3457E80F90E7
```

Verify it's gone:

```console
$ tmutil destinationinfo

tmutil: No destinations configured.
```

You can double check that this worked by checking in System Settings -> General -> Time Machine. There should be no backup drive listed. If it's still there, you can manually remove it in the GUI.

Now delete the old volume. The container stays, so there's no need to repartition the whole disk:

```console
$ diskutil apfs deleteVolume disk5s1

Started APFS operation
Deleting APFS Volume from its APFS Container
Unmounting disk5s1
Erasing any xART session referenced by 016C2F32-854B-4B94-8B87-DEA1774902CB
Deleting Volume
Removing any Preboot and Recovery Directories
Finished APFS operation
```

{{< admonition type="warning" >}}
If you get an error like:

```text
The volume on disk4 couldn't be unmounted because it is in use by process 48552 (mdwrite)
Error: -69877: Couldn't open device
```

That's Spotlight. Removing the Time Machine destination makes macOS stop treating the drive as a backup target, so Spotlight starts indexing it like any other volume and holds it open.

Turn indexing off for that volume and try again:

```shell
sudo mdutil -i off /Volumes/backup
```
{{< /admonition >}}

## Creating a Volume

Now that the external drive has been erased, we need to create an APFS volume on the drive. Decide if you want your backups to be unencrypted or encrypted and follow the corresponding steps.

Note that this only worked for me with the `-role T` flag in the commands. Do not leave it out! If you skip this option, macOS deletes the volume you just created and builds its own in its place when you register the drive in a later step. It has to do with APFS volume roles. The `T` role is for Time Machine backup stores. You can read more about these roles here: [How do APFS volume roles work?](https://eclecticlight.co/2024/11/21/how-do-apfs-volume-roles-work/).

### Creating a Volume Without Encryption

Run the following command to create a volume without encryption:

```console
$ diskutil apfs addVolume disk5 APFS backup -role T

Will export new APFS Volume "backup" from APFS Container Reference disk5
Started APFS operation on disk5
Preparing to add APFS Volume to APFS Container disk5
Creating APFS Volume
Created new APFS Volume disk5s2
Mounting APFS Volume
Setting volume permissions
Disk from APFS operation: disk5s2
Finished APFS operation on disk5
```

### Creating a Volume With Encryption

Run the following command. It'll prompt you for a password for your drive.

```console
$ diskutil apfs addVolume disk5 APFS backup -passprompt -role T

Passphrase:
Repeat passphrase:
Will export new encrypted APFS Volume "backup" from APFS Container Reference disk5
Started APFS operation on disk5
Preparing to add APFS Volume to APFS Container disk5
Creating APFS Volume
Created new APFS Volume disk5s2
Mounting APFS Volume
Setting volume permissions
Disk from APFS operation: disk5s2
Finished APFS operation on disk5
```

Run this to confirm everything went well.

```console
$ diskutil apfs list disk5 | grep -E "Volume disk|Role|Name:|FileVault"

    +-> Volume disk5s2 E3EA1B15-B920-4313-AB30-01E4AF10A27D
        APFS Volume Disk (Role):   disk5s2 (Backup)
        Name:                      backup (Case-insensitive)
        FileVault:                 Yes (Unlocked)
```

Under `disk5s2` you'll see `FileVault: Yes (Unlocked)` if it's an encrypted volume. You'll see `FileVault: No` if it's an unencrypted volume.

Two things to note here:
- The volume identifier won't always be `disk5s2`, APFS reuses freed slots so yours may be something like `disk5s1` or `disk5s3`.
- Write down the volume UUID, we'll need it later on to verify everything works.

## Enabling Ownership

Time Machine refuses any destination that doesn't enforce file ownership. It can't preserve the UID or GID of what it backs up without file ownership. Volumes created from the command line have it turned off by default. Run the following to enable ownership, replacing the path with your own external drive's path:

```console
$ sudo diskutil enableOwnership /Volumes/backup

File system user/group ownership enabled
```

Run the following to double check that it worked. `Owners` should be `Enabled`:

```console
$ diskutil info /Volumes/backup | grep -i owners

   Owners:                    Enabled
```

## Registering the Drive in Time Machine

"Registering" means telling Time Machine to use this volume as a backup destination. It's what the GUI's "Add Backup Disk" button does. The button and the command we're going to run both write to `/Library/Preferences/com.apple.TimeMachine.plist`.

Run the following to register your drive:

```console
$ sudo tmutil setdestination -a /Volumes/backup
```

No output means it worked. `-a` appends to your destination list rather than replacing it.

If you run into this error, try waiting a bit and then try again:

```text
/Volumes/backup: The operation couldn't be completed. Operation not supported (error 45)
The backup destination could not be added.
```

Then run these commands to double check everything went well:

```console
$ tmutil destinationinfo

====================================================
Name          : backup
Kind          : Local
Mount Point   : /Volumes/backup
ID            : F746C44B-AB49-4D1A-803C-C55087343DC5
```

The output containing `Mount Point` confirms that a destination exists and its volume is reachable.

Confirm it points at your volume and not a replacement:

```console
$ defaults read /Library/Preferences/com.apple.TimeMachine.plist Destinations | grep -A2 DestinationUUIDs

        DestinationUUIDs =         (
            "E3EA1B15-B920-4313-AB30-01E4AF10A27D"
        );
```

That UUID should match the one from `diskutil apfs list` earlier. If it doesn't, macOS replaced your volume with one of its own, which is what happens when the `-role T` flag gets left out.

{{< admonition type="note" >}}
`ID` and `DestinationUUIDs` are different identifiers. One names the destination record and the other one names the volume it points at.
{{< /admonition >}}

## Adding Exclusions

We can add paths we want to exclude from backups through the command line too.

There are three kinds of exclusions: fixed-path exclusions, sticky exclusions, and volume exclusions. But for our purposes we only care about fixed-path and sticky exclusions.
- Fixed-path exclusions are tied to a path regardless of what is there. Use these exclusions for anything that gets deleted and recreated, like build caches.
- Sticky exclusions are the default. They're tied to the item itself. It follows the file if you move it and copies inherit it. Deleting and recreating a directory loses its stickiness.

Here's an example of adding a fixed-path exclusion:

```console
$ sudo tmutil addexclusion -p ~/Library/Developer/Xcode/DerivedData
```

Here's an example of adding a sticky exclusion (same command without the `-p` this time):

```console
$ sudo tmutil addexclusion ~/some-directory
```

Check any path to make sure it was added to the exclusions. You should see `[Excluded]` next to the path (If you see `[UNKNOWN]` that means no file or directory is there, not that the exclusion didn't register):

```console
$ tmutil isexcluded ~/some-directory
[Excluded]  /Users/nelson/some-directory
```

To list fixed-path exclusions we need to read them out of the preferences plist:

```console
$ defaults read /Library/Preferences/com.apple.TimeMachine.plist SkipPaths
(
    "/Users/nelson/Library/Developer/Xcode/DerivedData"
)
```

Sticky exclusions don't appear in the preferences and are stored as an extended attribute on the item:

```console
$ xattr -l ~/some-directory

com.apple.metadata:com_apple_backup_excludeItem: bplist00_com.apple.backupd
```

Removing them is similar to adding. We use `removeexclusion` instead. The `-p` flag is still necessary for removing fixed-path exclusions but not for sticky exclusions.

Here's an example of how to remove a fixed-path exclusion:

```console
$ sudo tmutil removeexclusion -p ~/Library/Developer/Xcode/DerivedData
```

And here's an example of how to remove a sticky exclusion:
```console
$ sudo tmutil removeexclusion ~/some-directory
```

## Verifying That Everything Worked

Try manually starting a backup through the command line:

```console
$ tmutil startbackup --auto --block
```

The command above may look like it's stuck if your backup takes a while. You can run this in a separate terminal tab/window to monitor its progress:

```console
$ tmutil status

Backup session status:
{
    ClientID = "com.apple.backupd";
    Percent = "0.5771912029826294";
    Running = 1;
}
```

If you get an error like:

```text
tmutil: Backup already in progress.
POSIXError(_nsError: Error Domain=NSPOSIXErrorDomain Code=3 "No such process")
```

That just means macOS started a backup automatically.

Once it's done you can verify with:

```console
$ tmutil latestbackup

/Volumes/.timemachine/E3EA1B15-B920-4313-AB30-01E4AF10A27D/2026-08-01-005134.backup/2026-08-01-005134.backup
```

The path in the output confirms that a real backup exists. You can also check the result code:

```console
$ defaults read /Library/Preferences/com.apple.TimeMachine.plist Destinations | grep -E "RESULT|SnapshotDates" -A2

        RESULT = 0;
        ReferenceLocalSnapshotDate = "2026-08-01 08:24:45 +0000";
        SMBConversionState = 0;
        SnapshotDates =         (
            "2026-08-01 07:51:34 +0000"
        );
```

`RESULT = 0` means the backup was successful. Anything else means the last backup failed.

## Storing an Encrypted Drive's Passphrase in Apple Keychain

This only applies to encrypted drives.

From what I can tell, there's no way to store the Time Machine drive's passphrase in Apple Keychain using the command line. If you prefer your drive to unlock automatically when it's plugged into your machine, you'll need to do the following.

Eject the drive (change the path name to your drive's):

```console
$ diskutil eject /Volumes/backup
```

Plug it back in. When the password dialog appears, type the passphrase and check "Remember this password." That'll save the passphrase in your local keychain so that macOS can unlock the drive automatically next time you plug it in. No need to type in the passphrase every time.

Confirm it worked by ejecting and replugging once more. If you aren't prompted to type in your passphrase, that means it worked. You can double check via the command line too:

```console
$ tmutil destinationinfo
```

If `Mount Point` is present, that means the drive unlocked and mounted.

## Cleaning Up Older Time Machine Drives From Apple Keychain

If you go through this process a few times there's a good chance you'll have several Keychain entries for old Time Machine drives. Deleting a volume doesn't remove its Keychain entry, you'll have to do this manually.

Normally, these Keychain entries point at volumes. But since those volumes were deleted, the entries are pointing at volumes that no longer exist. We can run the following to list all relevant Keychain entries:

```console
$ security dump-keychain ~/Library/Keychains/login.keychain-db | awk '
/0x00000007 <blob>=/{l=$0; sub(/.*<blob>="/,"",l); sub(/"$/,"",l); lbl=l}
/"acct"<blob>=/{v=$0; sub(/.*<blob>="/,"",v); sub(/"$/,"",v); a=v}
/"svce"<blob>=/{v=$0; sub(/.*<blob>="/,"",v); sub(/"$/,"",v); if(v==a && v ~ /^[0-9A-Fa-f]{8}-/) print lbl"  "v}'

backup  016C2F32-854B-4B94-8B87-DEA1774902CB
backup  E3EA1B15-B920-4313-AB30-01E4AF10A27D
```

There's two `backup` entries. To find the one that actually points to a volume, run:

```console
$ diskutil apfs list | grep -E "^\s+\+->"

    +-> Volume disk5s2 E3EA1B15-B920-4313-AB30-01E4AF10A27D
```

So UUID `E3EA1B15-B920-4313-AB30-01E4AF10A27D` points to a volume. Which means UUID `016C2F32-854B-4B94-8B87-DEA1774902CB` is safe to delete. We can delete it like so:

```console
$ security delete-generic-password -s "016C2F32-854B-4B94-8B87-DEA1774902CB"

keychain: "/Users/nelson/Library/Keychains/login.keychain-db"
version: 512
class: "genp"
attributes:
    0x00000007 <blob>="backup"
    0x00000008 <blob>=<NULL>
    "acct"<blob>="016C2F32-854B-4B94-8B87-DEA1774902CB"
    "cdat"<timedate>=0x32303236303733313034303530325A00  "20260731040502Z\000"
    "crtr"<uint32>=<NULL>
    "cusi"<sint32>=<NULL>
    "desc"<blob>="Encrypted Volume Password"
    "gena"<blob>=<NULL>
    "icmt"<blob>=<NULL>
    "invi"<sint32>=<NULL>
    "mdat"<timedate>=0x32303236303733313034303530325A00  "20260731040502Z\000"
    "nega"<sint32>=<NULL>
    "prot"<blob>=<NULL>
    "scrp"<sint32>=<NULL>
    "svce"<blob>="016C2F32-854B-4B94-8B87-DEA1774902CB"
    "type"<uint32>=<NULL>
password has been deleted.
```

We can then double check that we only have the necessary Keychain entries left:

```console
$ security dump-keychain ~/Library/Keychains/login.keychain-db | awk '
/0x00000007 <blob>=/{l=$0; sub(/.*<blob>="/,"",l); sub(/"$/,"",l); lbl=l}
/"acct"<blob>=/{v=$0; sub(/.*<blob>="/,"",v); sub(/"$/,"",v); a=v}
/"svce"<blob>=/{v=$0; sub(/.*<blob>="/,"",v); sub(/"$/,"",v); if(v==a && v ~ /^[0-9A-Fa-f]{8}-/) print lbl"  "v}'

backup  E3EA1B15-B920-4313-AB30-01E4AF10A27D
```

However, this is just for the sake of being tidy. I don't think having these kinds of entries in Keychain affects macOS negatively in a significant way.

## References

- https://support.apple.com/guide/mac-help/back-up-your-mac-with-time-machine-mh35860/mac
- https://keith.github.io/xcode-man-pages/diskutil.8.html
- https://keith.github.io/xcode-man-pages/tmutil.8.html
- https://eclecticlight.co/2024/11/21/how-do-apfs-volume-roles-work/
- https://eclecticlight.co/2024/04/02/apfs-containers-and-volumes/
- https://eclecticlight.co/2021/10/12/juggling-with-hfs-and-apfs-partitions-and-volumes-a-primer/
