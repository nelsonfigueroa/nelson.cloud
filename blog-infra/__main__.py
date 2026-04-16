"""An AWS Python Pulumi program"""

import pulumi
import pulumi_aws as aws

# Explicit provider for resources in us-west-1
us_west_1 = aws.Provider("us-west-1", region="us-west-1")

# CloudFront Function to strip /gc prefix for GoatCounter proxy
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

# S3 bucket for nelson.cloud site files
bucket = aws.s3.Bucket("nelson-cloud-bucket",
    bucket="nelson.cloud",
    opts=pulumi.ResourceOptions(
        provider=us_west_1,
    ),
)

# IAM users
hugo_user = aws.iam.User("hugo",
    name="hugo",
)

personal_laptop_user = aws.iam.User("personal-laptop",
    name="personal-laptop",
)

# IAM policy attachments for IAM users
personal_laptop_admin = aws.iam.UserPolicyAttachment("personal-laptop-admin",
    user=personal_laptop_user.name,
    policy_arn="arn:aws:iam::aws:policy/AdministratorAccess",
)

hugo_s3_full_access = aws.iam.UserPolicyAttachment("hugo-s3-full-access",
    user=hugo_user.name,
    policy_arn="arn:aws:iam::aws:policy/AmazonS3FullAccess",
)

hugo_cloudfront_full_access = aws.iam.UserPolicyAttachment("hugo-cloudfront-full-access",
    user=hugo_user.name,
    policy_arn="arn:aws:iam::aws:policy/CloudFrontFullAccess",
)

# CloudFront Distributions
nelsonfigueroa_sh_cloudfront = aws.cloudfront.Distribution("nelsonfigueroa-sh",
    aliases=[
        "nelsonfigueroa.sh",
        "www.nelsonfigueroa.sh",
    ],
    default_cache_behavior={
        "allowed_methods": [
            "GET",
            "HEAD",
        ],
        "cached_methods": [
            "GET",
            "HEAD",
        ],
        "compress": True,
        "default_ttl": 60,
        "forwarded_values": {
            "cookies": {
                "forward": "none",
            },
            "query_string": False,
        },
        "max_ttl": 300,
        "target_origin_id": "S3-nelsonfigueroa.sh",
        "viewer_protocol_policy": "redirect-to-https",
    },
    default_root_object="index.html",
    enabled=True,
    http_version="http2",
    is_ipv6_enabled=True,
    origins=[{
        "custom_origin_config": {
            "http_port": 80,
            "https_port": 443,
            "origin_protocol_policy": "http-only",
            "origin_ssl_protocols": [
                "TLSv1",
                "TLSv1.1",
                "TLSv1.2",
            ],
        },
        "domain_name": "nelsonfigueroa.sh.s3-website-us-west-1.amazonaws.com",
        "origin_id": "S3-nelsonfigueroa.sh",
    }],
    price_class="PriceClass_All",
    restrictions={
        "geo_restriction": {
            "restriction_type": "none",
        },
    },
    viewer_certificate={
        "acm_certificate_arn": "arn:aws:acm:us-east-1:741977555688:certificate/d0a0f229-e4bf-4459-b1d7-a91bfab11c94",
        "minimum_protocol_version": "TLSv1.2_2019",
        "ssl_support_method": "sni-only",
    },
    opts = pulumi.ResourceOptions(protect=True))


