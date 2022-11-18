+++
title = "The Many Ways of Iterating Through Ruby Hashes"
summary = "Several methods of iterating through Ruby hashes."
date = "2022-11-17"
categories = ["Ruby"]
toc = true
+++

In my career, I have only seen Ruby hashes being iterated through by declaring both the key and value. 
However, there are several ways of approaching hash iterations.

Note that at the time of this writing I'm running Ruby 3.1.2.

## Iterating With Key and Value

The most common way I've seen hash iterations is by declaring both the key and value in the loop:

```ruby
items = {
  'string_key': 'str',
  'integer_key': 1,
  'float_key': 1.0,
  'array_key': %w[a b c],
  'symbol_key': :symbol_value
}

items.each do |key, value|
  puts "key class: #{key.class}, "
  puts "value class: #{value.class}"
  puts "#{key}: #{value}"
  puts
end
```

The code above has the following output:

```
key class: Symbol,
value class: String
string_key: str

key class: Symbol,
value class: Integer
integer_key: 1

key class: Symbol,
value class: Float
float_key: 1.0

key class: Symbol,
value class: Array
array_key: ["a", "b", "c"]

key class: Symbol,
value class: Symbol
symbol_key: symbol_value
```

The keys are of the Symbol class even though they are written like strings in the code. The hash values retain their original types.

Side note: since keys are Symbols, to access an individual value using a key you need to write the key in a Symbol form:

```ruby
puts items[:float_key]

# => 1.0
```

## Iterating With a Single Variable

When iterating through a hash and declaring only a single variable, the variable is an array containing the key and value of the hash as elements.

```ruby
items = {
  'string_key': 'str',
  'integer_key': 1,
  'float_key': 1.0,
  'array_key': %w[a b c],
  'symbol_key': :symbol_value
}

items.each do |item|
  puts "item class: #{item.class}, item: #{item}"
  puts "item has #{item.count} elements"
  puts "item[0]: #{item[0]}, item[0].class: #{item[0].class}"
  puts "item[1]: #{item[1]}, item[1].class: #{item[1].class}"
  puts
end
```

The code above has the following output:

```
item class: Array, item: [:string_key, "str"]
item has 2 elements
item[0]: string_key, item[0].class: Symbol
item[1]: str, item[1].class: String

item class: Array, item: [:integer_key, 1]
item has 2 elements
item[0]: integer_key, item[0].class: Symbol
item[1]: 1, item[1].class: Integer

item class: Array, item: [:float_key, 1.0]
item has 2 elements
item[0]: float_key, item[0].class: Symbol
item[1]: 1.0, item[1].class: Float

item class: Array, item: [:array_key, ["a", "b", "c"]]
item has 2 elements
item[0]: array_key, item[0].class: Symbol
item[1]: ["a", "b", "c"], item[1].class: Array

item class: Array, item: [:symbol_key, :symbol_value]
item has 2 elements
item[0]: symbol_key, item[0].class: Symbol
item[1]: symbol_value, item[1].class: Symbol
```

In this case, the `item` variable is an array of 2 elements. The first one (`item[0]`) is the key. The second one (`item[1]`) is the value.

The keys are once again Symbols and values retain their original type.

There is also the `.each_pair` method, which works exactly the same way:

```ruby
items = {
  'string_key': 'str',
  'integer_key': 1,
  'float_key': 1.0,
  'array_key': %w[a b c],
  'symbol_key': :symbol_value
}

items.each_pair do |pair|
  puts "pair class: #{pair.class}, pair: #{pair}"
  puts "pair has #{pair.count} elements"
  puts "pair[0]: #{pair[0]}, pair[0].class: #{pair[0].class}"
  puts "pair[1]: #{pair[1]}, pair[1].class: #{pair[1].class}"
  puts
end
```

The output is:

```
pair class: Array, pair: [:string_key, "str"]
pair has 2 elements
pair[0]: string_key, pair[0].class: Symbol
pair[1]: str, pair[1].class: String

pair class: Array, pair: [:integer_key, 1]
pair has 2 elements
pair[0]: integer_key, pair[0].class: Symbol
pair[1]: 1, pair[1].class: Integer

pair class: Array, pair: [:float_key, 1.0]
pair has 2 elements
pair[0]: float_key, pair[0].class: Symbol
pair[1]: 1.0, pair[1].class: Float

pair class: Array, pair: [:array_key, ["a", "b", "c"]]
pair has 2 elements
pair[0]: array_key, pair[0].class: Symbol
pair[1]: ["a", "b", "c"], pair[1].class: Array

pair class: Array, pair: [:symbol_key, :symbol_value]
pair has 2 elements
pair[0]: symbol_key, pair[0].class: Symbol
pair[1]: symbol_value, pair[1].class: Symbol
```

## Iterating Through Keys

The keys of a hash can be retrieved with `.keys`:

```ruby
items = {
  'string_key': 'str',
  'integer_key': 1,
  'float_key': 1.0,
  'array_key': %w[a b c],
  'symbol_key': :symbol_value
}

puts items.keys
```

The above prints out the following:
```
string_key
integer_key
float_key
array_key
symbol_key
```

We can use `.keys` to iterate over each key as well:

```ruby
items = {
  'string_key': 'str',
  'integer_key': 1,
  'float_key': 1.0,
  'array_key': %w[a b c],
  'symbol_key': :symbol_value
}

items.keys.each do |key|
  puts "class: #{key.class}, key: #{key}"
end
```

```
class: Symbol, key: string_key
class: Symbol, key: integer_key
class: Symbol, key: float_key
class: Symbol, key: array_key
class: Symbol, key: symbol_key
```

There is also the `.each_key` method that works the same way and is recommended by [Rubocop](https://rubocop.org/):

```ruby
items = {
  'string_key': 'str',
  'integer_key': 1,
  'float_key': 1.0,
  'array_key': %w[a b c],
  'symbol_key': :symbol_value
}

items.each_key do |key|
  puts "class: #{key.class}, key: #{key}"
end
```

The code above results in the same output:
```
class: Symbol, key: string_key
class: Symbol, key: integer_key
class: Symbol, key: float_key
class: Symbol, key: array_key
class: Symbol, key: symbol_key
```

## Iterating Through Values

Similarly, the values of a hash can be retrieved with `.values`:

```ruby
items = {
  'string_key': 'str',
  'integer_key': 1,
  'float_key': 1.0,
  'array_key': %w[a b c],
  'symbol_key': :symbol_value
}

puts items.values
```

```
str
1
1.0
a
b
c
symbol_value
```

We can use `.values` to iterate through the values of a hash:

```ruby
items = {
  'string_key': 'str',
  'integer_key': 1,
  'float_key': 1.0,
  'array_key': %w[a b c],
  'symbol_key': :symbol_value
}

items.values.each do |value|
  puts "class: #{value.class}, value: #{value}"
end
```

```
class: String, value: str
class: Integer, value: 1
class: Float, value: 1.0
class: Array, value: ["a", "b", "c"]
class: Symbol, value: symbol_value
```

There is also the `.each_value` method that works similarly and is also recommended by [Rubocop](https://rubocop.org/):

```ruby
items = {
  'string_key': 'str',
  'integer_key': 1,
  'float_key': 1.0,
  'array_key': %w[a b c],
  'symbol_key': :symbol_value
}

items.each_value do |value|
  puts "class: #{value.class}, value: #{value}"
end

```

```
class: String, value: str
class: Integer, value: 1
class: Float, value: 1.0
class: Array, value: ["a", "b", "c"]
class: Symbol, value: symbol_value
```