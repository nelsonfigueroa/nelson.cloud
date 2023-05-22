+++
title = "Automatically Delete Development Logs in Ruby on Rails"
summary = "How to automatically delete development logs in Ruby on Rails."
date = "2023-05-21"
categories = ["Ruby on Rails"]
toc = false
+++

<div class="outer">
<div class="inner">
<pre class="pre">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'▀▀&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;φ▓_&nbsp;&nbsp;_,╥φ@▓▓▓▓▓▓▓@φ╗╖_'▀▀&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',╥▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓╗╖_&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_▄▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓φ╖&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_&nbsp;&nbsp;&nbsp;&nbsp;╔▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▀"╓,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;╒φ╗&nbsp;&nbsp;`"▀▓▓▓▄&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;~▓▓▀&nbsp;▄▓▓▓▓▓▓▓▓▓▓▓▓▓▓▀"&nbsp;&nbsp;&nbsp;'▀^&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`╙▀▓▄&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▄▓▓▓▓▓▓▓▓▓▓▓▓▓▓▀&nbsp;▄&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'▀W&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;╓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▌&nbsp;&nbsp;╙▀&nbsp;&nbsp;&nbsp;&nbsp;__________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Æ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▀&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▓▓▓▓▓▓▓▓▄&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;╓▓▓▓▓▓▓▓▓▓▓▓,&nbsp;&nbsp;&nbsp;▓▓▓▓▓&nbsp;&nbsp;╟▓▓▓▓L&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;╔▓▓▓▓▓▓▓▓▓▓▌&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▌&nbsp;_&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▓▓▓▓▓▓▓▓▓▓_&nbsp;&nbsp;▐▓▓▓▓▓▓▓▓▓▓▓▓▓L&nbsp;&nbsp;▓▓▓▓▓&nbsp;&nbsp;╟▓▓▓▓L&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▓▓▓▓▓▓▓▓▌&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;▓▓╗&nbsp;┌▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓&nbsp;▐▓▌&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▌&nbsp;&nbsp;&nbsp;&nbsp;╙▓▓▓▓▓&nbsp;&nbsp;╫▓▓▓▓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▌&nbsp;&nbsp;▓▓▓▓▓&nbsp;&nbsp;╟▓▓▓▓L&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓⌐&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;╙▀▓Γ&nbsp;▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓&nbsp;&nbsp;`&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▌____Æ▓▓▓▓▌&nbsp;&nbsp;╫▓▓▓▓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▌&nbsp;&nbsp;▓▓▓▓▓&nbsp;&nbsp;╟▓▓▓▓L&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;╣▓▓▓▓▓▓▓▓▓▓▄&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▌&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▓▓▓▓▓▓▓▓▓▀&nbsp;&nbsp;&nbsp;╫▓▓▓▓φφφφφ▓▓▓▓▌&nbsp;&nbsp;▓▓▓▓▓&nbsp;&nbsp;╟▓▓▓▓L&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;╙▀▓▓▓▓▓▓▓▓▓▓&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;╫▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▌&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▓▓▓▓▓▓▓"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;╫▓▓▓▓▓▓▓▓▓▓▓▓▓▌&nbsp;&nbsp;▓▓▓▓▓&nbsp;&nbsp;╟▓▓▓▓L&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;j▓▓▓▓&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;╒▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▌&nbsp;▓▓@&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▌╙▓▓▓▓▓▓▄&nbsp;&nbsp;&nbsp;&nbsp;╫▓▓▓▓▀▀▀▀▀▓▓▓▓▌&nbsp;&nbsp;▓▓▓▓▓&nbsp;&nbsp;╟▓▓▓▓▓▓▓▓▓▓▓L&nbsp;▓▓▓▓▓▓▓▓▓▓▓▓▓&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓&nbsp;'╙▀&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▌&nbsp;&nbsp;╙▓▓▓▓▓▓▄&nbsp;&nbsp;╫▓▓▓▓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;▓▓▓▓▌&nbsp;&nbsp;▓▓▓▓▓&nbsp;&nbsp;╟▓▓▓▓▓▓▓▓▓▓▓L&nbsp;╫▓▓▓▓▓▓▓▓▓▓▀&nbsp;&nbsp;&nbsp;
</pre>
</div>
</div>

I usually don't need development logs persisted after I'm done developing a Rails app. Sometimes the `development.log` file can balloon up to gigabytes if I forget to delete it. I found a way to delete this file after a Ruby on Rails application exits in development mode.

In `config/environments/development.rb`, add the following:

```ruby
# delete local dev logs after exiting
at_exit do
  puts "Deleting development.log..."
  File.delete(Rails.application.config.paths['log'].first)
end
```

This code will delete `development.log` after the Rails process exits.

For example, I can start up a Rails app locally and see that a `development.log` file has been created:

```
$ rails s
=> Booting Puma
=> Rails 7.0.4.3 application starting in development
=> Run `bin/rails server --help` for more startup options
Puma starting in single mode...
* Puma version: 6.0.0 (ruby 3.1.2-p20) ("Sunflower")
*  Min threads: 5
*  Max threads: 5
*  Environment: development
*          PID: 18528
* Listening on http://127.0.0.1:3000
* Listening on http://[::1]:3000
Use Ctrl-C to stop
```

And I can see the `development.log` file under `log/`:

```
$ ls -1 log/
bullet.log
development.log # here's the development log file
production.log
test.log
```

Now when I exit the `rails` process, I should see a message saying "Deleting development.log..." and I should also see the `development.log` file disappear:

```
^C- Gracefully stopping, waiting for requests to finish
=== puma shutdown: 2023-05-21 13:58:25 -0700 ===
- Goodbye!
Exiting
Deleting development.log...
```

The custom message is there. Now to verify that there is no `development.log` file:

```
$ ls -1 log/
bullet.log
production.log
test.log
```

The `development.log` file is now gone!