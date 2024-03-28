+++
title = "Restricting Ruby on Rails Routes with :only and :except"
summary = "How to restrict Ruby on Rails routes with :only and :except."
date = "2022-12-03"
lastmod = "2023-08-27"
categories = ["Ruby on Rails"]
ShowToc = true
TocOpen = true
+++

## Generating All Routes for a Model

Lets say we have an `item` model in a Ruby on Rails application. To create standard routes for this model, we write the following in `config.rb`:

```ruby
Rails.application.routes.draw do
  resources :items
end
```

We can run `rails routes` to see the routes created by the code above:

```
$ rails routes -c items

   Prefix Verb   URI Pattern               Controller#Action
    items GET    /items(.:format)          items#index
          POST   /items(.:format)          items#create
 new_item GET    /items/new(.:format)      items#new
edit_item GET    /items/:id/edit(.:format) items#edit
     item GET    /items/:id(.:format)      items#show
          PATCH  /items/:id(.:format)      items#update
          PUT    /items/:id(.:format)      items#update
          DELETE /items/:id(.:format)      items#destroy
```

What if we don't need all of these routes though? We can use `:only` and `:except` to restrict the routes that are created for a model.

## Restricting Routes with :only

If we only need a few routes, it makes sense to use `:only` to create only the routes we need. For example, if we only want the `index` and `show` routes, we can specify them inside an array after `only:`:

```ruby
Rails.application.routes.draw do
  resources :items, only: [:index, :show]
end
```

This results in only 2 routes being created and we can verify with `rails routes`:

```
$ rails routes -c items

Prefix Verb URI Pattern          Controller#Action
 items GET  /items(.:format)     items#index
  item GET  /items/:id(.:format) items#show
```

## Restricting Routes with :except

`:except` works in the opposite way. Instead of specifying the routes we want to create, we specify the ones we don't want to create.

If we take the example from above and replace `only:` with `:except`, we can see what happens:

```ruby
Rails.application.routes.draw do
  resources :items, except: [:index, :show]
end
```

This time we can see that all routes except for `index` and `show` were created:

```
$ rails routes -c items

   Prefix Verb   URI Pattern               Controller#Action
    items POST   /items(.:format)          items#create
 new_item GET    /items/new(.:format)      items#new
edit_item GET    /items/:id/edit(.:format) items#edit
     item PATCH  /items/:id(.:format)      items#update
          PUT    /items/:id(.:format)      items#update
          DELETE /items/:id(.:format)      items#destroy
```

## Restricting a Single Route

Note that if you need to restrict a single route with either `:only` or `:except`, there is no need to place the route in an array. Write it as a symbol after `:only` or `:except` like so:

Using `except:`:
```ruby
Rails.application.routes.draw do
  resources :items, except: :index
end
```

Using `only:`:
```ruby
Rails.application.routes.draw do
  resources :items, only: :index
end
```

---

References:
- https://guides.rubyonrails.org/routing.html#restricting-the-routes-created
