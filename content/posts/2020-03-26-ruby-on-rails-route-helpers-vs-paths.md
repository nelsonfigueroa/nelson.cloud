+++
title = "Ruby on Rails Route Helpers vs Paths"
summary = "Comparing Ruby on Rails URL Helpers and Paths"
date = "2020-03-26"
categories = ["Ruby on Rails"]
keywords = ["Ruby on Rails", "rails routes", "rails URL helpers", "rails URI patterns", "Rails routing", "rails link_to", "ruby on rails paths", "ruby on rails nested routes", "Rails helpers", "ruby on rails web development"]
+++

>**2021-11-08 update**:
When I first wrote this post I thought this was a better way of writing links, specially after seeing it done this way in a professional setting.
However, it may not be best practice. It's best to stick with Rails helpers since they're dynamic. If routes change, the helpers may not need to be changed at all.

While learning how to write tests for requests, I came across a different way of writing links in Rails. Up until this discovery I've used URL helpers as such:

```rb
<%= link_to 'Add Account', new_account_path %>
```

By checking the application's routes, we can see URL helpers (1st column) and URI Patterns (3rd column).

```
$ rails routes -c accounts

      Prefix Verb   URI Pattern                  Controller#Action
    accounts GET    /accounts(.:format)          accounts#index
             POST   /accounts(.:format)          accounts#create
 new_account GET    /accounts/new(.:format)      accounts#new
edit_account GET    /accounts/:id/edit(.:format) accounts#edit
     account GET    /accounts/:id(.:format)      accounts#show
             PATCH  /accounts/:id(.:format)      accounts#update
             PUT    /accounts/:id(.:format)      accounts#update
             DELETE /accounts/:id(.:format)      accounts#destroy
```


URL helpers are mapped URI patterns, but we can also use these endpoints directly. In this case, the `new_account` URL helper is the same as `/accounts/new`. So the previous link can be changed to:

```rb
<%= link_to 'Add Account', '/accounts/new' %>
```

If a link requires an ID, we can add it through string interpolation. So the following link...

```rb
<%= link_to account.name, account_path(@account) %>
```

...can be rewritten like this:

```rb
<%= link_to account.name, "/accounts/#{@account.id}" %>
```

This also applies to nested routes. Lets say we have the following in `routes.rb`:

```rb
resources :accounts do
  resources :statements, only: %i[new create edit update]
end
```

This block generates the following routes for the `statement` model:

```
$ rails routes -c statements

                Prefix Verb  URI Pattern                                         Controller#Action
    account_statements POST  /accounts/:account_id/statements(.:format)          statements#create
 new_account_statement GET   /accounts/:account_id/statements/new(.:format)      statements#new
edit_account_statement GET   /accounts/:account_id/statements/:id/edit(.:format) statements#edit
     account_statement PATCH /accounts/:account_id/statements/:id(.:format)      statements#update
                       PUT   /accounts/:account_id/statements/:id(.:format)      statements#update
```

If we want to create a link to edit a statement using a URL helper, we would write the following. (Notice we need to pass in two different IDs):

```rb
<%= link_to 'Edit', edit_account_statement_path(account_id: @account.id, id: @statement.id) %>
```

We can rewrite the link using a URI pattern instead.

```rb
<%= link_to 'Edit', "/accounts/#{@account.id}/statements/#{@statement.id}/edit" %>
```

Both URL helpers and URI patterns get the job done, but I enjoyed using URI patterns for familiarity. If an external application is going to communicate with a Rails API, they'll use the same endpoints defined in URI patterns. It keeps things a bit more consistent.
