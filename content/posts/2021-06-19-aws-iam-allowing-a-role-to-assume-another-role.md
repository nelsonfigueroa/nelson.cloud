+++
title = "AWS IAM: Allowing a Role to Assume Another Role"
summary = "How to allow an IAM Role to assume another Role."
date = "2021-06-19"
categories = ["AWS"]
keywords = ["AWS IAM", "IAM roles", "role assumption", "trust relationship", "AWS security", "cross-account roles", "AssumeRole", "IAM policies", "AWS permissions", "role-based access"]
+++

To allow an IAM Role to assume another Role, we need to modify the **trust relationship** of the role that is to be assumed. This process varies depending if the roles exist within the same account or if they're in separate accounts.

## Roles in the Same Account

Let's say we have two roles, `Role_A` and `Role_B`. If we want to allow `Role_A` to assume `Role_B`, we need to modify the **trust relationship** of `Role_B` with the following:

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Principal": {
        "AWS": "arn:aws:iam::111111111111:role/Role_A"
      },
      "Action": "sts:AssumeRole"
    }
  ]
}
```

This is all that's needed to allow a role to assume another role within the same account.

> Note the `Principal` element where we specify the role that we want to give permissions to. In general, the `Principal` element is used in policies to give users/roles/services access to other AWS resources. However, the `Principal` element cannot be used in policies attached to Roles. It can only exist in the trust relationships of roles (you'll get errors if you try to use the `Principal` element in an IAM Role policy). You can read more about this element in the [AWS docs](https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_policies_elements_principal.html).


## Roles in Different Accounts

Let's say `Role_A` and `Role_B` are in different accounts. In this case, the process from above stays the same. `Role_B` needs to have its trust relationship modified to allow `Role_A` to assume it. The difference here is that `Role_A` will need an additional policy with `sts:AssumeRole` permissions. So the final result is as follows:

The `Role_B` trust relationship stays the same:

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Principal": {
        "AWS": "arn:aws:iam::111111111111:role/Role_A"
      },
      "Action": "sts:AssumeRole"
    }
  ]
}
```

And `Role_A` needs the following attached as a policy:

```json
{
  "Version": "2012-10-17",
  "Statement": {
    "Effect": "Allow",
    "Action": "sts:AssumeRole",
    "Resource": "arn:aws:iam::222222222222:role/Role_B"
  }
}
```

Now `Role_A` will be able to assume `Role_B` even if they're in different accounts.
