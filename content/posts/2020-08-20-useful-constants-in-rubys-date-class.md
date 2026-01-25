+++
title = "Useful Constants in Ruby's Date Class"
summary = "Diving into useful constants in Ruby's Date class"
date = "2020-08-20"
categories = ["Ruby"]
+++

While trying to modify dates in string form, I came across a convenient way to convert months into their numerical values.
For example, say I had the string `Aug 20, 2020` and I wanted to convert it into `8-20-2020`.
It's easy to split the string and add a dash in between each number. But what about `Aug`? How do we get the numerical form of `Aug` and all the other months?
We could manually create something like a hash that contains months in string and numerical form. But, Ruby already comes with a built-in solution.

In [the documentation](https://ruby-doc.org/stdlib-2.7.1/libdoc/date/rdoc/Date.html#Constants), I discovered that Ruby's `Date` class comes with two array constants that can help in this situation.
Those constants are `MONTHNAMES` and `ABBR_MONTHNAMES`.

`MONTHNAMES` is an array of the full names of all the months.

```ruby
# irb

> require 'date'

=> true

> Date::MONTHNAMES

=> [nil, "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
```

`ABBR_MONTHNAMES` is an array of abbreviated month names.

```ruby
# irb

> require 'date'

=> true

> Date::ABBR_MONTHNAMES

=> [nil, "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
```

In my situation, `ABBR_MONTHNAMES` will solve my problem since the data I'm parsing contains abbreviated month names.
Now, when parsing `Aug 20, 2020`, I can run the following to get a numerical value for `Aug`:

```ruby
# irb

> require 'date'

=> true

Date::ABBR_MONTHNAMES.index('Aug')

=> 8
```

No need to create a hash or array myself, this constant gets the job done.

I noticed both arrays have `nil` as their first value. At first I asked "why?", but it quickly became clear that the `nil` values are simply filler to take up index `0` since there is no month with this numerical value.

This was a nice discovery. Ruby continues to make writing code a pleasant experience.
