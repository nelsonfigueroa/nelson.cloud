+++
title = "Inspecting the Tor Network and Detecting Tor Traffic in Your Infrastructure"
summary = "Tor relay and exit IPs are public. You can find them and then use them to watch for Tor traffic in your own infrastructure."
date = "2026-10-27"
categories = ["Cybersecurity", "Privacy"]
ShowToc = false
TocOpen = false
featured = false
+++

I read this post recently: [How I got over 300 Tor exit nodes removed](https://kaba.cx/posts/20261020_Tor/) and I thought it was interesting that there is a lot of public information you can gather from the Tor network. A lot of it is public due to the nature of the way Tor works. I wanted to see what other information I could gather and how I can use that information to watch for Tor traffic in my own infrastructure. Turns out there's a lot you can figure out and a lot of cloud providers already take this knowledge into account.

## Inspecting the Tor Network

A lot of the Tor network is public. Tor has bridges and relays. Clients connecting to the Tor network need the list of relays to build circuits. Directory authorities, listed under ["Signatures" in the Consensus Health](https://consensus-health.torproject.org/) page, publish a consensus [every hour](https://collector.torproject.org/recent/relay-descriptors/consensuses/). Tor users stay anonymous by proxying their requests through 3 of these relays. No single relay knows both who a user is and where they're going.

### Relays

You can inspect the Tor network by hitting the [Onionoo](https://metrics.torproject.org/onionoo.html) endpoint. It's the API endpoint managed by the Tor Project.

```console
$ curl -s 'https://onionoo.torproject.org/summary?running=true'

{"version":"8.0",
"build_revision":"89598ea",
"relays_published":"2026-10-27 04:00:00",
"relays":[
{"n":"SENDNOOSEplz","f":"000F3EB75342BE371F1D8D3FAE90890AEB5664EE","a":["204.137.14.106"],"r":true},
{"n":"titamon3","f":"0011254CC8444369B20EF11156B8990438221A54","a":["178.218.144.18"],"r":true},
{"n":"ForPrivacyNET","f":"0036FA36AB435FD5D0F640626636867EBFB72C68","a":["185.220.101.33","[2a0b:f4c2:2::33]"],"r":true},
{"n":"relayon0170","f":"003A08F1ADC002D24979C68AA5A33369C04D9C7F","a":["185.220.101.170","[2a0b:f4c2:1::170]"],"r":true},
{"n":"Quintex216","f":"004B5DD461C58121FF5BBDE8C9B295242013A3FC","a":["204.8.96.106","[2620:7:6003::106]"],"r":true},
{"n":"NTH43R5","f":"004F9A0513C84E72054CEB555DB51EEEF319546D","a":["192.42.116.43","[2001:67c:e60:c0c:192:42:116:43]"],"r":true},
.
. # output trimmed for brevity
.
],
"bridges_published":"2026-10-27 05:53:32",
"bridges":[
{"n":"ams1","h":"0009FD594D4F125E3300826ADADE11964C925DD5","r":true},
{"n":"Afterburn","h":"004B3E68289819C117DA777F82E709015D0D81EF","r":true},
{"n":"usabcdefg","h":"005F44D111B690420CA70B0815C7C4870A2128CD","r":true},
{"n":"Debe","h":"00AFD5CA2F89305B89171450CF34F247858F14E8","r":true},
{"n":"TriplebitSnowflake2","h":"00B2069390A9D67F9F23581E2B99F59FCA08D96B","r":true},
{"n":"Quetzalcoatl32Kitt","h":"00D68427427BB2834E769FF744C166444D1E4934","r":true},
{"n":"ForPrivacyNETbr40","h":"00E1AE6CB75E47E363E6AEF9F67A49C0E854FDE7","r":true},
{"n":"saberrider2008","h":"00EA2F57C92A26A1E375AA2C26383FC6B6FEA143","r":true},
.
. # output trimmed for brevity
.
]}
```

You get a list of relays and bridges. Here's how the JSON breaks down:
For relays:
- `n` is the nickname of the relay
- `f` is the cryptographic fingerprint
- `a` is a list of IP addresses
- `r` is a boolean that says if a relay is running or not. It comes from the consensus.

For bridges:
- `n` is the nickname of the bridge
- `h` is the SHA-1 hash of the bridge's fingerprint
- `r` is a boolean that says if a bridge is running or not. Bridges are *not* in the consensus.

There are two things you can do with this information in your network monitoring tools:
1. If one of your machines connects to a relay IP, something on that machine is probably running Tor.
2. If one of your IP addresses shows up in the list, someone is running a relay on your infrastructure.

Note that hosts that connect through Tor bridges won't match any IP address on the relay list!

### Exit Relays

You can also get IP addresses that exit relays use to reach the internet:

```console
$ curl -s https://check.torproject.org/torbulkexitlist

91.132.144.59
5.180.40.109
185.132.53.45
185.132.53.58
92.119.164.208
185.132.53.46
142.249.174.12
51.91.18.151
69.48.228.178
123.253.35.32
.
. # output trimmed for brevity
.
212.38.189.186
```

If these IP addresses are hitting your network with suspicious requests, it's probably best to block them. This is an ever-changing list of IP addresses (relays come and go, and the consensus is generated every hour), so building a blocklist from this information requires that blocklist to be updated on a schedule. Also, note that blocking all Tor users means you'd be blocking users from censored countries, journalists, and the like.

Cloud providers conveniently already know all this and provide easy ways to detect or block Tor traffic. A few examples:
- AWS GuardDuty has [TorClient](https://docs.aws.amazon.com/guardduty/latest/ug/guardduty_finding-types-ec2.html#unauthorizedaccess-ec2-torclient) which checks if an EC2 instance is making connections to Tor, and [TorRelay](https://docs.aws.amazon.com/guardduty/latest/ug/guardduty_finding-types-ec2.html#unauthorizedaccess-ec2-torrelay) which checks if an EC2 instance is making connections to Tor as a Tor relay.
- AWS WAF has a managed rule group [AWSManagedRulesAnonymousIpList](https://docs.aws.amazon.com/waf/latest/developerguide/aws-managed-rule-groups-ip-rep.html#aws-managed-rule-groups-ip-rep-anonymous) used to block requests from services that anonymize viewers, such as Tor.
- Google Cloud Platform lets you block traffic from Tor exit nodes with their Cloud Armor service. You can reference the `iplist-tor-exit-nodes` [threat intelligence feed](https://docs.cloud.google.com/armor/docs/threat-intel) in a Cloud Armor security policy. This is an enterprise feature, however.
- Microsoft Azure [detects and alerts](https://learn.microsoft.com/en-us/azure/defender-for-cloud/alerts-azure-storage#authenticated-access-from-a-tor-exit-node) you if files in your storage account were accessed from an IP address that belongs to an active Tor exit node.

### Historical Relay IP Addresses

So far we've covered Tor-related IP addresses that are current. If you need to check for historical IP addresses belonging to Tor relays, the Tor Project has the [ExoneraTor](https://metrics.torproject.org/exonerator.html) service.

Using ExoneraTor, you can see if an IP address belonged to a Tor relay on a given date. Use this to see if IP addresses in older network logs were coming from Tor. As far as I can tell, ExoneraTor doesn't have a JSON API so you'll need to use the web GUI or parse the HTML yourself after querying the URL. Here's what a positive result looks like:

```console
$ curl -s 'https://metrics.torproject.org/exonerator.html?ip=92.119.164.208&timestamp=2026-10-26&lang=en' | grep -o 'Result is [a-z]*'
Result is positive
```

At the time of writing, it looks like if an IP address doesn't belong to a relay on a specified date, ExoneraTor just displays "Server problem" ([example with 1.1.1.1 IP address](https://metrics.torproject.org/exonerator.html?ip=1.1.1.1&timestamp=2026-10-26&lang=en)). So don't rely on that for detecting negatives.

## Conclusion

It's worth understanding how the Tor network works at a high level to be able to find Tor-related IP addresses. Tor relays have to be public for the network to work. This openness allows you to detect Tor traffic in your infrastructure, and it's what let the author of the original post I linked test every exit relay and find 300+ malicious ones.
