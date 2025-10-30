+++
title = "Validate HTTP Status Codes in Go Using Built-in Constants"
summary = "Use Go net/http constants like StatusOK and StatusNotFound for more readable code."
date = "2025-05-24"
categories = ["Go", "HTTP"]
keywords = ["Go HTTP", "HTTP status codes", "Go net/http constants", "net/http package", "Go web development", "Go HTTP response codes", "HTTP error codes Go", "Go net/http", "response handling Go", "REST API Go", "Go status constants", "http.StatusText", "golang http status codes", "golang net/http constants"]
ShowToc = true
TocOpen = true
+++

## Constants to Use When Checking for HTTP Status Codes

Go has useful [constants in the net/http package](https://pkg.go.dev/net/http#pkg-constants) that can make your code more readable when checking for status codes in responses.

For example, instead of writing something like

```go
if resp.StatusCode == 200 {
    // do something if the status code is 200
}
```

You can write
```go
if resp.StatusCode == http.StatusOK {
    // do something if the status code is 200
}
```

Unfortunately the the link doesn't show any any full, working examples. So here's an example covering some of the more common http status codes that you can use and modify as needed.

```go
package main

import (
	"fmt"
	"log"
	"net/http"
)

func main() {
	resp, err := http.Get("https://example.com")
	if err != nil {
		log.Fatalf("error: %v", err)
	}

	defer resp.Body.Close()

	// Status code 200
	if resp.StatusCode == http.StatusOK {
		fmt.Println("ok!")
	}

	// Status code 301
	if resp.StatusCode == http.StatusMovedPermanently {
		fmt.Println("moved permanently")
	}

	// Status code 403
	if resp.StatusCode == http.StatusForbidden {
		fmt.Println("forbidden")
	}

	// Status code 404
	if resp.StatusCode == http.StatusNotFound {
		fmt.Println("not found")
	}

	// Status code 429
	if resp.StatusCode == http.StatusTooManyRequests {
		fmt.Println("too many requests")
	}

	// Status code 500
	if resp.StatusCode == http.StatusInternalServerError {
		fmt.Println("internal server error")
	}

	// Status code 502
	if resp.StatusCode == http.StatusBadGateway {
		fmt.Println("bad gateway")
	}
}
```

## Turning Http Status Codes into Messsages with Statustext()

There's also [a function `http.StatusText()`](https://pkg.go.dev/net/http#StatusText) that allows you to pass in status codes and get a message for logging purposes or displaying to users. You can see all the responses in [the source code here](https://go.dev/src/net/http/status.go), it's a bunch of `case` statements.

For example, lets say we want to display a message if we don't get a 200 status code. Instead of writing code to print out a different message depending on the status code value, we can use the `http.StatusText()` function:

```go
package main

import (
	"fmt"
	"log"
	"net/http"
)

func main() {
	resp, err := http.Get("https://example.com/404") // this URL returns a 404 status code
	if err != nil {
		log.Fatalf("error: %v", err)
	}

	defer resp.Body.Close()

	if resp.StatusCode != http.StatusOK {
		// if it's not 200, print out a message depending on the status code
		fmt.Println(http.StatusText(resp.StatusCode)) // http.StatusText() is called here
	}
}
```

```
$ go run example.go

Not Found
```

Super convenient.

## References
- https://pkg.go.dev/net/http
- https://go.dev/src/net/http/status.go
