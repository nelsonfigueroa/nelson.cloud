+++
title = "Quick Tip: Mute the Terminal Login Message with A .hushlogin File"
summary = "Create a .hushlogin file in your home directory to silence login messages."
date = "2025-09-28"
categories = ["Shell", "macOS", "Linux"]
keywords = ["hushlogin", "hushlogin file", "stop terminal login message", "terminal login message settings", "turn off terminal login message"]
ShowToc = false
TocOpen = false
+++

Today I learned that you can create an empty `.hushlogin` file in your home directory on macOS and Linux to hide the login message you get when starting up a new terminal window or tab.

I tried this on macOS but it should work on Linux too.

For example, when I start up a new terminal window/tab I see the following message:

<img src="/mute-terminal-login-message/before.webp" alt="terminal with login message" width="980" height="254" style="max-width: 100%; height: auto; aspect-ratio: 980 / 254;" loading="lazy" decoding="async">

After I create the `.hushlogin` file in my home directory, the login message goes away. First I created the file:

```
touch ~/.hushlogin
```

Then I opened a new terminal window to verify that the message no longer shows up:

<img src="/mute-terminal-login-message/after.webp" alt="terminal without login message after creating .hushlogin file" width="980" height="254" style="max-width: 100%; height: auto; aspect-ratio: 980 / 254;" loading="lazy" decoding="async">

## References:
- https://stackoverflow.com/questions/15769615/remove-last-login-message-for-new-tabs-in-terminal
- https://www.cyberciti.biz/howto/turn-off-the-login-banner-in-linux-unix-with-hushlogin-file/
