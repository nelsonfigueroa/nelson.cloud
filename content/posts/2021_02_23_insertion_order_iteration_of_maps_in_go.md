+++
title = "Insertion Order Iteration of Maps in Go"
summary = "Iterating through Go maps in insertion order."
date = "2021-02-23"
categories = ["Go"]
toc = false
+++

Recently, I discovered that map contents in Go are randomized. This was odd to me coming from Ruby. In Ruby, iterating through a map (called "hash" in Ruby) is always in insertion order:

```ruby
# example.rb

# define the hash
h = { a: 0,
      b: 1,
      c: 2,
      d: 3,
      e: 4,
      f: 5 }

# iterate through hash and print each key, value
h.each do |key, value|
  puts "Key: #{key}, Value: #{value}"
end
```

```
$ ruby example.rb

Key: a, Value: 0
Key: b, Value: 1
Key: c, Value: 2
Key: d, Value: 3
Key: e, Value: 4
Key: f, Value: 5
```

But Go doesn't care about insertion order. Elements in a map are always random when iterating through them:

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

```
$ go run example.go

Key: b 	 Value: 1
Key: c 	 Value: 2
Key: d 	 Value: 3
Key: e 	 Value: 4
Key: f 	 Value: 5
Key: a 	 Value: 0
```

What threw me off even more is that printing the map without a loop prints elements in insertion order:

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

Ruby, unsurprisingly, also prints elements in insertion order.

```ruby
# example.rb

# define the hash
h = { a: 0,
      b: 1,
      c: 2,
      d: 3,
      e: 4,
      f: 5 }

# print the hash
puts h
```

```
$ ruby example.rb

{:a=>0, :b=>1, :c=>2, :d=>3, :e=>4, :f=>5}
```

## The Solution

To iterate through map contents in insert order, we need to create a slice that keeps track of each key. Then, instead of iterating through the map, we iterate through the slice and use its contents (which are the map keys) to access the map's values in the order in which they were inserted:

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