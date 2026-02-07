+++
title = "Get Unique Elements in a Python List"
summary = "Remove duplicate elements from Python lists using the set() function."
date = "2024-04-22"
categories = ["Python"]
ShowToc = false
TocOpen = false
+++

In Python we can get the unique elements from a list by converting it to a set with `set()`. Sets are a [collection of **unique** elements](https://docs.python.org/3/tutorial/datastructures.html#sets):

```python
values = [1, 1, 1, 2, 2, 3, 3, 3, 3]

values = set(values)

print(values)
```

Output:

```
{1, 2, 3}
```

And if we still need a list instead of a set, we can easily convert back to a list using `list()`:

```python
values = [1, 1, 1, 2, 2, 3, 3, 3, 3]

# convert to set to get unique values
values = set(values)
# convert back to list
values = list(values)

print(values)
```

Output:

```
[1, 2, 3]
```

---

I learned this trick after realizing that unfortunately Python doesn't have a [`uniq()` method like Ruby](https://apidock.com/ruby/Array/uniq) that does this exact thing.

There's also other ways of getting unique values from a list that you can read about in [this GeeksforGeeks article](https://www.geeksforgeeks.org/python-get-unique-values-list/). I didn't cover the other cases because I feel like the easiest way is to use `set()`.

## References
- https://docs.python.org/3/tutorial/datastructures.html#sets
- https://www.geeksforgeeks.org/python-get-unique-values-list/
