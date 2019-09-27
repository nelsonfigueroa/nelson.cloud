+++
title = "Wifi Hacking with aircrack-ng"
description = "A guide to using aircrack-ng to bruteforce a router's password"
date = "2019-09-26"
+++

## Quick note before we start...

This post will essentially be a re-write of notes I took around 2 years ago now. Learning to use `aircrack-ng` and break into my own router was fun and a bit shocking as to how easily someone could do this. The difficult part is guessing the password to a router simply due to processing power needed, but sniffing wifi networks is a trivial process. After doing it once, it'll stick.

There are likely better methods of doing this now. In fact, I discovered [bettercap](https://github.com/bettercap/bettercap) earlier this year and it is easy to use. Perhaps I'll write a post using that tool in the future.

For reference, I used Fedora Linux for this entire process. However, the process should be very similar for any linux distribution. This tool is already included on Kali Linux but I wanted to start from scratch and set it up myself.

## What is aircrack-ng?

As described on the [official aircrack-ng website](https://www.aircrack-ng.org/doku.php?id=Main), aircrack-ng is a complete suite of tools to assess WiFi network security. It can be used to scan wifi signals and to perform denial-of-service (DOS) attacks. I have personally found this tool to work better on linux systems, but perhaps support for Windows/Mac has greatly improved now. 

The goal in this post is to use the tools included in `aircrack-ng` to scan for nearby routers, send spoofed deauthentication packets on behalf of a connected client, capture the 4-way handshake hash, and attempt to crack the hash, which reveals the password of the router in plaintext.

## Setup

To install `aircrack-ng` on Fedora, simply run `yum install aircrack-ng` in the command line. 

We'll also need to set up monitor mode in our wireless network adapter. Monitor mode allows the wireless network interface to capture all wireless traffic. This means we'll be able to see nearby wireless access points and devices connected to each one. Keep in mind that not all network adapters support this mode, and you might need a USB wireless adapter specifically used in penetration testing.

To set the network adapter to monitor mode, first find the name of the interface as your system detects it. You can see this using `ifconfig`. In my case, the interface name is `wlp2s0`. Now I can set it to monitor mode using the following commands:

```shell
~$ ifconfig wlp2s0 down
~$ iwconfig wlp2s0 mode monitor # iwconfig, NOT ifconfig
~$ ifconfig wlp2s0 up 
```

Now, we need to check that no processes interfere with the `airmon-ng` tool, which is part of the `aircrack-ng` suite. To do this, simply run `airmon-ng check wlp2s0`, replacing the interface name with your own. You might get an output as such:

```shell
Found 5 processes that could cause trouble.
1215 avahi-daemon
1216 avahi-daemon
1312 NetworkManager
1556 wpa_supplicant
17917 dhclient
```

We'll need to kill all those processes to prevent any issues. Simply issue `kill` commands for each process ID. Run `airmon-ng check` once again to be sure that all is well. We are now ready to scan for access points.

## Scanning for access points