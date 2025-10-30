+++
title = "Set a Default Ruby Version with Ruby Version Manager (RVM)"
summary = "Set a default Ruby version using RVM with rvm --default use <version number>"
date = "2022-11-06"
categories = ["Ruby"]
keywords = ["RVM", "Ruby Version Manager", "set default Ruby version", "Ruby installation", "ruby version management", "RVM commands", "configure RVM", "switch Ruby versions rvm", "rvm default use"]
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
