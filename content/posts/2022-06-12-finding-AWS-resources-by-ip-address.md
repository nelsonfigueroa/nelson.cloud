+++
title = "Finding AWS Resources by IP Address"
summary = "Find AWS Resources by private or public IP addresses."
date = "2022-06-12"
lastmod = "2022-06-12"
categories = ["AWS"]
ShowToc = true
TocOpen = true
+++

## Finding EC2 Instances by IP Address

### In the EC2 Console

In the AWS EC2 Management Console, you can search for EC2 instances using a private or public IP address. Filter by either `Private IP address` or `Public IPv4 address` in the search field:

{{< figure src="/finding-aws-resources-by-ip-address/ec2-private-ip-search.webp" alt="Results after searching for an EC2 Instance with a private IP address." >}}
{{< figure src="/finding-aws-resources-by-ip-address/ec2-public-ip-search.webp" alt="Results after searching for an EC2 Instance with a public IP address." >}}

### Using the AWS CLI

The AWS CLI can be used to find EC2 instances by either private or public IP address.

#### Using a Private IP Address

To find EC2 instances by private IP address, the command looks like this (Replace `--region` with your region if it's not set by default. Replace `Values` with the IP address):

```bash
aws ec2 describe-instances --region=us-west-1 --filter Name=private-ip-address,Values=10.0.0.1
```

#### Using a Public IP Address

To find EC2 instances by public IP address, the `Name` filter changes to `ip-address` but otherwise the command is the same as the one from above:

```bash
aws ec2 describe-instances --region=us-west-1 --filter Name=ip-address,Values=10.0.0.1
```

#### Specifying Multiple IP Addresses

For either of these commands, you can specify several IP addresses by adding them to the `Values` filter as such:

```bash
aws ec2 describe-instances --region=us-west-1 --filter Name=private-ip-address,Values=10.0.0.1,10.0.0.2,10.0.0.3
```

Example Output

The output for these commands will look something like this:

```json
{
    "Reservations": [
        {
            "Groups": [],
            "Instances": [
                {
                    "AmiLaunchIndex": 0,
                    "ImageId": "ami-1234abcd",
                    "InstanceId": "i-001122334455abcd",
                    "InstanceType": "t3.micro",
                    "KeyName": "key-name",
                    "LaunchTime": "2022-01-01T00:00:00.000Z",
                    "Monitoring": {
                        "State": "enabled"
                    },
                    "Placement": {
                        "AvailabilityZone": "us-west-1a",
                        "GroupName": "",
                        "Tenancy": "default"
                    },
                    "PrivateDnsName": "ip-10-0-0-1.ec2.internal",
                    "PrivateIpAddress": "10.0.0.1",
                    "ProductCodes": [],
                    "PublicDnsName": "",
                    "State": {
                        "Code": 16,
                        "Name": "running"
                    },
                    "StateTransitionReason": "",
                    "SubnetId": "subnet-1234abcd",
                    "VpcId": "vpc-1234abcd",
                    "Architecture": "x86_64",
                    "BlockDeviceMappings": [
                        {
                            "DeviceName": "/dev/xvda",
                            "Ebs": {
                                "AttachTime": "2022-01-01T00:00:00.000Z",
                                "DeleteOnTermination": true,
                                "Status": "attached",
                                "VolumeId": "vol-012345abcdef"
                            }
                        }
                    ],
                    "ClientToken": "00000000",
                    "EbsOptimized": false,
                    "Hypervisor": "xen",
                    "IamInstanceProfile": {
                        "Arn": "arn:aws:iam::0000000000",
                        "Id": "ABCDEF12345"
                    },
                    "NetworkInterfaces": [
                        {
                            "Attachment": {
                                "AttachTime": "2022-00-00T00:00:00.000Z",
                                "AttachmentId": "eni-attach-abcd1234",
                                "DeleteOnTermination": true,
                                "DeviceIndex": 0,
                                "Status": "attached",
                                "NetworkCardIndex": 0
                            },
                            "Description": "",
                            "Groups": [
                                {
                                    "GroupName": "security-group-1",
                                    "GroupId": "sg-abc123"
                                },
                                {
                                    "GroupName": "security-group-2",
                                    "GroupId": "sg-def456"
                                }
                            ],
                            "Ipv6Addresses": [],
                            "MacAddress": "aa:00:bb:22:cc:33",
                            "NetworkInterfaceId": "eni-abcd1234",
                            "OwnerId": "112233445566",
                            "PrivateDnsName": "ip-10-0-0-1.ec2.internal",
                            "PrivateIpAddress": "10.0.0.1",
                            "PrivateIpAddresses": [
                                {
                                    "Primary": true,
                                    "PrivateDnsName": "ip-10-0-0-1.ec2.internal",
                                    "PrivateIpAddress": "10.0.0.1"
                                }
                            ],
                            "SourceDestCheck": true,
                            "Status": "in-use",
                            "SubnetId": "subnet-abcd1234",
                            "VpcId": "vpc-abcd1234",
                            "InterfaceType": "interface"
                        }
                    ],
                    "RootDeviceName": "/dev/xvda",
                    "RootDeviceType": "ebs",
                    "SecurityGroups": [
                        {
                            "GroupName": "security-group-1",
                            "GroupId": "sg-123abc"
                        },
                        {
                            "GroupName": "security-group-2",
                            "GroupId": "sg-456def"
                        }
                    ],
                    "SourceDestCheck": true,
                    "Tags": [

                        {
                            "Key": "App",
                            "Value": "Testing App"
                        }
                    ],
                    "VirtualizationType": "hvm",
                    "CpuOptions": {
                        "CoreCount": 1,
                        "ThreadsPerCore": 2
                    },
                    "CapacityReservationSpecification": {
                        "CapacityReservationPreference": "open"
                    },
                    "HibernationOptions": {
                        "Configured": false
                    },
                    "MetadataOptions": {
                        "State": "applied",
                        "HttpTokens": "optional",
                        "HttpPutResponseHopLimit": 1,
                        "HttpEndpoint": "enabled",
                        "HttpProtocolIpv6": "disabled",
                        "InstanceMetadataTags": "disabled"
                    },
                    "EnclaveOptions": {
                        "Enabled": false
                    },
                    "PlatformDetails": "Linux/UNIX",
                    "UsageOperation": "RunInstances",
                    "UsageOperationUpdateTime": "2022-01-01T00:00:00.000Z",
                    "PrivateDnsNameOptions": {},
                    "MaintenanceOptions": {
                        "AutoRecovery": "default"
                    }
                }
            ],
            "OwnerId": "112233445566",
            "RequesterId": "112233445566",
            "ReservationId": "r-012345abcdef"
        }
    ]
}
```


## Finding Other Resources by IP Address

### In the AWS Console

To identify other AWS resources (such as Lambdas) based on IP address, you can search for the corresponding ENI.
In the AWS Console, browse to the EC2 console and click on Network Interfaces on the left hand side. Then search by "Primary private IPv4 address" (or "Public IPv4 address" if you want to search by a public IP address).

{{< figure src="/finding-aws-resources-by-ip-address/eni-search.webp" alt="Results after searching for Elastic Network Interface using a private IP address" >}}

You can then poke around through the ENI details to figure out what resource is associated with the IP address.

### Using the AWS CLI

This can also be done using the AWS CLI with the following command, replacing `--region` and `Values` as needed:

```bash
aws ec2 describe-network-interfaces --region=us-west-1 --filters Name=addresses.private-ip-address,Values=10.0.0.1
```

Here's what the output looks like

```json
{
    "NetworkInterfaces": [
        {
            "Attachment": {
                "AttachmentId": "ela-attach-12345abcde",
                "DeleteOnTermination": false,
                "DeviceIndex": 1,
                "InstanceOwnerId": "amazon-aws",
                "Status": "attached"
            },
            "AvailabilityZone": "us-west-1a",
            "Description": "Test AWS Lambda",
            "Groups": [
                {
                    "GroupName": "test-group",
                    "GroupId": "sg-01234abcde"
                }
            ],
            "InterfaceType": "lambda",
            "Ipv6Addresses": [],
            "MacAddress": "00:aa:11:bb:22:cc",
            "NetworkInterfaceId": "eni-012345abcdef",
            "OwnerId": "112233445566",
            "PrivateDnsName": "ip-10-0-0-1.ec2.internal",
            "PrivateIpAddress": "10.0.0.1",
            "PrivateIpAddresses": [
                {
                    "Primary": true,
                    "PrivateDnsName": "ip-10-0-0-1.ec2.internal",
                    "PrivateIpAddress": "10.0.0.1"
                }
            ],
            "RequesterId": "AAABBBCCCDDDEEEFFF:112233445566",
            "RequesterManaged": false,
            "SourceDestCheck": true,
            "Status": "in-use",
            "SubnetId": "subnet-1234abcd",
            "TagSet": [
                {
                    "Key": "App",
                    "Value": "Lambda Test"
                }
            ],
            "VpcId": "vpc-1234abcd"
        }
    ]
}
```

Check the value of `InterfaceType` for clues as to what resource is using the ENI. In this case, it's a Lambda function.

## References

Thanks to this Serverfault post for information regarding EC2 Instances:
[https://serverfault.com/questions/710931/is-it-possible-to-get-aws-ec2-instance-id-based-on-its-ip-address](https://serverfault.com/questions/710931/is-it-possible-to-get-aws-ec2-instance-id-based-on-its-ip-address)

Thanks to this AWS support page for the information regarding ENIs:
[How can I find the resource that owns the unknown IP addresses in my Amazon VPC?](https://aws.amazon.com/premiumsupport/knowledge-center/vpc-find-owner-unknown-ip-addresses/)
