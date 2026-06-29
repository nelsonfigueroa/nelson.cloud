+++
title = "You Can Detect if Code Is Being Run Inside a Terminal"
summary = "Most programming languages have a way to detect if it's being run in a terminal."
date = "2026-06-28"
categories = ["Shell"]
ShowToc = false
TocOpen = false
featured = false
+++

I learned about the `sys.stdout.isatty()` method in Python which allows you to detect if your code is running in a terminal. For example:

```python
import sys

if sys.stdout.isatty():
    print("This program is running in a terminal!")
else:
    print("This program is running somewhere else, like a CI/CD pipeline")
```

Running the program outputs the following:

```console
$ python3 tty.py
This program is running in a terminal!
```

But if we pipe the program into some other tool, like `cat`, it is no longer running in a terminal and prints the other message:

```console
$ python3 tty.py | cat
This program is running somewhere else, like a CI/CD pipeline
```

This is why some colorized output from code is not colorized in CI/CD pipelines. The code itself checks if it's being run in a terminal, and if not, it doesn't display colors that would result in junk being displayed due to the nature of [ANSI escape codes](https://en.wikipedia.org/wiki/ANSI_escape_code#Colors) used in colorization. I always thought that it was the CI/CD systems that suppressed color output but that's not necessarily the case.

This capability is not unique to Python. Other languages support this too.

In JavaScript:

```javascript
if (process.stdout.isTTY) {
  console.log("This program is running in a terminal!");
} else {
  console.log("This program is running somewhere else, like a CI/CD pipeline");
}
```

In Ruby:

```ruby
if $stdout.tty?
  puts "This program is running in a terminal!"
else
  puts "This program is running somewhere else, like a CI/CD pipeline"
end
```

In Bash:

```bash
if [ -t 1 ]; then
  echo "This program is running in a terminal!"
else
  echo "This program is running somewhere else, like a CI/CD pipeline"
fi
```

Go doesn't have a built-in method to detect if code is being run inside a terminal, so we need to import the `x/term` package:

```go
package main

import (
	"fmt"
	"os"

	"golang.org/x/term"
)

func main() {
	if term.IsTerminal(int(os.Stdout.Fd())) {
		fmt.Println("This program is running in a terminal!")
	} else {
		fmt.Println("This program is running somewhere else, like a CI/CD pipeline")
	}
}
```
