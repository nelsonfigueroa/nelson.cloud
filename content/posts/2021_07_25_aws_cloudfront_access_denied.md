+++
title = "Resolving AWS CloudFront Access Denied Errors"
summary = "Resolving Access Denied Errors in a CloudFront Distribution that uses a Private S3 Bucket, Origin Access Identity, and Contains Multiple index.html Templates."
date = "2021-07-25"
lastmod = "2021-07-25"
categories = ["AWS"]
toc = false
+++

If you're seeing "Access Denied" errors on CloudFront and [the official troubleshooting](https://aws.amazon.com/premiumsupport/knowledge-center/s3-website-cloudfront-error-403/) docs aren't helping, you might be running into the following issue.

I discovered that "Access Denied" errors may show up when a CloudFront Distribution is set up under the following conditions:

- A private S3 Bucket is being used along with a S3 Origin in the CloudFront Distribution
- An Origin Access Identity is being used. (This is necessary if the bucket is private. When a user hits the distribution, the Origin Access Identity retrieves files from the bucket and forwards them to the distribution and to the end user. The end user never accesses the bucket directly.)
- Multiple index.html templates exist in the bucket

An Origin Access Identity is useful because this allows S3 contents to be accessible only through CloudFront. However, it doesn't work well when there are multiple `index.html` templates in the bucket and we end up seeing "Access Denied" errors when accessing any sub-pages. This is unfortunate for those like me that use Hugo to generate static sites. Hugo creates several index.html templates when building a site.

The solution is to either:

- Use a public bucket.
- Enable static website hosting on the bucket (which means the Distribution will have a Custom Origin).
- Avoid using an Origin Access Identity.
- Acknowledge that users will be able to access S3 contents directly without HTTPS.

Or

- Use a private bucket.
- Don't enable static website hosting on the bucket (the Distribution will have a S3 Origin).
- Use an Origin Access Identity so that users cannot access S3 contents directly and can only view contents via CloudFront.
- Avoid having multiple index.html templates in the bucket, excluding the root index.html template, which could be a hurdle.


I was surprised that this isnt documented in Amazon's own troubleshooting guide. I only learned about this issue through a [comment on a forum](https://forums.aws.amazon.com/thread.jspa?threadID=85849) that reads as follows:

> "...CloudFront provides default root object support as well, but not for any subdirectories. You can solve this by using a custom origin instead. When CloudFront uses the S3 static website URL as the origin, you get the desired functionality."

This seems like a limitation of CloudFront. While this issue won't affect me too much, there are others who would not want their buckets to be publicly accessible. For security purposes, it's best if users are only able to access a site through the distribution and not directly from the bucket, specially since S3 static website hosting does not support HTTPS. Hopefully AWS fixes this flaw.
