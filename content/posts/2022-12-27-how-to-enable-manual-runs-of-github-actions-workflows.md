+++
title = "How to Enable Manual Runs of GitHub Actions Workflows"
summary = "How to enable manual runs of GitHub Actions workflows."
date = "2022-12-27"
categories = ["GitHub"]
keywords = ["GitHub Actions", "workflow_dispatch", "manual workflow", "GitHub CI/CD", "workflow triggers", "GitHub automation", "manual trigger", "GitHub workflow", "CI/CD pipeline", "GitHub tutorial"]
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

This configuration allows for workflows to run on commit pushes to the `master` branch, but there is no option to run this workflow manually on GitHub:

<img src="/how-to-enable-manual-runs-of-github-actions-workflows/no-manual-run.webp" alt="GitHub Actions doesn't show a manual way of running the workflow." width="720" height="259" style="max-width: 100%; height: auto; aspect-ratio: 1862 / 672;" loading="lazy" decoding="async">

To enable manual runs of workflows, I added the `workflow_dispatch` key. There was no need to add anything else under it:

```yaml
name: Deploy
on:
  push:
    branches:
      - master
  workflow_dispatch:
```

After pushing changes, I was able to see a "Run workflow" button that allows me to run the workflow manually.

<img src="/how-to-enable-manual-runs-of-github-actions-workflows/manual-run-enabled.webp" alt="GitHub Actions now shows a manual way of running the workflow." width="720" height="306" style="max-width: 100%; height: auto; aspect-ratio: 1854 / 790;" loading="lazy" decoding="async">

Note that there are a lot more configuration options available when adding the `workflow_dispatch` key. However, I just wanted to enable manual runs and nothing more. Refer to [the documentation](https://docs.github.com/en/actions/using-workflows/events-that-trigger-workflows#workflow_dispatch) for more information.

## References

- https://docs.github.com/en/actions/managing-workflow-runs/manually-running-a-workflow
- https://docs.github.com/en/actions/using-workflows/events-that-trigger-workflows#workflow_dispatch
- https://docs.github.com/en/actions/writing-workflows/choosing-when-your-workflow-runs/events-that-trigger-workflows
