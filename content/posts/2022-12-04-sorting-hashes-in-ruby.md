+++
title = "Sorting Hashes in Ruby"
summary = "Many ways of sorting hashes in Ruby."
date = "2022-12-04"
categories = ["Ruby"]
ShowToc = true
TocOpen = true
+++

## Sorting Hashes by Key

### Ascending Order

Lets say we had the following hash:

```ruby
items = {
  "b": 2,
  "a": 3,
  "c": 1
}
```

If we wanted to sort it by keys, we can use the `.sort` method to sort in ascending order:

```ruby
items = {
  "b": 2,
  "a": 3,
  "c": 1
}

# sorting the hash by keys. `items` is now an array
items = items.sort
```

The code above will result in `items` being an array of arrays (sorted alphabetically by keys) that looks like this:

```ruby
[[:a, 3], [:b, 2], [:c, 1]]
```

### Descending Order

To sort a hash by key in descending order, we can chain the `.reverse` method to the previous code:

```ruby
items = {
  "b": 2,
  "a": 3,
  "c": 1
}

items = items.sort.reverse
```

The code above turns `items` into the following:

```ruby
[[:c, 1], [:b, 2], [:a, 3]]
```

## Sorting Hashes by Value

### Ascending Order

To sort a hash by value, we need to use `.sort_by` like so:

```ruby
items = {
  "b": 2,
  "a": 3,
  "c": 1
}

# sorting by values
items = items.sort_by { |k, v| v }
```

The code above will result in `items` being an array once again, but this time sorted by values in ascending order:

```ruby
[[:c, 1], [:b, 2], [:a, 3]]
```

### Descending Order

We can also use `.sort_by` to sort values in descending order by using `-v`:

```ruby
items = {
  "b": 2,
  "a": 3,
  "c": 1
}

# sorting by values in descending order, note the `-v`
items = items.sort_by { |k, v| -v }
```

The code above transforms `items` into the following:

```ruby
[[:a, 3], [:b, 2], [:c, 1]]
```

## Side Note: Converting Resulting Arrays to Hashes

As a side note: if you still need the result in a hash format, you can convert the resulting array into a hash with `.to_h`:

```ruby
items = {
  "b": 2,
  "a": 3,
  "c": 1
}

# sorting and converting the result to a hash
items = items.sort.to_h
```

The code above will result in `items` being a hash again:

```ruby
{:a=>3, :b=>2, :c=>1}
```
