+++
title = "Wifi Hacking with aircrack-ng"
description = "A guide to using aircrack-ng to bruteforce a router's password"
date = "2019-09-26"
+++

## Before we start...

This post will essentially be a re-write of notes I took around 2 years ago now. Learning to use `aircrack-ng` and break into my own router was fun and a bit shocking as to how easily someone could do this. The difficult part is guessing the password to a router simply due to processing power needed, but sniffing wifi networks is a trivial process. After doing it once, it'll stick.

There are likely better methods of doing this now. In fact, I discovered [bettercap](https://github.com/bettercap/bettercap) earlier this year and it is easy to use. Perhaps I'll write a post using that tool in the future.

For reference, I used Fedora Linux for this entire process. However, the process should be very similar for any linux distribution. This tool is already included on Kali Linux but I wanted to start from scratch and set it up myself.

I shouldn't have to say this but, this is purely for educational purposes. Do NOT try to break into access points that do not belong to you.

## What is aircrack-ng?

As described on the [official aircrack-ng website](https://www.aircrack-ng.org/doku.php?id=Main), aircrack-ng is a complete suite of tools to assess WiFi network security. It can be used to scan wifi signals and to perform denial-of-service (DOS) attacks. I have personally found this tool to work better on linux systems, but perhaps support for Windows/Mac has greatly improved now. 

The goal in this post is to use the tools included in `aircrack-ng` to scan for nearby routers, send spoofed deauthentication packets on behalf of a connected client, capture the 4-way handshake hash, and attempt to crack the hash, which reveals the password of the router in plaintext.

## Setup

To install `aircrack-ng` on Fedora, simply run: 

```
yum install aircrack-ng
```

We'll also need to set up monitor mode in our wireless network adapter. Monitor mode allows the wireless network interface to capture all wireless traffic. This means we'll be able to see nearby wireless access points and devices connected to each one. Keep in mind that not all network adapters support this mode, and you might need a USB wireless adapter specifically used in penetration testing.

To set the network adapter to monitor mode, first find the name of the interface as your system detects it. You can see this using `ifconfig`. In my case, the interface name is `wlp2s0`. Now I can set it to monitor mode using the following commands:

```
ifconfig wlp2s0 down
iwconfig wlp2s0 mode monitor
ifconfig wlp2s0 up 
```

Now, we need to check that no processes interfere with the `airmon-ng` tool, which is part of the `aircrack-ng` suite. To do this, simply run: 

```
airmon-ng check wlp2s0
```

You might get an output as such:

```
Found 5 processes that could cause trouble.
1215 avahi-daemon
1216 avahi-daemon
1312 NetworkManager
1556 wpa_supplicant
17917 dhclient
```

We'll need to kill all those processes to prevent any issues. Simply issue `kill` commands for each process ID. Run `airmon-ng check` once again to be sure that all is well. We are now ready to scan for access points.

## Scanning for access points

To run a wireless scan using a particular interface, run the following command:

```
airodump-ng wlp2s0
```

**screenshot will be added in the future**

You'll see a list of access points and devices associated with each access point. You'll notice there are acronyms above each column. Here's a list of them and what they mean:

- BSSID - MAC Address of the access point
- PWR - Strength of the signal. The closer to 0, the better
- CH - Channel
- ESSID - Name of access point 
- STATION - Device connected to access point

Now lets select an access point and run a scan on only that particular device. We'll capture traffic and save it to a file. You'll need to make note of the BSSID and channel of the access point. We can do that using the following command formula:

```
airodump-ng -c [channel number] -w [filename for output] --bssid [MAC Address of A.P.] [your interface]
```

In my case, the full command looks like this:

```
airodump-ng -c 6 -w SCAN_OUTPUT --bssid A1:B2:C3:C1:BB:18 wlp2s0
```
You'll be able to see the access point and associated devices. This provides a much cleaner look instead of your terminal screen being full of information.

Now we can commence an attack.

## Attacking an access point

We will be deauthenticating a device
This is also a method you can use to perform a DOS attacks on wireless networks guaranteed to work
Wireless networks are very vulnerable to this and you will be able to deauthenticate everybody even in a university

You will not be able to perform this method if you do not have any stations (devices) that are connected to a certain access point because you’ll have nothing to deauthenticate. There is nothing conducting the authentication process which you can capture.

We'll be flooding the access point with deauthentication frames to keep devices from reconnecting to it.
