+++
title = "Insertion Order Iteration of Maps in Go"
summary = "Iterating through Go maps in insertion order."
date = "2021-02-23"
lastmod = "2024-01-23"
categories = ["Go"]
keywords = ["Go maps", "map iteration", "insertion order", "Go programming", "ordered maps", "Go slices", "map keys", "Go tutorial", "data structures", "Go basics"]
+++

In Go, map contents are randomized. Go doesn't care about insertion order. Elements in a map are always random when iterating through them.

To iterate through map contents in insertion order, we need to create a slice that keeps track of each key. Then we iterate through this slice and use its contents (which are the map keys) to access the map's values in the order in which they were inserted:

```go
package main

import "fmt"

func main() {
	m := make(map[string]int)
	m["a"] = 0
	m["b"] = 1
	m["c"] = 2
	m["d"] = 3
	m["e"] = 4
	m["f"] = 5

	// store map keys in a slice
	keys := []string{"a", "b", "c", "d", "e", "f"}

	// iterate through "keys" slice to get map values in insert order
	// the underscore is there because we wont be using the first value, which is the index of the slice
	for _, key := range keys {
		fmt.Println("Key:", key, "\t", "Value:", m[key])
	}
}
```

```
$ go run example.go

Key: a 	 Value: 0
Key: b 	 Value: 1
Key: c 	 Value: 2
Key: d 	 Value: 3
Key: e 	 Value: 4
Key: f 	 Value: 5
```

Now each key and value is printed in insertion order.


## Additional Notes

The following example shows how iterating through a map won't print out values in order even if assignment was done in a certain order:

```go
// example.go

package main

import "fmt"

func main() {
	m := make(map[string]int)

	m["a"] = 0
	m["b"] = 1
	m["c"] = 2
	m["d"] = 3
	m["e"] = 4
	m["f"] = 5

	// iterate through map and print each key, value
	for key, value := range m {
		fmt.Println("Key:", key, "\t", "Value:", value)
	}
}
```

And the output is:

```
$ go run example.go

Key: e 	 Value: 4
Key: f 	 Value: 5
Key: a 	 Value: 0
Key: b 	 Value: 1
Key: c 	 Value: 2
Key: d 	 Value: 3
```

Interestingly, printing a map without a loop prints elements in insertion order:

```go
// example.go

package main

import "fmt"

func main() {
	m := make(map[string]int)

	m["a"] = 0
	m["b"] = 1
	m["c"] = 2
	m["d"] = 3
	m["e"] = 4
	m["f"] = 5

	// print map directly
	fmt.Println(m)
}
```

```
$ go run example.go

map[a:0 b:1 c:2 d:3 e:4 f:5]
```
