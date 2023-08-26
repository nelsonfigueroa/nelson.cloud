+++
title = "Set a Default Ruby Version with Ruby Version Manager (RVM)"
summary = "How to recursively ignore files when building Docker images."
date = "2022-11-06"
lastmod = "2022-11-06"
categories = ["Ruby"]
toc = false
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