+++
title = "Remove hugo generated meta tag html bullshit and keep your hugo site lean as fuck"
summary = "why does hugo do this."
date = "2025-06-15"
lastmod = "2025-06-15"
categories = ["Hugo", "HTML"]
ShowToc = false
TocOpen = false
+++

I like to keep my [Hugo](https://gohugo.io/) site as lean as possible for performance reasons and to save on hosting costs. I recently discovered that Hugo generates a HTML `<meta>` tag with the version number of Hugo in the HTML `<head>` tag. This is done by default and here's what it looks like:

```html
<head>
    <meta name=generator content="Hugo 0.146.2">
</head>
```

In `config.yaml` or `config.toml`.

In YAML:

```YAML
disableHugoGeneratorInject: true
```

In TOML:

```TOML
disableHugoGeneratorInject = true
```

## References:
- https://gohugo.io/configuration/all/#disablehugogeneratorinject
