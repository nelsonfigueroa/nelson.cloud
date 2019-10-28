+++
title = "Teaching Myself Go"
description = "Learning Go"
date = "2019-10-31"
+++

This post will mostly be notes that I took while learning Go. I think they could be useful to many others out there. Previous programming experience is recommended, as I will simply explain how to do things in Go (i.e. arrays) as opposed to explaining programming fundamentals.


Go has workspaces

```
go env GOPATH
```

This is the path. The directory itself might not exist, but you can create it.

```
mkdir go
```

Inside it we need another folder: `src`

```
mkdir src
```

that's where all our source code will go
inside this folder you can create an additional directory for each of your projects

```
mkdir example
```

the path of the directory should be `~/go/src/example`

generally go files are named `main.go` but it can be named anything

```
touch hello.go
```

The first line of a go program needs to be the name of the package. Every program, at least the ones you want to be able to execute, will need the package `main`.

Next section of a go program is the `import` section, where you can import different packages. There are lots of packages you can discover in the official documentation. A popular one is `fmt` since it has functions related to input and output.

the `main` function is where the program starts, a function called `main` inside of the package `main`. It takes no arguments and doesn't return anything.

Run a program

```
go run hello.go
```

If you want to compile the code into an executable. no need to specify file

```
go build
```

you'll have an executable named after the project directory `example`
in windows, it'll be a `.exe` file
you can run this executable and it'll print `hello world`

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