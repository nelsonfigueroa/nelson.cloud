+++
title = "Replacing AWS ACM SSL Certificates With No Downtime"
summary = "Updating Amazon Certificate Manager SSL certificates with no downtime"
date = "2022-06-12"
categories = ["AWS"]

[cover]
image = "/opengraph-images/aws.png"
alt = "ASCII art of AWS logo."
caption = ""
+++

I recently learned that it is possible to replace an existing ACM SSL certificate on any AWS resource with no downtime.
The key is that the old certificate must exist while making SSL certificate updates to a resource. AWS does not allow users to delete a certificate that has resources associated. That means there is no risk of an in-use SSL certificate from being missing.

Here's what a resource associated to an ACM SSL certificate looks like. In this case it's a CloudFront Distribution:

<img src="/replacing-aws-acm-ssl-certificates-with-no-downtime/acm-cert-associated-resource.webp" alt="ACM SSL certificate associated to a CloudFront Distribution" width="720" height="298" style="max-width: 100%; height: auto; aspect-ratio: 864 / 358;" loading="lazy" decoding="async">

If I wanted to replace the SSL certificate on the CloudFront Distribution above, I would create a new certificate and associate it with the Distribution.
The updating process is then handled automatically by AWS. Since the old certificate still exists, there would be no downtime during the update. After updates are finished, I would be free to delete the old certificate.

This applies to any AWS resource that can be associated with an ACM SSL certificate.
