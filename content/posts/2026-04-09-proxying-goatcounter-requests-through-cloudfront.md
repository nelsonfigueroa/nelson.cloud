+++
title = "Proxying GoatCounter Requests Through CloudFront to Bypass Ad Blockers"
summary = "How to configure CloudFront to proxy requests to GoatCounter so that adblockers don't block page views."
aliases = ["/proxying-goatcounter-requests-for-a-hugo-blog-on-cloudfront-to-bypass-ad-blockers/"]
date = "2026-04-09"
lastmod = "2026-04-18T14:32:00-07:00"
categories = ["AWS", "Pulumi"]
ShowToc = true
TocOpen = true
featured = false
+++

This blog is a [Hugo](https://gohugo.io/)-generated static site hosted on AWS using S3 and CloudFront. I've been running [GoatCounter](https://www.goatcounter.com/) on my site using [the provided script](https://gc.zgo.at/count.js) to see who views my blog posts. Every time someone visits my site, a request goes out to GoatCounter. The problem is that adblockers like uBlock Origin block it (understandably).

<img src="/proxying-goatcounter-requests/before.webp" alt="uBlock Origin showing a blocked domain" width="720" height="546" style="max-width: 100%; height: auto; aspect-ratio: 1292 / 980;" loading="lazy" decoding="async">

To get around this, I set up proxying so that the GoatCounter requests go to an endpoint under my domain `nelson.cloud/gc/count`, and then from there CloudFront handles it and sends it to GoatCounter. Most ad blockers work based on domain and GoatCounter is on the blocklists. Since the browser is now sending requests to the same domain as my site, it shouldn't trigger any ad blockers. This post explains how I did it in case it's useful for anyone else.

It's possible to [self-host](https://github.com/arp242/goatcounter) GoatCounter, but my approach was easier to do and less infrastructure to maintain. Perhaps in the future.

## On Analytics and Privacy

I know I'm bypassing a user's preference to not be tracked, even if it's (in my opinion) a harmless analytics tool. I just want to see who reads my stuff, that's all.

Read the GoatCounter developer's take if you want another opinion: [Analytics on personal websites](https://www.arp242.net/personal-analytics.html).

## Managing Infrastructure with Pulumi

Clicking through the AWS console to configure CloudFront distributions is a pain in the ass. I took the time to finally get the infrastructure for my blog managed as infrastructure-as-code with [Pulumi and Python](https://www.pulumi.com/docs/iac/languages-sdks/python/). So while you can click around the console and do all of this, I will be showing how to configure everything with Pulumi.

If you don't want to use IaC, you can still find all of these options/settings in AWS itself.

## Setting it up

To set up GoatCounter proxying via CloudFront, we'll need to
- Create a new [CloudFront function](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/cloudfront-functions.html) resource
- Add a second [origin](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/DownloadDistS3AndCustomOrigins.html) to the distribution
- Add an ordered [cache behavior](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/DownloadDistValuesCacheBehavior.html) to the distribution (which references the CloudFront function using its ARN)
- Update the GoatCounter script to point to this new endpoint

### CloudFront Function

CloudFront functions are JavaScript scripts that run before a request reaches a CloudFront distribution's origin. In this case, the function strips the `/gc` from `nelson.cloud/gc/count`.

We need to strip `/gc` for two reasons:
1. I chose to proxy requests that hit the `/gc/count` endpoint on my site to make sure there's no collision with post titles/slugs. I'll never use the `/gc/*` path for posts.
2. GoatCounter accepts requests under `/count`, not `/gc/count`

Here is the code for the function:
```js
function handler(event) {
    var request = event.request;
    request.uri = request.uri.replace(/^\/gc/, '');
    if (request.uri === '') request.uri = '/';
    return request;
}
```

And here is the CloudFront function resource defined in Pulumi (using Python) that includes the JavaScript from above. This is a new resource defined in the same Python file where my existing distribution already exists:

```python
goatcounter_rewrite = aws.cloudfront.Function("goatcounter-rewrite",
    name="goatcounter-rewrite",
    runtime="cloudfront-js-2.0",
    code="""\
function handler(event) {
    var request = event.request;
    request.uri = request.uri.replace(/^\\/gc/, '');
    if (request.uri === '') request.uri = '/';
    return request;
}
""",
)
```

### CloudFront Distribution Origin and Cache Behavior

Here is my existing CloudFront distribution being updated with a new origin and cache behavior in Pulumi code.

{{< admonition type="note" >}}
At the time of writing CloudFront only allows `allowed_methods` to be a list of HTTP methods in specific combinations. The value must be one of these:
- `[HEAD, GET]`
- `[HEAD, GET, OPTIONS]`
- `[HEAD, DELETE, POST, GET, OPTIONS, PUT, PATCH]`

Since the GoatCounter JavaScript sends a `POST` request, and the third option is the only one that includes `POST`, we're forced to use all HTTP verbs. It should be harmless though.
{{< /admonition >}}

```python
nelson_cloud_distribution = aws.cloudfront.Distribution("nelson-cloud",
    aliases=["nelson.cloud"],
    ###
    # other configuration
    ###
    # route /gc/* requests to GoatCounter, stripping the /gc prefix via CloudFront function
    ordered_cache_behaviors=[{
        "path_pattern": "/gc/*", # the path gets matched here
        "allowed_methods": ["GET", "HEAD", "OPTIONS", "PUT", "PATCH", "POST", "DELETE"],
        "cached_methods": ["GET", "HEAD"],
        "target_origin_id": "goatcounter",
        "viewer_protocol_policy": "redirect-to-https",
        "compress": False,
        "cache_policy_id": "4135ea2d-6df8-44a3-9df3-4b5a84be39ad",  # CachingDisabled
        "origin_request_policy_id": "b689b0a8-53d0-40ab-baf2-68738e2966ac",  # AllViewerExceptHostHeader
        "function_associations": [{
            "event_type": "viewer-request",
            "function_arn": goatcounter_rewrite.arn, # CloudFront function from above
        }],
    }],
    origins=[
        ###
        # other existing origins
        ###
        # a new origin to proxy GoatCounter requests
        {
            "custom_origin_config": {
                "http_port": 80,
                "https_port": 443,
                "origin_protocol_policy": "https-only",
                "origin_ssl_protocols": ["TLSv1.2"],
            },
            "domain_name": "nelsonfigueroa.goatcounter.com",
            "origin_id": "goatcounter",
        },
    ],
    ###
    # rest of configuration
    ###
    opts = pulumi.ResourceOptions(protect=True))
```

Now that my Pulumi code has both the CloudFront function defined and the CloudFront distribution has been updated, I ran `pulumi up` to apply changes.

### Update the GoatCounter Script

Finally, I updated goatcounter.js to use the new endpoint. So instead of `goatcounter.com` I changed it to my own domain `nelson.cloud/gc/count` at the very top of the snippet:

```javascript
<script data-goatcounter="https://nelson.cloud/gc/count">
/* rest of script */
</script>
```

After this, I built my site with Hugo and deployed it on S3/CloudFront by updating the freshly built HTML/CSS/JS in my S3 Bucket and then [invalidating the existing CloudFront cache](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/Invalidation.html).

## Verifying that it Works

Now, GoatCounter should no longer be blocked by uBlock Origin. I tested by loading my site on an incognito browser window and checked that uBlock Origin was no longer blocking anything on my domain.

<img src="/proxying-goatcounter-requests/after.webp" alt="uBlock Origin no longer showing a blocked domain" width="720" height="540" style="max-width: 100%; height: auto; aspect-ratio: 1290 / 968;" loading="lazy" decoding="async">

And for further proof, checking the network tab shows a successful `POST` request to the `/gc/count` endpoint on my domain along with response headers from AWS/CloudFront:

<img src="/proxying-goatcounter-requests/network.webp" alt="Firefox network tab showing a successful request to /gc/count" width="609" height="720" style="max-width: 100%; height: auto; aspect-ratio: 870 / 1028;" loading="lazy" decoding="async">
    
Everything looks good!

## Support GoatCounter

If you're using GoatCounter you should consider [sponsoring the developer](https://github.com/sponsors/arp242/). It's a great project.

## References
- https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/cloudfront-functions.html
- https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/DownloadDistS3AndCustomOrigins.html
- https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/DownloadDistValuesCacheBehavior.html
- https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/Invalidation.html
- https://www.goatcounter.com/help/js
- https://www.goatcounter.com/help/backend
- https://www.goatcounter.com/help/countjs-host
