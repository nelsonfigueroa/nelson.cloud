+++
title = "Using time.Sleep() in Go"
summary = "Examples of using time.Sleep() in Go because the official documentation is lacking."
date = "2026-01-29"
categories = ["Go"]
ShowToc = true
TocOpen = true
+++

## Basic Usage of `time.Sleep()`

You can use `time.Sleep()` to pause your program for a predetermined amount of time, similar to most programming languages.


First, you should know that the `time` package has useful constants that allow you to conveniently specify time in units.
```go
const (
	Nanosecond  Duration = 1
	Microsecond          = 1000 * Nanosecond
	Millisecond          = 1000 * Microsecond
	Second               = 1000 * Millisecond
	Minute               = 60 * Second
	Hour                 = 60 * Minute
)
```

They're accessed with the `time.<Constant>` notation. (e.g. `time.Second`)

You can use these constants with the `time.Sleep()` function. For example, if we want to pause execution for 1 second, we can write the following code:

```go
package main

import (
	"fmt"
	"time"
)

func main() {
	fmt.Println("one second will pass between this message...")
	time.Sleep(time.Second)
	fmt.Println("...and this message")
}
```

We can do some multiplication with `time.Second` to pause a program for 30 seconds:

```go
package main

import (
	"fmt"
	"time"
)

func main() {
	fmt.Println("30 seconds will pass between this message...")
	time.Sleep(time.Second * 30)
	fmt.Println("...and this message")
}
```

You can do multiplication with all of the other constants mentioned as well.


## Examples

Here are some more examples using the other constants.

### Sleep for 500 milliseconds

```go
package main

import (
	"time"
)

func main() {
	time.Sleep(time.Millisecond * 500)
}
```

### Sleep for 10 seconds

```go
package main

import (
	"time"
)

func main() {
	time.Sleep(time.Second * 10)
}
```

### Sleep for 5 minutes

```go
package main

import (
	"time"
)

func main() {
	time.Sleep(time.Minute * 5)
}
```

### Sleep for 2 hours
Not really sure why you would use this, but just know it's possible.
```go
package main

import (
	"time"
)

func main() {
	time.Sleep(time.Hour * 2)
}
```

## References
- https://pkg.go.dev/time

My main motivation for writing this is that I think the official documentation is way too dense and doesn't show several examples of `time.Sleep()`. I just needed some examples to understand the syntax and move on with my day.
