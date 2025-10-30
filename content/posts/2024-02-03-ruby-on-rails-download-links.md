+++
title = "Ruby on Rails: Enable File Downloads Using link_to"
summary = "How to enable file downloads using the link_to helper in Ruby on Rails."
date = "2024-02-03"
categories = ["Ruby on Rails", "HTML"]
keywords = ["Ruby on Rails file download", "ruby on rails link_to", "ruby on rails html download attribute", "Rails download attribute", "rails html download attribute", "force download Rails", "Rails file export", "link_to download Rails", "Rails JSON download"]
+++

In a Ruby on Rails app I was working on, I wanted to include a link that would download a user's data (bank statements in this case) as JSON.

Originally I had the following ERB:
```ruby
<%= link_to 'Download All Statements as JSON', statements_download_path %>
```

Clicking this link would show JSON in the browser, but it wouldn't download a JSON file, which is what I wanted.

The solution was to add a `download` attribute to the ERB snippet:
```ruby
<%= link_to 'Download All Statements as JSON', statements_download_path, download: 'statements.json' %>
```

## Full Setup

For additional context, here is all the code required to get this to work.

First I created this method in `statements_controller.rb`:
```ruby
def download
  render json: @user.statements
end
```

Then I added a new route to `routes.rb`:
```ruby
get 'statements/download', to: 'statements#download'
```

And then I added the `link_to` helper in the view:
```ruby
<%= link_to 'Download All Statements as JSON', statements_download_path, download: 'statements.json' %>
```

The `link_to` ERB helper is creating this HTML:
```html
<a download="statements.json" href="/statements/download">Download All Statements as JSON</a>
```

The `download` attribute can be customized in ERB. For example, I can name the file based on the user's name like so:
```ruby
<%= link_to 'Download All Statements as JSON', statements_download_path, download: "#{@user.name.downcase}-statements" %>
```

And it will generate the following HTML:
```html
<a download="nelson-statements" href="/statements/download">Download All Statements as JSON</a>
```

This link will trigger a download for `nelson-statements.json` in my case.

Ultimately this was a case of understanding the `download` HTML attribute for `<a>` tags and how to use it in Rails.

## Further Reading and References

Note that there are other ways of handling this, like using the `send_data` method in a controller. But that's beyond the scope of this post. Read more about it here: https://api.rubyonrails.org/v7.0.1/classes/ActionController/DataStreaming.html#method-i-send_data

References:
- https://www.w3schools.com/tags/att_a_download.asp
- https://forum.upcase.com/t/download-html-file-instead-of-opening-in-browser-with-link-to/2994
- https://www.reddit.com/r/rails/comments/co0h6i/whats_the_best_way_create_a_download_image_link/
