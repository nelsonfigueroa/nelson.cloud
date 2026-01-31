+++
title = "Adding Headers to HTTP Requests in Go"
summary = "Build requests with http.NewRequest() to add headers, then send them with http.Client{}"
date = "2026-01-30"
categories = ["Go"]
ShowToc = false
TocOpen = false
+++

Go has several ways of sending requests, including some convenient methods such as:
- `http.Get()`
- `http.Head()`
- `http.Post()`
- `http.PostForm()`

However, these don't let you add headers to requests! If you need customization of the HTTP method or headers, you need to use `http.NewRequest()`.

There are three parts to this:

1. Create a request using `http.NewRequest()` where you specify the HTTP method and URL
2. Add headers to the request with `Header.Set()`
3. Send the request using `http.Client{}`

Here's a full example:

```go
package main

import (
	"net/http"
)

func main() {
	// create the client that will send the request later
	client := &http.Client{}

	// create a request with NewRequest, specifying HTTP method and URL
	req, err := http.NewRequest("GET", "https://example.com/", nil)
	if err != nil {
		// handle any errors however you'd like
	}

	// add headers to the request
	req.Header.Set("User-Agent", "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:147.0) Gecko/20100101 Firefox/147.0")
	req.Header.Set("Accept-Language", "en-US,en;q=0.9")
	req.Header.Set("Accept-Encoding", "gzip, deflate")
	req.Header.Set("Sec-GPC", "1")
	req.Header.Set("Connection", "keep-alive")

	// use the client to actually send the request
	resp, err := client.Do(req)
	if err != nil {
		// handle any errors however you'd like
	}
	defer resp.Body.Close()
}
```

{{< admonition type="note">}}
`req.Header.Set()` can be used to set a header but will ***overwrite*** any existing value for that header.

`req.Header.Add()` can be used to add a value to a header and will **append** to any existing value for that header.

For the purposes of this blog post we only need to worry about setting the header once, hence the usage of `req.Header.Set()`.
{{< /admonition >}}

Similar to my ["Using time.Sleep() in Go"]({{< ref "/posts/2026-01-29-go-time-sleep.md" >}}) post, I wrote this up because the Go docs are too dense and I just needed one full example to understand it and get going.

## References

- https://pkg.go.dev/net/http
