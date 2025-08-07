+++
title = "Optional Type Hints in Python"
summary = "How to use optional type hints in Python"
date = "2024-01-16"
lastmod = "2024-01-16"
categories = ["Python"]
keywords = ["Python type hints", "optional types", "Python typing", "type annotations", "Optional", "Union types", "Python static typing", "type checking", "Python tutorial"]
ShowToc = true
TocOpen = true
+++

Python lets you write optional type hints where you can return either a specified type or `None`. This is a guide with some examples demonstrating different use cases.

> Note that despite type hints, a function will still let you return whatever type you want. Type hints are more useful when using linting tools or an IDE to ensure that functions return the correct value(s).
>
> All examples on this post have been tested with Python 3.12.1

For reference, here is what it looks like when a *non-optional* type hint for a string is specified in a Python function's argument and return value:

```python
def example(string: str) -> str:
    return "Hello!"
```

## Two Ways of Specifying Optional Type Hints

There are two ways to specify optional type hints.

You can import `Optional` from the `typing` module and then specify type hints as follows:

```python
from typing import Optional

def example(value: Optional[str]) -> Optional[str]:
    return value
```

As of Python 3.10, you can also use a shorthand notation, replacing `Optional[str]` with `str | None`. There is no import required:

```python
def example(value: str | None) -> str | None:
    return value
```

Both examples above are equivalent to each other. Both functions also accept `None` as a return type.

## Examples

Here are some useful examples showing optional type hints using the `typing` module and the shorthand notation.

### One Optional Type Hint

A single optional type hint for an integer as an argument and as a return value looks like this:

```python
from typing import Optional

def example(num: Optional[int] = 0) -> Optional[str]:
    return num
```

And this is how the shorthand notation looks like:

```python
def example(num: int | None = 0) -> str | None:
    return num
```

In the examples above, the `num` variable has to be an integer or `None`, and it has a default value of `0`. The return type has to be a string or `None`.

### Two or More Optional Type Hints

To specify two or more optional type hints, we need to import `Union` from the `typing` module.

In this example, the type hints indicate that either an integer or a string can be assigned to the `data` input variable. The `data` variable has a default value of `None`.

The type hints also indicate that either an integer or a string can be returned. Like before, `None` can also be returned.

```python
from typing import Union

def example(data: Union[int, str] = None) -> Union[int, str]:
    return
```

And this is how the shorthand notation would look like in Python 3.10+:

```python
def example(data: int | str | None = None) -> int | str:
    return
```

### Mandatory Presence of Two or More Optional Type Hints

If you'd like to create a type hint that specifies a function *must* return 2 or more types (as opposed to at least one of the specified types), you need to use tuples. In this case we import `Tuple` instead of `Union` and use it in the type hint:

```python
from typing import Tuple

def example(num: int = 0, string: str = "Hello!") -> Tuple[int, str]:
    return num, string
```

Tuples can also be used as type hints for function arguments. In the example below, the type of `values` must be a tuple consisting of an integer and a string.

```python
from typing import Tuple

def example(values: Tuple[int, str] = None) -> Tuple[int, str]:
    return values
```

In both examples above, `None` is also an acceptable return value.

To my knowledge, there is no shorthand version when using tuples as type hints.

## References

I am no expert in Python so please Refer to the official documentation for more information:
- https://docs.python.org/3/library/typing.html
