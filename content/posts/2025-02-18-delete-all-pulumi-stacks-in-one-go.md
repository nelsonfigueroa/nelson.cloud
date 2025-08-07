+++
title = "Delete All Pulumi Stacks with One Command"
summary = "How to delete all pulumi stacks with a shell one-liner."
date = "2025-02-18"
lastmod = "2025-02-18"
categories = ["Shell", "Pulumi"]
keywords = ["Pulumi stacks", "Pulumi commands", "delete Pulumi stacks", "Pulumi CLI", "infrastructure as code", "Pulumi automation", "shell scripting", "Pulumi management", "cloud infrastructure"]
+++

There currently isn't a way to delete all stacks with `pulumi stack rm` so this is an alternative way to achieve that.

## Delete All Stacks in the Current Project

To delete all Pulumi stacks in the current Pulumi project you can run the following command:

```shell
pulumi stack ls | tail -n +2 | tr -d "*" | awk '{print $1}' | while read -r stack; do pulumi stack rm -y "$stack"; done;
```

## Delete All Stacks Across All Projects

**Be careful when doing this.**

To delete all Pulumi stacks across all Pulumi projects we need to use `pulumi stack ls -a` instead of `pulumi stack ls`. So the full command is:

```shell
pulumi stack ls -a | tail -n +2 | tr -d "*" | awk '{print $1}' | while read -r stack; do pulumi stack rm -y "$stack"; done;
```

## Command Break Down

List pulumi stacks (use `-a` option for all stacks across all projects):
```shell
pulumi stack ls
```

Start at the second line of the previous output:
```shell
tail -n +2
```

Delete all occurrences of `*`. There is a `*` character next to the currently selected stack and we need to remove this:
```shell
tr -d "*"
```

Print only the first column:
```shell
awk '{print $1}'
```

This is a loop in one-liner format. It reads the previous output line by line and assigns each line to an array called `stack`, then runs the command `pulumi stack rm -y` on each stack.
```shell
while read -r stack; do pulumi stack rm -y "$stack"; done;
```

## References
- https://stackoverflow.com/questions/7318497/omitting-the-first-line-from-any-linux-command-output
- https://www.pulumi.com/docs/iac/cli/commands/pulumi_stack_ls/
- https://www.pulumi.com/docs/iac/cli/commands/pulumi_stack_rm/