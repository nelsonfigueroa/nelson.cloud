+++
title = "How to Serve Your Hugo Site on a Local Network" 
summary = "You can serve your Hugo site on a local network with `hugo server --bind 0.0.0.0 --baseURL <local-IP-address>`. Then other devices on the same network can reach the site."
date = "2026-11-05"
categories = ["Hugo"]
ShowToc = false
TocOpen = false
featured = false
+++

Sometimes it's useful to check how a Hugo site looks on a real mobile device before deploying. That requires serving the site on a local network so other devices on that network can access it. Here's the command to serve a Hugo site on a local network:

```shell
hugo server --bind 0.0.0.0 --baseURL <local-IP-address>
```

Then open `http://<local-IP-address>:1313` on your phone. The site should load and work as expected.

The `--bind 0.0.0.0` option is necessary because Hugo binds itself to `127.0.0.1` by default, which means no other devices except the one serving the site would be able to reach said site.

The `--baseURL <local-IP-address>` option is needed so that links generated in the Hugo site all point to the local IP address the site is being served from. Otherwise, the links will be generated with `localhost:1313`.

Note that if you're on public wifi this command may not work due to client isolation!

If you need to get your local IP address, here are a few methods depending on your operating system.

On macOS, try running:

```shell
ipconfig getifaddr en0
```

If it returns nothing, try `en1` instead.

On Linux, try this command. The first address is usually the one you need:

```shell
hostname -I
```

On Windows, try this command. Look for "IPv4 Address":

```shell
ipconfig
```

I felt the need to write this post because I tried this command that worked for loading the home page on my phone, but everything else was broken. I couldn't tell if I was doing something wrong or if there was something wrong with Hugo.

```shell
hugo server --bind 0.0.0.0
```
With this command, the home page loaded on my mobile device on the same local network, but clicking on any links to other pages of the site resulted in a connection error. That's because the links are all generated with `localhost:1313`, which means the device tries to connect to itself and the Hugo server is running on a separate device. Using `--baseURL` is necessary to get past this.
