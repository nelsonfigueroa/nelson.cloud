+++
title = "Remove Unnecessary Hugo Meta Tag to Keep HTML Lean"
summary = "Remove an unnecessary and auto-generated Hugo meta tag by setting `disableHugoGeneratorInject` to `true`."
date = "2025-06-15"
categories = ["Hugo", "HTML"]
keywords = ["Hugo meta tag", "disableHugoGeneratorInject", "Hugo performance", "HTML optimization", "Hugo configuration", "static site generator", "Hugo guide", "lean HTML", "Hugo settings"]
ShowToc = false
TocOpen = false
+++

I like to keep my [Hugo](https://gohugo.io/) site as lean as possible for performance reasons and to save on hosting costs.

I recently discovered that Hugo generates a HTML `<meta>` tag with the Hugo version in the `<head>` tag. This is done by default and looks like this:

```html
<head>
    <meta name=generator content="Hugo 0.146.2">
</head>
```

To remove this tag, we can disable it in `config.yaml` or `config.toml` by setting the `disableHugoGeneratorInject` boolean to `true`.

In YAML:

```YAML
disableHugoGeneratorInject: true
```

In TOML:

```TOML
disableHugoGeneratorInject = true
```

If setting `disableHugoGeneratorInject` doesn't work, do a global search in your Hugo project for `{{ hugo.Generator }}`. This shortcode outputs the same `<meta>` tag and doesn't respect the `disableHugoGeneratorInject` configuration (at least as of Hugo v0.147.8).

## References:
- https://gohugo.io/configuration/all/#disablehugogeneratorinject
- https://gohugo.io/functions/hugo/generator/
