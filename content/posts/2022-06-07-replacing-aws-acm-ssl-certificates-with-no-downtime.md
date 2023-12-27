+++
title = "Replacing AWS ACM SSL Certificates With No Downtime"
summary = "Updating Amazon Certificate Manager SSL certificates with no downtime"
date = "2022-06-12"
lastmod = "2022-06-12"
categories = ["AWS"]
toc = false
+++

It is possible to replace an existing ACM SSL certificate on any AWS resource with no downtime.
The key is that the old certificate must exist while making SSL certificate updates to a resource. AWS does not allow users to delete a certificate that has resources associated. That means there is no risk of an in-use SSL certificate from being missing.

Here's what a resource associated to an ACM SSL certificate looks like:

{{< figure src="/replacing_aws_acm_ssl_certificates_with_no_downtime/acm_cert_associated_resource.png" alt="ACM SSL certificate associated to a CloudFront Distribution" >}}

If I wanted to replace the SSL certificate on the CloudFront Distribution above, I would create a new certificate and associate it with the Distribution.
The updating process is then handled automatically by AWS. Since the old certificate still exists, there would be no downtime during the update. After updates are finished, I would be free to delete the old certificate.

This applies to any AWS resource that can be associated with an ACM SSL certificate.