+++
title = "Scrape Contributor Emails From Any Git Repository"
summary = "Scraping contributor emails from git repositories using git shortlog."
date = "2022-07-21"
categories = ["Cybersecurity", "Privacy"]
toc = false
+++

In a [previous post]({{< relref "/posts/2021_10_30_scraping_github_contributor_emails.md" >}}) I wrote about how it's possible to scrape emails from GitHub repositories using their API.
I even wrote up a [Ruby script](https://github.com/nelsonfigueroa/github-email-scraper) to do this.
I now realize that is a very complicated way to go about it after discovering the `git shortlog` command.

With `git shortlog` you can list all contributor emails for any git repository, not just GitHub repos.

---
**Disclaimer: I am writing about this to make others aware of this form of scraping. I am simply exposing a privacy issue with git. I do not plan on doing anything with emails from git repos and you shouldn't either.**

---

## Extracting Emails With `git shortlog`

Run this command within any git repo to extract all contributor emails:

```bash
git shortlog -sea | grep -E -o "\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}\b" | awk '{print tolower($0)}' | sort | uniq | grep -wv 'users.noreply.github.com'
```

## Command Breakdown

The `git shortlog -sea` part of the command is short for `git shortlog --summary --email --all`. This command outputs the number of commits each user has made, along with their name and email, across all branches. 

```bash
$ git shortlog -sea

    54  First Last <FirstLast@example.com>
   385  Another User <Anotheruser@example.com>
     2  user1 <user1@example.com>
    31  first last <firstlast@example.com>
    10  Someone Else <1234567+someoneelse@users.noreply.github.com>
```

The next command, `grep -E -o "\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}\b"`, extracts emails from each line using a regular expression.

```bash
$ git shortlog -sea | grep -E -o "\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}\b"

FirstLast@example.com
Anotheruser@example.com
user1@example.com
firstlast@example.com
1234567+someoneelse@users.noreply.github.com
```

The output from the previous command is piped into `awk '{print tolower($0)}'`, which lowercases all the emails. Sometimes emails are typed in with capital letters. Lowercasing all characters will help with sorting and finding unique emails later.

```bash
$ git shortlog -sea | grep -E -o "\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}\b" | awk '{print tolower($0)}'

firstlast@example.com
anotheruser@example.com
user1@example.com
firstlast@example.com
1234567+someoneelse@users.noreply.github.com
```


After that, the output is piped into `sort` and `uniq`. These commands are straightforward. The emails are sorted alphabetically, then duplicates are excluded from the output.

```bash
$ git shortlog -sea | grep -E -o "\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}\b" | awk '{print tolower($0)}' | sort | uniq

1234567+someoneelse@users.noreply.github.com
anotheruser@example.com
firstlast@example.com
user1@example.com
```

That should suffice for a lot of git repos, but I also added `grep -wv 'users.noreply.github.com'` to the end of the command to exclude noreply emails associated with GitHub.

```bash
$ git shortlog -sea | grep -E -o "\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}\b" | awk '{print tolower($0)}' | sort | uniq | grep -wv 'users.noreply.github.com'

anotheruser@example.com
firstlast@example.com
user1@example.com
```

## Extracting Emails With `git log`

It's possible to do something similar with the `git log --pretty="%ce"` command. However, I noticed that this command does not show as many emails as `git shortlog`. I didn't look too much into it, but I believe it only pulls emails from one branch rather than all branches like with `git shortlog --all`.

## References

I learned about `git shortlog` from this Stack Overflow question:
- [https://stackoverflow.com/questions/9597410/list-all-developers-on-a-project-in-git](https://stackoverflow.com/questions/9597410/list-all-developers-on-a-project-in-git)

I got the email regex from here:
- [https://www.shellhacks.com/regex-find-email-addresses-file-grep/](https://www.shellhacks.com/regex-find-email-addresses-file-grep/)
