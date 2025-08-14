+++
title = "Ignore Files Across All Subdirectories in .dockerignore"
summary = "How to recursively ignore files when building Docker images."
date = "2022-11-01"
categories = ["Docker"]
keywords = ["Docker", "dockerignore", "ignore files", "Docker build", "recursive ignore", "Docker patterns", "build context", "Docker tutorial", "file exclusion", "container build"]
+++

Ignoring files within directories using `.dockerignore` is a bit different compared to `.gitignore`.

To ignore a file across all subdirectories, prefix the filename with `**`.
For example, to ignore the file `file.txt` in all subdirectories, add the following to `.dockerignore`:

```
**file.txt
```

Another example to ignore `.DS_Store` files on macOS devices:

```
**.DS_Store
```

To ignore a specific file extension across all subdirectories, prefix the file extension with `**/*`. In this example, all files with the `.txt` extension will be ignored by Docker:

```
**/*.txt
```


Also, keep in mind that `.dockerignore` should be in the root of the context you are passing in to Docker or it won't be taken into account.
