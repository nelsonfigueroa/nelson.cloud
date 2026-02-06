+++
title = "Keep Homebrew Tidy With \"brew leaves\""
summary = "Use the 'brew leaves' command to find potentially unnecessary Homebrew packages."
date = "2024-01-22"
categories = ["Homebrew", "macOS"]

[cover]
image = "/opengraph-images/brew.png"
alt = "ASCII art of Homebrew logo."
caption = ""
+++

Homebrew has a `brew leaves` command that shows all installed packages with no dependencies. That means that they can be uninstalled without causing issues to other installed Homebrew packages. It's good to regularly run this command to keep Homebrew from getting too bloated.

Here's what my `brew leaves` output looks like at the time of this writing:

```
$ brew leaves

automake
bat
black
coreutils
ffmpeg
go
htop
hugo
jq
libksba
libpq
libtool
libyaml
node
openssl@1.1
pkg-config
postgresql@14
python-typing-extensions
tldr
tree
yt-dlp
zlib
```

In my case, I know I don't need the `libpq` package anymore. So I can remove this package and then run `brew leaves` again to confirm it's gone.

```
$ brew remove libpq

Uninstalling /opt/homebrew/Cellar/libpq/16.1_1... (2,380 files, 29.9MB)
```

```
$ brew leaves

automake
bat
black
coreutils
ffmpeg
go
htop
hugo
jq
libksba
libtool
libyaml
node
openssl@1.1
pkg-config
postgresql@14
python-typing-extensions
tldr
tree
yt-dlp
zlib
```

And now `libpq` is gone! Try out `brew leaves` yourself. You may be surprised at the amount of things installed that you may not actually need.
