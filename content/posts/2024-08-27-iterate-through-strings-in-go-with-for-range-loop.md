+++
title = "Iterate Through Strings in Go with a for-range Loop"
summary = "You can use for-range loops to iterate through strings in Go without splitting because Go handles strings as byte slices."
date = "2024-08-27"
categories = ["Go"]
keywords = ["Go strings iteration", "Go loop through string", "for range loop go", "golang strings iteration", "golang loop through string", "Go string bytes", "golang string bytes"]
ShowToc = false
TocOpen = false
+++

Today I learned you can use `for...range` loops in Go to iterate over a string. There is no need to split the string first like in other programming languages.

Here's an example:
```go
package main

import "fmt"

func main() {
	for i, v := range "testing" {
		fmt.Printf("index: %d, char: %d\n", i, v)
	}
}
```

This outputs:

```
index: 0, char: t
index: 1, char: e
index: 2, char: s
index: 3, char: t
index: 4, char: i
index: 5, char: n
index: 6, char: g
```

Without any formatting from `fmt.Printf` each character in the string is printed out in its Unicode form:

```go
package main

import "fmt"

func main() {
	for _, v := range "testing" {
		fmt.Println(v)
	}
}
```

This outputs:

```
116
101
115
116
105
110
103
```

This is cleaner than looping through a string using a `for` loop without `range`:

```go
package main

import "fmt"

func main() {
	str := "testing"

	for i := 0; i < len(str); i++ {
		fmt.Printf("index: %d, char: %c\n", i, str[i])
	}
}
```

As a side note, strings in Go are basically slices of bytes. This is why we can iterate through them using `range` like we would a typical slice.

From https://go.dev/blog/strings:
> In Go, a string is in effect a read-only slice of bytes

## References
- https://go.dev/tour/moretypes/16
- https://www.freecodecamp.org/news/iteration-in-golang/
- https://go.dev/blog/strings
