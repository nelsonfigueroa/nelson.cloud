+++
title = "Learning Go"
description = "Learning Go basics"
date = "2019-10-31"
categories = ["programming"]
tags = ["golang", "programming"]
draft = true
+++

## Introduction

Go is a programming language developed by Google. It is also known as Golang. It's a statically typed and compiled language. 

Key features of Go include: 
- Relatively simple syntax
- Fast compile times 
- Garbage collection 
- Built-in concurrency
- Compilation to standalone binaries.

Previous programming experience is recommended as I will not be explaining programming concepts in-depth.

## Following Along

### Go online playground

Go has an online playground to test out code: [play.golang.org](https://play.golang.org/). Feel free to use this site to follow along. You can also install Go if you prefer.

### Installing Go

You can download Go on the official Go site: [golang.org](https://golang.org/dl/). If you're on MacOS and have `brew` installed, you can run `brew install go`. On Linux distros, you can download and install Go using the package manager for your OS.

To verify that all is well, you can check for the currently installed Go version by running:

```
$ go version

go version go1.13.7 darwin/amd64
```

### Environment Setup

*(This section only applies if you choose to install Go on your machine.)*

Go has an environment variabled called `GOROOT` that tells the environment where to find the Go binaries. It is not necessary to set this variable manually if you've installed Go in its default location. You can check where the Go binaries are installed by running:

```
$ go env GOROOT

/usr/local/Cellar/go/1.13.7/libexec
```

Go also has an environment called `GOPATH` that specifies where Go projects are located. The variable can be modified if the location does not suit you. You see the default path by running:

```
$ go env GOPATH

/Users/nelson/go
```

The directory itself might not exist, but you can create it.

Browse into the `go` directory and create three directories, `src`, `bin`, and `pkg`.

The `src` directory is where all of our source code will go. It is inside this `src` directory where you can create additional directories for each of your projects. The `bin` directory will hold binaries when we compile code. The `pkg` directory will hold intermediate binaries used when compiling, such as third-party libraries that we might import.

Let's make a "hello world" project in the `src` directory by creating a directory for the project itself:

```
$ mkdir hello
```

And we'll create a `hello.go` file in our newly created directory:

```
$ cd hello
$ touch hello.go
```

The final layout of the `go` directory should look like the following:

```
$ tree

.
├── bin
├── hello
│   └── hello.go
├── pkg
└── src
```

Go files can be named anything, although generally the main go file is named `main.go`. We can ignore that for now.

## 'Hello World' in Go

Now we're ready to start writing our very first Go program.

Every Go application is structured into packages. Every Go file will have to declare what package it is a part of. The package `main` is special because it will be the entrypoint to the application. Add the following line to the `hello.go` file:

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
  fmt.Println("Hello world!")
}
```

Let's try running what we have so far:

```
$ go run hello.go

Hello world!
```

Go also allows us to compile the code into an executable. Let's try it. Notice that there is no need to specify the file this time:

```
$ go build
```

This will create an executable named after the project directory `hello`. In Windows, this will be a `.exe` file. In this case it is an executable named `hello`. You can run this executable and it'll print "hello world".

```
$ ./hello

Hello world!
```

Alternatively, you can run `go install` which is the same thing except the executable is put in the `bin` directory we created earlier. If the program imported something from outside the standard library, it would compile and cache those dependencies in a `package` directory.

> **Note:** At any point while writing Go code, you can run `go fmt` in your terminal and Go will automatically format your code. If you're using the online Go Playground, there is a 'Format' button that does this same action.


## Declaring Variables

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

It is possible to declare variables at the package level. However, you cannot use the `:=` operator when declaring variables at the package level. You'll need to use the full declaration syntax. For example:

```go
package main

import (
  "fmt"
)

// declaring a variable at the package level
var i int = 420

func main() {
  fmt.Println(i)
}
```

Keep in mind that declaring variables at the package level beginning with a lowercase letter will limit that variable to the package. If the variable begins with an uppercase letter, it will be exported and globally visibile.

When declaring several variables, it can get repetitive using the `var` keyword. Declaring many variables can also get messy in the long run. Go has a way of declaring multiple variables in a block with a single `var` keyword as such:

```go
package main

import (
  "fmt"
)

// declaring several variables in a block
var (
  name string = "Nelson"
  favoriteGame string = "Red Dead Redemption 2"
  gameRating int = 10
)

func main() {
  fmt.Println(name + " likes " + favoriteGame + " and rates it a", gameRating , "out of 10.")
}
```

Similar to other programming language, the variable declared with the inner-most scope will take precedence. For example, if the same variable is declared at the package level and at the `main()` level, the one at the `main()` level will take precedence. This is referred to as "shadowing":

```go
package main

import (
  "fmt"
)

// package level variable
var i int = 10

func main() {
  var i int = 25

  // prints out 25 as opposed to 10
  fmt.Println(i)
}
```

The variable `i` only gets reassigned after the second declaration, so if we were to put a print statement right before the declaration, it would print out 10.

```go
package main

import (
  "fmt"
)

var i int = 10

func main() {
  // prints out 10
  fmt.Println(i)

  var i int = 25

  // prints out 25
  fmt.Println(i)
}
```

An important thing to know about Go is that all variables declared must be used. If you declare a variable and don't use it, the program will not run and you'll get an error. For example, running the program below:

```go
package main

import (
  "fmt"
)

func main() {
  i := 5
  x := 10

  fmt.Println(i)
}
```

Will result in the following error:

```
./hello.go:9:3: x declared and not used
```

This is done to keep code clean. When codebases grow large and features are deprecated, there's a good chance that old, unused code will stick around. Go helps to detect that.

## Variable Conversions

To convert variables in Go you will need to use appropriate conversion functions. For example, to convert an `int` variable to a `float32` variable we would use the `float32()` function. An example is shown below:

```go
package main

import (
  "fmt"
)

func main() {
  var i int = 10
  fmt.Printf("%v, %T", i, i)

  fmt.Println()

  var j float32
  j = float32(i) // convert int to float32, assign to j
  fmt.Printf("%v, %T", j, j)
}
```

In the `Printf()` function, `%v` is used to print out the value, while `%T` prints out the type.
The output should be:

```
10, int
10, float32
```

> **Note:** Be careful when converting values. If a `float32` value of, say, 100.25 gets converted to an `int` value, it will lose its decimal and result in only 100.

Converting an integer to a string is a little more involved. If we try to use the `string()` conversion function on an integer, Go will look for the unicode value set at the number.

```go
package main

import (
  "fmt"
)

func main() {
  var i int = 47
  fmt.Printf("%v, %T", i, i)

  fmt.Println()

  var j string
  j = string(i)
  fmt.Printf("%v, %T", j, j)
}
```

The output will be:

```
47, int
/, string
```

Because the `/` character is at unicode value at number 47. If we want the expected result of `"47"` then we'll need to import the `strconv` package and use its `Itoa()` function which converts an integer into an ASCII string:

```go
package main

import (
  "fmt"
  "strconv" // import string conversion package
)

func main() {
  var i int = 47
  fmt.Printf("%v, %T", i, i)

  fmt.Println()

  var j string
  // convert to string
  j = strconv.Itoa(i)
  fmt.Printf("%v, %T", j, j)
}
```

Now the result is as expected:

```
47, int
47, string
```

## Arrays

Arrays in Go are fixed. Arrays are also zero-indexed, so the first element is always at index 0. The size of the array is specified when declaring the array. We use the same `var` keyword to declare arrays along with the array size and type as shown below:

```go
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

> **Note:** When creating variables, if no value is assigned, a default value will be assigned. For integers, the default value is 0. For strings, it is an empty string. An array of type integer, like the one we created, will contain zeroes if we don't specify values.

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

We can do the same thing with a map. Instead of looping over an index, we'll loop over a key/index:

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

// custom function
func hello() {
  fmt.Println("hello!")
}

func main() {

  // calling custom function
  hello()
}

```

We can also create functions that accept parameters. To specify a parameter, write the variable name followed by its type. Multiple parameters are separated by commas:

```go
package main

import (
  "fmt"
)

// function with parameters
func sum(x int, y int) {
  sum := x + y
  fmt.Println("The sum is:", sum)
}

func main() {

  // passing in parameters
  sum(3, 4)
}

```

Instead of printing the sum, we can return the actual value. If a function returns a value, the type of the value returned must be specified after the function name:

```go
package main

import (
  "fmt"
)

// function that returns an integer
func sum(x int, y int) int {
  sum := x + y
  return sum
}

func main() {

  // assigning returned value to variable
  result := sum(3, 4)

  fmt.Println(result)
}

```

In Go, functions can have multiple return values. For example, a function that returns both an integer and a boolean would list the return types in parentheses after the function name. We return the values in the same order:

```go
package main

import (
  "fmt"
)

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

func main() {

  result, pos := multiply(10, -10)

  fmt.Println("Result:", result)
  fmt.Println("Positive?:", pos)
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

## Conclusion

What I covered here should be enough to get started with Go. All that's left to do is to practice before diving into deeper Go functionality.

Thanks for reading, and I hope this helped!