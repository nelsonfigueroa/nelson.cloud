+++
title = "How to Enable Manual Runs of GitHub Actions Workflows"
summary = "How to enable manual runs of GitHub Actions workflows."
date = "2022-12-27"
lastmod = "2022-12-27"
categories = ["GitHub"]
toc = false
+++

By default, GitHub Actions does not allow you to run a workflow manually. In order to enable manual workflow runs, I had to add `workflow_dispatch` to the YAML file under `.github/workflows/`.

For example, the beginning of my GitHub Actions YAML file looked like this:


```yaml
name: Deploy
on:
  push:
    branches: 
      - master
```

This configuration allows for workflows to run on commit pushes, but there is no option to run this workflow manually on GitHub:

{{< figure src="/how-to-enable-manual-runs-of-github-actions-workflows/no-manual-run.png" alt="GitHub Actions doesn't show a manual way of running the workflow." >}}

To enable manual runs of workflows, I added the `workflow_dispatch` key and specified the master branch:

```yaml
name: Deploy
on:
  push:
    branches: 
      - master
  workflow_dispatch:
    branches:
      - master
```

After pushing changes, I was able to see a "Run workflow" button that allows me to run the workflow manually.

{{< figure src="/how-to-enable-manual-runs-of-github-actions-workflows/manual-run-enabled.png" alt="GitHub Actions doesn't show a manual way of running the workflow." >}}

Note that there are a lot more configuration options available when adding the `workflow_dispatch` key. However, I just wanted to enable manual runs and nothing more. Refer to [the documentation](https://docs.github.com/en/actions/using-workflows/events-that-trigger-workflows#workflow_dispatch) for more information.

## References

- https://docs.github.com/en/actions/managing-workflow-runs/manually-running-a-workflow
- https://docs.github.com/en/actions/using-workflows/events-that-trigger-workflows#workflow_dispatch