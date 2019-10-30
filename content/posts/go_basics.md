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

// you can list out several packages, just add to the list. no commas needed to separate them.
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

This will create an executable named after the project directory `example`. In Windows, this will be a `.exe` file. In this case it is an executable named `example`. You can run this executable and it'll print `hello world`.

```
./example
```

Alternatively, you can run `go install` which is the same thing except the executable is put in a `bin` directory in the same location as the `src` folder we created (the `go` workspace). 
The path should be `~/go/bin` and in that directory you should see the `example` binary.
If the program had external dependencies like if we imported something from outside the standard library, it would compile and cache those dependencies in a `package` directory i think in the same go workspace.

## Variables

if no initial value is provided for integers the default value is 0
for strings it is simply an empty string

for arrays, number of elements that an array holds is fixed