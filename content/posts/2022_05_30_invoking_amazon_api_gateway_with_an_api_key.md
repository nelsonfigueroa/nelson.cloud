+++
title = "Invoking Amazon API Gateway with an API Key"
summary = "Invoking Amazon API Gateway with an API Key"
date = "2022-05-30"
categories = ["AWS"]
toc = true
+++

To invoke an Amazon API Gateway with an API Key we need to pass in the API key in a `x-api-key` header.
For the following examples, assume the invoke URL is `https://12abcde45.execute-api.us-west-1.amazonaws.com/prod/create` and the API key is `abc123`.

## Invoking with curl
To invoke this API with `curl` it would look like this:

```sh
# GET request
curl --header "x-api-key: abc123" https://12abcde45.execute-api.us-west-1.amazonaws.com/prod/create
```

```sh
# POST request with data
curl -d "key1=value1&key2=value2" --header "x-api-key: abc123" -X POST https://12abcde45.execute-api.us-west-1.amazonaws.com/prod/create
```

## Invoking with HTTPie
To invoke the API with [HTTPie](https://httpie.io/):

```sh
# GET request
http https://12abcde45.execute-api.us-west-1.amazonaws.com/prod/create x-api-key:abc123
```

```sh
# POST request with data
http post https://12abcde45.execute-api.us-west-1.amazonaws.com/prod/create key1=value1 x-api-key:abc123

```

## Invoking with Python

`GET` request:

```python
import requests

url = "https://12abcde45.execute-api.us-west-1.amazonaws.com/prod/create"

# API key specified as a header
# Key hardcoded for demonstrational purposes. Do not push/commit plaintext keys!
headers = {"x-api-key": "abc123"}

# GET request with custom header
response = requests.get(url, headers=headers)
print(response.status_code)

```

`POST` request with data:

```python
import requests

url = "https://12abcde45.execute-api.us-west-1.amazonaws.com/prod/create"

# API key specified as a header
# Key hardcoded for demonstrational purposes. Do not push/commit plaintext keys!
headers = {"x-api-key": "abc123"}

# Data to be sent
data = {"key1": "value1"}

# POST request with custom header and data
response = requests.post(url, json=data, headers=headers)
print(response.status_code)

```