+++
title = "Handling Decimal Precision in Rails"
summary = "Set precision and scale to decimal type database columns."
date = "2021-03-22"
tags = ["rails"]
toc = true
+++

Ruby on Rails allows us to specify how precise we want decimals to be by defining precision and scale in database migrations. Rails also provides a way of adding front-end validation to forms that accept decimal values. I'll be using an `expense` model that lets a user track expense amounts as an example. I'll also point out differences between SQLite and PostgreSQL in regards to saving decimals beyond constraints.

## Migration

The migration for an expense model that has a decimal field looks like this:

```rb
class CreateExpenses < ActiveRecord::Migration[6.0]
  def change
    create_table :expenses do |t|
      t.decimal :amount, precision: 5, scale: 2
      t.timestamps
    end
  end
end
```

Notice that we're specifying `precision` and `scale` in the decimal column:
- Precision is the total number of digits in the number, both before and after the decimal point.
- Scale is the number of digits after the decimal

So this field will take a decimal value up to 999.99.

## Model

For the expense model, we want to add validations so that the amount is positive and is less than 1000. This is based on the precision and scale we defined in the migration. We can also add validation to take into account decimal places by using a regular expression that allows values up to 999.99.

```rb
class Expense < ApplicationRecord
  validates :amount, numericality: { greater_than_or_equal_to: 0, less_than: BigDecimal(10**3) },
                     format: { with: /\A\d{1,3}(\.\d{1,2})?\z/ }
end
```

Note for the regular expression: `\A` is the same as `^`, while `\z` is the same as `$`.

## Controller

For the expense model, we have a standard controller. We don't need to do anything here in regards to decimal precision. I'm showing this for completeness of the example.

```rb
class ExpensesController < ApplicationController

  def index
    @expenses = @user.expenses
  end

  def new
    @expense = Expense.new
  end

  def create
    @expense = Expense.new(expense_params)

    if @expense.save
      flash[:notice] = 'Expense created'
      redirect_to(expenses_path)
    else
      flash[:alert] = @expense.errors.full_messages.join(', ')
      render('new')
    end
  end
end
```

## ERB Form

In the form, we need to use use `step` to add front-end validation and to be able to accept decimal values in the field.

```py
<%= form_with model: @expense, url: {controller: 'expenses', action: 'create'} do |f| %>
  <%= f.label :amount %>
  <%= f.number_field :amount, step: 0.01, class: 'input' %>
  <%= f.submit "Add", class: 'button is-primary' %>
<% end %>
```

In this form, `step: 0.01` is the same as specifying a scale of 2 in the database. Decimal values will only be accepted if they have two decimal places and are in increments of 0.01. (If we had specified `step: 0.05`, then values accepted would have to be in increments of 0.05, such as 1, 1.05, and 1.10). Without `step`, the form would only take whole numbers without decimals.

Thanks to our model validation, a user won't be able to submit values like `555.555` even if they were clever enough to skip front-end validation.

## SQLite vs PostgreSQL Validation

Lets say we didn't have any front-end or model validations. How would the database handle decimal inputs that exceed both precision and scale? We can do some experiments in the Rails console.

### Exceeding Scale Constraints

First, we'll try the value `555.555`, which exceeds the scale of 2.

**Rails console with SQLite:**

```
$ rails c

e = Expense.new(amount: 555.555)
 => #<Expense id: nil, amount: 0.55556e3, created_at: nil, updated_at: nil>

e.save!
  TRANSACTION (0.0ms)  begin transaction
  Expense Create (0.6ms)  INSERT INTO "expenses" ("amount", "created_at", "updated_at") VALUES (?, ?, ?)  [["amount", 555.56], ["created_at", "2021-03-22 09:49:35.881478"], ["updated_at", "2021-03-22 09:49:35.881478"]]
  TRANSACTION (1.1ms)  commit transaction
 => true

e.amount
 => 0.55556e3
```

After saving a new expense with the amount `555.555`, the resulting amount is `0.55556e3`, or `555.56`. The database rounded our input since it was set to a scale of 2 in the migration.

**Rails console with PostgreSQL:**

```
$ rails c

e = Expense.new(amount: 555.555)
 => #<Expense id: nil, amount: 0.55556e3, created_at: nil, updated_at: nil>

e.save!
  D, [2021-03-23T01:47:26.928433 #1] DEBUG -- :   TRANSACTION (0.4ms)  BEGIN
  D, [2021-03-23T01:47:26.929505 #1] DEBUG -- :   Expense Create (0.7ms)  INSERT INTO "expenses" ("amount", "created_at", "updated_at") VALUES ($1, $2, $3) RETURNING "id"  [ ["amount", "555.56"], ["created_at", "2021-03-23 01:47:26.927355"], ["updated_at", "2021-03-23 01:47:26.927355"]]
  D, [2021-03-23T01:47:26.931487 #1] DEBUG -- :   TRANSACTION (1.6ms)  COMMIT
 => true

e.amount
 => 0.55556e3
```

PostgreSQL behaves the same way and rounds to two decimal places if the scale is exceeded.

### Exceeding Precision Constraints

Next we'll try the value `123456.01`, which exceeds the precision of 5.

**Rails console with SQLite:**

```
$ rails c

e = Expense.new(amount: 123456.01)
 => #<Expense id: nil, amount: 0.12346e6, created_at: nil, updated_at: nil>

e.save!
  TRANSACTION (0.1ms)  begin transaction
  Expense Create (0.8ms)  INSERT INTO "expenses" ("amount", "created_at", "updated_at") VALUES (?, ?, ?)  [["amount", 123460.0], ["created_at", "2021-03-23 02:10:09.951895"], ["updated_at", "2021-03-23 02:10:09.951895"]]
  TRANSACTION (0.5ms)  commit transaction
 => true

e.amount
 => 0.12346e6
```

Interestingly, SQLite saves the value `123456.01` incorrectly as `1234560.00` with no errors whatsoever. This is undesired behavior because it defeats the point of defining a precision in the first place.

**Rails console with PostgreSQL:**

```
$ rails c

e = Expense.new(amount: 123456.01)
 => #<Expense id: nil, amount: 0.12346e6, created_at: nil, updated_at: nil>

e.save!
  D, [2021-03-23T01:54:25.490193 #1] DEBUG -- :   TRANSACTION (0.5ms)  BEGIN
  D, [2021-03-23T01:54:25.491212 #1] DEBUG -- :   Expense Create (0.7ms)  INSERT INTO "expenses" ("amount", "created_at", "updated_at") VALUES ($1, $2, $3) RETURNING "id"  [["amount", "123460.0"], ["created_at", "2021-03-23 01:54:25.489022"], ["updated_at", "2021-03-23 01:54:25.489022"]]
  D, [2021-03-23T01:54:25.491842 #1] DEBUG -- :   TRANSACTION (0.4ms)  ROLLBACK
  Traceback (most recent call last):
          1: from (irb):17:in `<main>'
  ActiveRecord::RangeError (PG::NumericValueOutOfRange: ERROR:  numeric field overflow)
  DETAIL:  A field with precision 5, scale 2 must round to an absolute value less than 10^3.
```

PostgreSQL rejects the value and shows us an error telling us that the number does not fit in with the precision and scale constraints. This is preferable to storing an incorrect value.

While Rails offers several ways of validating decimals, it's still important to choose the correct database to handle decimals.