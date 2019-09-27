+++
title = "Wifi Hacking with aircrack-ng"
description = "A guide to using aircrack-ng to bruteforce a router's password"
date = "2019-09-26"
+++

## Before we start...

This post will essentially be a re-write of notes I took around 2 years ago now. Learning to use `aircrack-ng` and break into my own router was fun and a bit shocking as to how easily someone could do this. The difficult part is guessing the password to a router simply due to processing power needed, but sniffing wifi networks is a trivial process. After doing it once, it'll stick.

There are likely better methods of doing this now. In fact, I discovered [bettercap](https://github.com/bettercap/bettercap) earlier this year and it is easy to use. Perhaps I'll write a post using that tool in the future.

For reference, I used Kali linux for the entire process. However, the process should be very similar for any linux distribution. This tool is already included on Kali Linux but you can easily install it on any linux OS.

I shouldn't have to say this but, this is purely for educational purposes. Do NOT try to break into access points that do not belong to you.

## What is aircrack-ng?

As described on the [official aircrack-ng website](https://www.aircrack-ng.org/doku.php?id=Main), aircrack-ng is a complete suite of tools to assess WiFi network security. It can be used to scan wifi signals and to perform denial-of-service (DOS) attacks. I have personally found this tool to work better on linux systems, but perhaps support for Windows/Mac has greatly improved now. 

The goal in this post is to use the tools included in `aircrack-ng` to scan for nearby routers, send spoofed deauthentication packets on behalf of a connected client, capture the 4-way handshake hash, and attempt to crack the hash, which reveals the password of the router in plaintext.

## Setup

We'll need to set up monitor mode in our wireless network adapter. Monitor mode allows the wireless network interface to capture all wireless traffic. This means we'll be able to see nearby wireless access points and devices connected to each one. Keep in mind that not all network adapters support this mode, and you might need a USB wireless adapter specifically used in penetration testing. I purchased a Panda Wireless wireless USB adapter for around $20.

To set the network adapter to monitor mode, first find the name of the interface as your system detects it. You can see this using `ifconfig`. In my case, the interface name is `wlan0`. I can set it to monitor mode using the following commands:

```
ifconfig wlan0 down
iwconfig wlan0 mode monitor
ifconfig wlan0 up 
```

Now, we need to check that no processes interfere with the `airmon-ng` tool, which is part of the `aircrack-ng` suite. To do this, simply run: 

```
airmon-ng check wlan0
```

You might get an output as such:

```
Found 3 processes that could cause trouble.
If airodump-ng, aireplay-ng or airtun-ng stops working after
a short period of time, you may want to run 'airmon-ng check kill'

 PID Name
 1229 NetworkManager
 1329 wpa_supplicant
 3383 dhclient
```

We'll need to kill all those processes to prevent any issues. Simply issue `kill` commands for each process ID. Run `airmon-ng check` once again to be sure that all is well. We are now ready to scan for access points.

## Scanning for access points

To run a wireless scan using a particular interface, run the following command:

```
airodump-ng wlan0
```

Your terminal screen will fill up with something like this:

```
 CH 10 ][ Elapsed: 0 s ][ 2019-09-27 21:57                                         
                                                                                                                                                                                        
 BSSID              PWR  Beacons    #Data, #/s  CH  MB   ENC  CIPHER AUTH ESSID
                                                                                                                                                                                        
 50:13:95:2E:47:72  -55        0        0    0   3  -1                    <length:  0>                                                                                                  
 6C:B0:CE:B6:07:53  -42        3        0    0   7  130  WPA2 CCMP   PSK  Lower The Rent                                                                                                
 AC:3B:77:1A:84:50  -75        2        0    0   1  195  WPA2 CCMP   PSK  MySpectrumWiFi4a-2G                                                                                           
 14:B7:F8:73:97:FE  -77        2        0    0   1  195  WPA2 CCMP   PSK  LAKERTOWN                                                                                                     
 18:1B:EB:DC:31:A7  -75        2        0    0   1  130  WPA2 CCMP   PSK  Figgy                                                                                                         
 84:A0:6E:C4:0A:36  -71        3        0    0   1  195  WPA2 CCMP   PSK  MySpectrumWiFi30-2G                                                                                           
 4C:01:43:EA:E0:66  -62        3        0    0   1  360  WPA2 CCMP   PSK  Oakland                                                                                                       
 4C:01:43:EA:E0:63  -62        3        0    0   1  360  WPA2 CCMP        <length:  0>                                                                                                  
 4C:01:43:EA:E0:69  -62        3        0    0   1  360  OPN              <length:  0>                                                                                                  
 70:F1:96:62:68:21  -63        3        0    0   1  130  WPA2 CCMP   PSK  PHG5C                                                                                                         
 FC:3F:DB:64:A4:6C  -79        2        0    0   1   65  WPA2 CCMP   PSK  DIRECT-6B-HP Officejet 5740                                                                                   
 26:F5:A2:92:08:33  -66        4        0    0   9  260  OPN              Nice jiraffe-guest                                                                                            
                                                                                                                                                                                        
 BSSID              STATION            PWR   Rate    Lost    Frames  Probe                                                                                                               
                                                                                                                                                                                         
 (not associated)   68:6D:BC:88:4C:9D  -47    0 - 1     95        5                                                                                                                      
 (not associated)   92:0E:63:10:82:3A  -63    0 - 1      0        2                                                                                                                      
 6C:B0:CE:B6:07:53  38:53:9C:93:ED:4A  -29    0 - 1      0        1     
```

You'll see a list of access points and devices associated with each access point. You'll notice there are acronyms above each column. Here's a list of the ones we care about and what they mean:

- BSSID - MAC Address of the access point
- PWR - Strength of the signal. The closer to 0, the better
- CH - Channel
- ESSID - Name of access point 
- STATION - Device connected to access point (Laptop, smartphone, etc)

Now lets select an access point and run a scan on only that particular device. We'll capture traffic and save it to a file. You'll need to make note of the BSSID and channel of the access point. In my case, my router's ESSID is "Lower The Rent". We'll scan it using the following command formula:

```
airodump-ng -c [channel number] -w [filename for output] --bssid [MAC Address of A.P.] [your interface]
```

In my case, the full command looks like this:

```
airodump-ng -c 7 -w SCAN_OUTPUT --bssid 6C:B0:CE:B6:07:53 wlan0
```

Here's the output:

```
 CH  7 ][ Elapsed: 24 s ][ 2019-09-27 22:05                                         
                                                                                                                                                                                        
 BSSID              PWR RXQ  Beacons    #Data, #/s  CH  MB   ENC  CIPHER AUTH ESSID
                                                                                                                                                                                        
 6C:B0:CE:B6:07:53  -47 100      231       92    0   7  130  WPA2 CCMP   PSK  Lower The Rent                                                                                            
                                                                                                                                                                                        
 BSSID              STATION            PWR   Rate    Lost    Frames  Probe                                                                                                              
                                                                                                                                                                                        
 6C:B0:CE:B6:07:53  38:53:9C:93:ED:4A  -31    0e- 1      0      130     
```

You'll be able to see the access point and associated devices. This provides a much cleaner look instead of your terminal screen being full of information. We can see that there is a single device associated with the access point.

Now we can commence an attack.

## Attacking an access point

To put network interfaces back to normal:

```
sudo service network-manager start
```

We will be deauthenticating a device
This is also a method you can use to perform a DOS attacks on wireless networks guaranteed to work
Wireless networks are very vulnerable to this and you will be able to deauthenticate everybody even in a university

You will not be able to perform this method if you do not have any stations (devices) that are connected to a certain access point because you’ll have nothing to deauthenticate. There is nothing conducting the authentication process which you can capture.

We'll be flooding the access point with deauthentication frames to keep devices from reconnecting to it.
