+++
title = "Resolving AWS CloudFront Access Denied Errors when using Private Bucket and Multiple index.html Templates"
summary = "Resolving Access Denied Errors in a CloudFront Distribution Using an Origin Access Identity and Containing Multiple index.html Templates."
date = "2021-07-25"
tags = ["AWS"]
toc = false
+++

I recently discovered that "Access Denied" errors may show up when a CloudFront Distribution is set up under the following conditions:

- A private bucket is being used (which means the Distribution has a S3 Origin)
- An Origin Access Identity is being used. (This is necessary if the bucket is private. When a user hits the distribution, the Origin Access Identity retrieves files from the bucket and forwards them to the distribution and to the end user. The end user never accesses the bucket directly.)
- Multiple index.html templates exist in the bucket

This is unfortunate for those like me that use Hugo to generate static sites. Hugo creates several index.html templates when building a site.

The solution is to either:

- Use a public bucket.
- Enable static website hosting on the bucket (which means the Distribution will have a Custom Origin).
- Avoid using an Origin Access Identity.
- Acknowledge that users will be able to access S3 contents directly with no HTTPS.

Or

- Use a private bucket.
- Don't enable static website hosting on the bucket (the Distribution will have a S3 Origin).
- Use an Origin Access Identity.
- Avoid having multiple index.html templates in the bucket, excluding the root index.html template, which could be a hurdle.


I was surprised that this isnt documented in [Amazon's own troubleshooting guide](https://aws.amazon.com/premiumsupport/knowledge-center/s3-website-cloudfront-error-403/). I only learned about this issue through a [comment on a forum](https://forums.aws.amazon.com/thread.jspa?threadID=85849) that reads as follows:

> "...CloudFront provides default root object support as well, but not for any subdirectories. You can solve this by using a custom origin instead. When CloudFront uses the S3 static website URL as the origin, you get the desired functionality."

This seems like a limitation of CloudFront. While this issue won't affect me too much, there are others who would not want their buckets to be publicly accessible. For security purposes, it's best if users are only able to access a site through the distribution and not directly from the bucket, specially since S3 static website hosting does not support HTTPS. Hopefully AWS fixes this flaw.
