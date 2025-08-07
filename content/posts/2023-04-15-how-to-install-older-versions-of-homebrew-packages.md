+++
title = "How To Install Older Versions of Homebrew Packages"
summary = "How to install a specific version of Homebrew packages."
date = "2023-04-18"
lastmod = "2024-10-04"
categories = ["Homebrew", "macOS"]
keywords = ["Homebrew", "package downgrade", "Homebrew versions", "brew install", "macOS package management", "Terraform downgrade", "Homebrew formula", "package versioning", "brew remove", "Homebrew GitHub"]
+++

It's possible to install older versions of Homebrew packages by saving an older version of the corresponding Ruby file locally and running `brew install <package>.rb`. I'll use the `terraform` package as an example.

> TL;DR: If I wanted to downgrade to Terraform 1.3.6, I would need to:
> - Find the Ruby file for that specific version of [Terraform on the Homebrew GitHub repo](https://github.com/Homebrew/homebrew-core/blob/169f333f93fe0703b542cdf75b1decd4cb78f68d/Formula/terraform.rb)
> - Download the Ruby file
> - Uninstall the current version of terraform by running `brew remove terraform`
> - Install the older version defined in the Ruby file by running `brew install terraform.rb`

Let's say we have `terraform` version 1.4.5 but we need `terraform` version 1.3.6. We can start by browsing to https://github.com/Homebrew/homebrew-core/tree/master/Formula and try to find the formula for `terraform` under the `t` directory.

<img src="/how-to-install-older-versions-of-homebrew-packages/formulas.webp" alt="List of Homebrew formulas" width="720" height="410" style="max-width: 100%; height: auto; aspect-ratio: 3120 / 1780;" loading="lazy" decoding="async">

Since there are a lot of files here, it's easier to just modify the URL path in the browser. Modify the path based on the directory the command is in: Take the name of the package and append it at the end of the url, adding `/<directory-containing-package>/<package-name>.rb` to the URL. The `.rb` is important because all Homebrew packages are defined in Ruby (files with the `.rb` extension).

In this case, we'll append `/t/terraform.rb` to the URL like so: https://github.com/Homebrew/homebrew-core/blob/master/Formula/t/terraform.rb

That URL will then take us to the Ruby file where the `terraform` Homebrew package is defined.

<img src="/how-to-install-older-versions-of-homebrew-packages/terraform.webp" alt="Terraform Homebrew formula" width="720" height="410" style="max-width: 100%; height: auto; aspect-ratio: 3120 / 1780;" loading="lazy" decoding="async">

Next, click the "History" link on the upper right above the code, or just click on this link https://github.com/Homebrew/homebrew-core/commits/master/Formula/t/terraform.rb. In the next page, scroll down until you see the "terraform: update 1.3.6 bottle" link. Note that you may need to click on "Browse History" at the bottom of this page before continuing your search.

<img src="/how-to-install-older-versions-of-homebrew-packages/1.3.6.webp" alt="Commit history for the terraform formula" width="720" height="410" style="max-width: 100%; height: auto; aspect-ratio: 3120 / 1780;" loading="lazy" decoding="async">

Click on the [terraform: update 1.3.6 bottle](https://github.com/Homebrew/homebrew-core/commit/169f333f93fe0703b542cdf75b1decd4cb78f68d) link to see this page:

<img src="/how-to-install-older-versions-of-homebrew-packages/commit.webp" alt="Commit for terraform 1.3.6" width="720" height="395" style="max-width: 100%; height: auto; aspect-ratio: 3840 / 2110;" loading="lazy" decoding="async">

On the right side above the code block, click on the three dots, then click on "View file".

<img src="/how-to-install-older-versions-of-homebrew-packages/view-file.webp" alt="Three dots menu showing view file link" width="720" height="353" style="max-width: 100%; height: auto; aspect-ratio: 1872 / 918;" loading="lazy" decoding="async">

This will take you to the [package formula for this specific version of terraform](https://github.com/Homebrew/homebrew-core/blob/169f333f93fe0703b542cdf75b1decd4cb78f68d/Formula/terraform.rb).

<img src="/how-to-install-older-versions-of-homebrew-packages/older-terraform.webp" alt="Formula for terraform 1.3.6" width="720" height="395" style="max-width: 100%; height: auto; aspect-ratio: 3840 / 2110;" loading="lazy" decoding="async">

On the upper right side of the code block, click on "Raw". This [gives us the exact code we need](https://raw.githubusercontent.com/Homebrew/homebrew-core/169f333f93fe0703b542cdf75b1decd4cb78f68d/Formula/terraform.rb) to install Terraform 1.3.6. Save the code locally to a file called `terraform.rb`. You can manually copy and paste or use `curl`:

```sh
curl https://raw.githubusercontent.com/Homebrew/homebrew-core/169f333f93fe0703b542cdf75b1decd4cb78f68d/Formula/terraform.rb > terraform.rb
```

Then, remove the existing package:

```
$ brew remove terraform

Uninstalling /usr/local/Cellar/terraform/1.4.5... (6 files, 69MB)
```

Then run `brew install` but specify the file you saved locally to install the older version. We'll get some warnings but it should be fine.

```
$ brew install terraform.rb

Error: Failed to load cask: terraform.rb
Cask 'terraform' is unreadable: wrong constant name #<Class:0x00007feed1183f18>
Warning: Treating terraform.rb as a formula.
==> Downloading https://formulae.brew.sh/api/formula.jws.json
######################################################################## 100.0%
==> Fetching terraform
==> Downloading https://ghcr.io/v2/homebrew/core/terraform/manifests/1.3.6
######################################################################## 100.0%
==> Downloading https://ghcr.io/v2/homebrew/core/terraform/blobs/sha256:dad3b9cce25f6ae0d5ddb06029fc266af2d337013828fda6b5fb6c2bcf3f5d
==> Downloading from https://pkg-containers.githubusercontent.com/ghcr1/blobs/sha256:dad3b9cce25f6ae0d5ddb06029fc266af2d337013828fda6b
######################################################################## 100.0%
Warning: terraform 1.4.5 is available and more recent than version 1.3.6.
==> Pouring terraform--1.3.6.ventura.bottle.tar.gz
==> Downloading https://formulae.brew.sh/api/cask.jws.json
######################################################################## 100.0%
🍺  /usr/local/Cellar/terraform/1.3.6: 6 files, 65MB
==> Running `brew cleanup terraform`...
Disable this behaviour by setting HOMEBREW_NO_INSTALL_CLEANUP.
Hide these hints with HOMEBREW_NO_ENV_HINTS (see `man brew`).
Removing: /Users/nelson/Library/Caches/Homebrew/terraform--1.3.6... (19.4MB)
```

Now `terraform` version 1.3.6 is installed!

```
$ terraform version

Terraform v1.3.6
```
