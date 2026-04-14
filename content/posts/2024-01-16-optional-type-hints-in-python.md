+++
title = "Optional Type Hints in Python"
summary = "Comparing Optional[str] vs str | None syntax in Python v3.10+ with practical examples."
date = "2024-01-16"
lastmod = "2026-04-14T16:36:00-07:00"
categories = ["Python"]
ShowToc = true
TocOpen = true
+++

Python lets you write optional type hints where you can return either a specified type or `None`. This is a guide with some examples demonstrating different use cases.

{{< admonition type="note" >}}
Despite type hints, a function will still let you return whatever type you want. Type hints are more useful when using linting tools or an IDE to ensure that functions return the correct value(s).

All examples on this post have been tested with Python 3.12.1
{{< /admonition >}}

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

def example(num: Optional[int] = 0) -> Optional[int]:
    return num
```

And this is what the shorthand notation looks like:

```python
def example(num: int | None = 0) -> int | None:
    return num
```

In the examples above, the `num` variable has to be an integer or `None`, and it has a default value of `0`. The return type has to be an integer or `None`.

### Two or More Optional Type Hints

To specify two or more optional type hints, we need to import `Union` from the `typing` module.

In this example, the type hints indicate that either an integer, a string, or `None` can be assigned to the `data` input variable. The `data` variable has a default value of `None`.

The type hints also indicate that an integer, a string, or `None` can be returned.

Linters like [mypy](https://mypy.readthedocs.io/en/stable/getting_started.html) prefer an explicit `None` return value when `None` is a valid return type. That's why the examples have `return None` instead of just `return` at the end of the function.

```python
from typing import Union

def example(data: Union[int, str, None] = None) -> Union[int, str, None]:
    return None
```

And this is what the shorthand notation would look like in Python 3.10+:

```python
def example(data: int | str | None = None) -> int | str | None:
    return None
```

{{< admonition type="note" >}}
When using `Union`, it does not include the `None` type by default. Unlike `Optional`, which includes `None` by default.

If you want `None` to be included in `Union` you need to be explicit: 
```python
Union[int, None]
```

With `Optional`, you don't need to be explicit: 
```python
Optional[int]
```
{{< /admonition >}}

## References

I am no expert in Python, so please refer to the official documentation for more information:
- https://docs.python.org/3/library/typing.html
