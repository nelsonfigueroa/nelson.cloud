+++
title = "Getting EC2 Instance Metadata Using IMDSv1"
summary = "How to get EC2 Instance metadata using IMDSv1"
date = "2022-02-08"
lastmod = "2023-12-04"
categories = ["AWS"]
toc = false
+++

Sometimes I need to get information about an EC2 instance within a script but the [AWS documentation](https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/instancedata-data-retrieval.html) doesn't provide many useful examples.
Instead of poking around through all the available metadata endpoints, I made this list of curl commands to retrieve commonly used EC2 information.
Run any of these commands within an EC2 instance. These commands have been tested on an Amazon Linux 2 instance.

These are not all the possible values you can retrieve from the metadata service, these are only the ones I found most useful.

Note that these commands are using Instance Metadata Service Version 1 (IMDSv1) which use a simple request/response method. I created a separate post covering IMDSv2: [Getting EC2 Instance Metadata Using IMDSv2]({{< relref "/posts/2023-12-01-ec2-metadata-using-imdsv2" >}})

If you want a bash script that you can copy and paste, scroll down to the bottom of this article.

## Metadata Retrieval with curl

View all available categories of metadata:

```sh
$ curl -s http://169.254.169.254/latest/meta-data/

ami-id
ami-launch-index
ami-manifest-path
block-device-mapping/
events/
hibernation/
hostname
iam/
identity-credentials/
instance-action
instance-id
instance-life-cycle
instance-type
local-hostname
local-ipv4
mac
metrics/
network/
placement/
profile
public-keys/
reservation-id
security-groups
services/
```

Get instance AMI ID:

```sh
$ curl -s http://169.254.169.254/latest/meta-data/ami-id

ami-0d43f810ac49e9511
```

Get hostname:

```sh
$ curl -s http://169.254.169.254/latest/meta-data/hostname

ip-10-128-128-128.us-west-1.compute.internal
```

Get AWS account ID:

```sh
$ curl -s http://169.254.169.254/latest/meta-data/identity-credentials/ec2/info | grep "AccountId" | awk -F\" '{print $4}'

123456789012
```

Get instance ID:

```sh
$ curl -s http://169.254.169.254/latest/meta-data/instance-id

i-01234567890f1234b
```

Get instance type

```sh
$ curl -s http://169.254.169.254/latest/meta-data/instance-type

t2.medium
```

Get IPv4 address:

```sh
$ curl -s http://169.254.169.254/latest/meta-data/local-ipv4

10.128.128.128
```

Get MAC address:

```sh
$ curl -s http://169.254.169.254/latest/meta-data/mac

05:5f:bd:1a:4c:77
```

Get availability zone:

```sh
$ curl -s http://169.254.169.254/latest/meta-data/placement/availability-zone

us-west-1a
```

Get availability zone ID:

```sh
$ curl -s http://169.254.169.254/latest/meta-data/placement/availability-zone-id

usw1-az1
```

Get security groups associated with the instance:

```sh
$ curl -s http://169.254.169.254/latest/meta-data/security-groups

my-security-group-1
my-security-group-2
my-security-group-3
```

## Bash Script

Copy and paste this bash snippet and use values as needed:

```sh
AMI_ID=$(curl -s http://169.254.169.254/latest/meta-data/ami-id)
HOSTNAME=$(curl -s http://169.254.169.254/latest/meta-data/hostname)
ACCOUNT_ID=$(curl -s http://169.254.169.254/latest/meta-data/identity-credentials/ec2/info | grep "AccountId" | awk -F\" '{print $4}')
INSTANCE_ID=$(curl -s http://169.254.169.254/latest/meta-data/instance-id)
INSTANCE_TYPE=$(curl -s http://169.254.169.254/latest/meta-data/instance-type)
LOCAL_IPV4=$(curl -s http://169.254.169.254/latest/meta-data/local-ipv4)
MAC_ADDRESS=$(curl -s http://169.254.169.254/latest/meta-data/mac)
AVAILABILITY_ZONE=$(curl -s http://169.254.169.254/latest/meta-data/placement/availability-zone)
AVAILABILITY_ZONE_ID=$(curl -s http://169.254.169.254/latest/meta-data/placement/availability-zone-id)
SECURITY_GROUPS=$(curl -s http://169.254.169.254/latest/meta-data/security-groups)

echo $AMI_ID
echo $HOSTNAME
echo $ACCOUNT_ID
echo $INSTANCE_ID
echo $INSTANCE_TYPE
echo $LOCAL_IPV4
echo $MAC_ADDRESS
echo $AVAILABILITY_ZONE
echo $AVAILABILITY_ZONE_ID
echo $SECURITY_GROUPS
```
