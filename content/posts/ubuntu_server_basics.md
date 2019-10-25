+++
title = "Ubuntu Server Basics"
description = "Linux maintenance with Ubuntu Server"
date = "2019-10-19"
+++

 
<!-- ![Example image](/ubuntu.svg) -->
<style>
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
}
</style>
<img src="/ubuntu.svg" alt="Ubuntu" class="center">


This is a simple guide to managing and maintaining Ubuntu Server. There is no desktop GUI on this version of Ubuntu. The goal here is to learn basic Ubuntu maintenance with just the command line.

## Prerequisites

Of course, you'll need to run Ubuntu Server if you want to follow along. For this post, I will be using Ubuntu 18.04. The .iso is available on the [official Ubuntu website](https://ubuntu.com/download/server) if you'd like to create a virtual machine. You can also use Amazon Web Services to boot up an Ubuntu EC2 instance.

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

## Unattended Upgrades

As with any system, security is a series of trade-offs. This trade off is convenience vs. security. Always keep security in mind when configuring any system. 

By default, Ubuntu Server comes with `unattended-upgrades` enabled, which automatically updates and install packages considered important or security updates. You can also run the command manually at any time. 

Sometimes it makes sense to turn off automatic updates in a production environment. If you do decide to turn off automatic security updates, be sure to schedule downtime to manually install them. You can enable/disable automatic security updates with:

```
dpkg-reconfigure unnatended-upgrades
```

For troubleshooting `unattended-upgrades`, you can refer to the log file located in `/var/log/unattended-upgrades/unaddended-updates.log`.

## Managing Users

Ubuntu Server, as well as other Linux distributions, come with several user accounts by default. Most of these are service accounts, meaning that they are used by specific services like NGINX. Generally speaking, the only human account is the one created during installation. User accounts help keep files and roles separate from others. User accounts are very mninimal, they are simply text entries in a few files. The following are files you should be aware of:

- `/etc/passwd` User accounts are stored in this file. You can see that several accounts already exist.
- `/etc/shadow` Where hashes of user passwords are stored.
- `/etc/group` Where group information is stored, such as the sudo group of users who have sudo privileges.

In Ubuntu Server, we use the `adduser` command to create new users, `usermod` command to modify users, and `deluser` to remove a user from the system. Additionally, we can use the `id` command to view more information about a specific user, the `addgroup` command to create new groups to place users in, and the `groups` command to view groups a user belongs to. Examples are shown below.

Adding a user:

```
sudo adduser nelson
```

```
Adding user `nelson' ...
Adding new group `nelson' (1001) ...
Adding new user `nelson' (1001) with group `nelson' ...
Creating home directory `/home/nelson' ...
Copying files from `/etc/skel' ...
Enter new UNIX password:
Retype new UNIX password:
passwd: password updated successfully
Changing the user information for nelson
Enter the new value, or press ENTER for the default
	Full Name []:
	Room Number []:
	Work Phone []:
	Home Phone []:
	Other []:
Is the information correct? [Y/n] Y
```

Notice that the output will prompt for a password. Additionally, there is a line that says `Copying files from '/etc/skel'`. The `skel` directory is a template to provide new users with predefined files or folders. The output also promops for user information such as full name and room number, but these are optional and can be skipped by hitting enter.

Find out more information about a user:

```
id nelson
```

```
uid=1001(nelson) gid=1001(nelson) groups=1001(nelson)
```

- `uid` stands for User ID
- `gid` stands for Group ID
- `groups` all the groups the user belongs to. Only group `nelson` in this case.

In many Linux distributions, the numbering system for regular users starts at 1000. Numbers below 1000 are system accounts and service accounts.

View groups a user belongs to:

```
groups nelson
```

```
nelson : nelson
```

In this case, the user only belongs to a single group with the same name.

Create a new group:

```
sudo addgroup developers
```

```
Adding group `developers' (GID 1002) ...
Done.
```

Add a user to the group:

```
sudo usermod -aG developers nelson
```

- `-G` ​Means you’ll provide a list of groups that the user will be a member of, but that list will replace what already exists.
- `-a` Tells `usermod` to append or add to your list to what’s already there instead of replacing them.

Now try running the `groups` command again:

```
groups nelson
```

```
nelson : nelson developers
```

We can see that an additional group has been added to the user.

Try the `id` command once again to see what's changed:

```
id nelson
```

