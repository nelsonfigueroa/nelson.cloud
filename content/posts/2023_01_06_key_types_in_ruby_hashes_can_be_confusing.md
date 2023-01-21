+++
title = "Ruby Hash Key Types Vary Depending on Hash Syntax"
summary = "Key types in Ruby hashes depend on hash syntax."
date = "2023-01-06"
categories = ["Ruby"]
toc = true
+++

In Ruby, when a hash is created with keys and values, the keys may or may not retain their type depending on the hash syntax.

If a hash is created using "hash rocket" (`=>`) notation then the keys will retain their type.
If the hash is written in JSON-style syntax, all keys become symbols.

In both scenarios, values will retain their type.

If a key is added to the hash after the hash has been created, that key will retain it's type.
This can lead to situations where a hash has keys that are of multiple data types.

## Hashes With Hash Rocket Syntax

In the example below, a hash is created using hash rocket notation:

```ruby
items = {
  'A' => 1,
  'B' => 2,
  'C' => 3
}

puts items['A']
puts items.keys.first.class
```

The output is;

```
1
String
```

We can see that with hash rocket syntax the `A` key retained it's string type and can be written as a string in `items['A']`.

## Hashes with JSON Syntax

Let's see what happens if we take the previous example but change the hash syntax to use JSON-style colons:

```ruby
items = {
  'A': 1,
  'B': 2,
  'C': 3
}

puts items['A']
puts items.keys.first.class
```

The output is as follows:

```

Symbol
```

The empty line above `Symbol` is not a typo. In this case, `items['A']` doesn't exist because `'A'` is a symbol and should be written as `:A`. As a result, Ruby prints an empty value.

To get the expected value, we need to write `A` as a symbol inside `puts items[]`:

```ruby
items = {
  'A': 1,
  'B': 2,
  'C': 3
}

# writing A as a symbol
puts items[:A]
```

Now we get the expected output:

```
1
```

We can see that JSON syntax turns all keys in the hash symbols.

## Mixing Hash Key Data Types

It's possible to mix certain hash key data types (integers, strings, symbols) when using hash rocket syntax:

```ruby
items = {
  1 => 1,
  'B' => 2,
  :C => 3
}

items.each do |key, value|
  puts "#{key}, #{key.class}"
end
```

The output from the code above is:

```
1, Integer
B, String
C, Symbol
```

However, if we change the hash rockets to colons, the code does not work:

```ruby
items = {
  1: 1,
  'B': 2,
  :C: 3
}

items.each do |key, value|
  puts "#{key}, #{key.class}"
end
```

The code above throws the error:

```
syntax error, unexpected ':', expecting =>
```

This is because when using JSON syntax the keys must be written as strings (which are then converted to symbols).

## Key Types When They Are Appended to Hashes

Creating a hash with JSON-style syntax results in all keys being converted to the symbol data type.
However, if keys are added to the hash after creation, we can have keys of different data types.

```ruby
items = {
  'A': 1,
  'B': 2,
  'C': 3
}

items[4] = 4
items['5'] = 5

items.each do |key, value|
  puts "#{key}, #{key.class}"
end
```

The output is:

```
A, Symbol
B, Symbol
C, Symbol
4, Integer
5, String
```

We can see that the original 3 keys in the `items` hash were converted to symbols, while the new appended keys retained their data types.

This also works with rocket syntax hashes since they are more flexible about key data types:

```ruby
items = {
  'A' => 1,
  'B' => 2,
  'C' => 3
}

items[4] = 4
items['5'] = 5

items.each do |key, value|
  puts "#{key}, #{key.class}"
end
```

The output is:

```
A, String
B, String
C, String
4, Integer
5, String
```

The difference between the JSON-style hash and the rocket notation hash is that the original 3 keys in the `items` hash retained their type.

More information in the Ruby docs:
- https://ruby-doc.org/3.1.2/Hash.html