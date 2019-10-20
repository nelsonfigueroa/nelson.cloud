+++
title = "Ubuntu Server Maintenance"
description = "Linux maintenance with Ubuntu Server"
date = "2019-10-19"
+++

This is a simple guide to managing and maintaining Ubuntu Server. There is no desktpo GUI on this version of Ubuntu. The goal here is to learn basic Ubuntu maintenance with just the command line.

## Prerequisites

Of course, you'll need to run Ubuntu Server if you want to follow along. For this post, I will be using Ubuntu 18.04. The .iso is available on the [official Canonical website](https://ubuntu.com/download/server) if you'd like to create a virtual machine. You can also use Amazon Web Services to boot up an Ubuntu EC2 instance.

## Unattended Upgrades

As with any system, security is a series of trade-offs. This trade off is convenience vs. security. Always keep security in mind when configuring any system. 

By default, Ubuntu Server comes with `unattended-upgrades` enabled, which automatically updates and install packages considered important or security updates. You can also run the command manually at any time. 

Sometimes it makes sense to turn off automatic updates in a production environment. If you do decide to turn off automatic security updates, be sure to schedule downtime to manually install them. You can enable/disable automatic security updates with:

```sh
dpkg-reconfigure unnatended-upgrades
```

For troubleshooting `unattended-upgrades`, you can refer to the log file located in `/var/log/unattended-upgrades/unaddended-updates.log`.

## Setting up SSH

Ubuntu Server comes with SSH already installed. However, the default configuration uses a username and password combination to connect, which is not the best for security. The ideal way to set up SSH is to use SSH keys. With key pairs, you can copy your public key to a remote server in order to SSH without using a password. This can be done for several users in the event that multiple people need SSH access to a server.

First, set up SSH keys on your local machine.

SSH comes with a package called `ssh-keygen` that is used to generate keys, although there are other ways to do it as well. Let's generate SSH keys by running the command:

```sh
ssh-keygen
```

The command will prompt you for an optional password. If you don't want an additional password, press enter to skip the option.

This command will save two keys, `id_rsa` and `id_rsa.pub`, in the `~/.ssh/` directory. `id_rsa` is your private key, DO NOT reveal this to anyone. `id_rsa.pub` is your public key. This is the key that will need to be present in the remote server you want to SSH to.

Next, copy the contents of the public key into the remote server. In this case, the remote server is Ubuntu Server. SSH into Ubuntu Server using the username and password you chose during installation:

```sh
# replace with your details where needed

ssh user@192.168.1.2
```

Then, paste the contents of `id_rsa.pub` into a file called `authorized_keys` in the `~/.ssh/` directory. If the file does not exist, create it. You can add multiple keys in this file if you wish to allow multiple machines to connect to this server under the same user.

Now that the public key is in the Ubuntu server, we need to configure SSH to only use key authentication and not username/password combinations. The configuration we need to modify is at `/etc/ssh/sshd_config`. Use your favorite terminal text editor to modify the file.

Look for the line that says `PermitRootLogin`. Uncomment it, and set it to `no`. We do not want the root account to be able to log in directly. Any superuser activity in SSH should be done through a regular user using `sudo`.

```sh
PermitRootLogin no
```

Look for `PubkeyAuthentication`. Uncomment it and set it to `yes`. This allows the usage of SSH keys to login.

```sh
PubkeyAuthentication yes
```

Search for `PasswordAuthentication`. Uncomment the line, and set it to `no`. This disables password logins.

```sh
PasswordAuthentication no
```

With these settings, any user with a public SSH key on the server will be able to login. Restart the SSH service to apply changes:

```sh
sudo systemctl restart sshd
```

Exit and reconnect to the remote server. The server should not prompt for a password, and if all steps were followed, you should be able to login to the server. The `ssh` command automatically uses the private key located in `~/.ssh/id_rsa`. If your key is stored elsewhere, you can use the `-i` flag to specify its location:

```sh
ssh user@10.0.2.20 -i /path/to/key/id_rsa
```

You can repeat this process for multiple users. Create a user, generate SSH keys on their local machine, and then copy their public keys to their respective home directories in the remote server.

## Firewall Configuration

(In Progress)