+++
title = "Terminal Colors Are Just Text"
summary = "ANSI escape codes are used to colorize text in a terminal."
date = "2026-07-01"
categories = ["Shell"]
ShowToc = false
TocOpen = false
featured = false
+++

Terminal colors are determined by [ANSI escape codes](https://en.wikipedia.org/wiki/ANSI_escape_code), which are just strings of text.

The ANSI escape code for a red color is `\033[31m`. If we prepend that to a string that we print out to the terminal, the string comes out red. This works regardless of the programming language. Here's an example in Python:

```python
print("\033[31mthis text is red")
```

We can split out the ANSI escape code to make it easier to see the two parts:

```python
red_ansi_code = "\033[31m"
message = "this text is red"
print(red_ansi_code + message)
```

Both code snippets above produce the following output:

<pre><code><span style="color:#c01c28">this text is red</span></code></pre>

We can mix colors too. Here is an example with green and blue as well as red. We use the escape code `\033[0m` to terminate the colorization of text after each string.
```python
print("\033[31mred text\033[0m \033[32mgreen text\033[0m \033[34mblue text\033[0m")
```

We can split things out once again to make it more legible:

```python
red_ansi_code = "\033[31m"
green_ansi_code = "\033[32m"
blue_ansi_code = "\033[34m"
terminator = "\033[0m"

print(
    red_ansi_code
    + "red text "
    + terminator
    + green_ansi_code
    + "green text "
    + terminator
    + blue_ansi_code
    + "blue text"
    + terminator
)
```

The two snippets above print:

<pre><code><span style="color:#c01c28">red text <span style="color:#26a269">green text <span style="color:#2a7bde">blue text</span></span></span></code></pre>

Libraries exist to make this easier since ANSI codes look like nonsense among normal text. For example, there is a `termcolor` library for Python that lets you wrap text and specify a color.

```python
# import the colored() function from the termcolor library
from termcolor import colored

print(
    colored("red text", "red"),
    colored("green text", "green"),
    colored("blue text", "blue"),
)
```

That code has the same output as before:

<pre><code><span style="color:#c01c28">red text <span style="color:#26a269">green text <span style="color:#2a7bde">blue text</span></span></span></code></pre>

The `colored` function from the `termcolor` library makes it much cleaner to colorize terminal output.

There are many ANSI codes for colors. Here's a few examples of different colors:

| Color   | Code | Escape String | Output |
|---------|------|---------------|------------|
| Red     | `31` | `\033[31m`    | <span style="color:#c01c28">red text</span> |
| Green   | `32` | `\033[32m`    | <span style="color:#26a269">green text</span> |
| Yellow  | `33` | `\033[33m`    | <span style="color:#c4a000">yellow text</span> |
| Blue    | `34` | `\033[34m`    | <span style="color:#2a7bde">blue text</span> |
| Magenta | `35` | `\033[35m`    | <span style="color:#a347ba">magenta text</span> |
| Cyan    | `36` | `\033[36m`    | <span style="color:#2aa1b3">cyan text</span> |

There are [so many more ANSI escape codes](https://gist.github.com/fnky/458719343aabd01cfb17a3a4f7296797) to change the color of text, the background color, and even to underline and bold text.

It's useful to know the general look of ANSI escape codes because they may show up in other places. From personal experience, I've seen these codes being printed out along with machine logs and it just looked like gibberish. Now I know that all that gibberish was actually ANSI escape codes that weren't being rendered. There was code somewhere that was printing colorized output without [checking if it was running in a terminal or not]({{< ref "/posts/2026-06-28-you-can-detect-if-code-is-being-run-inside-a-terminal.md" >}}).

Here's an example of raw ANSI codes being printed out from a Rust build with warnings. Take this Rust code:

```rust
fn main() {
    let year = 2026;
    println!("hello world");
}
```

If we try to build it with `cargo`, we get a warning due to the unused `year` variable. We can build it with the `--color=always` option to force colorization. Then we can write the warning to `build.log` and print out `build.log` with `cat -v` so that it reveals raw ANSI codes:

```console
$ cargo build --color=always &> build.log
$ cat -v build.log

^[[1m^[[33mwarning^[[0m^[[1m: unused variable: `year`^[[0m
 ^[[1m^[[94m--> ^[[0msrc/main.rs:2:9
  ^[[1m^[[94m|^[[0m
^[[1m^[[94m2^[[0m ^[[1m^[[94m|^[[0m     let year = 2026;
  ^[[1m^[[94m|^[[0m         ^[[1m^[[33m^^^^^[[0m ^[[1m^[[33mhelp: if this is intentional, prefix it with an underscore: `_year`^[[0m
  ^[[1m^[[94m|^[[0m
  ^[[1m^[[94m= ^[[0m^[[1mnote^[[0m: `#[warn(unused_variables)]` (part of `#[warn(unused)]`) on by default

^[[1m^[[33mwarning^[[0m: `ansidemo` (bin "ansidemo") generated 1 warning (run `cargo fix --bin "ansidemo" -p ansidemo` to apply 1 suggestion)
^[[1m^[[92m    Finished^[[0m `dev` profile [unoptimized + debuginfo] target(s) in 0.10s
```

Try running both `cat build.log` and `cat -v build.log` to see the colorized and non-colorized (with raw ANSI codes) outputs side by side.
