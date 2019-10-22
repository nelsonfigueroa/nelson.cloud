+++
title = "Ubuntu Server Maintenance"
description = "Linux maintenance with Ubuntu Server"
date = "2019-10-19"
+++

This is a simple guide to managing and maintaining Ubuntu Server. There is no desktpo GUI on this version of Ubuntu. The goal here is to learn basic Ubuntu maintenance with just the command line.

## Prerequisites

Of course, you'll need to run Ubuntu Server if you want to follow along. For this post, I will be using Ubuntu 18.04. The .iso is available on the [official Ubuntu website](https://ubuntu.com/download/server) if you'd like to create a virtual machine. You can also use Amazon Web Services to boot up an Ubuntu EC2 instance.

## Unattended Upgrades

As with any system, security is a series of trade-offs. This trade off is convenience vs. security. Always keep security in mind when configuring any system. 

By default, Ubuntu Server comes with `unattended-upgrades` enabled, which automatically updates and install packages considered important or security updates. You can also run the command manually at any time. 

Sometimes it makes sense to turn off automatic updates in a production environment. If you do decide to turn off automatic security updates, be sure to schedule downtime to manually install them. You can enable/disable automatic security updates with:

```
dpkg-reconfigure unnatended-upgrades
```

For troubleshooting `unattended-upgrades`, you can refer to the log file located in `/var/log/unattended-upgrades/unaddended-updates.log`.

## Setting up SSH

Ubuntu Server comes with SSH already installed. However, the default configuration uses a username and password combination to connect, which is not the best for security. The ideal way to set up SSH is to use SSH keys. With key pairs, you can copy your public key to a remote server in order to SSH without using a password. This can be done for several users in the event that multiple people need SSH access to a server.

First, set up SSH keys on your local machine.

SSH comes with a package called `ssh-keygen` that is used to generate keys, although there are other ways to do it as well. Let's generate SSH keys by running the command:

```
ssh-keygen
```

The command will prompt you for an optional password. If you don't want an additional password, press enter to skip the option.

This command will save two keys, `id_rsa` and `id_rsa.pub`, in the `~/.ssh/` directory. `id_rsa` is your private key, DO NOT reveal this to anyone. `id_rsa.pub` is your public key. This is the key that will need to be present in the remote server you want to SSH to.

Next, copy the contents of the public key into the remote server. In this case, the remote server is Ubuntu Server. SSH into Ubuntu Server using the username and password you chose during installation:

```
# replace with your details where needed

ssh user@192.168.1.2
```

Then, paste the contents of `id_rsa.pub` into a file called `authorized_keys` in the `~/.ssh/` directory. If the file does not exist, create it. You can add multiple keys in this file if you wish to allow multiple machines to connect to this server under the same user.

Now that the public key is in the Ubuntu server, we need to configure SSH to only use key authentication and not username/password combinations. The configuration we need to modify is at `/etc/ssh/sshd_config`. Use your favorite terminal text editor to modify the file.

Look for the line that says `PermitRootLogin`. Uncomment it, and set it to `no`. We do not want the root account to be able to log in directly. Any superuser activity in SSH should be done through a regular user using `sudo`.

```
PermitRootLogin no
```

Look for `PubkeyAuthentication`. Uncomment it and set it to `yes`. This allows the usage of SSH keys to login.

```
PubkeyAuthentication yes
```

Search for `PasswordAuthentication`. Uncomment the line, and set it to `no`. This disables password logins.

```
PasswordAuthentication no
```

With these settings, any user with a public SSH key on the server will be able to login. Restart the SSH service to apply changes:

```
sudo systemctl restart sshd
```

Exit and reconnect to the remote server. The server should not prompt for a password, and if all steps were followed, you should be able to login to the server. The `ssh` command automatically uses the private key located in `~/.ssh/id_rsa`. If your key is stored elsewhere, you can use the `-i` flag to specify its location:

```
ssh user@10.0.2.20 -i /path/to/key/id_rsa
```

You can repeat this process for multiple users. Create a user, generate SSH keys on their local machine, and then copy their public keys to their respective home directories in the remote server.

## Firewall Configuration

For firewall configuration, Ubuntu Server comes with `ufw`, or Uncomplicated FireWall. This is just a simple way to manage `iptables`. By default, `ufw` blocks any incoming traffic that isn't associated with outgoing requests.

The `ufw` service is disabled by default. Let's allow port 22 to be able to SSH, and then enable the firewall.

To allow TCP traffic through port 22 run:

```
sudo ufw allow 22/tcp
```

You should see the following response:

```
Rules updated
Rules updated (v6)
```

Then we'll enable the firewall so that it starts on system boot:

```
sudo ufw enable
```

You can check the status of `ufw` at any moment. Let's try it now:

```
sudo ufw status
```

You should see the following:

```
Status: active

To                         Action      From
--                         ------      ----
22/tcp                     ALLOW       Anywhere
22/tcp (v6)                ALLOW       Anywhere (v6)

```

We can see that our rule was successfully added to the list.

Let's add another rule for HTTPS:

```
sudo ufw allow 443/tcp
```

