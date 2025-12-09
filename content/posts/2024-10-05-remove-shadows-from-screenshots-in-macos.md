+++
title = "Remove Shadows From Screenshots in macOS"
summary = "Run this command to remove shadows from your screenshots in macOS: `defaults write com.apple.screencapture \"disable-shadow\" -bool \"true\"`"
date = "2024-10-05"
categories = ["macOS"]
+++

On macOS, screenshots of windows have an added drop shadow by default for whatever reason. Here's how to remove it:

To remove shadows from screenshots copy and paste this into the command line:

```shell
defaults write com.apple.screencapture "disable-shadow" -bool "true"
```

<br>

To reset this setting and have shadows in your screenshots again run this:

```shell
defaults delete com.apple.screencapture "disable-shadow"
```

<br>

Alternatively, you can press `command` + `shift` + `4` and then press `space`. Then hold `option` before you click on a window to take a screenshot. This removes the drop shadow.

## References
- https://www.reddit.com/r/MacOS/comments/q7h3xl/any_way_to_take_a_screenshot_on_mac_without_the/
- https://www.idownloadblog.com/2014/08/03/how-to-remove-the-shadow-window-screenshots-on-mac-os-x/

There were several other sites covering this but they didn't get right to the point and/or they were ad-ridden, so I decided to write a straightforward post about this.
