+++
title = "Fix Devise Errors in Ruby on Rails 7"
summary = "How to fix Devise errors in Ruby on Rails 7."
date = "2022-11-16"
categories = ["Ruby on Rails"]
toc = true
+++

In Ruby on Rails 7, Webpacker, Turbolinks, and UJS were [replaced with Import Maps and Hotwire](https://world.hey.com/dhh/rails-7-will-have-three-great-answers-to-javascript-in-2021-8d68191b).
Honestly, I'm not much of a front-end guy so I didn't know what these changes meant for me exactly, so I decided to blindly upgrade a Ruby on Rails app for personal use.
This resulted in issues with [Devise](https://github.com/heartcombo/devise).

I noticed that some Devise actions were not working as intended. 
Specifically, the new user registration and logout actions were broken.
The solution was to add a couple gems and then update some views.

## Installing Necessary Gems

At the time of this writing, I am on the following versions:

- Rails 7.0.4
- Ruby 3.1.2
- Devise 4.8.1

First, I added the `importmap-rails` and `hotwire-rails` gems to my Gemfile:

```ruby
gem 'importmap-rails'
gem 'hotwire-rails', '~> 0.1.3'
```

Then I installed the gems with `bundle`

```
$ bundle install
```

Then I ran the following to complete the `importmap` installation:

```sh
$ rails importmap:install
```

Then I had to run an additional command to install Hotwire:


```sh
$ rails hotwire:install
```

That completed the installation phase. Next, I had to update my views.

## Updating Views

### Fixing the Logout Action

Before the changes, my 'logout' link looked like this:

```ruby
<%= link_to('Logout', destroy_user_session_path, method: :delete) %>
```

To get this to work properly, I had to remove `method: :delete` and replace it with `data: { turbo_method: :delete }`:

```ruby
<%= link_to('Logout', destroy_user_session_path, data: { turbo_method: :delete }) %>
```

That took care of my 'Logout' action. 

### Fixing New User Registrations

To fix the new user registrations, I had to add `data: { turbo: false }` to the `form_for` helper. 

This is what it looked like before the changes:

```py
<%= form_for(resource, as: resource_name, url: registration_path(resource_name)) do |f| %>
  <%= render "devise/shared/error_messages", resource: resource %>
  <%= f.text_field :name, autofocus: true, placeholder: 'Name', autocomplete: "name" %>
  <%= f.email_field :email, autofocus: true, placeholder: 'Email', autocomplete: "email" %>
  <%= f.password_field :password, placeholder: "Password (#{@minimum_password_length} characters minimum)", autocomplete: "new-password" %>
  <%= f.password_field :password_confirmation, placeholder: 'Repeat Password', autocomplete: "new-password" %>
  <%= f.submit "Sign up" %>
<% end %>
```

And this is what the form looked like after the changes were implemented:

```py
<%= form_for(resource, as: resource_name, url: registration_path(resource_name), data: { turbo: false }) do |f| %>
  <%= render "devise/shared/error_messages", resource: resource %>
  <%= f.text_field :name, autofocus: true, placeholder: 'Name', autocomplete: "name" %>
  <%= f.email_field :email, autofocus: true, placeholder: 'Email', autocomplete: "email" %>
  <%= f.password_field :password, placeholder: "Password (#{@minimum_password_length} characters minimum)", autocomplete: "new-password" %>
  <%= f.password_field :password_confirmation, placeholder: 'Repeat Password', autocomplete: "new-password" %>
  <%= f.submit "Sign up" %>
<% end %>
```

Alternatively, another solution that worked for me to fix new user registrations was to modify `config/initializers/devise.rb`. 
I added the following line:

```ruby
Devise.setup do |config|
  #
  # new line
  config.navigational_formats = ['*/*', :html, :turbo_stream]
  #
  #
end
```

This line was present (but commented out) in the default Devise installation. However, the only two formats present were `'*/*'` and `:html`. 
The `:turbo_stream` format had to be added.

This resolved the new user registration issue without having to add `data: { turbo: false }` to the view. I don't know which approach is best, so you may want to do further research.

This is what worked for me, but there are other potential solutions in the references below. Some of the solutions didn't work for me, but they may work for you.

## References

- https://github.com/rails/rails/issues/44185
- https://github.com/hotwired/hotwire-rails/issues/41#issuecomment-946649719
- https://github.com/heartcombo/devise/issues/5439
- https://world.hey.com/dhh/rails-7-will-have-three-great-answers-to-javascript-in-2021-8d68191b