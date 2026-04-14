+++
title = "Set a Default Ruby Version with Ruby Version Manager (RVM)"
summary = "Set a default Ruby version using RVM with rvm --default use <version number>"
date = "2022-11-06"
lastmod = "2025-12-08T19:34:09-08:00"
categories = ["Ruby"]
+++

To set the system-wide version of Ruby with `rvm` run:

```bash
rvm --default use <version number>
```

For example, to use 3.1.2 as the default version system-wide, run:

```bash
rvm --default use 3.1.2
```

Now this version will be used even when a new terminal tab or window is opened.

References:

-  https://rvm.io/rubies/default
