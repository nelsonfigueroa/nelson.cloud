+++
title = "How To Install Older Versions of Homebrew Packages"
summary = "How to install older versions of homebrew packages."
date = "2023-04-18"
categories = ["Homebrew", "macOS"]
toc = false
featured = false
+++

It's possible to install older versions of Homebrew packages by saving an older version of the corresponding Ruby file locally and running `brew install <package>.rb`. I'll use the `terraform` package as an example.

<br>

Let's say we have `terraform` version 1.4.5 but we need `terraform` version 1.3.6. We can start by browsing to https://github.com/Homebrew/homebrew-core/tree/master/Formula and try to find the formula for `terraform`.

{{< figure src="/how_to_install_older_versions_of_homebrew_packages/formulas.png" alt="List of Homebrew formulas" >}}

Since there are a lot of files here, it's easier to just modify the URL. Take the name of the package and append it at the end of the url, adding `/<package-name>.rb` to the URL. The `.rb` is important because all Homebrew packages are defined in Ruby (files with the `.rb` extension). 

In this case, we'll append `/terraform.rb` to the URL like so: https://github.com/Homebrew/homebrew-core/tree/master/Formula/terraform.rb

That URL will then take us to the Ruby file where the `terraform` Homebrew package is defined.

{{< figure src="/how_to_install_older_versions_of_homebrew_packages/terraform.png" alt="Terraform Homebrew formula" >}}

Next, click the "History" link on the upper right above the code. In the next page, scroll down until you see the "terraform: update 1.3.6 bottle" link.

{{< figure src="/how_to_install_older_versions_of_homebrew_packages/1.3.6.png" alt="Commit history for the terraform formula" >}}

Click on the [terraform: update 1.3.6 bottle](https://github.com/Homebrew/homebrew-core/commit/169f333f93fe0703b542cdf75b1decd4cb78f68d) link to see this page:

{{< figure src="/how_to_install_older_versions_of_homebrew_packages/commit.png" alt="Commit for terraform 1.3.6" >}}

On the right side above the code block, click on the three dots, then click on "View file".

{{< figure src="/how_to_install_older_versions_of_homebrew_packages/view_file.png" alt="Three dots menu showing view file link" >}}

This will take you to the [package formula for this specific version of terraform](https://github.com/Homebrew/homebrew-core/blob/169f333f93fe0703b542cdf75b1decd4cb78f68d/Formula/terraform.rb).

{{< figure src="/how_to_install_older_versions_of_homebrew_packages/older_terraform.png" alt="Formula for terraform 1.3.6" >}}

On the upper right side of the code block, click on "Raw". This [gives us the exact code we need](https://raw.githubusercontent.com/Homebrew/homebrew-core/169f333f93fe0703b542cdf75b1decd4cb78f68d/Formula/terraform.rb) to install Terraform 1.3.6. Save the code locally to a file called `terraform.rb`. You can manually copy and pase or use `curl`:

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

