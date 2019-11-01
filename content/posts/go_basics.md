+++
title = "A Quick Guide to Go"
description = "Learn Go Quickly"
date = "2019-10-31"
+++

<style>
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
}
</style>
<img src="/gopher.svg" alt="Go Gopher" class="center">

This post is a reiteration of notes that I took while learning Go. I think they could be useful to many others out there. Previous programming experience is recommended.

## Introduction

Go is a programming language developed by Google. It is also known as Golang. It's a statically typed and compiled language. I got interested in Go after seeing how readable its syntax is. My goal is to become good with an interpreted language and a compiled language. I was already decent at Ruby, so I started searching for compiled languages and weighed their pros and cons. I ultimately chose Go because I preferred its syntax over other languages like Java. It also helps that it's very popular amongst DevOps/Infrastructure teams and that's something I'm very interested in.

## Installing

You can download Go on the official Go site: [golang.org](https://golang.org/dl/). If you're on MacOS and have `brew` installed, you can run `brew install go`. On Linux distros, you can download and install Go using the package manager for your OS.

## Setup

Go has workspaces. The default workspace can be found be running the command:

```
go env GOPATH
```

The directory itself might not exist, but you can create it.

```
mkdir go
```

Inside the `go` directory, we'll need another directory: `src`

```
mkdir src
```

This is where all of our source code will go. It is inside this `src` directory where you can create additional directories for each of your projects. Let's make a "hello world" project:

```
mkdir hello
```

The path of the example directory should be `~/go/src/hello`.

## Go Basics

*Note: At any point while writing Go code, you can run `go fmt` in your terminal and Go will automatically format your code!*

Now, let's create our first go program. Create a file called `hello.go`:

```
touch hello.go
```

Go files can be named anything, although generally the main go file is named `main.go`. We can ignore that for now.

The first line of a go program needs to be the name of the package. Every program, at least the ones you want to be able to execute, will need the package `main`. Add the following line to the file:

```go
package main
```

The next section of a go program is the import section where you can import different packages. There are lots of packages you can discover in the official documentation. A popular one is `fmt` since it has functions related to input and output. Add the following

```go
package main

// you can list out several packages. no commas needed to separate them.
import (
	"fmt"
)
```

Next, we'll add the `main` function. This function is where the program starts, a function called `main` inside of the package `main`. It takes no arguments and doesn't return anything. Add a `main` function that prints out "hello world". Functions in go are declared with `func`. Function blocks are enclosed in curly braces.

```go
package main

import (
	"fmt"
)

func main() {
	fmt.Println("hello world")
}
```

Let's try running what we have so far:

```
go run hello.go
```

Go also allows us to compile the code into an executable. Let's try it. Notice that there is no need to specify the file this time:

```
go build
```

This will create an executable named after the project directory `example`. In Windows, this will be a `.exe` file. In this case it is an executable named `example`. You can run this executable and it'll print "hello world".

```
./example
```

Alternatively, you can run `go install` which is the same thing except the executable is put in a `bin` directory in the same location as the `src` folder we created (the `go` workspace). 
The directory path will be `~/go/bin` and in that directory you should see the `example` binary (assuming you're following this guide). If the program imported something from outside the standard library, it would compile and cache those dependencies in a `package` directory.

## Variables

In Go, we declare variables using the `var` keyword followed by the variable name and its type. We can then assign a value to this variable with `=`:

```go
package main

import (
	"fmt"
)

func main() {

	// declaring variable
	var x int

	// assigning value to variable
	x = 5

	fmt.Println(x)
}
```

We can also declare a variable and assign it a value in a single line:

```go
package main

import (
	"fmt"
)

func main() {

	// declare variable and assign value
	var x int = 5

	fmt.Println(x)
}
```

As with other languages, you can assign the result of a mathematical operation to a variable:

```go
package main

import (
	"fmt"
)

func main() {

	var x int = 5
	var y int = 10

	// add the two previous variables and assign to 'sum'
	var sum int = x + y

	fmt.Println(sum)
}
```

Go has a shorthand syntax we can use when declaring and assigning variables at the same time. We can ommit the `var` keyword as well as the variable type. Go will infer the value assigned and set an approriate type for the variable. The shorthand syntax makes use of `:=` to assign values:

```go
package main

import (
	"fmt"
)

func main() {

	// shorthand syntax for variable assignment
	x := 5
	y := 5
	sum := x + y

	fmt.Println(sum)
}
```

## Arrays

Arrays in Go are fixed. Arrays are also zero-indexed, so the first element is always at index 0. The size of the array is specified when declaring the array. We use the same `var` keyword to declare arrays along with the array size and type as shown below:

```	go
package main

import (
	"fmt"
)

func main() {
	// create an array that holds 5 integers
	var arr [5]int

	fmt.Println(arr)
}
```

We can then assign values at a specific index just like in any other language:

```go
package main

import (
	"fmt"
)

func main() {

	var arr [5]int

	// assign the value 7 at index 2
	arr[2] = 7

	fmt.Println(arr)
}
```

The Go shorthand syntax also exists for arrays. We can ues it to define an array along with values:

```go
package main

import (
	"fmt"
)

func main() {
	// shorthand syntax for arrays
	arr := [5]int{10, 15, 20, 25, 30}

	fmt.Println(arr)
}
```

**Quick note on default values**

When creating variables, if no value is assigned, a default value will be assigned. For integers, the default value is 0. For strings, it is an empty string. Arrays with no values assigned will also have default values. For example, an array of type integer will contain zeroes.

## Slices

Since arrays are fixed, we can't more elements than the specified size. We can get around this using array slices. Slices don't have a fixed number of elements. Slices are an abstraction of arrays to make them easier to work with. You do not necessarily need an existing fixed array to create a slice. The syntax is similar to fixed arrays, except we do not specify the array size:

```go
package main

import (
	"fmt"
)

func main() {
	// slice of integers, no element count needed
	slice := []int{10, 15, 20, 25, 30}

	fmt.Println(slice)
}
```

Since slices aren't fixed, we can take advantage of functions such as `append()` to add elements. `append()` doesn't modify the original slice, it returns a new one. Under the hood, Go will be creating a new array and copying things over.

```go
package main

import (
	"fmt"
)

func main() {

	slice := []int{10, 15, 20, 25, 30}

	// add the element 35
	slice = append(slice, 35)

	// prints [10 15 20 25 30 35]
	fmt.Println(slice)
}
```

## Maps

Maps hold key-value pairs. They're what Python calls "dictionaries" and what Ruby calls "hashes". Declaring a map in Go is a little different compared to declaring arrays and variables. We'll need to make use of the `make()` function and pass in the types for the map's keys and values. In the example below we'll be creating a map with keys that are of type `string` and values that are  of type `integer`:

```go
package main

import (
	"fmt"
)

func main() {

	// create a map called 'inventory'
	inventory := make(map[string]int)

	fmt.Println(inventory)
}
```

To add key-value pairs, it is similar to arrays:

```go
package main

import (
	"fmt"
)

func main() {

	inventory := make(map[string]int)

	// add key-value pairs
	inventory["apples"] = 3
	inventory["oranges"] = 10
	inventory["peaches"] = 8

	fmt.Println(inventory)	
}
```

We can use the same syntax to get a value for a specific key:

```go
package main

import (
	"fmt"
)

func main() {

	inventory := make(map[string]int)

	inventory["apples"] = 3
	inventory["oranges"] = 10
	inventory["peaches"] = 8

	// print out corresponding values
	fmt.Println(inventory["oranges"])
	fmt.Println(inventory["apples"])
	fmt.Println(inventory["peaches"])
}
```

We can use the `delete()` function to remove key-pairs. We need to pass in the map and the key name:

```go
package main

import (
	"fmt"
)

func main() {

	inventory := make(map[string]int)

	inventory["apples"] = 3
	inventory["oranges"] = 10
	inventory["peaches"] = 8

	// before deleting
	fmt.Println(inventory)

	// delete key 'apples' along with its value
	delete(inventory, "apples")

	// after deleting
	fmt.Println(inventory)
}
```

## If-Else Statements

If-Else statements are not much different compared to other languages. Go does not require the comparison to be enclosed in parentheses.

```go
package main

import (
	"fmt"
)

func main() {

	x := 5

	// no parentheses needed around comparisons
	if x > 1 {
		fmt.Println("x is more than 1")
	} else if x < 5 {
		fmt.Println("x is less than 5")
	} else {
		fmt.Println("x is out of range")
	}
}
```

## Loops

The only type of loop in Go is the `for` loop. It is similar to `for` loops in other languages. We define a counter, a stopping condition, and a counter increment/decrement. See the example below:

```go
package main

import (
	"fmt"
)

func main() {

	// create a for loop
	for i := 0; i < 5; i++ {
		fmt.Println(i)
	}

}
```

The `for` loop can also double as a `while` loop by rearranging the counter and increment/decrement as such:

```go
package main

import (
	"fmt"
)

func main() {

	// counter
	i := 0

	// for loop acting as a while loop
	for i < 5 {
		fmt.Println(i)

		// increment
		i++
	}

}
```

We can use `for` loops to iterate over each element in an array or slice by using the `range` keyword:

```go
package main

import (
	"fmt"
)

func main() {

	// create a slice of strings
	arr := []string{"a", "b", "c"}

	// loop over each element of slice
	for index, value := range arr {
		fmt.Println("index:", index, "value:", value)
	}

}
```

We can do the same thing with a map. Instead of looping over an index, we'll loop over a key:

```go
package main

import (
	"fmt"
)

func main() {

	// create a map
	inventory := make(map[string]int)
	inventory["apples"] = 3
	inventory["oranges"] = 10
	inventory["peaches"] = 8

	// loop over each key/value of map
	for key, value := range inventory {
		fmt.Println("key:", key, "value:", value)
	}

}
```

## Functions

So far we've been running everything in the `main()` function. We can create our own custom functions and call them in the `main()` function.

```go
package main

import (
	"fmt"
)

func main() {

	// calling custom function
	hello()
}

// custom function
func hello() {
	fmt.Println("hello!")
}
```

We can also create functions that accept parameters. To specify a parameter, write the variable name followed by its type. Multiple parameters are separated by commas:

```go
package main

import (
	"fmt"
)

func main() {

	// passing in parameters
	sum(3, 4)
}

// function with parameters
func sum(x int, y int) {
	sum := x + y
	fmt.Println("The sum is:", sum)
}
```

Instead of printing the sum, we can return it. If a function returns a value, the type of the value returned must be specified after the function name:

```go
package main

import (
	"fmt"
)

func main() {

	// assigning returned value to variable
	result := sum(3, 4)

	fmt.Println(result)	
}

// function that returns an integer
func sum(x int, y int) int {
	sum := x + y
	return sum
}
```

In Go, functions can have multiple return values. For example, a function that returns both an integer and a boolean would list the return types in parentheses after the function name. We return the values in the same order:

```go
package main

import (
	"fmt"
)

func main() {

	result, pos := multiply(10, -10)

	fmt.Println("Result:", result)
	fmt.Println("Positive?:", pos)
}

// function that returns an integer and boolean
func multiply(x int, y int) (int, bool) {

	var positive bool

	product := x * y

	if product > 0 {
		positive = true
	} else {
		positive = false
	}

	// return int, bool as defined in function
	return product, positive
}
```

## Structures

It is important to know that there are no classes in Go. Instead, there are Structures (or Structs), which are similar to classes. Structs consist of several typed attributes that form a single entity. It's probably easier to create a struct yourself to understand it:

```go
package main

import (
	"fmt"
)

// struct for Product
type Product struct{
	name string
	price float64
	available bool
}

func main() {

	// create new Product struct with attributes and assign to variable
	p := Product{name: "Laptop", price: 499.99, available: true}

	fmt.Println(p)

	// print out a single attribute using dot notation
	fmt.Println(p.name)
}
```
