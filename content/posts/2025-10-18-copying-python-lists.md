+++
title = "How to Actually Copy a List in Python"
summary = "Copy a list in Python using the copy() method, not the assignment operator."
date = "2025-10-18"
categories = ["Python", "Computer Science"]
keywords = ["python lists memory address", "python copy list", "python duplicate list"]
ShowToc = false
TocOpen = false

[cover]
image = "/copying-python-lists/python-logo.webp"
alt = "ASCII art of Python logo."
caption = ""
+++

> tl;dr: use the `copy()` method.

Say we have two Python lists -- `list_a` and `list_b`.

If we try to make a copy of `list_a` and assign it to `list_b` using the assignment operator `=`, what really happens is that both `list_a` and `list_b` point to the same memory address.

That means that any list-manipulating actions that are done on either `list_a` or `list_b` will affect the same list in memory. We don't have actually have two separate lists we can act upon.

In the example below, although we append the integer `4` to `list_a`, we can see that printing out `list_b` shows the newly added element. That's because both list variables point to the same memory address:

```python
list_a = [1, 2, 3]
list_b = []

list_b = list_a

print(list_b) # [1, 2, 3]

list_a.append(4)

print(list_b) # [1, 2, 3, 4]
```

Output of the program above:
```
[1, 2, 3]
[1, 2, 3, 4]
```

To make an actual copy, use the `copy()` method. Then, when `list_a` is modified, it is independent of `list_b`, because `list_b` is stored in a separate memory address.

Now if we append the same integer `4` to `list_a`, `list_b` will be completely unaffected.

```python
list_a = [1, 2, 3]
list_b = []

list_b = list_a.copy() # using copy()

print(list_b) # [1, 2, 3]

list_a.append(4)

print(list_a) # [1, 2, 3, 4]
print(list_b) # [1, 2, 3]
```

Output of the program above:
```
[1, 2, 3]
[1, 2, 3, 4]
[1, 2, 3]
```

Here's more proof. We can print out the memory address of each variable to see when they're the same and when they differ. We can do this using the `id()` function.

Here are the same lists from above but this time with their unique identifiers printed out. In this case, the IDs match because both `list_a` and `list_b` point to the same memory address.
```python
list_a = [1, 2, 3]
list_b = []

list_b = list_a

print(f'list_a address: {id(list_a)}')
print(f'list_b address: {id(list_b)}')
```

The program above outputs:
```
list_a address: 140226819497536
list_b address: 140226819497536
```

The memory addresses are the same.

Now let's try the same thing but using the `copy()` method instead of just an assignment operation with `=`:
```python
list_a = [1, 2, 3]
list_b = []

list_b = list_a.copy()

print(f'list_a address: {id(list_a)}')
print(f'list_b address: {id(list_b)}')
```

The program above outputs:
```
list_a address: 140264515620480
list_b address: 140264514892160
```

We can see the memory addresses are different (most obvious due to the ending digits).

Although I've been in the field for some time, I still have my smooth brain moments. This is a reminder to myself (and whoever reads this) to remember the basics!

## References
- https://www.geeksforgeeks.org/python/python-list-copy-method/
- https://www.geeksforgeeks.org/python/id-function-python/