```
uid=1001(nelson) gid=1001(nelson) groups=1001(nelson),1002(developers)
```

We can see an additional group that the user belongs to.

Finally, let's try deleting a user:

```
sudo deluser nelson
```

```
Removing user `nelson' ...
Warning: group `nelson' has no more members.
Done.
```

That covers the basics of user management in Ubuntu.

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

The service in charge of logs is `rsyslog`, which will chop log files, start new ones, and archive old ones. This service compresses logs as .gz (gzip) numbered in reverse order. The higher the number, the older the log. Instead of unzipping logs, you can use the `zcat` command to print out compressed logs directly as such:

```
zcat ufw.log.gz
```

Another useful tool is `lastlog`, which shows the last time each user in the system logged in.

```
sudo lastlog
```

The output will look similar to the following (I trimmed some of the output to save space):

```
Username         Port     From             Latest
root                                       **Never logged in**
daemon                                     **Never logged in**
bin                                        **Never logged in**
sys                                        **Never logged in**
sync                                       **Never logged in**
man                                        **Never logged in**
lp                                         **Never logged in**
statd                                      **Never logged in**
sshd                                       **Never logged in**
ubuntuuser          pts/0    10.0.2.2      Tue Oct 19 22:19:38 +0000 2019
```

Additionally, the `who` command will show which users are currently connected.

## Managing Processes

To see a general overview of processes, memory, and CPU usage, use the `top` command. There is also a prettier `htop` command installed by default which adds some color. Some other useful tools include `iftop` and `iotop`. They are not installed by default, so you'll need to install them first. `iftop` shows network activity, while `iotop` shows input/output activity between processes and system storage. These commands are useful when troubleshooting processes that are taking up networking or storage resources.

When running `top` or `htop`, there is a column labeled `NI`.

```
  CPU[||                                        1.3%]   Tasks: 25, 17 thr; 1 running
  Mem[|||||||||||||||||||                 73.7M/985M]   Load average: 0.00 0.00 0.00
  Swp[                                       0K/980M]   Uptime: 00:39:14

  PID USER      PRI  NI  VIRT   RES   SHR S CPU% MEM%   TIME+  Command
 1432 vagrant    20   0  105M  5112  3976 S  0.7  0.5  0:00.25 sshd: vagrant@pts/0
    1 root       20   0 77632  8556  6432 S  0.0  0.8  0:01.08 /sbin/init
  409 root       19  -1 94804 13608 12912 S  0.0  1.3  0:00.10 /lib/systemd/systemd-journald
  416 root       20   0  103M  1868  1640 S  0.0  0.2  0:00.00 /sbin/lvmetad -f
  426 root       20   0 46256  5112  3140 S  0.0  0.5  0:00.29 /lib/systemd/systemd-udevd
  492 root       20   0 47600  3432  3040 S  0.0  0.3  0:00.01 /sbin/rpcbind -f -w
  547 systemd-r  20   0 70628  5200  4644 S  0.0  0.5  0:00.03 /lib/systemd/systemd-resolved
  661 syslog     20   0  256M  4444  3672 S  0.0  0.4  0:00.00 /usr/sbin/rsyslogd -n
  662 syslog     20   0  256M  4444  3672 S  0.0  0.4  0:00.00 /usr/sbin/rsyslogd -n
  663 syslog     20   0  256M  4444  3672 S  0.0  0.4  0:00.00 /usr/sbin/rsyslogd -n
  650 syslog     20   0  256M  4444  3672 S  0.0  0.4  0:00.01 /usr/sbin/rsyslogd -n
  664 root       20   0  280M  6916  6056 S  0.0  0.7  0:00.04 /usr/lib/accountsservice/accounts-daemon
  676 root       20   0  280M  6916  6056 S  0.0  0.7  0:00.00 /usr/lib/accountsservice/accounts-daemon
  655 root       20   0  280M  6916  6056 S  0.0  0.7  0:00.06 /usr/lib/accountsservice/accounts-daemon
  657 root       20   0 70608  6064  5336 S  0.0  0.6  0:00.02 /lib/systemd/systemd-logind
  659 messagebu  20   0 50056  4364  3768 S  0.0  0.4  0:00.14 /usr/bin/dbus-daemon --system --address=systemd: -
  707 root       20   0 95540  1616  1488 S  0.0  0.2  0:00.00 /usr/bin/lxcfs /var/lib/lxcfs/
  708 root       20   0 95540  1616  1488 S  0.0  0.2  0:00.00 /usr/bin/lxcfs /var/lib/lxcfs/
```

