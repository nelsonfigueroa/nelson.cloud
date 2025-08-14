+++
title = "How to Clone a Specific Git Branch Without Other Branches"
summary = "How to clone a specific git repository branch without cloning all other branches."
date = "2025-02-27"
categories = ["Git", "Shell"]
keywords = ["Git clone", "Git branch", "single branch clone", "Git commands", "version control", "Git tutorial", "repository cloning", "Git workflow", "branch isolation"]
+++

## Cloning a Specific Branch

To clone a specific branch of a git repository without cloning all other branches, use the following command formula:

```
git clone --single-branch --branch <branch_name> <repo_URL.git>
```

For example, if you want to clone the `release-1.28` branch of the [Kubernetes GitHub repository](https://github.com/kubernetes/kubernetes/tree/release-1.28), run:

```
git clone --single-branch --branch release-1.28 https://github.com/kubernetes/kubernetes.git
```

## Cloning the Latest Commit of a Specific Branch

If you only want to clone the latest commit of a specific branch (which results in a faster and smaller cloning operation) use `--depth 1`. The command formula looks like this:

```
git clone --single-branch --branch <branch_name> --depth 1 <repo_URL.git>
```

And here is another example using the `release-1.28` branch of the [Kubernetes GitHub repository](https://github.com/kubernetes/kubernetes/tree/release-1.28):

```
git clone --single-branch --branch release-1.28 --depth 1 https://github.com/kubernetes/kubernetes.git
```

## References
- https://www.freecodecamp.org/news/git-clone-branch-how-to-clone-a-specific-branch/
- https://git-scm.com/docs/git-clone