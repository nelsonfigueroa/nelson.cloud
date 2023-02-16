+++
title = "Redirect One Domain to Another Using AWS S3 and CloudFront"
summary = "How to redirect a domain to another one using AWS S3, ACM, and CloudFront"
date = "2023-01-17"
categories = ["AWS"]
toc = true
+++

With AWS S3, it's possible to redirect one domain to another. This is useful when migrating domains.

I recently migrated my domain from `nelsonfigueroa.dev` to `nelson.cloud`, so I'll be using those domains as an example.

This guide assumes that you already have ownership or control of the domain you want to redirect.

## Redirecting The Old Domain Using HTTP

This section covers the redirecting of the HTTP version of the old domain. In my case, this section covers how I redirected `http://nelsonfigueroa.dev` to `https://nelson.cloud`. There is an additional section you can read if you need to redirect the HTTPS version of the old domain.

### Configuring a S3 Bucket

To begin redirecting one domain to another, we need to create a S3 bucket.

Go to [Amazon S3](https://s3.console.aws.amazon.com/s3/) and click the "Create bucket" button.
I prefer to name the bucket with the same name as the domain being redirected, but you can choose any name you want.

Click into the bucket and then click on the "Properties" tab. Scroll to the bottom to the "Static website hosting" section and click the "Edit" button.

Set the following configuration:
- Under "Static website hosting" click the "Enable" radio button
- Under "Hosting type" select "Host a static website"
- Under "Index document" type in "index.html", even though we won't need an index.html file.

Finally, under the "Redirection rules" text box add the following (change the domain for your needs):

```json
[
    {
        "Redirect": {
            "HostName": "nelson.cloud",
            "Protocol": "https"
        }
    }
]
```

This rule will redirect to the new domain and append paths as well. So if a user goes to `nelsonfigueroa.dev/about` they will be redirected to `https://nelson.cloud/about`.

(If you want to redirect to HTTP instead of HTTPS, you can change the protocol to `http`)

{{< figure src="/redirect_one_domain_to_another/bucket_configuration.png" alt="S3 bucket configuration" >}}

Then click the "Save changes" button at the very bottom of the page.

Make a note of the "Static website endpoint" URL at the bottom of the "Properties" tab. Next, we'll need to point the old domain to this endpoint.

### Setting Up DNS for the Old Domain

In the bucket you created, still on the "Properties" tab, scroll all the way to the bottom and copy the "Bucket website endpoint".

You'll need to create a CNAME record that points the old domain to the bucket website endpoint. The process depends on the DNS provider you're using for the old domain but it should be fairly straightforward.

#### Creating a CNAME Record in Route 53
On Amazon Web Services, you can use Route 53 to do this.

First, make sure you have a public hosted zone in Route 53. If not, create one and specify the old domain name:

{{< figure src="/redirect_one_domain_to_another/creating_hosted_zone.png" alt="Route 53 hosted zone" >}}

Then create a CNAME record that points the old domain to the S3 Bucket Website endpoint.

{{< figure src="/redirect_one_domain_to_another/cname_record.png" alt="CNAME record" >}}

After the CNAME record is created (and changes have propagated), all requests going to the old domain will be redirected to the new domain.
However, this will only work with HTTP requests to the old domain and *not* HTTPS requests.

In my case, `http://nelsonfigueroa.dev` will be redirected to `https://nelson.cloud`, but `https://nelsonfigueroa.dev` will time out. This is because there is nothing redirecting the HTTPS version of the old domain.

If this isn't a problem to you, you are free to stop here. Otherwise, read on.

## Redirecting the Old Domain Using HTTPS

To redirect the old domain when using HTTPS we'll need a ACM SSL Certificate and a CloudFront Distribution. This will allow the HTTPS version of the old domain to be redirected.

### Requesting a ACM SSL Certificate


### Creating a CloudFront Distribution


### Updating DNS to use the new CloudFront Distribution
Update the previous record so that it points to the distribution instead of the bucket website endpoint

Now `https://olddomain.com` will redirect as well.

You can test this out by entering the old domain in a browser prefixed with `https://`. Or you can test this out in a terminal with `curl`:

```bash
curl -IX GET https://olddomain.com
```

The output should include a `location` header that should point to your new domain.

Test this out with a path too. `https://olddomain.com/test-path` should redirect to `https://newdomain.com/test-path`


You're all done!