The `NI` column specifies the "nicesness" value, which is used to prioritize processes. You can change this value with the `renice` command. This command allows you to specifiy a priority value randing from -20 to 19. The lower the value, the higher the priority a process will have. A process with a `NI` value of -15 will have higher priority over a process with a `NI` value of 5. Normally, you won't need to change process priority, but it is good to know you can change this value. For example, in the previous output of `htop` we have the following process with a `NI` value of 0:

```
  PID USER      PRI  NI  VIRT   RES   SHR S CPU% MEM%   TIME+  Command
 1432 vagrant    20   0  105M  5112  3976 S  0.7  0.5  0:00.25 sshd: vagrant@pts/0
```

With the following command, we can change the value to 5. We specify the process using the `PID`:

```
renice -n 5 -p 1432
```

- `-n` is used to specify a niceness value
- `-p` is used to specify the process ID

Now the process has a `NI` value of 5:

```
  PID USER      PRI  NI  VIRT   RES   SHR S CPU% MEM%   TIME+  Command
 1432 vagrant    20   5  105M  5112  3976 S  0.7  0.5  0:00.27 sshd: vagrant@pts/0
```

It is also possible to change the `NI` value of all processes owned by a user with the following command:

```
renice -n 5 -u user
```

- `-u` is used to specify the user

While `top` and `htop` provide a nice overview, we can use the `ps` command to be more precise. Running the command by itself shows processes running as the current user in the current shell:

```
  PID TTY          TIME CMD
 1433 pts/0    00:00:00 bash
 1649 pts/0    00:00:00 ps
```

To show all processes on the system run:

```
ps -e
```

```
  PID TTY          TIME CMD
    1 ?        00:00:01 systemd
    2 ?        00:00:00 kthreadd
    4 ?        00:00:00 kworker/0:0H
    6 ?        00:00:00 mm_percpu_wq
    7 ?        00:00:00 ksoftirqd/0
    8 ?        00:00:00 rcu_sched
    9 ?        00:00:00 rcu_bh
   10 ?        00:00:00 migration/0
   11 ?        00:00:00 watchdog/0
   12 ?        00:00:00 cpuhp/0
   13 ?        00:00:00 kdevtmpfs
   14 ?        00:00:00 netns
   15 ?        00:00:00 rcu_tasks_kthre
   16 ?        00:00:00 kauditd
   17 ?        00:00:00 khungtaskd
   18 ?        00:00:00 oom_reaper
   19 ?        00:00:00 writeback
   20 ?        00:00:00 kcompactd0
    .
    .
    .
 1651 pts/0    00:00:00 ps
```

You can pipe the output through `grep` to find a specific process:

```
ps -e | grep "bash"
```

```
 1433 pts/0    00:00:00 bash
 1654 pts/0    00:00:00 bash
```

You can also view process trees with the following command:

```
ps -eHj
```

```
  PID  PGID   SID TTY          TIME CMD
    2     0     0 ?        00:00:00 kthreadd
    4     0     0 ?        00:00:00   kworker/0:0H
    6     0     0 ?        00:00:00   mm_percpu_wq
    7     0     0 ?        00:00:00   ksoftirqd/0
    8     0     0 ?        00:00:00   rcu_sched
    9     0     0 ?        00:00:00   rcu_bh
    .
    .
    .
  686   686   686 ?        00:00:00   cron
  706   706   706 ?        00:00:00   polkitd
  889   889   889 ?        00:00:00   sshd
 1355  1355  1355 ?        00:00:00     sshd
 1432  1355  1355 ?        00:00:00       sshd
 1433  1433  1433 pts/0    00:00:00         bash
 1657  1657  1433 pts/0    00:00:00           ps
  905   903   903 ?        00:00:00   VBoxService
  906   906   906 tty1     00:00:00   agetty
 1279  1279  1279 ?        00:00:00   systemd-network
 1357  1357  1357 ?        00:00:00   systemd
```

- `-e` displays information about other users' processes
- `-H` repeats the information header as needed
- `-j` prints information associated with keywords such as `user`, `pid`, `ppid`, `pgid`

You can terminate processes with the `kill` command. If you want to terminate a process with a PID of 150 you can run the following command:

```
kill 150
```

You can immediately terminate a process with the following command:

```
kill -9 150
```

