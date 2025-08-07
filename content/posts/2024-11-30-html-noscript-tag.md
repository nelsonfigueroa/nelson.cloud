+++
title = "Render HTML and CSS if JavaScript Is Disabled Using the `<noscript>` Tag"
summary = "Render HTML and apply CSS styles if JavaScript is disabled."
date = "2024-11-30"
lastmod = "2024-11-30"
categories = ["HTML", "CSS", "JavaScript"]
keywords = ["noscript tag", "HTML noscript", "JavaScript disabled", "progressive enhancement", "HTML accessibility", "fallback content", "no JavaScript", "HTML tutorial", "CSS fallback"]
+++

I recently learned about the [`<noscript>` HTML tag](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/noscript). HTML and CSS contained within `<noscript>` tags will be rendered if JavaScript is disabled in the browser.

For example, try reloading this page with JavaScript disabled and you'll see a new message below:

<noscript>
    <blockquote>This message only shows up when JavaScript is disabled!</blockquote>
</noscript>

The HTML for the message above looks like this:

```html
<noscript>
    <blockquote>This message only shows up when JavaScript is disabled!</blockquote>
</noscript>
```

Here's an example where CSS styles within `<noscript>` tags are applied when JavaScript is disabled. Try reloading this page without JavaScript and the message below will be italicized.

<noscript>
    <style>
        blockquote#example {
            font-style: italic;
        }
    </style>
</noscript>

<blockquote id="example">The text within this blockquote element is italicized when JavaScript is disabled!</blockquote>

And this is what the HTML and CSS looks like for the example above:

```html
<noscript>
    <style>
        blockquote#example {
            font-style: italic;
        }
    </style>
</noscript>

<blockquote id="example">The text within this blockquote element is italicized when JavaScript is disabled!</blockquote>
```

## References

- https://developer.mozilla.org/en-US/docs/Web/HTML/Element/noscript