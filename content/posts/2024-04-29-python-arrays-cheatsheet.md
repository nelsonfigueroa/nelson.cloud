+++
title = "Python Lists Cheatsheet"
summary = "Python list basics and methods to learn fast or refresh your memory."
date = "2024-04-29"
lastmod = "2024-12-09"
categories = ["Python"]
ShowToc = true
TocOpen = false
+++

This is yet another cheatsheet I made for myself when studying for [Leetcode](https://leetcode.com/) and [Hackerrank](https://www.hackerrank.com/) kinds of interviews. It's organized in a way that makes sense to me.

I previously made a [Ruby Arrays Cheatsheet]({{< relref "/posts/2024-04-10-ruby-arrays-cheatsheet.md" >}}) you can check out.

All examples were tested using the Python 3.12.3 [REPL](https://www.pythonmorsels.com/using-the-python-repl/).

## Initializing a List

### Empty List

```python
my_list = []
```

### List with Elements

Initializing a list of integers:

```python
my_list = [1, 2, 3, 4, 5]

print(my_list)
# [1, 2, 3, 4, 5]
```

Initializing a list of strings:

```python
my_list = ["A", "B", "C"]
# ['A', 'B', 'C']
```

You can mix different types of elements:

```python
my_list = [1, "A", [2, 3], {"my_key": "my_value"}]

print(my_list)
# [1, 'A', [2, 3], {'my_key': 'my_value'}]
```

## Adding Elements

### At the Beginning of a List

We can also use `insert()` to add elements to the beginning of a list by specifying index 0:

```python
my_list = [1, 2, 3]
my_list.insert(0, 4)

print(my_list)
# [4, 1, 2, 3]
```

### At the End of a List

Use `append()` to add an element to the end of a list:

```python
my_list = [1, 2, 3]
my_list.append(4)

print(my_list)
# [1, 2, 3, 4]
```

### At a Specific Index

Use `insert()` and specify the index and element to add:

```python
my_list = ["A", "B", "C"]
my_list.insert(1, "D")

print(my_list)
# ['A', 'D', 'B', 'C']
```

## Removing Elements

### At the Beginning of a List

We can use `del()` and specify index 0. This modifies the list in-place:

```python
my_list = [1, 2, 3, 4]
del(my_list[0])

print(my_list)
# [2, 3, 4]

del my_list[0]

print(my_list)
# [3, 4]
```

### At the End of a List

We can use `pop()`. Modifies list in-place:
```python
my_list = [1, 2, 3]
my_list.pop()

print(my_list)
# [1, 2]
```

We can also use `del()` and specify the last index, which should be `len(list) - 1`, but using `pop()` is a bit cleaner in my opinion.

### At a Specific Index

Similar to removing an element from the beginning of the list, except we pass in a different index aside from 0:

```python
my_list = ['A', 'B', 'C', 'D']
del(my_list[2])

print(my_list)
# ['A', 'B', 'D']
```

## Retrieving Elements

### The First Element

There's no bult-in Python method to get the first element as far as I know, just specify index 0 like in most programming languages:

```python
my_list = ['A', 'B', 'C', 'D']
char = my_list[0]

print(char)
# 'A'
```

### The Last Element

Use index -1 to get the last element of a list. Negative indices start at the end of the list.

```python
my_list = ['A', 'B', 'C', 'D']
char = my_list[-1]

print(char)
# 'D'
```

### Element at a Specific Index

Similar to other programming languages, specify an index:

```python
my_list = ['A', 'B', 'C', 'D']
char = my_list[2]

print(char)
# 'C'
```

## Sorting Lists

We can use `sort()` to sort a list. This modifies the list in-place:

```python
my_list = [4, 5, 1, 3, 2]
my_list.sort()

print(my_list)
# [1, 2, 3, 4, 5]
```

We can also use `sorted()`. This returns a new list and does not modify the original list:

```python
my_list = [4, 5, 1, 3, 2]
sorted_list = sorted(my_list)

print(my_list)
# [4, 5, 1, 3, 2]

print(sorted_list)
# [1, 2, 3, 4, 5]
```

## Looping Through Lists

### Each Element

Use `for <element> in <list_name>` syntax:

```python
my_list = ['A', 'B', 'C', 'D']

for char in my_list:
    print(char)

# output:
# A
# B
# C
# D
```

### Each Index

Use `range()` and `len()` to loop through a list's indices:

```python
my_list = ['A', 'B', 'C', 'D']

for index in range(len(my_list)):
    print(index)

# output:
# 0
# 1
# 2
# 3
```

### Element and Index

Use `enumerate()` to loop through both elements and indices:

```python
my_list = ['A', 'B', 'C', 'D']

for index, element in enumerate(my_list):
    print(f'Index: {index}, Element: {element}')

# output:
# Index: 0, Element: A
# Index: 1, Element: B
# Index: 2, Element: C
# Index: 3, Element: D
```

## Other Things to Know

### Lists vs Arrays

Note that there are both Arrays and Lists in Python. Arrays are saved contiguously in memory and have fixed sizes so they are faster for reading but insertion and deletion costs are high. Arrays can only have elements of the same type.

Lists are more flexible. Lists can have elements of different types and do not have fixed sizes. The flexibility of Lists results in more memory being used by these data structures.

Here's an example of an Array of integers being created in Python.

```python
import array

# requires a more verbose syntax compared to a List
a = array.array('i', [1, 2, 3, 4, 5])

print(a)
# array('i', [1, 2, 3, 4, 5])
```

Attempting to add an element that doesn't match the type of the existing elements results in an error:

```python
import array

a = array.array('i', [1, 2, 3, 4, 5])
a.append("str")

# Traceback (most recent call last):
#   File "<stdin>", line 1, in <module>
# TypeError: 'str' object cannot be interpreted as an integer
```

Appending elements of the same type will work as expected:

```python
import array

a = array.array('i', [1, 2, 3, 4, 5])
a.append(6)

print(a)
# array('i', [1, 2, 3, 4, 5, 6])
```

You can learn more about Arrays in this [GeeksForGeeks article](https://www.geeksforgeeks.org/difference-between-list-and-array-in-python/).

### Reversing a List

We can reverse a list using this syntax. This does not modify a list in-place, it returns a new list:

```python
my_list = [1, 2, 3, 4, 5]
reversed_list = my_list[::-1]

print(my_list)
# [1, 2, 3, 4, 5]

print(reversed_list)
# [5, 4, 3, 2, 1]
```

We can also use `sort(reverse=True)` to reverse a list. This modifies the list in-place:

```python
my_list = [1, 2, 3, 4, 5]
my_list.sort(reverse=True)

print(my_list)
# [5, 4, 3, 2, 1]
```

There is also a `reversed()` function. This function returns an iterator which we can then convert back to a list:

```python
my_list = [1, 2, 3, 4, 5]
reversed_list = list(reversed(my_list))

print(reversed_list)
# [5, 4, 3, 2, 1]
```

### Count Number of Occurrences
We can check for the number of occurrences of a value in a list using `.count()`. In the example below, we check to see how many times the number `1` appears in the list, which is 5 times.:

```python
my_list [1, 2, 3, 4, 5, 4, 3, 3, 2, 2, 2, 1, 1, 1, 1]

my_list.count(1)
# 5
```

## References
- https://treyhunner.com/2016/04/how-to-loop-with-indexes-in-python/
- https://www.geeksforgeeks.org/difference-between-list-and-array-in-python/
- https://www.geeksforgeeks.org/python-get-first-and-last-elements-of-a-list/
- https://www.w3schools.com/python/python_lists_add.asp
- https://www.w3schools.com/python/ref_list_sort.asp
- https://stackoverflow.com/questions/43025748/deleting-first-element-of-a-list-in-python
- https://docs.python.org/3/library/functions.html#enumerate