Keep in mind this is not a graceful process termination and should be used sparingly. Additional `kill` signal names can be found through the `kill -l` command.

## Service Management

In Ubuntu, the `systemd` process is one of the first processes on boot. It is a process manager that launches daemons and services. Services are programs that run in the background to accomplish tasks, such as web server software or SSH. On Ubuntu Server, we primarily interact with `systemd` using the `systemctl` command. Services managed by `systemd` are defined by unit files which specify what each service does and how it does it. `systemd` also uses "targets", which are groups of services. We can take a look at these with the following command:

```
systemctl -t target
```

```
UNIT                   LOAD   ACTIVE SUB    DESCRIPTION
basic.target           loaded active active Basic System
cryptsetup.target      loaded active active Local Encrypted Volumes
getty.target           loaded active active Login Prompts
graphical.target       loaded active active Graphical Interface
.
.
.
```

The same can be done for services:

```
systemctl -t service
```

```
UNIT                               LOAD   ACTIVE SUB     DESCRIPTION
accounts-daemon.service            loaded active running Accounts Service
apparmor.service                   loaded active exited  AppArmor initialization
apport.service                     loaded active exited  LSB: automatic crash report generation
atd.service                        loaded active running Deferred execution scheduler
blk-availability.service           loaded active exited  Availability of block devices
.
.
.
```

You can view more specific details for a particular service with `systemctl status`:

```
systemctl status rsyslog.service
```

```
● rsyslog.service - System Logging Service
   Loaded: loaded (/lib/systemd/system/rsyslog.service; enabled; vendor preset: enabled)
   Active: active (running) since Wed 2019-10-23 01:17:54 UTC; 3s ago
     Docs: man:rsyslogd(8)
           http://www.rsyslog.com/doc/
 Main PID: 2092 (rsyslogd)
    Tasks: 4 (limit: 1135)
   CGroup: /system.slice/rsyslog.service
           └─2092 /usr/sbin/rsyslogd -n

Oct 23 01:17:54 myprecise systemd[1]: Starting System Logging Service...
Oct 23 01:17:54 myprecise systemd[1]: Started System Logging Service.
Oct 23 01:17:54 myprecise rsyslogd[2092]: imuxsock: Acquired UNIX socket '/run/systemd/journal/syslog' (fd 3) fro
Oct 23 01:17:54 myprecise rsyslogd[2092]: rsyslogd's groupid changed to 106
Oct 23 01:17:54 myprecise rsyslogd[2092]: rsyslogd's userid changed to 102
Oct 23 01:17:54 myprecise rsyslogd[2092]:  [origin software="rsyslogd" swVersion="8.32.0" x-pid="2092" x-info="ht
```

The lines displayed below the service/target information are the most recent logs for that particular service or target.


The same command can be used for targets:

```
systemctl status basic.target
```

```
● basic.target - Basic System
   Loaded: loaded (/lib/systemd/system/basic.target; static; vendor preset: enabled)
   Active: active since Tue 2019-10-22 22:19:15 UTC; 2h 59min ago
     Docs: man:systemd.special(7)

Oct 22 22:19:15 myprecise systemd[1]: Reached target Basic System.
```

When running these commands, you will see words such as "active" and "running" as part of their descriptions. When a service is "active", it starts when the server boots up. If the service is continuously running, it will have the "running" descriptor. If the service only performs one task and then exits, it will be shown to be "active" but "(exited)". 

Services can be configured to start on system boot:

```
sudo systemctl enable rsyslog
```

Services can also be configured to not run on system boot:

```
sudo systemctl disable rsyslog
```

You can manually start a service as such:

```
sudo systemctl start rsyslog.service
```

You can stop a service as well:

```
sudo systemctl stop rsyslog.service
```

And you can also restart a service:

```
sudo systemctl restart rsyslog.service
```

Keep in mind starting/stopping a service is not the same as enabling/disabling. An enabled service that has been manually stopped will start automatically once the system is rebooted.

You can see more activity for a service in the log file, or use the command:

```
sudo journalctl -xe
```

The logs there can be helpful when troubleshooting why a service isn’t starting as expected.

## Conclusions

There is a lot more to cover on Ubuntu Server, and this is just a single distribution, there are many more out there. This post should be enough to get anyone started. All Linux distributions have similar functionality so learning one distribution, like Ubuntu Server, will help to pick up other distributions much more quickly. I hope this post was useful!