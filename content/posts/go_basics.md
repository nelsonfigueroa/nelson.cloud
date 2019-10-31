+++
title = "Teaching Myself Go"
description = "Learning Go"
date = "2019-10-31"
+++

This post will mostly be notes that I took while learning Go. I think they could be useful to many others out there. Previous programming experience is recommended, as I will simply explain how to do things in Go (i.e. arrays) as opposed to explaining programming fundamentals.


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
The path should be `~/go/bin` and in that directory you should see the `example` binary.
If the program had external dependencies like if we imported something from outside the standard library, it would compile and cache those dependencies in a `package` directory.

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

	// shorhand syntax for variable assignment
	x := 5
	y := 5
	sum := x + y

	fmt.Println(sum)
}
```

## Arrays

Arrays in Go are fixed. The size of the array is specified when declaring the array. We use the same `var` keyword to declare arrays along with the array size and type as shown below:

```	go
package main

import (
	"fmt"
)

func main() {
	// create an array that holds 5 integers
	var arr [5]int

	fmt.Println(a) // will print out an array full of zeros (default values)
}
```

```go
	//set element at index 2 to a specific value
	a[2] = 7
	fmt.Println(a)

	// shorthand syntax for arrays
	a := [5]int{5, 4, 3, 2, 1}
	fmt.Println(a)

	// can't add a 6the element because array length is part of array's type
	// you can get around this with slices
	// slices don't have a fixed number of elements
	// slices are an abstraction of the top of arrays to make them easier to work with
	
	// create a slice of integers instead of an arary of integers
	// by simply removing the element count
	a := []int{5, 4, 3, 2, 1}
	fmt.Println(a)
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

## Default Values

if no initial value is provided for integers the default value is 0
for strings it is simply an empty string
for arrays, number of elements that an array holds is fixed