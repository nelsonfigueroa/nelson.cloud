+++
title = "Rails Links With URI Patterns"
summary = "Comparing Rails URL Helpers and URI Patterns"
date = "2020-03-26"
categories = ["code"]
tags = ["code"]
draft = false
+++

While learning how to write tests for requests, I came across a different way of writing links in Rails. Up until this discovery I had used URL helpers like the following:

```rb
<%= link_to 'Add Account', new_account_path %>
```

By checking the rails routes, we can see URL helpers and URI Patterns. 

```
$ rails routes -c accounts

    accounts GET    /accounts(.:format)          accounts#index
             POST   /accounts(.:format)          accounts#create
 new_account GET    /accounts/new(.:format)      accounts#new
edit_account GET    /accounts/:id/edit(.:format) accounts#edit
     account GET    /accounts/:id(.:format)      accounts#show
             PATCH  /accounts/:id(.:format)      accounts#update
             PUT    /accounts/:id(.:format)      accounts#update
             DELETE /accounts/:id(.:format)      accounts#destroy
```


URL helpers point to URI patterns, but we can also use these endpoints directly. In this case, the `new_account` URL helper is the same as `/accounts/new`. So the previous link can be changed to:

```rb
<%= link_to 'Add Account', '/accounts/new' %>
```

If a link requires an ID, we can add it through string interpolation. So the following link...

```rb
<%= link_to account.name, account %>
```

...can be rewritten like this:

```rb
<%= link_to account.name, "/accounts/#{account.id}" %>
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
<%= link_to 'Edit', edit_account_statement_path(account_id: @account.id, id: statement) %>
```

We can rewrite the link using a URI pattern instead.

```rb
<%= link_to 'Edit', "/accounts/#{@account.id}/statements/#{statement.id}/edit" %>
```

Both URL helpers and URI patterns get the job done, but I enjoyed using URI patterns for consistency. If an external application is going to communicate with a Rails API, they'll use the same endpoints defined in URI patterns.