+++
title = "How to Generate the Current Date and Time in Hugo"
summary = "How to generate dates and times with Hugo shortcodes."
date = "2024-12-22"
categories = ["Hugo"]
keywords = ["Hugo dates", "Hugo time", "Hugo now function", "Hugo date formatting", "Hugo templates", "current date Hugo", "Hugo shortcodes", "date generation", "Hugo tutorial"]
ShowToc = true
TocOpen = true
+++

Here are a bunch of [Hugo](https://gohugo.io/) snippets you can use to generate the current date and time in your Hugo site.

You can copy and paste these into HMTL templates. If you want to use these within your posts/articles (Markdown files) you'll need to create a shortcode first. Scroll down to the [Using Shortcodes](#using-shortcodes) section for more details.

I'm running Hugo v0.140.0+extended. For all examples, I will assume the time is 2024-12-22 2:30PM PT.

## Date Only

```go-html-template
{{ now | dateFormat "2006-01-02" }}
```

Output:
```
2024-12-22
```

## Date and 12-Hour Time

```go-html-template
{{ now | dateFormat "2006-01-02 3:04" }}
```

Output:
```
2024-12-22 2:30
```

You can Specify AM or PM as well:
```go-html-template
{{ now | dateFormat "2006-01-02 3:04AM" }}
```

Output:
```
2024-12-22 2:30AM
```

<br>

```go-html-template
{{ now | dateFormat "2006-01-02 3:04PM" }}
```

Output:
```
2024-12-22 2:30PM
```

## Date and 24-Hour Time

Essentially just changing `3:04` to `15:04`.

```go-html-template
{{ now | dateFormat "2006-01-02 15:04" }}
```

Output:
```
2024-12-22 14:30
```

## Date and Time with Timezone

```go-html-template
{{ now | dateFormat "2006-01-02 15:04 PST" }}
```

Output:
```
2024-12-22 14:30 PST
```

<br>

It's possible to add just about anything in place of the timezone and it'll take it. You can even add text that isn't a valid timezone.

```go-html-template
{{ now | dateFormat "2006-01-02 15:04 test" }}
```

Output:
```
2024-12-22 14:30 test
```

## Date, Time, Timezone, and UTC Offset

There are two ways that I found of doing this.

The first way:
```go-html-template
{{ now | dateFormat "2006-01-02 15:04 PST -0700" }}
```

Output. Note how Hugo changed the offset from `-0700` to `-0800` in this case:
```
2024-12-22 14:30 PST -0800
```

<br>

The second way:
```go-html-template
{{ now | dateFormat "2006-01-02 15:04 PST UTC-0700" }}
```

Output. Hugo once again changed the offset from `UTC-0700` to `UTC-0800`:
```
2024-12-22 14:30 PST UTC-0800
```

## Timezones and CI/CD

These snippets work for your current timezone but if you use a [CI/CD solution](https://en.wikipedia.org/wiki/CI/CD) you may need to specify the timezone through an environment variable or etc. It depends on your CI/CD solution. I couldn't find a way to set the timezone in Hugo itself so I don't think it's possible.

## Using Shortcodes

These snippets can be copied and pasted into `.html` templates. However, to use them in Markdown files, you'll need to create a separate file in `layouts/shortcodes/` and then use that shortcode in a markdown file.

For example, I can create a file `generate_date.html` in `layouts/shortcodes/` with the following contents:

```go-html-template
{{ now | dateFormat "2006-01-02" }}
```

Then I can reference this shortcode in a markdown file with this syntax:

```md
{{</* generate_date */>}}
```

An example along with some other markdown:

```md
# A Header in Markdown

Some text in markdown

*The current date is*: {{</* generate_date */>}}
```

## References
- https://discourse.gohugo.io/t/how-to-display-time-by-timezone/42643/7
- https://community.cloudflare.com/t/hugo-timezone-format-issue/390678
