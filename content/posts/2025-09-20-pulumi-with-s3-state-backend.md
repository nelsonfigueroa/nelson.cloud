+++
title = "How to Use an AWS S3 Bucket as a Pulumi State Backend"
summary = "Create an S3 Bucket, create an IAM User, use IAM credentials with the Pulumi CLI."
date = "2025-09-20"
categories = ["Pulumi", "AWS"]
keywords = ["pulumi", "pulumi cloud", "pulumi S3 state backend", "pulumi aws s3 backend", "pulumi amazon web services", "pulumi CLI login", "pulumi login s3 bucket"]
ShowToc = true
TocOpen = true

[cover]
image = "/pulumi-with-s3-backend/pulumi-up-y.gif"
alt = "terminal output when running pulumi up -y"
caption = ""
+++

I'll be starting from scratch and creating an IAM user with access to an S3 bucket that will be used to store the Pulumi state file. If you're working in an enterprise setting, your authentication methods may vary.

> The TL;DR is that you can run this if you already have an S3 bucket and AWS credentials configured on your machine:
>```
>pulumi login 's3://<bucket-name>?region=<region>&awssdk=v2&profile=<aws-profile-name>'
>```

This post assumes you have the Pulumi CLI installed. Check out the following guide if you don't have it installed: [Download & install Pulumi](https://www.pulumi.com/docs/iac/download-install/).

## Creating an S3 Bucket

First, we need to create the S3 bucket where the Pulumi state file will be stored. I created a bucket called `nelsons-pulumi-state-backend` and left all the default settings as-is.

<img src="/pulumi-with-s3-backend/s3-bucket.webp" alt="S3 Bucket naming" width="720" height="391" style="max-width: 100%; height: auto; aspect-ratio: 1520 / 824;" loading="lazy" decoding="async">

## Creating an IAM User

Then we need to create an IAM user in AWS that the Pulumi CLI can use. This IAM user needs permissions to access the S3 bucket we just created.

I go to IAM and create a new user. I just called it `pulumi`:

<img src="/pulumi-with-s3-backend/iam-user.webp" alt="Creating an IAM user" width="720" height="434" style="max-width: 100%; height: auto; aspect-ratio: 1500 / 904;" loading="lazy" decoding="async">

Then in the next step, I selected "Attach policies directly" and selected the AWS-managed "AdministratorAccess" policy just to keep things simple. You can provide more fine-grained access depending on your needs. Then click "Next" at the bottom.

<img src="/pulumi-with-s3-backend/iam-permissions.webp" alt="IAM permissions" width="720" height="418" style="max-width: 100%; height: auto; aspect-ratio: 1968 / 1144;" loading="lazy" decoding="async">

In the next screen, double check everything and then click on "Create user".

<img src="/pulumi-with-s3-backend/iam-review.webp" alt="Reviewing IAM user" width="720" height="384" style="max-width: 100%; height: auto; aspect-ratio: 2540 / 1356;" loading="lazy" decoding="async">

Now that we have a user with the appropriate permissions, we'll need to get an AWS access key and secret to use with the Pulumi CLI.

Go to your IAM user and click on "Create access key" on the right side.

<img src="/pulumi-with-s3-backend/iam-user-view.webp" alt="The new IAM user" width="720" height="183" style="max-width: 100%; height: auto; aspect-ratio: 2348 / 596;" loading="lazy" decoding="async">

In the next screen, select "Command Line Interface (CLI)". Check the box at the bottom, then click "Next".

<img src="/pulumi-with-s3-backend/iam-access-key.webp" alt="Creating an access key" width="720" height="601" style="max-width: 100%; height: auto; aspect-ratio: 1836 / 1532;" loading="lazy" decoding="async">

The next screen will ask for setting a description tag. This is optional. I chose to skip it and clicked on "Create access key".

