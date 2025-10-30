+++
title = "Preserve Child Objects When Parent Objects Are Deleted in Ruby on Rails"
summary = "Use dependent: :nullify in Rails associations to preserve child records when parent objects are deleted."
date = "2024-11-18"
categories = ["Ruby on Rails", "Ruby"]
keywords = ["Ruby on Rails dependent nullify", "dependent nullify rails", "Rails associations dependent nullify", "Active Record dependent nullify", "Rails database dependencies", "ruby on rails object relationships", "ruby on rails soft delete", "ruby on rails dependent destroy vs nullify", "data integrity Rails", "prevent cascade delete Rails"]
+++

In most Ruby on Rails models I've seen `dependent: :destroy` being used to handle child objects when a parent object is destroyed. I don't know why it took me this long to learn that there is also a `:nullify` option. Using `dependent: :nullify` is handy for situations where keeping associated objects after destroying the parent object is important (data retention purposes, etc).

For example, let's say we had a `category` model and a `transaction` model defined like so:

```ruby
class Category < ApplicationRecord
  has_many :transactions, dependent: :nullify
end

class Transaction < ApplicationRecord
  belongs_to :category
end
```

With this setup, if a category is deleted, and it has transactions associated, the `category_id` field on each transaction is set to `null`. The category is deleted but the transactions remain. A new category can then be set for each transaction, or the association can be left as `null`.

## References
- https://guides.rubyonrails.org/association_basics.html
