+++
title = ".gitignore Isn’t the Only Way To Ignore Files in Git"
summary = "You can ignore files in .gitignore, .git/info/exclude, and ~/.config/git/ignore"
date = "2026-06-18"
categories = ["Git"]
ShowToc = false
TocOpen = false
featured = false
+++

I've been using Git for so long and I just realized you can ignore files at three different levels and not just with `.gitignore`. The three files you can use to ignore files are:
- `.gitignore`
- `.git/info/exclude`
- `~/.config/git/ignore`

## .gitignore

`.gitignore` is the usual file where you write files you want to ignore. It's checked into Git along with the rest of the code. Whatever files you add to it will not get taken into account when running `git` commands.

## .git/info/exclude

The `exclude` file lives in the `.git` directory of every Git repository but changes to it are not checked into Git. It usually has a few comment lines on a fresh Git repository. This file is useful for ignoring things on a per-repo basis. For example, you may have a personal `notes.txt` file in a repository that you don't want to check into git but you also don't want to add to `.gitignore` because it's unique to your workflow. In that case you would add `notes.txt` to `.git/info/exclude`.

## ~/.config/git/ignore

The `ignore` file lives in your machine's home directory in `~/.config/git/ignore`. Whatever filenames are added to this file are ignored globally at a machine-level. This file is not checked into Git and isn't associated with any particular repository. It's a great place to add files that you want to ignore in every git repository on your computer. For example, if you're on macOS, adding `.DS_Store` here would be ideal.

You can customize the global ignore file to be a different file. For example, if you want your global git ignore file to be `.gitignore_global` you would run the command:
```shell
git config --global core.excludesFile ~/.gitignore_global
```

And if you ever want to return to the default setting, run:
```shell
git config --global --unset core.excludesFile
```

## Checking Which File Is Ignoring a Specific File

When adding filenames to any of these, you can use this command to check how a filename is being ignored. For example, if you want to check how `.DS_Store` is being ignored, run `git check-ignore -v .DS_Store` in any Git repository.

Here is the output when the repository's `.gitignore` is ignoring `.DS_Store`:
```console
$ git check-ignore -v .DS_Store
.gitignore:1:.DS_Store	.DS_Store
```

Here is the output when the repository's `.git/info/exclude` is ignoring `.DS_Store`:
```console
$ git check-ignore -v .DS_Store
.git/info/exclude:7:.DS_Store	.DS_Store
```

Here is the output when the global `~/.config/git/ignore` file is ignoring `.DS_Store`:
```console
$ git check-ignore -v .DS_Store
/Users/nelson/.config/git/ignore:2:.DS_Store	.DS_Store
```

And here is the output when a custom global ignore file `.gitignore_global` is ignoring `.DS_Store`:
```console
$ git check-ignore -v .DS_Store
/Users/nelson/.gitignore_global:1:.DS_Store	.DS_Store
```

If there is nothing ignoring a file, the `git check-ignore -v` command produces no output.

---

This post received a lot of attention on Hacker News! Check it out here: https://news.ycombinator.com/item?id=48583356
