+++
title = "You Can Hide Languages From GitHub's Languages Bar"
summary = "You can create a .gitattributes file to hide languages from the GitHub languages bar."
date = "2026-10-09"
categories = ["Git", "GitHub"]
ShowToc = false
TocOpen = false
featured = false
+++

Today I learned that you can hide languages that show up on the side on GitHub repositories and other hosted Git services like GitLab. You can do this by adding a `.gitattributes` file to your repository.

For example, I have a bunch of fake PHP files to mess with bots and script kiddies, such as: https://nelson.cloud/wp-login.php. As a result, GitHub was showing that most of my language usage for this repository was "Hack":

<img src="/hiding-languages-in-git-services/before.webp" alt="GitHub languages bar showing Hack at 47.5%" width="316" height="130" style="max-width: 100%; height: auto; aspect-ratio: 632 / 260;" loading="lazy" decoding="async">

Hack is not representative of what I actually use to build this static site. I wanted to get rid of "Hack" from the languages bar. To do that, I created a `.gitattributes` file at the root of my repository, then I added this line to exclude PHP files from being counted:

```text
*.php -linguist-detectable
```

This will exclude all PHP files in this repository from being counted in the languages bar. Committing and pushing this `.gitattributes` file to my repository results in "Hack" being gone (along with 0.1% of PHP):

<img src="/hiding-languages-in-git-services/after.webp" alt="GitHub languages bar without Hack or PHP" width="316" height="130" style="max-width: 100%; height: auto; aspect-ratio: 632 / 260;" loading="lazy" decoding="async">

You can also only exclude files in certain directories. For example, I could exclude PHP files only in my `static/` directory like so:

```text
static/**/*.php -linguist-detectable
```

You can substitute any path and filetype. All of these examples should work on GitLab, Gitea, Forgejo, and Codeberg as well.

The `linguist-detectable` part determines whether matching files are counted in the languages bar. Prefixing it with a dash `-` means matching files are not counted, which is why it has a leading dash in all of the examples here. You can read more about how `linguist-detectable` works in the official repository: [Linguist](https://github.com/github-linguist/linguist/blob/main/docs/overrides.md). Although Linguist is a GitHub project, other hosted Git services chose to support its attributes, which is why all of this works in GitLab and the others.

## References
- https://github.com/github-linguist/linguist/blob/main/docs/overrides.md
