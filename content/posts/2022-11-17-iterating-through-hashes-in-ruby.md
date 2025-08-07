+++
title = "Iterating Through Hashes in Ruby"
summary = "Several methods of iterating through Ruby hashes."
date = "2022-11-17"
lastmod = "2022-11-17"
categories = ["Ruby"]
keywords = ["Ruby hashes", "hash iteration", "Ruby each", "hash methods", "Ruby programming", "data structures", "Ruby tutorial", "hash loops", "Ruby basics"]
ShowToc = true
TocOpen = true
+++

In my career, I have only seen Ruby hashes being iterated through by declaring both the key and value.
However, there are several ways of approaching hash iterations.

Note that at the time of this writing I'm running Ruby 3.1.2.

## Iterating With Key and Value

The most common way I've seen hash iterations is by declaring both the key and value variables in the loop. The result is as expected:

```ruby
items = {
  'A': 1,
  'B': 2,
  'C': 3
}

items.each do |key, value|
  puts "key: #{key}, value: #{value}"
end
```

The code above has the following output:

```
key: A, value: 1
key: B, value: 2
key: C, value: 3
```

## Iterating With a Single Variable

When iterating through a hash and declaring only a single variable, the variable is an array containing the key and value of the hash as elements.

```ruby
items = {
  'A': 1,
  'B': 2,
  'C': 3
}

items.each do |item|
  puts "#{item}"
end
```

The code above has the following output:

```
[:A, 1]
[:B, 2]
[:C, 3]
```

In this case, the `item` variable is an array of 2 elements. The keys are Symbols while the values retain their original type.

There is also the `.each_pair` method, which works exactly the same way:

```ruby
items = {
  'A': 1,
  'B': 2,
  'C': 3
}

items.each_pair do |pair|
  puts "#{pair}"
end
```

The output is exactly the same as before:

```
[:A, 1]
[:B, 2]
[:C, 3]
```

## Iterating Through Keys

The keys of a Ruby hash can be retrieved with `.keys`:

```ruby
items = {
  'A': 1,
  'B': 2,
  'C': 3
}

puts items.keys
```

The code above prints out the following:

```
A
B
C
```

If we want to iterate over each key, we can use `.keys` in a loop:

```ruby
items = {
  'A': 1,
  'B': 2,
  'C': 3
}

items.keys.each do |key|
  puts key
end
```

And the output is exactly the same:

```
A
B
C
```

Finally, there is also the `.each_key` method that works the same way and is recommended by [Rubocop](https://rubocop.org/):

```ruby
items = {
  'A': 1,
  'B': 2,
  'C': 3
}

items.each_key do |key|
  puts key
end
```

Once again, the output is the same:

```
A
B
C
```

## Iterating Through Values

Like with keys, the values of a hash can be retrieved with `.values`:

```ruby
items = {
  'A': 1,
  'B': 2,
  'C': 3
}

puts items.values
```

The code above outputs the following:

```
1
2
3
```

We can use `.values` to iterate through the values of a hash:

```ruby
items = {
  'A': 1,
  'B': 2,
  'C': 3
}

items.values.each do |value|
  puts value
end
```

The output is the same:

```
1
2
3
```

There is also the `.each_value` method that achieves the same result and is also recommended by [Rubocop](https://rubocop.org/):

```ruby
items = {
  'A': 1,
  'B': 2,
  'C': 3
}

items.each_value do |value|
  puts value
end
```

Once again, the output is exactly the same as before:

```
1
2
3
```
