+++
title = "Uniqueness Constraint Between Two Columns in Rails"
summary = "Add a uniqueness constraint between two columns in Ruby on Rails."
date = "2021-05-11"
categories = ["Ruby on Rails"]
toc = false
+++

Ruby on Rails allows us to define uniqueness between two database table columns (i.e. two model attributes). At the time of this writing, I couldn't find official Rails documentation that shows how to do this in both the migration and the model, hence this post.

## Defining a Uniqueness Constraint in the Migration

Creating a uniqueness constraint in a migration requires us to add an index on two columns/attributes. The key here is to place the attributes in an array and set them to be unique as a pair. The line in the migration looks like this:

```rb
t.index [:attribute, :another_attribute], unique: true
```

Here's a more realistic example. Assume the following:
- We have an existing `Account` model
- We want to create a new table for a `Statement` model. 
- An account can have many statements
- A statement belongs to one account
- We want a maximum of 1 statement per date, per account (which means we need the `date` and `account_id` attributes to be unique together)

To accomplish the above, we add indexes to `date` and `account_id` and set them to be unique as a pair.

```rb
class CreateStatements < ActiveRecord::Migration[6.0]
  def change
    create_table :statements do |t|
      t.references :account, foreign_key: true, index: true
      t.date :date
      # date and account_id are unique as a pair
      t.index [:date, :account_id], unique: true
    end
  end
end
```

Now an Account can have many Statements, but only a maximum of 1 per date. Without this constraint, an existing statement with today's date would prevent any other statements from being created today, even for separate accounts.

Uniqueness constraints can also be added after a table has been created. All we need to do is add the indexes to both attributes like before. Also, instead of using `t.index` we use `add_index`:

```rb
class AddUniqueIndexToStatements < ActiveRecord::Migration[6.1]
  def change
    add_index :statements, [:account_id, :date], unique: true
  end
end
```

## Model Validation

Now that the constraint is set in the database, we can also add it in the model. To do that, we add a uniqueness validation of an attribute and scope it to another attribute. For example, in the Statement model previously mentioned it looks like this:

```rb
validates :date, uniqueness: { scope: :account_id, message: "Statement already exists for this date." }
```

And that's it! The model's attributes are now unique as a pair and validated at both the database layer and model layer.