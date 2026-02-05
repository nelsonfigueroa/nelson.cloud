+++
title = "Scraping GitHub Contributor Emails"
summary = "Scraping GitHub contributor emails, and how you can protect yourself."
date = "2021-10-30"
categories = ["Git", "GitHub", "Privacy", "Cybersecurity"]
+++

{{< admonition type="note" >}}
2022-07-21 update: I discovered a better way to do this and I wrote about it in a separate blog post. Check it out here: [Scrape Contributor Emails From Any Git Repository]({{< ref "/posts/2022-07-21-scrape-contributor-emails-from-any-git-repository.md" >}})
{{< /admonition >}}

## Git Emails

When setting up Git on the command line, you are asked for your email.
When pushing commits to GitHub, the email you are using for Git gets pushed along with your code as part of the metadata.
While your GitHub email does not publicly show when viewing a repository's commits, it does come up when viewing commits using the GitHub API.

This presents a privacy risk and the potential for someone to find the email associated with your GitHub account.
I wanted to see how easily someone could do this so I created a scraper to do this for me. It turns out it's not hard to scrape commits for emails that are otherwise hidden from public view.

{{< admonition type="danger" title="Disclaimer" >}}
I don't plan on doing anything malicious with this script or the emails collected. I did this out of curiosity and for demonstrational purposes.
{{< /admonition >}}

The scraper I created is below. Instructions can be found on the README:

- https://github.com/nelsonfigueroa/github-email-scraper

## Scraping for Emails

By running the script, I can scrape for emails found in each commit for a given repository:

```
$ ruby main.rb -u torvalds -r linux

	+-------------------+
	|   GitHub          |
	|       Email       |
	|         Scraper   |
	+-------------------+


Scraping https://github.com/torvalds/linux/
Rate limit exceeded.
Pages scraped: 1-46 out of 10449
53 emails written to torvalds-linux.txt
```

Just like that, I've scraped several emails. It's not much, and I could get a lot more if I bothered to authenticate to prevent rate limiting.
A patient scraper could simply run this periodically to get as many emails as possible.

So what can we do about this?

## Hiding your Email in GitHub Commits

You can choose to hide your email when performing Git operations on the GitHub site as well as the command line. There are two checkboxes you'll need to tick. Instructions are below:

- [Blocking command line pushes that expose your personal email address](https://docs.github.com/en/account-and-profile/setting-up-and-managing-your-github-user-account/managing-email-preferences/blocking-command-line-pushes-that-expose-your-personal-email-address)

Next, you'll need to change your email Git uses on your machine to the `@users.noreply.github.com` email that GitHub provided in the previous step. Run the following:

```
$ git config --global user.email "00000000+yourusername@users.noreply.github.com"
```

Then verify that the email has been set:

```
$ git config --global user.email
00000000+yourusername@users.noreply.github.com
```

Now your actual email will be hidden from Git commits that have been pushed from your machine as well as any Git operations on GitHub. Note that your email will still show up in older commits that were pushed before these changes.
However, this will still improve your privacy on GitHub going forward.
