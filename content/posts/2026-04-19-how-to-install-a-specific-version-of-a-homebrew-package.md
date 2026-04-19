+++
title = "How to Install a Specific Version of a Homebrew Package with brew extract"
summary = "Use `brew extract` to install a specific version of a homebrew package."
date = "2026-04-19"
# lastmod = "2026-04-19T00:32:00-07:00"
categories = ["Homebrew", "macOS", "Hugo"]
ShowToc = true
TocOpen = true
featured = false
+++


I previously wrote about [how to install older versions of homebrew packages]({{< ref "/posts/2023-04-18-how-to-install-older-versions-of-homebrew-packages.md" >}}). That method involves installing a package from a Ruby file but it's outdated and doesn't always work. There's a better way with `brew extract`, although it still comes with caveats.

I'll be using `hugo` as an example. Let's say I wanted to install v0.145.0 because v0.146.0 introduced breaking changes that broke my theme.

{{< admonition type="info" title="tl;dr" >}}
To install hugo v0.145.0:
- Create a local tap with `brew tap-new $USER/local`
- Tap homebrew/core which is a 1.3GB clone at the time of writing
- Extract the formula with `brew extract`
- Patch the `hugo` formula. This isn't needed for every formula.
- Install as usual
{{< /admonition >}}

Note that this process will point your `hugo` command to the older version, but you can switch between versions with `brew link`.

## Create a local tap

Run `brew tap-new $USER/local`
```
$ brew tap-new $USER/local

Initialized empty Git repository in /opt/homebrew/Library/Taps/nelson/homebrew-local/.git/
[main (root-commit) 6af371f] Create nelson/local tap
 3 files changed, 111 insertions(+)
 create mode 100644 .github/workflows/publish.yml
 create mode 100644 .github/workflows/tests.yml
 create mode 100644 README.md
==> Created nelson/local
/opt/homebrew/Library/Taps/nelson/homebrew-local

When a pull request making changes to a formula (or formulae) becomes green
(all checks passed), then you can publish the built bottles.
To do so, label your PR as `pr-pull` and the workflow will be triggered.
```

It will enable developer mode. This is normal and safe.

## Tap homebrew/core

Next, run `brew tap --force homebrew/core`. At the time of writing, it's a 1.3GB download. This is necessary to get this working because Homebrew no longer keeps [homebrew-core](https://github.com/homebrew/homebrew-core) cloned locally. The `brew extract` command needs the full git history to search for older versions.

```
$ brew tap --force homebrew/core

✔︎ JSON API formula.jws.json                                                                                                                                    Downloaded   32.0MB/ 32.0MB
✔︎ JSON API cask.jws.json                                                                                                                                       Downloaded   15.4MB/ 15.4MB
==> Tapping homebrew/core
Cloning into '/opt/homebrew/Library/Taps/homebrew/homebrew-core'...
remote: Enumerating objects: 3385552, done.
remote: Counting objects: 100% (544/544), done.
remote: Compressing objects: 100% (119/119), done.
remote: Total 3385552 (delta 497), reused 427 (delta 425), pack-reused 3385008 (from 4)
Receiving objects: 100% (3385552/3385552), 1.08 GiB | 48.36 MiB/s, done.
Resolving deltas: 100% (2612327/2612327), done.
Tapped 5 commands and 8313 formulae (8,851 files, 1.3GB).
```

## Extract the desired version

Now we can use `brew extract`. This command will find a commit where the formula was at the version we want and copy that locally as `<package>@<version>.rb`.

In this case we want Hugo v0.145.0, so we run `brew extract --version=0.145.0 hugo $USER/local`:

```
$ brew extract --version=0.145.0 hugo $USER/local

==> Searching repository history
==> Writing formula for hugo at 0.145.0 from revision a110fdb to:
/opt/homebrew/Library/Taps/nelson/homebrew-local/Formula/hugo@0.145.0.rb
```

## Patch the formula

This isn't needed for every formula and is something I ran into specifically with Hugo. Without this patch, you'll run into errors.

After running `brew extract`, edit the file: `/opt/homebrew/Library/Taps/$USER/homebrew-local/Formula/hugo@0.145.0.rb`.

Change this line:

```ruby
system "go", "build", *std_go_args(ldflags:, tags:)
```

To this:

```ruby
system "go", "build", *std_go_args(output: bin/"hugo", ldflags:, tags:)
```

The reason we need to patch this file is because it prevents the error:

```
command not found: /opt/homebrew/Cellar/hugo@0.145.0/0.145.0/bin/hugo
```

It's a mismatch between the path Homebrew expects (`bin/hugo`) vs the path that is created when using `brew extract` on Hugo (`bin/hugo@0.145.0`).

## Install the older version

Now that Hugo is extracted and patched, we can install with `brew install hugo@0.145.0`:

