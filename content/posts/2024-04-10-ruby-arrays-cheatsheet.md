+++
title = "Ruby Arrays Cheatsheet"
summary = "A Ruby arrays cheatsheet with methods and examples for coding interviews."
date = "2024-04-10"
categories = ["Ruby"]
ShowToc = true
TocOpen = false
+++

This is a cheatsheet I made for myself when studying for ([Leetcode](https://leetcode.com/)/[Hackerrank](https://www.hackerrank.com/))-style interviews. It's organized in a way that makes sense to me when I'm trying to solve an array problem. I figured I would make it public incase it can help others :)

All examples were run using the [Ruby v3.2.3 REPL](https://www.rubyguides.com/2018/12/what-is-a-repl-in-ruby/).

## Initializing an Array

### Empty Array

```ruby
arr = Array.new
# => []
```

Alternative way:

```ruby
arr = []
# => []
```

### Array with Values

Initializing an array of integers:

```ruby
arr = [1, 2, 3, 4, 5]
# => [1, 2, 3, 4, 5]
```

Initializing an array of strings:

```ruby
arr = ["A", "B", "C"]
# => ["A", "B", "C"]
```

You can do the above with any type.

Initializing an array of symbols:

```ruby
arr = [:A, :B, :C]
# => [:A, :B, :C]
```

#### Shorthand Notations

With shorthand notations there is no need to add quotes for strings or colons for symbols. There's also no need to add commas after each element. Just separate each element with a space.

Initializing an array of strings using `%w`:

```ruby
arr = %w[A B C D E]
# => ["A", "B", "C", "D", "E"]
```

Initializing an array of symbols using `%i`:

```ruby
arr = %i[A B C D E]
# => [:A, :B, :C, :D, :E]
```

## Adding Elements

### At the Beginning of the Array

Add elements to the beginning of the array with `unshift()` or `prepend()`, which is an alias for `unshift()`.

```ruby
arr = [1, 2, 3]
arr.unshift(0)
# => [0, 1, 2, 3]

arr
# => [0, 1, 2, 3]
```

```ruby
arr = [1, 2, 3]
arr.prepend(0)
# => [0, 1, 2, 3]

arr
# => [0, 1, 2, 3]
```

### At the End of the Array

Add elements to the end of the array with `push()` or `append()`, which is an alias for `push()`:

```ruby
arr = [1, 2, 3]
arr.append(4)

arr
# => [1, 2, 3, 4]
```

```ruby
a = [1, 2, 3]
a.push(4)

a
# => [1, 2, 3, 4]
```

You can use `<<` as a shortcut to add elements to the end of an array:

```ruby
arr = []

arr << 1 # [1]
arr << 2 # [1, 2]
arr << 3 # [1, 2, 3]

arr
# => [1, 2, 3]
```

If you add arrays, the arrays added to the first one will be appended in the order in which they are added:

```ruby
[1, 2, 3] + [4]
# => [1, 2, 3, 4]

[1, 2, 3] + [7] + [6] + [5] + [4]
# => [1, 2, 3, 7, 6, 5, 4]
```

You can add arrays containing multiple elements and they will be appended:

```ruby
[1, 2, 3] + [1, 2, 3]
# => [1, 2, 3, 1, 2, 3]
```

### At a Specific Index

Use `insert()` to add an element at a specific index of an array. The `insert()` method accepts multiple parameters but the first one must be an index. This method modifies the array in-place.

In this example, `"A"` is being added at index 2:

```ruby
arr = ["A", "B", "C"]
arr.insert(2, "A")
# => ["A", "B", "A", "C"]

arr
# => ["A", "B", "A", "C"]
```

Multiple elements can be added at once:

```ruby
arr = ["A", "B", "C"]
arr.insert(2, "Z", "Z", "Z")
# => ["A", "B", "Z", "Z", "Z", "C"]

arr
# => ["A", "B", "Z", "Z", "Z", "C"]
```

## Removing Elements

### At the Beginning of the Array

Remove an element from the beginning of an array with `shift()`. This method returns the removed element and modifies the array in-place:

```ruby
arr = [1, 2, 3]
arr.shift
# => 1

arr
# => [2, 3]
```

### At the End of the Array

Remove an element from the end of the array with `pop()`. This method returns the removed element and modifies the array in-place:

```ruby
arr = [1, 2, 3]
arr.pop
# => 3

arr
# => [1, 2]
```

Delete all instances of an element by passing in that element to `delete()`. This method returns the removed element and modifies the array in-place:

```ruby
arr = [1, 2, 3, 3, 3]
arr.delete(3)
# => 3

arr
# => [1, 2]
```

Attempting to delete an element that isn't present returns `nil`:

```ruby
arr = [1, 2, 3, 3, 3]
arr.delete(5)
# => nil
```

Note that you cannot delete multiple values in one call of `delete()`. But it is possible to do it by subtracting arrays. Keep reading after this example to see how:

```ruby
arr = [1, 2, 3, 3, 3]
arr.delete(1,2,3)
# (irb):83:in `delete': wrong number of arguments (given 3, expected 1) (ArgumentError)
```

You can subtract an array to remove all occurrences of an element and get the resulting array as a return value, but it does not modify the array in-place:

``` ruby
arr = [1, 2, 3, 3, 3]
arr - [3]
# => [1, 2]

arr
# => [1, 2, 3, 3, 3]
```

Subtracted arrays can have multiple elements:

```ruby
[1, 2, 3, 4, 4, 5, 5, 6, 6, 7, 7] - [4, 5, 6]
# => [1, 2, 3, 7, 7]
```

Subtracting arrays with elements that aren't present in the first array does nothing:

```ruby
[1, 2, 3] - [4]
# => [1, 2, 3]
```

### At a Specific Index

We can delete an element at a specific index using `delete_at(index)`. The `delete_at()` method returns the element that is removed. Passing in an index that is out of bounds returns `nil`:

```ruby
arr = ["A", "B", "C"]
arr.delete_at(1)
# => "B"

arr
# => ["A", "C"]

arr.delete_at(100)
# => nil
```

## Retrieving Elements

### Element at a Specific Index

Like in most programming languages, an element can be retrieved using `my_array[index]` where `index` is an integer.

```ruby
arr = ["A", "B", "C"]
arr[1]
# => "B"
```

We can also use `at()` to get the element at a certain index. It works the same way as the previous example:

```ruby
arr = ["A", "B", "C"]
arr.at(1)
# => "B"
```

Negative indexes start at the end of the array.

```ruby
arr = ["A", "B", "C"]
arr[-1]
# => "C"
arr[-2]
# => "B"
arr[-3]
# => "A"
```

Out of bounds indexes return `nil`:

```ruby
arr = ["A", "B", "C"]
arr[5]
# => nil
arr[-4]
# => nil
```

### The First Element

There is a handy `first()` method to retrieve the first element of an array:

```ruby
arr = ["A", "B", "C"]
arr.first # equivalent to arr[0]
# => "A"
```

### The Last Element

There's also a `last()` method to retrieve the last element of an array:

```ruby
arr = ["A", "B", "C"]
arr.last
# => "C"
```

### Range of Elements

We can use a `..` or `...` to specify a range of indices within an array. These are useful for [sliding window problems](https://www.geeksforgeeks.org/window-sliding-technique/):

Using `..` results in an inclusive range:

```ruby
a = [1,2,3,4,5]

a[1..4]
# => [2, 3, 4, 5]
```

Using `...` results in an exclusive range:

```ruby
a = [1,2,3,4,5]

a[1...4]
# => [2, 3, 4]
```

### Maximum Element

We can use `max()` to retrieve the biggest number in an array:

```ruby
arr = [1, 5, 8, 2, 0, 4]

arr.max
# => 8
```

If `max()` is used on an array of strings it returns the last string after sorting alphabetically:

```ruby
arr = ["B", "D", "A", "C"]

arr.max
# => "D"
```

It works the same way for an array of Symbols:

```ruby
arr = [:A, :C, :D, :B]

arr.max
# => :D
```

`max()` will not work on an array containing elements of different types:

```ruby
arr = [1, "B", 4, "C", 2, "A"]

arr.max
# => (irb):14:in `max': comparison of Integer with String failed (ArgumentError)
```

### Minimum Element

Works the same way as `max()` but with the smallest value instead.

Retrieving the smallest number in an array:

```ruby
arr = [9, 2, 5, 1, 8]

arr.min
# => 1
```

If `min()` is used on an array of strings it returns the first string after sorting alphabetically:

```ruby
arr = ["B", "D", "A", "C"]

arr.min
# => "A"
 ```

 It works the same way for an array of Symbols:

 ```ruby
 arr = [:A, :C, :D, :B]

 arr.min
# => :A
 ```

 And once again, `min()` will not work on an array containing elements of different types:

 ```ruby
 arr = [1, "B", 4, "C", 2, "A"]

 arr.min
 # => (irb):27:in `min': comparison of Integer with String failed (ArgumentError)
 ```

## Sorting Arrays

The `sort()` method returns a sorted version of the array it is used on. This does not modify the original array:

```ruby
arr = ["E", "D", "A", "C", "B"]
arr.sort
# => ["A", "B", "C", "D", "E"]

arr
# => ["E", "D", "A", "C", "B"]
```

Use `sort!()` to modify the array in-place:

```ruby
arr = ["E", "D", "A", "C", "B"]
arr.sort!
# => ["A", "B", "C", "D", "E"]

arr
# => ["A", "B", "C", "D", "E"]
```

Sorting works with other types of elements as well.

Sorting integers:
```ruby
arr = [5, 9, 0, 3, 4]
# => [5, 9, 0, 3, 4]
arr.sort
# => [0, 3, 4, 5, 9]
```

Sorting symbols:
```ruby
arr = [:red, :blue, :green, :cyan]
# => [:red, :blue, :green, :cyan]
arr.sort
# => [:blue, :cyan, :green, :red]
```

Sorting multi-character strings:
```ruby
arr = ["someone", "hire", "me", "please"]
# => ["someone", "hire", "me", "please"]
arr.sort
# => ["hire", "me", "please", "someone"]
```
```ruby
arr = ["aaaa", "aa", "aaa", "a"]
# => ["aaaa", "aa", "aaa", "a"]
arr.sort
# => ["a", "aa", "aaa", "aaaa"]
 ```

{{< admonition type="note" >}}
There's also a `sort_by()` method but I am too dumb to come up with good examples. You should read about it in this article instead: [How to Sort Arrays & Hashes in Ruby](https://www.rubyguides.com/2017/07/ruby-sort/).
{{< /admonition >}}

## Looping Through Arrays

### Each Element

Use `each()` to iterate through each element in an array:
```ruby
arr = ["A", "B", "C", "D", "E"]

arr.each do |i|
  puts i
end

# output:
# A
# B
# C
# D
# E
```

### Each Index

Use `each_index()` to iterate through each index in an array beginning at 0:

```ruby
arr = ["A", "B", "C", "D", "E"]

arr.each_index do |i|
  puts i
end

# output:
# 0
# 1
# 2
# 3
# 4
```

### Element and Index

Use `each_with_index()` to iterate through both elements and indexes at the same time. The first variable in the `| |` is the element, the second one is the index.

```ruby
arr = ["A", "B", "C", "D", "E"]

arr.each_with_index do |element, index|
  puts "#{index}: #{element}"
end

# output:
# 0: A
# 1: B
# 2: C
# 3: D
# 4: E
```

## Modifying All Elements in an Array

We can use `map()` to modify all elements in an array.

For example, to increment all integers in an array we can do the following:

```ruby
arr = [1, 2, 3, 4, 5]
arr.map { |i| i += 1 }
#  => [2, 3, 4, 5, 6]

arr
# => [1, 2, 3, 4, 5]
```

To modify the elements/array in-place, add an exclamation point:

```ruby
arr = [1, 2, 3, 4, 5]
arr.map! { |i| i += 1 }
#  => [2, 3, 4, 5, 6]

arr
# => [2, 3, 4, 5, 6]
```

Here's another example where string names in an array are capitalized appropriately:

```ruby
names = ["nelson", "cindy", "john", "sophia"]
names.map! { |name| name.capitalize }
# => ["Nelson", "Cindy", "John", "Sophia"]
```

There is a shorthand notation when you want to run a method against all elements in an array. We can use `&:method_name`. The following example achieves the exact same thing as the previous example:

```ruby
names = ["nelson", "cindy", "john", "sophia"]
names.map!(&:capitalize)
# => ["Nelson", "Cindy", "John", "Sophia"]
```

Here's another example using shorthand notation to convert all strings to symbols:

```ruby
arr = ["A", "B", "C", "D", "E"]
arr.map!(&:to_sym)
# => [:A, :B, :C, :D, :E]
```

If you want to do more advanced things, like modify elements conditionally, you will need to stick to the long format. This example replaces all `nil` elements with `0` but leaves other elements unchanged:

```ruby
data = [100, 200, nil, 400, 500, nil]
data.map! { |i| i.nil? ? i = 0 : i = i }
# => [100, 200, 0, 400, 500, 0]
```

## Other Things to Know

### Arrays Can Contain Multiple Types

For example:

```ruby
arr = []

arr << 1
arr << "a"
arr << :my_symbol
arr << [1,2]
arr << {hash_key: "hash_value"}

arr
# => [1, "a", :my_symbol, [1, 2], {:hash_key=>"hash_value"}]
```

### Getting the Index of an Element

Use `index()` and pass in the element you are looking for to retrieve its index.

```ruby
arr = ["A", "B", "C"]
arr.index("B")
# => 1
```

If there are multiple occurrences of an element, `index()` will return the index of the first occurrence of the element:

```ruby
arr = ["A", "B", "B", "B", "C"]
arr.index("B")
# => 1
```

If an element isn't present in the array, `index()` returns `nil`:

```ruby
arr = ["A", "B", "C"]
arr.index("Z")
# => nil
```

### Reversing Elements in an Array

Elements in an array can be reversed with `reverse()`. This method returns an array with elements sorted in reverse and does not modify the original array:

```ruby
arr = ["A", "B", "C"]
arr.reverse
# => ["C", "B", "A"]

arr
# => ["A", "B", "C"]
```

The `reverse!()` method does the same thing but it modifies the array in-place:

```ruby
arr = ["A", "B", "C"]
arr.reverse!
# => ["C", "B", "A"]

arr
# => ["C", "B", "A"]
```

### Converting Multi-dimensional Array into 1-dimensional Array

We can use `flatten()` to recursively extract elements from all arrays within an array and return an array with only elements. The original array remains unchanged.

```ruby
arr = ["A", ["B", "C"], ["D", ["E", ["F"]]]]
arr.flatten
# => ["A", "B", "C", "D", "E", "F"]

arr
# => ["A", ["B", "C"], ["D", ["E", ["F"]]]]
```

We can use `flatten!()` to modify the array in-place:

```ruby
arr = ["A", ["B", "C"], ["D", ["E", ["F"]]]]
arr.flatten!
# => ["A", "B", "C", "D", "E", "F"]

arr
# => ["A", "B", "C", "D", "E", "F"]
```

### Removing All Elements from Array

We can use `clear()` to remove all elements from an array in-place.

```ruby
arr = ["A", "B", "C"]
arr.clear
# => []

arr
# => []
```

This is the same as assigning an empty array to the existing array variable.

```ruby
arr = ["A", "B", "C"]
arr = []
# => []

arr
# => []
```

### Removing `nil` Elements from Array

We can use `compact()` to remove all `nil` elements in an array. This method returns a new array.

```ruby
arr = [1, nil, 2, nil, 3]
arr.compact
# => [1, 2, 3]

arr
# => [1, nil, 2, nil, 3]
```

We can use `compact!()` to do the same operation to the array in-place:

```ruby
arr = [1, nil, 2, nil, 3]
arr.compact!
# => [1, 2, 3]

arr
#  => [1, 2, 3]
```

## References
- https://gist.github.com/mkdika/d1a9c72b6d2b86547f2269e82c235e83
- https://www.rubyguides.com/2017/07/ruby-sort/
- https://apidock.com/ruby/v2_5_5/Enumerable/sort_by
- https://www.shortcutfoo.com/app/dojos/ruby-arrays/cheatsheet
- https://apidock.com/ruby/Array/
- https://ruby-doc.org/core-3.1.0/Array.html
- https://www.rubyguides.com/2018/10/ruby-map-method/
- https://womanonrails.com/one-line-map-ruby
