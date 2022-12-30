+++
title = "Hosting a Static Website on AWS Using S3 and CloudFront"
summary = "How to host a static website on Amazon Web Services using S3 and CloudFront"
date = "2022-12-30"
categories = ["AWS"]
toc = true
+++

## Overview

Using Amazon Web Services, we can easily set up a static site worldwide without the need to maintain servers and load balancers.

In this guide, I'll go over how I set up this site on AWS. I used the following AWS services:
- [S3](https://aws.amazon.com/s3/)
- [Certificate Manager](https://aws.amazon.com/certificate-manager/)
- [CloudFront](https://aws.amazon.com/cloudfront/)
- [Route 53](https://aws.amazon.com/route53/)

I recently migrated my domain from `nelsonfigueroa.dev` to `nelson.cloud`, so I'll be using this new domain as an example.

This guide assumes the following:
- You have already purchased a domain from a registrar like [Namecheap](https://www.namecheap.com/), [Gandi](https://www.gandi.net/), or [Porkbun](https://porkbun.com/).
- You have already signed up for an Amazon Web Services account.

## Creating an S3 Bucket

First, we'll need to create an S3 bucket. This is where static files will be placed (HTML/CSS/JavaScript).

This bucket is where static files (HTML/CSS/JavaScript/Images, etc) will go.

In the AWS console, browse to S3. Create a new bucket. I named my bucket with the same name as my domain.

Then click on the bucket and go to the "properties" tab. Scroll all the way to the bottom to the "Static website hosting" section. Click the "Edit" button. Now configure this bucket to host a static site:
1. Under "Static website hosting" select "Enable"
2. Under "Hosting type" select "Host a static website"
3. In the "Index document" field, write in "index.html" (unless you want a different root page for your site)
4. Then click "Save changes"

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/s3_static_website_hosting.png" alt="Static website hosting configuration." >}}

Then go to the "Permissions" tab in the bucket. Click the "Edit" button under the "Block public access (bucket settings)" section.
Uncheck the "Block *all* public access" checkbox. We need this bucket to have public access so the site is viewable across the internet.
Save changes.

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/bucket_public_access.png" alt="S3 bucket access." >}}

Next, still under the "Permissions tab", click the "Edit" button on the "Bucket policy" section. We need to add permissions for public access. Here is what the policy should look like. Change the bucket name after `Resource` to your bucket name:

```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "PublicReadGetObject",
            "Effect": "Allow",
            "Principal": "*",
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::nelson.cloud/*"
        }
    ]
}
```

Then save changes.

At this point "Block *all* public access" should be off, and the bucket policy should show under the section:

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/completed_bucket_permissions.png" alt="Completed S3 bucket configuration." >}}

To verify that everything works, we can upload some HTML files to test out the site.

Go to the "Objects" tab on the bucket and drop in your static files. Even a single `index.html` file will do. In my case I have a lot more files since my site has blog posts and other things:

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/bucket_objects.png" alt="Objects in S3 bucket." >}}

Then go to the "Properties" tab and at the very bottom you should see a "Bucket website endpoint". Click on this link and your site should open in your browser. If you see the contents of your HTML file(s) then you are all done here with S3.

The AWS docs provide more information about S3 permissions for static hosting if you're curious: 
- https://docs.aws.amazon.com/AmazonS3/latest/userguide/WebsiteAccessPermissionsReqd.html

## Creating an ACM SSL Certificate

Next we need to request an SSL certificate in ACM that we'll use with CloudFront. This will allow us to set up our site with HTTPS.

In AWS, go to "AWS Certificate Manager (ACM)".
Check the upper right corner and make sure you are in the us-east-1 region. SSL certificates used with CloudFront must be in the us-east-1 region. Then click the "Request" button.

For "Certificate type" select "Request a public certificate". Then click Next

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/requesting_ssl_certificate.png" alt="Requesting an ACM SSL certificate." >}}

Then, under "Fully qualified domain name" write in the name of your domain
I also like to add in support for all subdomains. If you want to do this, add in the wildcard subdomain `*.yourdomain.com`

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/ssl_cert_domains.png" alt="Adding domain names to ACM SSL certificate." >}}

Under "Validation method" select DNS validation.
Under "Key algorithm" select "RSA 2048" or higher if you'd like

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/ssl_cert_validation_and_key_algorithm.png" alt="Validation method and key algorithm for ACM SSL certificate." >}}

Then click the "Request" button.

You'll see that the new certificate says "Pending validation" under the "Status" column. Click into your certificate.

Under "Domains" you should see the columns "CNAME name" and "CNAME value".
You'll have to create a CNAME record in Route 53 to validate this certificate.

Note that in my case the CNAME names and values are repeated. I only had to create the record once to validate both domains.

*Side note: The CNAME record(s) can also be created on your registrar, but I chose to do it all on AWS to keep things in one place.*

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/cname_record.png" alt="ACM SSL certificate CNAME records." >}}

Go to Route 53 and create a public hosted zone for your domain (note that at the time of this writing hosted zones are 50 cents per month).

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/hosted_zone.png" alt="Creating a Hosted Zone in Route 53." >}}

Once the zone is created, go back to your certificate and click on the upper right button that says "Create records in Route 53".
AWS will add the records for you without any manual work on your part.

After the record(s) are created, wait some time until the certificate is validated. In my case, it took around 10 minutes.

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/certificate_validated.png" alt="Validated ACM SSL certificate." >}}

This page from the AWS documentation elaborates more on DNS validation for ACM SSL Certificates: 
- https://docs.aws.amazon.com/acm/latest/userguide/dns-validation.html

## Creating a CloudFront Distribution

Next, we need to create a CloudFront distribution. The distribution will use the bucket and ACM certificate we created in order to host our site. 

CloudFront is [Amazon's content delivery network (CDN)](https://aws.amazon.com/cloudfront/). By deploying our site on CloudFront, our site will be available worldwide with low latency.

First, we'll need the S3 bucket website endpoint that we clicked on earlier.

Go to the S3 bucket you created.
Then, under the "Properties" tab, scroll all the way down and copy the value of "Bucket website endpoint"

Then go to the CloudFront service in the AWS console. Click the "Create distribution" button on the top right.

Under "Origin domain"  paste the value of "Bucket website endpoint" that you copied. The value should be something like `http://mybucket.s3-website-us-east-1.amazonaws.com`

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/origin_domain.png" alt="CloudFront Distribution Origin domain." >}}

Under "Viewer protocol policy" I like to select "Redirect HTTP to HTTPS", essentially disallowing HTTP connections. 

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/viewer_protocol_policy.png" alt="CloudFront Distribution viewer protocol policy." >}}

Under "Alternate domain name (CNAME)", add in your domain.

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/alternate_domain_name.png" alt="CloudFront Distribution Alternate domain name (CNAME)." >}}

Under "Custom SSL certificate" select the ACM certificate that you created earlier. It should show up in the drop-down menu.

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/custom_ssl_certificate.png" alt="CloudFront Distribution custom SSL certificate." >}}

Under "default root object" write in `index.html` or whatever you want your root to be. This is the page that gets loaded by default when hitting your domain without a path (`https://nelson.cloud/` in this case).

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/default_root_object.png" alt="CloudFront Distribution default root object." >}}

The rest of the settings can stay as is. Click the "Create distribution" button.
Allow some time for the distribution to deploy.
Once the "Last modified" field doesn't say "Deploying" and displays a date, it's done.

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/distribution_created.png" alt="Deployed CloudFront Distribution." >}}

I chose to use the S3 website endpoint as the Origin, but you can also use the S3 bucket itself. I chose the website endpoint because my site has multiple `index.html` templates [which can give errors in CloudFront](https://nelson.cloud/resolving-aws-cloudfront-access-denied-errors/).

More on Origins in the AWS documentation: 
- https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/DownloadDistS3AndCustomOrigins.html

## Pointing a Custom Domain to the CloudFront Distribution

The last step is to point your domain to the cloudfront distribution.
For this step, I once again used Route 53, but you can also create a CNAME or ALIAS record in your domain's registrar and get the same results.

Go to Route 53 again. Click on the Hosted Zone you created earlier.
Create a record with the following settings:
- Leave the subdomain blank, unless you want your site to be accessible under a subdomain like `blog.mysite.com`.
- For Record type, select "A - Routes traffic to an IPv4 address and some AWS resources."
- Click the "Alias" toggle to enable it.
- Under "Route traffic to" select "Alias to CloudFront distribution"
- Under the "Choose distribution" field select the distribution you created.
- The routing policy can stay as is.

Then click the "Create records" button.

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/route_53_record.png" alt="Creating a Route 53 record." >}}

It will take some time for these changes to propagate across DNS servers. In my experience, it's never been more than around 15 minutes. Usually faster.

After adding the DNS record and waiting some time, you should be able to go to your domain on a browser and see your site. Congrats! Your site is now live.

{{< figure src="/hosting_a_static_site_on_aws_using_s3_and_cloudfront/live_site.png" alt="Live site." >}}
