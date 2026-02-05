+++
title = "Getting EC2 Instance Metadata Using IMDSv2"
summary = "How to get EC2 Instance metadata using IMDSv2"
date = "2023-12-01"
categories = ["AWS"]

[cover]
image = "/opengraph-images/aws.png"
alt = "ASCII art of AWS logo."
caption = ""
+++

The EC2 Instance Metadata Service (IMDS) allows us to make an API call within an EC2 instance to retrieve instance metadata, such as a local IP address. There are 2 versions of IMDS.

Using IMDSv1, all we needed to do was to hit the `http://169.254.169.254/latest/meta-data/` endpoint to retrieve metadata. I previously created a post with some examples: [Getting EC2 Instance Metadata Using IMDSv1]({{< ref "/posts/2022-02-08-ec2-metadata-using-imdsv1" >}}).

Using IMDSv2, we now need to make an API call to `http://169.254.169.254/latest/api/token` to retrieve a token, then include that token in a `X-aws-ec2-metadata-token` header to hit the metadata endpoint `http://169.254.169.254/latest/meta-data/`.

During EC2 creation you can configure your instance to use IMDSv2. This is what the setting looks like under "Advanced details" when creating an EC2 Instance. As of December 2023, it defaulted to "V2 only (token required)":
<img src="/getting-ec2-metadata-using-imdsv2/imdsv2.webp" alt="AWS EC2 creation process" width="720" height="414" style="max-width: 100%; height: auto; aspect-ratio: 1140 / 656;" loading="lazy" decoding="async">

Once IMDSv2 is enabled on an instance, you can SSH into your instance and start making API calls from within.

## Useful Examples

Here are some examples I've found useful in my career.

View all available categories of metadata:

```shell
TOKEN=`curl -s -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600"` \
&& curl -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/

#---- output ----#
ami-id
ami-launch-index
ami-manifest-pathblock-device-mapping/
events/
hostname
iam/
identity-credentials/
instance-action
instance-id
instance-life-cycleinstance-type
local-hostname
local-ipv4
mac
metrics/
network/
placement/
profile
public-hostname
public-ipv4
reservation-id
security-groups
services/
````

Get instance AMI ID:

```shell
TOKEN=`curl -s -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600"` \
&& curl -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/ami-id

#---- output ----#
ami-0230bd60aa48260c6
```

Get hostname:

```shell
TOKEN=`curl -s -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600"` \
&& curl -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/hostname

#---- output ----#
ip-172-31-42-147.ec2.internal
```

Get instance ID:

```shell
TOKEN=`curl -s -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600"` \
&& curl -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/instance-id

#---- output ----#
i-0bca16d8b48523ac7
```

Get instance type:

```shell
TOKEN=`curl -s -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600"` \
&& curl -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/instance-type

#---- output ----#
t2.micro
```

Get local IPv4 address:

```shell
TOKEN=`curl -s -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600"` \
&& curl -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/local-ipv4

#---- output ----#
172.31.42.147
```

Get AWS account ID:

```shell
TOKEN=`curl -s -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600"` \
&& curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/identity-credentials/ec2/info | grep "AccountId" | awk -F\" '{print $4}'

#---- output ----#
123456789012
```

Get MAC address:

```shell
TOKEN=`curl -s -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600"` \
&& curl -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/mac

#---- output ----#
0e:53:26:7a:45:0b
```

Get availability zone:

```shell
TOKEN=`curl -s -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600"` \
&& curl -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/placement/availability-zone

#---- output ----#
us-east-1c
```

Get availability zone ID:

```shell
TOKEN=`curl -s -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600"` \
&& curl -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/placement/availability-zone-id

#---- output ----#
use1-az6
```

Get security groups associated with the instance:

```shell
TOKEN=`curl -s -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600"` \
&& curl -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/security-groups

#---- output ----#
my-security-group-1
my-security-group-2
my-security-group-3
```

## Bash Script

Copy and paste this Bash snippet and use the assigned values as needed:

```bash
TOKEN=$(curl -s -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600")

ACCOUNT_ID=$(curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/identity-credentials/ec2/info | grep "AccountId" | awk -F\" '{print $4}')
AMI_ID=$(curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/ami-id)
AVAILABILITY_ZONE=$(curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/placement/availability-zone)
AVAILABILITY_ZONE_ID=$(curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/placement/availability-zone-id)
HOSTNAME=$(curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/hostname)
INSTANCE_ID=$(curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/instance-id)
INSTANCE_TYPE=$(curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/instance-type)
LOCAL_IPV4=$(curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/local-ipv4)
MAC_ADDRESS=$(curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/mac)
SECURITY_GROUPS=$(curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/security-groups)

echo $ACCOUNT_ID
echo $AMI_ID
echo $AVAILABILITY_ZONE
echo $AVAILABILITY_ZONE_ID
echo $HOSTNAME
echo $INSTANCE_ID
echo $INSTANCE_TYPE
echo $LOCAL_IPV4
echo $MAC_ADDRESS
echo $SECURITY_GROUPS
```

## References

- https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/instancedata-data-retrieval.html
