+++
title = "GitHub Actions for Pulumi with an AWS S3 Backend"
summary = "How to set up GitHub Actions for Pulumi when the state is stored in an AWS S3 Bucket."
date = "2025-12-11"
categories = ["Pulumi", "AWS", "GitHub"]
ShowToc = false
TocOpen = false
+++

## Introduction

This is a quick guide to set up GitHub Actions for Pulumi with an [AWS S3](https://aws.amazon.com/s3/) Backend. There are some differences compared to running `pulumi` commands locally. This guide assumes you have the following:
- An AWS S3 Bucket created and ready to be used with Pulumi
- An IAM User that has permissions to read/write to the S3 bucket
- The Access Key and Secret Access Key for the IAM User to use for authenticating to AWS within GitHub Actions
- A passphrase of your choosing that will be used to encrypt secrets in the pulumi stack
- A GitHub repository

## Setting up Repository Secrets

First, set up secrets on your GitHub repository. These will be filled in by GitHub Actions once we create a workflow YAML. You'll need to create 3 secrets. You can name them whatever you want, but I'll be naming them:
- `AWS_ACCESS_KEY_ID`
- `AWS_SECRET_ACCESS_KEY`
- `PULUMI_CONFIG_PASSPHRASE`

You can create these by browsing to your GitHub repository > Settings > Secrets and variables > Actions. There are "Environment secrets" and "Repository secrets". In this case go with "Repository secrets".

Create the three secrets and fill in their respective values. `PULUMI_CONFIG_PASSPHRASE` can be whatever you want and doesn't come from AWS. **Make sure you don't change this after the fact though, or your Pulumi state may break**. The end result should look like this:

<img src="/github-actions-with-pulumi-s3/github-secrets.webp" alt="GitHub repository secrets showing AWS credentials and Pulumi passphrase" width="3096" height="1708" style="max-width: 100%; height: auto; aspect-ratio: 774 / 427;" loading="lazy" decoding="async">

## Defining the GitHub Actions Workflow

Next, clone the repository locally with `git clone`. We'll need to create a few files for a minimum viable Pulumi program. Run `pulumi new` and it'll guide you through the creation of a basic Pulumi program. The language you choose doesn't matter.

Then we can create the YAML file to set up GitHub Actions. Create a YAML file under `<your-repository-name>/.github/workflows/`. I'll call it `preview.yml` in my example. Fill it in with the following YAML, changing values as needed.

```yaml
name: Pulumi
on:
  push:
    branches:
      - main # change this if you're using a different branch name

jobs:
  preview:
    name: Preview
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Pulumi Preview
        uses: pulumi/actions@v6.6.1
        with:
          command: preview
          stack-name: dev # change this to your stack's name (or whatever you want it to be if it doesn't exist)
          cloud-url: s3://nelson-test-bucket-for-github-actions # change this to your bucket name to be used as a pulumi backend
          upsert: true # creates a stack if it doesn't exist (along with Pulumi.<stack>.yaml)
        env:
          AWS_ACCESS_KEY_ID: ${{ secrets.AWS_ACCESS_KEY_ID }}
          AWS_SECRET_ACCESS_KEY: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          PULUMI_CONFIG_PASSPHRASE: ${{ secrets.PULUMI_CONFIG_PASSPHRASE }}
          AWS_REGION: us-east-1 # change the region as needed
```

This runs a `pulumi preview` so it'll veryify that everything is set up correctly without actually deplpoying anything.

## Testing the Workflow

Now push your code to GitHub and see if the GitHub Action workflow ran successfully. You should see output from a successful `pulumi preview`.

<img src="/github-actions-with-pulumi-s3/github-actions-logs.webp" alt="GitHub Actions workflow logs showing successful Pulumi preview" width="2854" height="1632" style="max-width: 100%; height: auto; aspect-ratio: 1427 / 816;" loading="lazy" decoding="async">

If this works, you should be good to go. You can update your Pulumi code and, change `command: preview` to `command: up` in the GitHub Actions YAML file and run it again to actually deploy some infrastructure.

## References
- https://github.com/pulumi/actions
