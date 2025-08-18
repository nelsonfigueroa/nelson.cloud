+++
title = "Delete All node_modules Directories Recursively in macOS and Linux"
summary = "How to delete all node_modules directories recursively in macOS and Linux systems."
date = "2023-05-14"
categories = ["Linux", "Shell", "Bash", "macOS", "Node"]
keywords = ["node_modules", "delete directories", "find command", "recursive delete", "Node.js", "npm cleanup", "disk space", "shell commands", "macOS", "Linux"]
+++

We can use the `find` command to delete a specific directory recursively. This is the command formula:

```shell
find /path/to/starting/directory/ -type d -name "directory_to_delete" -exec rm -rf {} \;
```

For example, if we wanted to delete all `node_modules` directories within the path `/projects/javascript/`, we would run:

```shell
find /projects/javascript/ -type d -name "node_modules" -exec rm -rf {} \;
```

I noticed sometimes the output says:

```
find: ./node_modules: No such file or directory
```

But the command should still work.

If you look closely, we're really just executing a command against all `node_modules` directories in the `/projects/javascript/` directory (`rm -rf`). This command can be modified to execute other commands against all `node_modules` directories, but that is out of the scope of this post :)
