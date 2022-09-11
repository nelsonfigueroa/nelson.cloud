+++
title = "Adding Environment Variables to Serverless Functions"
summary = "How to add environment variables to Serverless functions"
date = "2022-09-10"
categories = ["Serverless"]
toc = false
+++

Environment variables can be set for each individual Serverless function or at the provider level for all functions.

## Set Environment Variables for Individual Functions
To add environment variables to a Serverless function, define them using `environment` under the function name. In the example below, the function `function1` will have the environment variable `S3_BUCKET_NAME` defined:

```yaml
provider:
  name: aws
  runtime: python3.9
  stage: production
  region: us-west-1

functions:
  function1:
    handler: function.lambda_handler
    environment:
      S3_BUCKET_NAME: bucket1 # setting environment variable
```

## Set Environment Variables for All Functions
It's also possible to add environment variables to all functions in the Serverless template by defining variables under `provider:`. In the example below, the functions `function1` and `function2` will have the environment variable `S3_BUCKET_NAME` defined:

```yaml
provider:
  name: aws
  runtime: python3.9
  stage: production
  region: us-west-1
  environment:
    S3_BUCKET_NAME: my-bucket # setting env variable for all functions

functions:
  function1:
    handler: function1.lambda_handler
  function2:
    handler: function2.lambda_handler
```

## Combining Both Ways of Setting Environment Variables
Finally, it's possible to add environment variables under `provider` as well as under each function.

```yaml
provider:
  name: aws
  runtime: python3.9
  stage: production
  region: us-west-1
  environment: # env variables for all functions
    STAGE: production
    REGION: us-west-1

functions:
  function1:
    handler: function1.lambda_handler
    environment:
      S3_BUCKET_NAME: bucket1 # setting env variable for only function1

  function2:
    handler: function2.lambda_handler
    environment:
      TABLE_NAME: table # setting env variable for only function2
```

In this case, both `function1` and `function2` will have both the `STAGE` and `REGION` environment variables set, but `function1` will have `S3_BUCKET_NAME` set while `function2` will have `TABLE_NAME` set.


