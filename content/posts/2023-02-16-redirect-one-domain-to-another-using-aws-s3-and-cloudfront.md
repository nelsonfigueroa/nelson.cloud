+++
title = "Redirect One Domain to Another Using AWS S3 and CloudFront"
summary = "How to redirect a domain to another one using AWS S3, ACM, and CloudFront"
date = "2023-02-16"
lastmod = "2023-02-16"
categories = ["AWS"]
ShowToc = true
TocOpen = true
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

{{< figure src="/redirect-one-domain-to-another/bucket-configuration.webp" alt="S3 bucket configuration" >}}

Then click the "Save changes" button at the very bottom of the page.

Make a note of the "Static website endpoint" URL at the bottom of the "Properties" tab. Next, we'll need to point the old domain to this endpoint.

### Setting Up DNS for the Old Domain

Next, we'll need to create a CNAME record that points the old domain to the bucket website endpoint. We can use Route 53 to do this.

#### Creating a CNAME Record in Route 53

On Amazon Web Services, browse to the Route 53 console.

First, make sure you have a public hosted zone in Route 53. If not, create one and specify the old domain name:

{{< figure src="/redirect-one-domain-to-another/creating-hosted-zone.webp" alt="Route 53 hosted zone" >}}

You will need to update the DNS nameservers for your domain specifying the nameservers that AWS provides to you in the hosted zone. This varies depending on the registrar so I will let you do some Googling for this part. It's pretty easy though :).

Next, create a CNAME record in the new hosted zone that points the old domain to the S3 Bucket Website endpoint.

(Note that in Route 53 specifically you can create an `A` record, enable the "Alias" toggle, and then select the S3 Bucket website endpoint. This is an alternative solution.)

{{< figure src="/redirect-one-domain-to-another/cname-record.webp" alt="CNAME record" >}}

After the CNAME record is created (and changes have propagated), all requests going to the old domain will be redirected to the new domain.
However, this will only work with HTTP requests to the old domain and *not* HTTPS requests.

In my case, `http://nelsonfigueroa.dev` will be redirected to `https://nelson.cloud`, but `https://nelsonfigueroa.dev` will time out. This is because there is nothing redirecting the HTTPS version of the old domain.

If this isn't a problem to you, you are free to stop here. Otherwise, read on.

## Redirecting the Old Domain Using HTTPS

To redirect the old domain when using HTTPS we'll need a ACM SSL Certificate and a CloudFront Distribution. This will allow the HTTPS version of the old domain to be redirected.

### Requesting a ACM SSL Certificate

Browse to the AWS Certificate Manager console. Make sure you are in the us-east-1 region by looking at the region on the top-right corner of the screen. Only ACM certificates from the us-east-1 region will work with a CloudFront Distribution.

Once you are sure you're in the us-east-1 region, click on "Request certificate" on the left-hand sidebar.

For the "Certificate type", select "Request a public certificate". Then click the "Next" button:

{{< figure src="/redirect-one-domain-to-another/request-certificate.webp" alt="Requesting public certificate" >}}

In the following form, do the following:
- For "Fully qualified domain name", enter the old domain (`nelsonfigueroa.dev` in my case)
- For "Validation method", select "DNS validation"
- For "Key algorithm", it's okay to leave it at RSA 2048. You can select a different algorithm if you'd like.

Then click the "Request" button on the bottom of the page.

{{< figure src="/redirect-one-domain-to-another/certificate-configuration.webp" alt="Certificate configuration" >}}

After the certificate is created we'll need to validate the certificate (we need to prove to AWS that we own this domain by creating a CNAME record in the hosted zone for this domain). Click into the certificate and scroll down to the "Domains" section. There will be a table with two columns named "CNAME name" and "CNAME value". You will need to create a CNAME record in Route 53 with this name/value combination following the same procedure as before.

After the CNAME record is created, wait around 15 minutes and the certificate will be validated and you should see "Success" under the "Status" and "Renewal status" columns:

{{< figure src="/redirect-one-domain-to-another/validated-certificate.webp" alt="Certificate configuration" >}}

Now we can create a CloudFront Distribution and associate this certificate to it.

### Creating a CloudFront Distribution

Browse to the CloudFront console and click on the "Create distribution" button on the top right.

In the form, fill out the form with the following:

For "Origin domain" select the S3 bucket you created for your old domain. Or you can paste in the S3 bucket website endpoint from earlier (this is what I did). Either one of these work for our case.

{{< figure src="/redirect-one-domain-to-another/origin.webp" alt="CloudFront Distribution origin" >}}

For "Viewer protocol policy", select "Redirect HTTP to HTTPS":

{{< figure src="/redirect-one-domain-to-another/viewer-protocol-policy.webp" alt="CloudFront Distribution viewer protocol policy" >}}

Under "Alternate domain name (CNAME)", click the "Add item" button and enter the old domain in the field that appears (`nelsonfigueroa.dev` in my case):

{{< figure src="/redirect-one-domain-to-another/alternate-domain-name.webp" alt="CloudFront Distribution alternate domain name" >}}

Under "Custom SSL certificate", select the ACM certificate that you created earlier.

{{< figure src="/redirect-one-domain-to-another/ssl-certificate.webp" alt="CloudFront Distribution SSL certificate" >}}

The rest of the settings can remain as is. Click the "Create distribution" button on the bottom of the page. Wait a few minutes for the distribution to deploy out. You'll know deployment is done when the “Last modified” field displays a date. Make a note of the "Domain name" column for your distribution. You'll need this value for the next step.

### Updating DNS to use the new CloudFront Distribution

The final step is to update the previous record so that it points to the distribution instead of the bucket website endpoint.

Go to Route 53. Click into the hosted zone for the old domain. Then edit the record that was previously created. Instead of entering the S3 Bucket website endpoint in the "Value field, enter the CloudFront Distribution domain name. Then click "Save".

{{< figure src="/redirect-one-domain-to-another/editing-record.webp" alt="Editing Route 53 CNAME record" >}}

Now both `http://nelsonfigueroa.dev` and `https://nelsonfigueroa.dev/` will redirect to the new domain.

## Testing the Redirect

You can test this out by entering the old domain in a browser prefixed with `https://`. Or you can test this out in a terminal with `curl`.

The HTTP version of the domain should give us a location header redirecting us to `https://nelsonfigueroa.dev`:

```
$ curl -IX GET http://nelsonfigueroa.dev

HTTP/1.1 301 Moved Permanently
Server: CloudFront
Date: Thu, 16 Feb 2023 05:55:35 GMT
Content-Type: text/html
Content-Length: 167
Connection: keep-alive
Location: https://nelsonfigueroa.dev/
```

And the HTTPS version of the domain should then redirect to the new domain `https://nelson.cloud`:

```
$ curl -IX GET https://nelsonfigueroa.dev

HTTP/2 301
content-length: 0
location: https://nelson.cloud/index.html
date: Thu, 16 Feb 2023 05:42:44 GMT
server: AmazonS3
x-cache: Miss from cloudfront
```

As a final test, we can make sure that paths are also redirected to the new domain. In other words, `https://nelsonfigueroa.dev/test-path/` should redirect to `https://nelson.cloud/test-path/`, and we should see this value in the `location` header:

```
$ curl -IX GET https://nelsonfigueroa.dev/test-path/

HTTP/2 301
content-length: 0
location: https://nelson.cloud/test-path/
date: Thu, 16 Feb 2023 05:58:52 GMT
server: AmazonS3
x-cache: Miss from cloudfront
```

It works, we are done!