```
$ brew install hugo@0.145.0

✔︎ JSON API cask.jws.json
✔︎ JSON API formula.jws.json
==> Fetching downloads for: hugo@0.145.0
✔︎ Formula hugo@0.145.0 (0.145.0)
==> Installing hugo@0.145.0 from nelson/local
==> go build -o=/opt/homebrew/Cellar/hugo@0.145.0/0.145.0/bin/hugo -tags=extended withdeploy -ldflags=-s -w -X github.com/gohugoio/hugo/common/hugo.commitHash=nelson -X github.com/gohugoio/hugo/common/
==> /opt/homebrew/Cellar/hugo@0.145.0/0.145.0/bin/hugo gen man --dir /opt/homebrew/Cellar/hugo@0.145.0/0.145.0/share/man/man1
Warning: These files were overwritten during the `brew link` step:
/opt/homebrew/etc/bash_completion.d/hugo
.
.
.
/opt/homebrew/share/zsh/site-functions/_hugo

They have been backed up to: /Users/nelson/Library/Caches/Homebrew/Backup
==> Summary
🍺  /opt/homebrew/Cellar/hugo@0.145.0/0.145.0: 53 files, 73MB, built in 8 seconds
==> Running `brew cleanup hugo@0.145.0`...
Disable this behaviour by setting `HOMEBREW_NO_INSTALL_CLEANUP=1`.
Hide these hints with `HOMEBREW_NO_ENV_HINTS=1` (see `man brew`).
==> Caveats
zsh completions have been installed to:
  /opt/homebrew/share/zsh/site-functions
```

Hugo v0.145.0 is now installed. There's a warning with long output in the previous example due to the normal Hugo package being already installed but that is expected. Homebrew is now pointing the `hugo` binary to v0.145.0 instead of the latest version (v0.160.1 at the time of writing). We can verify with `hugo version`:

```
$ hugo version

hugo v0.145.0+extended+withdeploy darwin/arm64 BuildDate=2025-02-26T15:41:25Z VendorInfo=brew
```

We can also see that Hugo v0.145.0 is installed along with the latest version with `brew list | grep hugo`:
```
$ brew list | grep hugo

hugo
hugo@0.145.0
```

## Switching Between Versions with `brew link`

Currently the `hugo` command is pointing to v0.145.0. To have it point back to the regular version, run `brew unlink hugo && brew link --overwrite hugo`:

```
$ brew unlink hugo && brew link --overwrite hugo

Unlinking /opt/homebrew/Cellar/hugo/0.160.1... 2 symlinks removed.
Linking /opt/homebrew/Cellar/hugo/0.160.1... 49 symlinks created.
```

And if we want `hugo` to point back to the old version, run `brew unlink hugo@0.145.0 && brew link --overwrite hugo@0.145.0`

```
$ brew unlink hugo@0.145.0 && brew link --overwrite hugo@0.145.0

Unlinking /opt/homebrew/Cellar/hugo@0.145.0/0.145.0... 1 symlinks removed.
Linking /opt/homebrew/Cellar/hugo@0.145.0/0.145.0... 48 symlinks created.
```

At first I expected `brew link --overwrite hugo` to work right off the bat, but running both `brew unlink` and `brew link --overwrite` is necessary to switch between versions properly. This is because homebrew tracks linked formulas and actual symlinks on disk separately. To help Homebrew track things properly we need to run both `brew unlink` to clean the records, then `brew link --overwrite` to write the new symlinks.

There's no need to use `brew pin` to prevent the older version of Hugo from updating. Since this is a local copy, there is no remote repository that would be updated that would in turn update our local version. You can even try running `brew update` to see the warning message:

```
$ brew update

==> Updating Homebrew...
Warning: No remote 'origin' in /opt/homebrew/Library/Taps/nelson/homebrew-local, skipping update!
Already up-to-date.
```

## Removing the Older Version

If you no longer need Hugo v0.145.0 you can run `brew uninstall hugo@0.145.0`:

```
$ brew uninstall hugo@0.145.0

Uninstalling /opt/homebrew/Cellar/hugo@0.145.0/0.145.0... (53 files, 73MB)
```

If you don't have any other packages you extracted with `brew extract`, you can also remove your local tap with `brew untap $USER/local`

```
$ brew untap $USER/local

Untapping nelson/local...
Untapped 1 formula (34 files, 36.7KB).
```

Finally, if you don't plan on using `brew extract` again in the future, you can remove the local clone of homebrew-core with `brew untap homebrew/core`. This will clean up the 1.3GB of files that was downloaded:

```
$ brew untap homebrew/core

Untapping homebrew/core...
Untapped 5 commands and 8313 formulae (8,975 files, 1.3GB).
```

Then re-link `hugo` to the latest version with `brew unlink hugo && brew link hugo`:

```
$ brew unlink hugo && brew link hugo

Unlinking /opt/homebrew/Cellar/hugo/0.160.1... 2 symlinks removed.
Linking /opt/homebrew/Cellar/hugo/0.160.1... 49 symlinks created.
```

## References
- https://docs.brew.sh/Manpage
- https://github.com/orgs/Homebrew/discussions/2941
- https://emmer.dev/blog/installing-old-homebrew-formula-versions/