<img src="/pulumi-with-s3-backend/iam-description-tag.webp" alt="Access key description tag" width="720" height="206" style="max-width: 100%; height: auto; aspect-ratio: 1820 / 520;" loading="lazy" decoding="async">

We finally have our Access key and Secret access key. Save these somewhere safe and click "Done". (Don't worry, the credentials in the screenshot are fake.)

<img src="/pulumi-with-s3-backend/retrieve-access-keys.webp" alt="Retrieving AWS access keys" width="720" height="435" style="max-width: 100%; height: auto; aspect-ratio: 2244 / 1356;" loading="lazy" decoding="async">

## Setting Up AWS Credentials for the Pulumi CLI

Now we can try using these credentials to tell the Pulumi CLI to use the S3 bucket as a backend.

>Note that you do NOT need the [AWS CLI](https://aws.amazon.com/cli/) installed. Pulumi just needs the AWS credentials.

Create the file `~/.aws/credentials` if you don't have it. Then add in your credentials there under the `[default]` profile. (You can add more profiles, but this is beyond the scope of this post.)

```
[default]
aws_access_key_id = <key_id>
aws_secret_access_key = <access_key>
```

You'll need the bucket's region and your local AWS profile name to use S3 as a backend.

The command formula looks like this:

```sh
pulumi login 's3://<bucket-name>?region=<region>&awssdk=v2&profile=<aws-profile-name>'
```

In my case, the command looks like this (make sure to edit for your needs):

```sh
pulumi login 's3://nelsons-pulumi-state-backend?region=us-west-1&awssdk=v2&profile=default'
```

A successful login shows the following message:

```
Logged in to 0x6E.local as nelson (s3://nelsons-pulumi-state-backend?region=us-west-1&awssdk=v2&profile=default)
```

Alternatively, you can add your backend to your `Pulumi.yaml` file. This is useful if you're working on multiple Pulumi projects that each have different backends. You won't need to run `pulumi login` all the time. Just add a `backend` key and a nested `url` key:

```yaml
name: my-pulumi-project
description: a pulumi project for testing
runtime: nodejs

# add this section
backend:
  url: s3://nelsons-pulumi-state-backend?region=us-west-1&awssdk=v2&profile=default
```

More information here: [Pulumi project file reference](https://www.pulumi.com/docs/iac/concepts/projects/project-file/).

## Testing The Setup

Finally, it's time to test this out.


To demonstrate, I created a simple Pulumi program by running:

```sh
pulumi new aws-python
```

You can choose whatever language you want though.

This is the main Pulumi code that is generated. It's code for creating an S3 bucket:

```python
"""An AWS Python Pulumi program"""

import pulumi
from pulumi_aws import s3

# Create an AWS resource (S3 Bucket)
bucket = s3.Bucket('my-bucket')

# Export the name of the bucket
pulumi.export('bucket_name', bucket.id)
```

Then I ran `pulumi up -y` and it worked!

<img src="/pulumi-with-s3-backend/pulumi-up-y.gif" alt="terminal output when running pulumi up -y" width="720" height="475" style="max-width: 100%; height: auto; aspect-ratio: 3532 / 2329;" loading="lazy" decoding="async">

And just to double check, I can see that my previously empty S3 bucket now has contents created by the Pulumi CLI:

<img src="/pulumi-with-s3-backend/s3-bucket-with-contents.webp" alt="S3 Bucket with Pulumi state contents" width="720" height="274" style="max-width: 100%; height: auto; aspect-ratio: 2236 / 850;" loading="lazy" decoding="async">

## References
- https://www.pulumi.com/docs/iac/download-install/
- https://www.pulumi.com/registry/packages/aws/installation-configuration/
- https://www.pulumi.com/docs/iac/concepts/state-and-backends/#aws-s3
- https://www.pulumi.com/docs/iac/concepts/projects/project-file/
- https://ashoksubburaj.medium.com/pulumi-with-aws-s3-as-backend-ac79533820f1