nelsonfigueroa_dev_distribution = aws.cloudfront.Distribution("nelsonfigueroa-dev",
    aliases=["nelsonfigueroa.dev"],
    custom_error_responses=[{
        "error_caching_min_ttl": 10,
        "error_code": 404,
        "response_code": 404,
        "response_page_path": "/404.html",
    }],
    default_cache_behavior={
        "allowed_methods": [
            "GET",
            "HEAD",
        ],
        "cache_policy_id": "658327ea-f89d-4fab-a63d-7e88639e58f6",
        "cached_methods": [
            "GET",
            "HEAD",
        ],
        "compress": True,
        "target_origin_id": "S3-nelsonfigueroa.dev",
        "viewer_protocol_policy": "redirect-to-https",
    },
    default_root_object="index.html",
    enabled=True,
    http_version="http2",
    is_ipv6_enabled=True,
    origins=[{
        "custom_origin_config": {
            "http_port": 80,
            "https_port": 443,
            "origin_protocol_policy": "http-only",
            "origin_ssl_protocols": ["TLSv1.2"],
        },
        "domain_name": "nelsonfigueroa.dev.s3-website-us-west-1.amazonaws.com",
        "origin_id": "S3-nelsonfigueroa.dev",
    }],
    price_class="PriceClass_All",
    restrictions={
        "geo_restriction": {
            "restriction_type": "none",
        },
    },
    viewer_certificate={
        "acm_certificate_arn": "arn:aws:acm:us-east-1:741977555688:certificate/a83c46aa-2846-461a-b80b-e9d444e07251",
        "minimum_protocol_version": "TLSv1.2_2021",
        "ssl_support_method": "sni-only",
    },
    opts = pulumi.ResourceOptions(protect=True))


