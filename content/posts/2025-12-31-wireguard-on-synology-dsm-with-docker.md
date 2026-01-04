+++
title = "Setting up WireGuard on Synology DSM 7 using Docker and Gluetun"
summary = "A guide to connect a Synology NAS to a WireGuard VPN server."
date = "2025-12-31"
categories = ["Docker", "Synology"]
ShowToc = true
TocOpen = true
+++

At the time of writing Synology DiskStation Manager (DSM) [v7.2.2-72806](https://www.synology.com/en-us/releaseNote/DSM#ver_72806-5) is running on Linux v4.4.4 which doesn't support WireGuard.  
It [doesn't look like Synology is interested in adding WireGuard support](https://community.synology.com/enu/forum/1/post/158013) the way OpenVPN is supported. So if you want certain services on your Synology NAS to connect through WireGuard, you'll need a workaround.

One workaround is to establish a WireGuard connection using [Gluetun](https://github.com/qdm12/gluetun) in Docker. Then have containerized services do their networking through this Gluetun container. The caveat is that whatever services you want to go through a WireGuard tunnel will need to be containerized.

## Prerequisites

This guide is intended for those comfortable with the [command line](https://en.wikipedia.org/wiki/Command-line_interface), SSH, and Docker.

You'll need [Container Manager](https://www.synology.com/en-us/dsm/feature/docker) installed, which is basically just Synology's wrapper around Docker. Install it via the Web UI, then you'll be able to use `docker` commands via SSH. Installing Container Manager is straightforward. Log into the Synology DSM Web UI -> open Package Center -> search for "Container Manager" -> click "Install".

You'll also need a WireGuard configuration file. For this guide I'll be using a configuration file from [Mullvad VPN](https://mullvad.net/).

## Why Gluetun?

A bit of background as to why I'm using Gluetun. There's a [linuxserver/wireguard](https://hub.docker.com/r/linuxserver/wireguard) docker image we can use, but that image expects the underlying kernel to have WireGuard support. Since Synology DSM runs on 4.4.4 at this time that means it doesn't support WireGuard, which means the linuxserver/wireguard image won't work. I tried to get it working myself but kept running into errors.

Unlike linuxserver/wireguard, Gluetun works on any kernel by using something called userspace WireGuard implementation. Basically it runs at the user level rather than at the kernel level. This is beyond my knowledge though, so I encourage you to do some of your own research if you want to learn more.

## Creating Gluetun Directories

First, let's create a directory where the Gluetun container will store a configuration file once it's running.

SSH into your Synology device with an admin user:

```
ssh <admin-user>@<synology-ip-address>
```

Once you're in, get root access to make this process easier:

```
sudo -i
```

If you can run `whoami` and get `root` as the output then you're good to go.

```
$ whoami
root
```

Now we can create the directory that Gluetun will need. In my case, I only have one volume and it's called `volume1`, so your path may be a little different:

```
mkdir -p /volume1/docker/gluetun
```

That should be it! Stay as `root` going forward to keep things simple.

## Creating a docker-compose.yml File

Next we can create a `docker-compose.yml` file where we'll tell Docker to run a Gluetun container. This file can also be easily extended with additional containers that should connect to Gluetun to have WireGuard access. More on that later though.

First, make sure Docker is actually installed as it's a prerequisite I mentioned at the beginning of this post:

```
$ docker --version
Docker version 24.0.2, build 610b8d0
```

Then create a `docker-compose.yml` file. I chose to create it in `/volume1/docker/` because it seemed logical but you can place this just about anywhere you'd like.

```
touch /volume1/docker/docker-compose.yml
```

Now we can fill in `docker-compose.yml`. Here's the starting point you'll need for Gluetun:

```yaml
version: "3.8"

services:
  gluetun:
    image: qmcgaw/gluetun:latest
    container_name: gluetun
    cap_add:
      - NET_ADMIN
    environment:
      - TZ=America/Los_Angeles
      - VPN_SERVICE_PROVIDER=mullvad # change as needed
      - VPN_TYPE=wireguard
      - WIREGUARD_PRIVATE_KEY=<tbd>
      - WIREGUARD_ADDRESSES=<tbd>
      - SERVER_CITIES=Los Angeles CA # change as needed
    volumes:
      - /volume1/docker/gluetun:/gluetun # edit if your directory is something other than `volume1`
    restart: unless-stopped
```

{{< admonition type="note" >}}
Note that if it's easier you can create `docker-compose.yml` locally on your device and then drag it over to a directory of your choosing through the Web UI.
{{< /admonition >}}

We'll need to fill in `WIREGUARD_PRIVATE_KEY` and `WIREGUARD_ADDRESSES` in `docker-compose.yml`. These can be retrieved from a WireGuard configuration file.

It depends on your provider but for Mullvad VPN you go to https://mullvad.net/en/account/wireguard-config and download the **Linux** version of the WireGuard configuration file. The file itself should look something like this regardless of your VPN provider:

```ini
[Interface]
PrivateKey = dGhpcyBoYXMgYmVlbiByZWRhY3RlZA==
Address = 10.67.205.85/32,fc00:bbbb:bbbb:bb01::4:cd54/128
DNS = 10.64.0.1

[Peer]
PublicKey = YWxzbyByZWRhY3RlZCB0aGlz
AllowedIPs = 0.0.0.0/0,::0/0
Endpoint = 138.199.43.91:51820
```

Copy the `PrivateKey` field and paste it as the value for `WIREGUARD_PRIVATE_KEY` in `docker-compose.yml`. Then copy `Address` and paste it as the value for `WIREGUARD_ADDRESSES`.

{{< admonition type="note" >}}
At the time of writing Gluetun only supports IPv4 addresses. So if your `Address` value contains an IPv6 range it will not work and you'll get an error like `cannot add address to wireguard interface: permission denied: when adding address`.

The value should look like this: `10.67.205.85/32`

The value should **NOT** look like this: `10.67.205.85/32,fc00:bbbb:bbbb:bb01::4:cd54/128`
{{< /admonition >}}

Here's what the updated `docker-compose.yml` file will look like in this case:

{{< highlight yaml "hl_lines=13-14" >}}
version: "3.8"

services:
  gluetun:
    image: qmcgaw/gluetun:latest
    container_name: gluetun
    cap_add:
      - NET_ADMIN
    environment:
      - TZ=America/Los_Angeles
      - VPN_SERVICE_PROVIDER=mullvad # change as needed
      - VPN_TYPE=wireguard
      - WIREGUARD_PRIVATE_KEY=dGhpcyBoYXMgYmVlbiByZWRhY3RlZA==
      - WIREGUARD_ADDRESSES=10.67.205.85/32
      - SERVER_CITIES=Los Angeles CA # change as needed
    volumes:
      - /volume1/docker/gluetun:/gluetun # edit if your directory is something other than `volume1`
    restart: unless-stopped
{{< /highlight >}}

Now we can start up the Gluetun container and verify that it works.

## Running the Gluetun Container and Verifying WireGuard Works

In the same directory as `docker-compose.yml`, spin up a Gluetun container with `docker-compose`:

```
docker-compose up -d
```

You'll see some output similar to the following:
```
[+] Running 4/4
 ✔ gluetun 3 layers [⣿⣿⣿]      0B/0B      Pulled                                                                                                            9.8s
   ✔ 2d35ebdb57d9 Pull complete
   ✔ af8ed9d65cfd Pull complete
   ✔ 55e433daf0d2 Pull complete
[+] Running 2/2
 ✔ Network docker_default  Created
 ✔ Container gluetun       Started
 ```
 
 For Mullvad VPN specifically there's a way to verify that a connection is going through their servers. We can run a command against the Gluetun container to confirm.
 
 ```
 $ docker exec gluetun wget -qO- https://am.i.mullvad.net/connected
 You are connected to Mullvad (server us-lax-wg-602). Your IP address is 23.162.40.236
 ```

Regardless of VPN provider, you can check that the `wget` command returns a different IP address from the IP address your internet provider has assigned to you.

Get your normal IP address first by running `wget` outside of Docker.

```
$ wget -qO- https://icanhazip.com/
205.154.229.24
```

Then run the same command against Gluetun to verify that you get a different IP address:

```
$ docker exec gluetun wget -qO- https://icanhazip.com/
23.162.40.236
```

If the IP addresses are different, you should be good to go. Now we can start creating containers that use the WireGuard connection through Gluetun.

## Connecting Another Container to Gluetun (qBittorrent)

I'll be using a qBittorrent container as an example as that is a common use case with WireGuard. People love their Linux ISOs. Adding containers is easy as we just need to append to the existing `docker-compose.yml` file.

First, create some directories that qBittorrent will need for configuration and downloads:

```
mkdir -p /volume1/docker/qbittorrent/config
mkdir -p /volume1/docker/qbittorrent/downloads
```

Then update `docker-compose.yml` like so:

{{< highlight yaml "hl_lines=16-19 23-35" >}}
version: "3.8"

services:
  gluetun:
    image: qmcgaw/gluetun:latest
    container_name: gluetun
    cap_add:
      - NET_ADMIN
    environment:
      - TZ=America/Los_Angeles
      - VPN_SERVICE_PROVIDER=mullvad # change as needed
      - VPN_TYPE=wireguard
      - WIREGUARD_PRIVATE_KEY=dGhpcyBoYXMgYmVlbiByZWRhY3RlZA==
      - WIREGUARD_ADDRESSES=10.67.205.85/32
      - SERVER_CITIES=Los Angeles CA # change as needed
    ports:
      - 8080:8080/tcp    # qBittorrent WebUI
      - 6881:6881/tcp    # qBittorrent torrenting
      - 6881:6881/udp    # qBittorrent torrenting
    volumes:
      - /volume1/docker/gluetun:/gluetun # edit if your directory is something other than `volume1`
    restart: unless-stopped
  qbittorrent:
    image: linuxserver/qbittorrent:latest
    container_name: qb
    network_mode: "service:gluetun"  # route all traffic through Gluetun (WireGuard)
    environment:
      - TZ=America/Los_Angeles # change as needed
      - WEBUI_PORT=8080
    volumes:
      - /volume1/docker/qbittorrent:/config
      - /volume1/docker/qbittorrent/downloads:/downloads
    depends_on:
      - gluetun
    restart: always
{{< /highlight >}}

Then run:

```
docker-compose up -d
```

You'll see output similar to:

```
[+] Running 11/11
 ✔ qbittorrent 10 layers [⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿]      0B/0B      Pulled
   ✔ 7b8aceaf0e4b Pull complete
   ✔ f6a4c3e338ed Pull complete
   ✔ 52f3b2f8d37e Pull complete
   ✔ b4ab7931093f Pull complete
   ✔ ca62986f3af9 Pull complete
   ✔ 34ebc4f55bf4 Pull complete
   ✔ 3bd5231a1430 Pull complete
   ✔ c3f2c6c748f6 Pull complete
   ✔ 11edf950eb5f Pull complete
   ✔ 9e67070a5e7e Pull complete
[+] Running 2/2
 ✔ Container gluetun  Started
 ✔ Container qb       Started
 ```
 
 qBittorrent has a web interface that can be accessed on port `8080`. Open up a web browser and go to `http://<your-synology-ip-address>:8080` and see if the web UI shows up. If it does, qBittorrent is running successfully and all of its network traffic will run through Gluetun and WireGuard!

We can do one final check with the qBittorrent container to make sure it has the same IP address as the Gluetun container:

```
$ docker exec gluetun wget -qO- https://icanhazip.com/
23.162.40.236

$ docker exec qb wget -qO- https://icanhazip.com/
23.162.40.236
```

Both IP addresses are the same, which means qBittorrent is running through Gluetun and through a WireGuard connection. Everything works!

## References
- https://github.com/qdm12/gluetun
- https://github.com/qdm12/gluetun-wiki/blob/main/setup/providers/mullvad.md
- https://docs.linuxserver.io/images/docker-qbittorrent/
- a lot of trial and error