Then we'll double-check that the rule has been added:

```
sudo ufw status
```

And we see the following:

```
Status: active

To                         Action      From
--                         ------      ----
22/tcp                     ALLOW       Anywhere
443/tcp                    ALLOW       Anywhere
22/tcp (v6)                ALLOW       Anywhere (v6)
443/tcp (v6)               ALLOW       Anywhere (v6)

```

In many cases, services you run on the server will only need one port to be accessible to and from the outside. Some services, however, need more than one port. UFW is aware of some of these services that installed packages use and can make it easier to set up relevant ports.

To see what applications UFW knows about, run:

```
sudo ufw app list
```

In this case, only OpenSSH is installed, so we see the following output:

```
Available applications:
  OpenSSH
```

We can use the name of the application to allow traffic through the firewall as opposed to specifying ports:

```
sudo ufw allow OpenSSH
```

And when running `sudo ufw status` we'll see a rule specifically for an application:

```
Status: active

To                         Action      From
--                         ------      ----
22/tcp                     ALLOW       Anywhere
443/tcp                    ALLOW       Anywhere
OpenSSH                    ALLOW       Anywhere
22/tcp (v6)                ALLOW       Anywhere (v6)
443/tcp (v6)               ALLOW       Anywhere (v6)
OpenSSH (v6)               ALLOW       Anywhere (v6)
```

You can also add ports by common service names. You can find a list of common services and their ports in `etc/services`. Simply find a service you want, and run:

```
sudo ufw allow [service_name]
```

If you need to block any ports, use `deny`. For example, if we wanted to deny SSH we could run the following command:

```
sudo ufw deny 22/tcp
```

These are just the basics of `ufw`. In addition to just allowing or denying access to a specific port, you can also make more complex rules that allow access from certain hosts or networks or only to a particular interface and etc.

To further test our firewall, we can use the `ss` tool to see what ports are open locally. These aren’t firewall ports, but services running behind the firewall listening on local ports. For example, if a web server is running and listening for traffic on port 80, but port 80 is blocked by the firewall, we could still access that web server locally from the same computer it's running on. This can be used to make sure services are running and listening as we expect them to.

Try running `ss -anpt`. The flags will be explained below:

```
sudo ss -anpt
```

You'll see an output similar to the following:

```
State    Recv-Q    Send-Q        Local Address:Port       Peer Address:Port
LISTEN   0         128                 0.0.0.0:22              0.0.0.0:*         users:(("sshd",pid=677,fd=3))
LISTEN   0         128                 0.0.0.0:111             0.0.0.0:*         users:(("rpcbind",pid=458,fd=8))
LISTEN   0         128           127.0.0.53%lo:53              0.0.0.0:*         users:(("systemd-resolve",pid=486,fd=13))
ESTAB    0         0                 10.0.2.15:22             10.0.2.2:50560     users:(("sshd",pid=1351,fd=3),("sshd",pid=1273,fd=3))
LISTEN   0         128                    [::]:22                 [::]:*         users:(("sshd",pid=677,fd=4))
LISTEN   0         128                    [::]:111                [::]:*         users:(("rpcbind",pid=458,fd=11))
```

Here's what the flags do:

- `-a`: Shows all sockets
- `-n`: Shows actual port numbers and not default ports (incase custom ports are being used)
- `-p`: Figures out which process/user is in charge of a given socket
- `-t`: Shows TCP sockets in addition to others

There are more flags available, such as `-u` which shows UDP sockets. Check out the man pages for more.

From another machine, we can use `nmap` to scan for open ports from the outside. This can be aggressive, so be careful and responsible when using it. To check open ports from a different machine run:

```
nmap -sS [ip_address_of_ubuntu_server]
```

Earlier, we allowed port 443 using `ufw` even though we have no service on the server actually using this port. As a result, `nmap` should report that port 443 is open but the state is closed. If you followed the previous steps you should see the following:

```
Nmap scan report for 192.168.0.42
Host is up (0.00032s latency).
Not shown: 998 filtered ports
PORT    STATE  SERVICE
22/tcp  open   ssh
443/tcp closed https
```
As expected, port 443 is open but in a closed state. Port 22 is completely open since we allowed it using `ufw`.

## Logs

Logs are always useful for maintenance and general troubleshooting. In Ubuntu Server, most logs are stored in `/var/log/`. In this directory you'll see several log files for various services. Some notable ones are listed below.

The system log, `syslog`, is where the kernel and almost everything else writes information about events, errors, and notifications. You can log events manually using the `logger` command as such:

```
sudo logger "hello!"
```

Authentication events on the system are shown in `auth.log`. These events include SSH logins and attempts. If your server is in the cloud, you may already have failed login attempts from malicious bots. This log file also shows when users use `sudo` along with whatever command they ran with it.

Some other useful logs include `dpkg.log`, which stores package manager activity, and `ufw.log`, which stores firewall-related logs.

The service in charge of logs is `rsyslog`, which will chop log files, start new ones, and archive old ones. This service compresses logs as .gz (gzip) numbered in reverse order. The higher the number, the older the log.
