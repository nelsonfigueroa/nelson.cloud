+++
title = "How to Add a Custom Response Header to an Amazon Cloudfront Distribution"
summary = "A guide to adding custom response headers to a CloudFront distribution using response headers policies in the AWS Console."
date = "2025-01-01"
categories = ["AWS", "HTTP"]
ShowToc = false
TocOpen = false
+++

This post assumes you already have an [Amazon CloudFront](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/Introduction.html) Distribution deployed and properly configured. You'll also need knowledge about [HTTP headers](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers) if you found your way here because your boss told you to do this and "figure it out".

Go to the AWS Console and click into your CloudFront Distribution. Then click on the "Behaviors" tab. If you already have a behavior, you can edit the existing one. Otherwise, create a behavior.

<img src="/aws-cloudfront-custom-response-header/distribution.webp" alt="CloudFront Distribution Behaviors tab" width="720" height="180" style="max-width: 100%; height: auto; aspect-ratio: 2284 / 572;" loading="lazy" decoding="async">

I won't cover the other Behavior settings as that is out of the scope of this post, but to add a custom response header look for the "Response headers policy - *optional*" field which is under "Cache key and origin requests".

<img src="/aws-cloudfront-custom-response-header/response-headers-policy.webp" alt="Response headers policy field" width="720" height="107" style="max-width: 100%; height: auto; aspect-ratio: 1580 / 236;" loading="lazy" decoding="async">

Click on the "Create response headers policy" link. On the next page fill in the "Name" field at the very top. Then, scroll a bit down and you should see the "Custom headers - *optional*" field.

<img src="/aws-cloudfront-custom-response-header/custom-headers.webp" alt="Custom headers field" width="720" height="589" style="max-width: 100%; height: auto; aspect-ratio: 2184 / 1788;" loading="lazy" decoding="async">

Click on the "Add header" button and add the custom header you'd like. In my case, I added the `Cache-Control` header with a value of `max-age=31536000`. I also checked the `Origin override` box because I want this header to take precedence over other headers that may be set in the origin.

<img src="/aws-cloudfront-custom-response-header/custom-header-filled.webp" alt="Custom headers field filled in" width="720" height="134" style="max-width: 100%; height: auto; aspect-ratio: 2172 / 404;" loading="lazy" decoding="async">

Then click the "Create" button on the bottom right. You'll be redirected to CloudFront > Policies. You can see your newly created response header under the "Response headers" tab. In my case it's called `cache-control-header`.

<img src="/aws-cloudfront-custom-response-header/policies.webp" alt="Policies view showing custom policy" width="720" height="533" style="max-width: 100%; height: auto; aspect-ratio: 1738 / 1286;" loading="lazy" decoding="async">

Now that this custom header policy is created, browse back to your Distribution and click on the "Behaviors" tab. Edit an existing Behavior or create one. Scroll down to the "Response headers policy - *optional*" field again. Then, select your new response header policy.

<img src="/aws-cloudfront-custom-response-header/behavior-filled.webp" alt="Selecting new response header policy in Behaviors tab" width="720" height="131" style="max-width: 100%; height: auto; aspect-ratio: 1236 / 224;" loading="lazy" decoding="async">

Then save your changes.

Wait a few minutes and your distribution will be updated with the new header. There is no need to create an [Invalidation](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/Invalidation.html).

You can test this out by running `curl -IX GET <your-distribution-domain-name>`. Your output should look something like this. Look for your custom header in the output. In my case, `cache-control` shows up at the very bottom.

```
$ curl -IX GET https://abcdefghijklmn.cloudfront.net

HTTP/2 301
content-length: 0
location: https://nelson.cloud/index.html
date: Wed, 01 Jan 2025 08:12:11 GMT
server: AmazonS3
x-cache: Hit from cloudfront
via: 1.1 98167d64569fd17ca63a5b7db2edfe28.cloudfront.net (CloudFront)
x-amz-cf-pop: LAX54-P1
x-amz-cf-id: l5n0bciPQfswC27jGnhcrkuldHWFDgZx0JdYo406qkoKKKP87i_dsA==
age: 2292
cache-control: max-age=31536000
```

Done!

## References
- https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers
- https://stackoverflow.com/questions/56187791/how-to-set-cache-control-header-in-amazon-cloudfront
- https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/modifying-response-headers.html
