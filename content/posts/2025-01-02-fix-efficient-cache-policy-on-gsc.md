+++
title = "Fix \"Efficient Cache Policy\" Warning on PageSpeed Insights When Using Amazon CloudFront"
summary = "Get rid of the efficient cache policy warning on Google Search Console by adding a `Cache-Control` header to CloudFront."
date = "2025-01-02"
lastmod = "2025-01-02"
categories = ["AWS", "Google", "SEO"]
ShowToc = false
TocOpen = false
+++

I ran into this warning recently on [PageSpeed Insights](https://pagespeed.web.dev/): *"Serve static assets with an efficient cache policy"*. The warning highlighted three assets that had no "Cache TTL" defined:

{{< figure src="/fix-efficient-cache-policy-on-gsc/before.webp" alt="Efficient cache policy warning on Google Search Console." >}}

To resolve this warning, I added a `Cache-Control` header with the value `max-age=31536000` to the HTTP responses of my domain (`31536000` is the number of seconds in a year).

Since I host [nelson.cloud](https://nelson.cloud) on [Amazon CloudFront](https://aws.amazon.com/cloudfront/) I added this header to the CloudFront Distribution that the `nelson.cloud` domain points to. This can be done by adding a "Response headers policy" in the Behavior of the Distribution. I wrote post demonstrating how to do this in detail: [How to Add a Custom Response Header to an Amazon Cloudfront Distribution]({{< relref "/posts/2025-01-01-aws-cloudfront-custom-response-header" >}}).

After the header was configured, I checked PageSpeed Insights again and the warning had gone away for the assets under my domain `nelson.cloud`:

{{< figure src="/fix-efficient-cache-policy-on-gsc/after.webp" alt="Efficient cache policy warning is gone for 2 assets." >}}

(The remaining asset is not under my control so I can't fix that one.)

Note that you may be able to use a different value smaller than `31536000` for your header. I chose a year in seconds to be on the safe side for PageSpeed Insights.

# References
- https://developer.chrome.com/docs/lighthouse/performance/uses-long-cache-ttl/
- https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control
- https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/Expiration.html