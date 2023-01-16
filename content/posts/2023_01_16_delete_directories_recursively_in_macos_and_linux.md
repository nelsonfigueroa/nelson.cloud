+++
title = "Delete Directories Recursively in MacOS and Linux"
summary = "How to delete directories recursively in MacOS and Linux systems."
date = "2023-01-16"
categories = ["Linux", "Shell", "Bash", "MacOS"]
toc = false
+++

To delete directories recursively, run the following command while replacing `/path/goes/here/` and `directory_name` as needed:

```
$ find /path/goes/here/ -type d -name "directory_name" -exec rm -rf {}
```

For example, if we wanted to delete all `node_modules` directories within the path `/projects/javascript/`, we would run:

```
$ find /projects/javascript/ -type d -name "node_modules" -exec rm -rf {}
```

If you look closely, we're really just executing a command against all `node_modules` directories in the `/projects/javascript/` directory (`rm -rf`). This command can be modified to execute other commands against all directories, but that is out of the scope of this post :)