nelson_cloud_distribution = aws.cloudfront.Distribution("nelson-cloud",
    aliases=["nelson.cloud"],
    custom_error_responses=[{
        "error_caching_min_ttl": 10,
        "error_code": 404,
        "response_code": 404,
        "response_page_path": "/404.html",
    }],
    default_cache_behavior={
        "allowed_methods": [
            "GET",
            "HEAD",
        ],
        "cache_policy_id": "658327ea-f89d-4fab-a63d-7e88639e58f6",
        "cached_methods": [
            "GET",
            "HEAD",
        ],
        "compress": True,
        "response_headers_policy_id": "1c10dca8-b7b6-476c-a137-2761529f090d",
        "target_origin_id": "nelson.cloud.s3.us-west-1.amazonaws.com",
        "viewer_protocol_policy": "redirect-to-https",
    },
    default_root_object="index.html",
    enabled=True,
    http_version="http2and3",
    is_ipv6_enabled=True,
    # route /gc/* requests to GoatCounter, stripping the /gc prefix via CloudFront Function
    ordered_cache_behaviors=[{
        "path_pattern": "/gc/*",
        "allowed_methods": ["GET", "HEAD", "OPTIONS", "PUT", "PATCH", "POST", "DELETE"],
        "cached_methods": ["GET", "HEAD"],
        "target_origin_id": "goatcounter",
        "viewer_protocol_policy": "redirect-to-https",
        "compress": False,
        "cache_policy_id": "4135ea2d-6df8-44a3-9df3-4b5a84be39ad",  # CachingDisabled
        "origin_request_policy_id": "b689b0a8-53d0-40ab-baf2-68738e2966ac",  # AllViewerExceptHostHeader
        "function_associations": [{
            "event_type": "viewer-request",
            "function_arn": goatcounter_rewrite.arn,
        }],
    }],
    origins=[
        {
            "custom_origin_config": {
                "http_port": 80,
                "https_port": 443,
                "origin_protocol_policy": "http-only",
                "origin_ssl_protocols": ["TLSv1.2"],
            },
            "domain_name": "nelson.cloud.s3-website-us-west-1.amazonaws.com",
            "origin_id": "nelson.cloud.s3.us-west-1.amazonaws.com",
        },
        # an origin to proxy GoatCounter requests
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
    price_class="PriceClass_All",
    restrictions={
        "geo_restriction": {
            "restriction_type": "none",
        },
    },
    viewer_certificate={
        "acm_certificate_arn": "arn:aws:acm:us-east-1:741977555688:certificate/971f99bd-dbda-4cbd-9714-b5f4672cebfc",
        "minimum_protocol_version": "TLSv1.2_2021",
        "ssl_support_method": "sni-only",
    },
    opts = pulumi.ResourceOptions(protect=True))


# ACM Certificates
nelsonfigueroa_sh_certificate = aws.acm.Certificate("nelsonfigueroa-sh",
    domain_name="nelsonfigueroa.sh",
    key_algorithm="RSA_2048",
    options={
        "certificate_transparency_logging_preference": "ENABLED",
        "export": "DISABLED",
    },
    region="us-east-1",
    subject_alternative_names=[
        "*.nelsonfigueroa.sh",
        "nelsonfigueroa.sh",
    ],
    validation_method="DNS",
    opts = pulumi.ResourceOptions(protect=True))

nelsonfigueroa_dev_certificate = aws.acm.Certificate("nelsonfigueroa-dev",
    domain_name="nelsonfigueroa.dev",
    key_algorithm="RSA_2048",
    options={
        "certificate_transparency_logging_preference": "ENABLED",
        "export": "DISABLED",
    },
    region="us-east-1",
    subject_alternative_names=[
        "*.nelsonfigueroa.dev",
        "nelsonfigueroa.dev",
    ],
    validation_method="DNS",
    opts = pulumi.ResourceOptions(protect=True))

nelson_cloud_certificate = aws.acm.Certificate("nelson-cloud",
    domain_name="nelson.cloud",
    key_algorithm="RSA_2048",
    options={
        "certificate_transparency_logging_preference": "ENABLED",
        "export": "DISABLED",
    },
    region="us-east-1",
    subject_alternative_names=[
        "*.nelson.cloud",
        "nelson.cloud",
    ],
    validation_method="DNS",
    opts = pulumi.ResourceOptions(protect=True))

# Route 53 Zones
nelsonfigueroa_dev_zone = aws.route53.Zone("nelsonfigueroa-dev",
    comment="",
    name="nelsonfigueroa.dev",
    opts = pulumi.ResourceOptions(protect=True))

nelson_cloud_zone = aws.route53.Zone("nelson-cloud",
    comment="",
    name="nelson.cloud",
    opts = pulumi.ResourceOptions(protect=True))

# Route 53 Records

## Records for nelsonfigueroa.dev
nelsonfigueroa_dev_a = aws.route53.Record("nelsonfigueroa-dev-a",
    aliases=[{
        "evaluate_target_health": False,
        "name": "d21xfi3nko5ufm.cloudfront.net",
        "zone_id": "Z2FDTNDATAQYW2",
    }],
    name="nelsonfigueroa.dev",
    type=aws.route53.RecordType.A,
    zone_id="Z05927031BXKMYS37I62Z",
    opts = pulumi.ResourceOptions(protect=True))

nelsonfigueroa_dev_aaaa = aws.route53.Record("nelsonfigueroa-dev-aaaa",
    aliases=[{
        "evaluate_target_health": False,
        "name": "d21xfi3nko5ufm.cloudfront.net",
        "zone_id": "Z2FDTNDATAQYW2",
    }],
    name="nelsonfigueroa.dev",
    type=aws.route53.RecordType.AAAA,
    zone_id="Z05927031BXKMYS37I62Z",
    opts = pulumi.ResourceOptions(protect=True))

nelsonfigueroa_dev_mx = aws.route53.Record("nelsonfigueroa-dev-mx",
    name="nelsonfigueroa.dev",
    records=["0 ."],
    ttl=300,
    type=aws.route53.RecordType.MX,
    zone_id="Z05927031BXKMYS37I62Z",
    opts = pulumi.ResourceOptions(protect=True))

nelsonfigueroa_dev_txt = aws.route53.Record("nelsonfigueroa-dev-txt",
    name="nelsonfigueroa.dev",
    records=["google-site-verification=Z6XialAUrcY8MKzEaYpfzsJ5YMg9k9Et11shkMS_ZWwl; v=spf1 -all;"],
    ttl=300,
    type=aws.route53.RecordType.TXT,
    zone_id="Z05927031BXKMYS37I62Z",
    opts = pulumi.ResourceOptions(protect=True))

nelsonfigueroa_dev_wildcard_txt = aws.route53.Record("nelsonfigueroa-dev-wildcard-txt",
    name="*.nelsonfigueroa.dev",
    records=["v=spf1 -all"],
    ttl=300,
    type=aws.route53.RecordType.TXT,
    zone_id="Z05927031BXKMYS37I62Z",
    opts = pulumi.ResourceOptions(protect=True))

nelsonfigueroa_dev_acm_validation = aws.route53.Record("nelsonfigueroa-dev-acm-validation",
    name="_1a4f4116967616f543b690fb21f46c65.nelsonfigueroa.dev",
    records=["_cfc95ae5fa66826ed893b0d493e04e1f.gjjrqxwdmz.acm-validations.aws."],
    ttl=300,
    type=aws.route53.RecordType.CNAME,
    zone_id="Z05927031BXKMYS37I62Z",
    opts = pulumi.ResourceOptions(protect=True))

nelsonfigueroa_dev_dmarc = aws.route53.Record("nelsonfigueroa-dev-dmarc",
    name="_dmarc.nelsonfigueroa.dev",
    records=["v=DMARC1; p=reject;"],
    ttl=300,
    type=aws.route53.RecordType.TXT,
    zone_id="Z05927031BXKMYS37I62Z",
    opts = pulumi.ResourceOptions(protect=True))

nelsonfigueroa_dev_dkim = aws.route53.Record("nelsonfigueroa-dev-dkim",
    name="*._domainkey.nelsonfigueroa.dev",
    records=["v=DKIM1; p="],
    ttl=300,
    type=aws.route53.RecordType.TXT,
    zone_id="Z05927031BXKMYS37I62Z",
    opts = pulumi.ResourceOptions(protect=True))

## Records for nelson.cloud
nelson_cloud_a = aws.route53.Record("nelson-cloud-a",
    aliases=[{
        "evaluate_target_health": False,
        "name": "d31hz6jsrwz1m1.cloudfront.net",
        "zone_id": "Z2FDTNDATAQYW2",
    }],
    name="nelson.cloud",
    type=aws.route53.RecordType.A,
    zone_id="Z032177030KUZLOKGS50G",
    opts = pulumi.ResourceOptions(protect=True))

nelson_cloud_aaaa = aws.route53.Record("nelson-cloud-aaaa",
    aliases=[{
        "evaluate_target_health": False,
        "name": "d31hz6jsrwz1m1.cloudfront.net",
        "zone_id": "Z2FDTNDATAQYW2",
    }],
    name="nelson.cloud",
    type=aws.route53.RecordType.AAAA,
    zone_id="Z032177030KUZLOKGS50G",
    opts = pulumi.ResourceOptions(protect=True))

nelson_cloud_mx = aws.route53.Record("nelson-cloud-mx",
    name="nelson.cloud",
    records=[
        "10 mx01.mail.icloud.com.",
        "20 mx02.mail.icloud.com.",
    ],
    ttl=300,
    type=aws.route53.RecordType.MX,
    zone_id="Z032177030KUZLOKGS50G",
    opts = pulumi.ResourceOptions(protect=True))

nelson_cloud_txt = aws.route53.Record("nelson-cloud-txt",
    name="nelson.cloud",
    records=[
        "apple-domain=shXv4LOydqcMHwRZ",
        "google-site-verification=hRsMFfsE6us1cN9TsKFQt7hUm700XU2SgLsZb6RJb4E",
        "v=spf1 include:icloud.com ~all",
    ],
    ttl=300,
    type=aws.route53.RecordType.TXT,
    zone_id="Z032177030KUZLOKGS50G",
    opts = pulumi.ResourceOptions(protect=True))

nelson_cloud_acm_validation = aws.route53.Record("nelson-cloud-acm-validation",
    name="_5870a8661430f6fdf996e15c8f998865.nelson.cloud",
    records=["_1e29dc944d393d448ca0f18f237ab593.stwyzjdjkd.acm-validations.aws."],
    ttl=300,
    type=aws.route53.RecordType.CNAME,
    zone_id="Z032177030KUZLOKGS50G",
    opts = pulumi.ResourceOptions(protect=True))

nelson_cloud_dmarc = aws.route53.Record("nelson-cloud-dmarc",
    name="_dmarc.nelson.cloud",
    records=["v=DMARC1; p=none"],
    ttl=300,
    type=aws.route53.RecordType.TXT,
    zone_id="Z032177030KUZLOKGS50G",
    opts = pulumi.ResourceOptions(protect=True))

nelson_cloud_dkim = aws.route53.Record("nelson-cloud-dkim",
    name="sig1._domainkey.nelson.cloud",
    records=["sig1.dkim.nelson.cloud.at.icloudmailadmin.com."],
    ttl=300,
    type=aws.route53.RecordType.CNAME,
    zone_id="Z032177030KUZLOKGS50G",
    opts = pulumi.ResourceOptions(protect=True))
