+++
title = "AWS SSM: Run a Shell Command Against Multiple EC2 Instances"
summary = "How to run a shell command against multiple EC2 instances using AWS Systems Manager"
date = "2024-01-18"
categories = ["AWS", "Shell"]
ShowToc = true
TocOpen = true

[cover]
image = "/opengraph-images/aws.png"
alt = "ASCII art of AWS logo."
caption = ""
+++

In AWS, you can use [AWS Systems Manager](https://aws.amazon.com/systems-manager/) to run commands on any EC2 Instances that have the [AWS Systems Manager Agent](https://docs.aws.amazon.com/systems-manager/latest/userguide/ssm-agent.html) installed. AWS Systems Manager has a lot more functionality than what I'm demonstrating here, but this is a specific use case.

## Shell Command Setup

To get started running a shell command on multiple EC2 Instances, head over to [AWS Systems Manager via the AWS Console](https://console.aws.amazon.com/systems-manager/home). Then, on the left side under "Node Management", click on "Run Command".

<img src="/run-a-shell-command-on-ec2-instances/node-management.webp" alt="Node Management menu" width="500" height="608" style="max-width: 100%; height: auto; aspect-ratio: 500 / 608;" loading="lazy" decoding="async">

On the next screen, click on the orange "Run a Command" button on the right.

<img src="/run-a-shell-command-on-ec2-instances/aws-ssm-run-command.webp" alt="AWS SSM home page" width="720" height="214" style="max-width: 100%; height: auto; aspect-ratio: 2040 / 608;" loading="lazy" decoding="async">

Next, under "Command document" search for "shell" to find `AWS-RunShellScript` and select it.

<img src="/run-a-shell-command-on-ec2-instances/command-document.webp" alt="AWS SSM command document" width="720" height="328" style="max-width: 100%; height: auto; aspect-ratio: 2704 / 1232;" loading="lazy" decoding="async">

Then, under "Command parameters" type in the shell command you want to run on your EC2 instances. In my case, I want to run security updates on my instances.

<img src="/run-a-shell-command-on-ec2-instances/command-parameters.webp" alt="AWS SSM command parameters" width="720" height="330" style="max-width: 100%; height: auto; aspect-ratio: 984 / 452;" loading="lazy" decoding="async">

Then select EC2 Instances by your preferred method under "Target Selection". I chose to manually select my instances but you can also select based on tags or resource groups. Make sure you are using the same region your EC2 instances are in!

<img src="/run-a-shell-command-on-ec2-instances/target-selection.webp" alt="AWS SSM target selection" width="720" height="333" style="max-width: 100%; height: auto; aspect-ratio: 2692 / 1248;" loading="lazy" decoding="async">

This part is optional. If you want log outputs of the commands that are run on every instance you can save logs to a S3 Bucket under "Output options". Make sure the Instance Profile IAM Role has permissions to write to this bucket!

<img src="/run-a-shell-command-on-ec2-instances/output-options.webp" alt="AWS SSM output options" width="720" height="269" style="max-width: 100%; height: auto; aspect-ratio: 2688 / 1006;" loading="lazy" decoding="async">

I left all other settings with their default values.

Now we can click the orange "Run" button on the bottom of the page to execute our command.

## After Running a Command

After running a command you'll see the following screen. You can see the status of the command execution on each EC2 instance to ensure everything went fine. You can also see the Command ID here which will come in handy if you enabled S3 logging.

<img src="/run-a-shell-command-on-ec2-instances/command-id.webp" alt="SSM screen after execution" width="720" height="261" style="max-width: 100%; height: auto; aspect-ratio: 2708 / 984;" loading="lazy" decoding="async">

### Viewing Logs

If you enabled logging to S3, you can browse to the bucket you selected and view logs. The output log file will be several directories deep under `<Command ID>/<ec2-instance-id/<command document>/...`. It's easier if you refer to the screenshot below:

<img src="/run-a-shell-command-on-ec2-instances/s3-logs.webp" alt="AWS SSM output logs" width="720" height="324" style="max-width: 100%; height: auto; aspect-ratio: 2444 / 1100;" loading="lazy" decoding="async">

I downloaded the `stdout` file locally and viewed it via the command line to confirm that my command executed properly on this particular EC2 instance:

```
$ cat stdout

Last metadata expiration check: 0:15:25 ago on Thu Jan 18 21:10:06 2024.
Dependencies resolved.
Nothing to do.
Complete!
```

## Troubleshooting

I personally ran into some issues when trying to do this for the first time. Here are a few troubleshooting solutions.

### EC2 Instances Don't Show Up in Systems Manager

If your EC2 Instances do not show up under "Target selection" they may not have the SSM agent installed. AWS has documentation to guide you through the installation process: [Manually installing SSM Agent on EC2 instances for Linux](https://docs.aws.amazon.com/systems-manager/latest/userguide/sysman-manual-agent-install.html).

If your EC2 Instances do have the SSM agent installed but still aren't showing up, they may not have an IAM instance profile. You can confirm this by clicking on an EC2 Instance and then checking under the "IAM Role" field. If this field is empty, assign an IAM Role and ensure it has proper permissions. In my case, I used to AWS managed policies `AmazonSSMManagedInstanceCore` and `AmazonSSMPatchAssociation`.

<img src="/run-a-shell-command-on-ec2-instances/permission-policies.webp" alt="AWS IAM permission policies" width="720" height="299" style="max-width: 100%; height: auto; aspect-ratio: 1500 / 624;" loading="lazy" decoding="async">

(The inline policy in the screenshot is to grant this Role S3 permissions for logging purposes which I cover in the next section.)

### No Log Output to S3

If you see no log output in your S3 bucket, you may need to add proper permissions to the instance profile IAM Role of the EC2 instances you selected.

Here is the policy I attached to the IAM instance profile role that got logging to work for me. Change the bucket name `my-bucket` accordingly!

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "s3:PutObject",
        "s3:GetBucketAcl",
        "s3:GetBucketLocation",
        "s3:GetObject",
        "s3:ListBucket"
      ],
      "Resource": [
        "arn:aws:s3:::my-bucket",
        "arn:aws:s3:::my-bucket/*"
      ]
    }
  ]
}
```
