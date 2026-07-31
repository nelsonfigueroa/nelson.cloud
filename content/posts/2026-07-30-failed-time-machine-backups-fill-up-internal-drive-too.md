+++
title = "Failed Time Machine Backups Fill Up Your Internal Drive Too"
summary = "Failed backups prevent older snapshots from being released. Old snapshots retain deleted files and can take up a bunch of space."
date = "2026-07-30"
categories = ["macOS"]
ShowToc = false
TocOpen = false
featured = false
+++

A failed Time Machine backup stops macOS from releasing an old local snapshot, and that snapshot retains everything you delete.

A snapshot doesn't store data. It just prevents blocks from being freed. Every file that is deleted since that snapshot was taken has its blocks pinned by it. In my case, my backups had been failing since March (it's July) so the old snapshot was never released. This old snapshot ballooned up to 226GiB.

I thought anything related to failed Time Machine backups would only take up space on the external SSD that I use for backups, but that is not the case. While macOS did warn me that my backups were failing due to my external drive being full, it never said anything about the same failure taking up 226GiB on my local drive.

The other misleading part is that deleting files wasn't actually freeing up space!

Before I cleared the old snapshot, this is what my storage looked like. There were 974GiB used. At this time, I didn't know that 226GiB were being taken up by an old snapshot.

```console
$ df -h /System/Volumes/Data
Filesystem      Size    Used   Avail Capacity iused ifree %iused  Mounted on
/dev/disk3s5   1.8Ti   974Gi   861Gi    54%    1.6M  9.0G    0%   /System/Volumes/Data
```

I ran this to see local snapshots

```console
$ tmutil listlocalsnapshots /
com.apple.TimeMachine.2026-03-15-121124.local
com.apple.TimeMachine.2026-07-30-071923.local
```

One of the snapshots was from today at the time of writing (2026-07-30), which is normal. The other snapshot was from 2026-03-15 (over 4 months old!), which is not normal.

More details with `diskutil`. Note the `Purgeable: Yes` lines. This means macOS would reclaim that space under disk pressure, but since I had 861GiB available that never happened.

```console
$ diskutil apfs listSnapshots disk3s5
Snapshots for disk3s5 (2 found)
|
+-- 65156CB2-C515-478B-BAB4-333F3B069097
|   Name:        com.apple.TimeMachine.2026-03-15-121124.local
|   XID:         7888254
|   Purgeable:   Yes
|
+-- 196FCBAF-037D-45CE-BB27-1F68E5627340
    Name:        com.apple.TimeMachine.2026-07-30-071923.local
    XID:         9579327
    Purgeable:   Yes
    NOTE:        This snapshot limits the minimum size of APFS Container disk3
```

According to [these docs](https://support.apple.com/guide/mac-help/about-time-machine-local-snapshots-mh35933/mac), Apple says:
> These files are stored for up to 24 hours or until space is needed on the disk.

And in [these other docs](https://support.apple.com/en-us/102154), Apple says:
> Time Machine saves one snapshot of your startup disk approximately every hour, and keeps it for 24 hours. It keeps an additional snapshot of your last successful Time Machine backup until space is needed. 

Normally these snapshots expire in 24 hours. The last successful backup snapshot doesn't expire until space pressure forces it out. So the old snapshot on my machine was expected. But since backups were failing for months the older snapshot was taking up space and saving every file I deleted since then. It was waiting for a successful backup that had not taken place since.

I deleted the local one that was old. This doesn't affect the external backup drive and I'm only giving up the ability to restore from that point in time without my external drive plugged in.

```console
$ sudo tmutil deletelocalsnapshots 2026-03-15-121124
Deleted local snapshot '2026-03-15-121124'
```

Then I checked SSD storage again to see the difference under "Used".

```console
$ df -h /System/Volumes/Data
Filesystem      Size    Used   Avail Capacity iused ifree %iused  Mounted on
/dev/disk3s5   1.8Ti   748Gi   1.1Ti    41%    1.6M   11G    0%   /System/Volumes/Data
```

"Used" went from 974GiB to 748GiB. So 226GiB was cleared up. Crazy.

Try checking your local drive for snapshots that are older than your typical backup schedule (e.g. if you back up weekly, look for snapshots older than a week). Hourly snapshots expire after 24 hours and it's normal to have several of these. The retained one is from your last successful backup. This is the one that stays around until space is needed. It's only as old as your last successful backup.

For example, here's what my snapshots look like now after everything has been fixed:

```console
$ tmutil listlocalsnapshots /
Snapshots for disk /:
com.apple.TimeMachine.2026-07-30-071923.local
com.apple.TimeMachine.2026-07-30-084235.local
com.apple.TimeMachine.2026-07-30-185721.local
com.apple.TimeMachine.2026-07-30-202600.local
```

## References
- https://support.apple.com/en-us/102154
- https://support.apple.com/guide/mac-help/about-time-machine-local-snapshots-mh35933/mac
- https://eclecticlight.co/2026/01/31/explainer-snapshots-2/
