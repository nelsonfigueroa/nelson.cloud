+++
title = "Proper Hugo Template Syntax Highlighting with go-html-template"
summary = "Use `go-html-template` to properly highlight Hugo template code blocks."
date = "2025-08-03"
lastmod = "2025-08-03"
categories = ["Hugo", "HTML"]
ShowToc = false
TocOpen = false
+++

I recently learned that you can highlight Hugo template code blocks by specifying `go-html-template` after the opening backticks.

So the opening backticks look like this:
```
```go-html-template
```

It makes a huge difference when highlighting Hugo template code blocks. I was previously using `html` and it wasn't as good.

For example, here is some HTML + Hugo templating stuff being highlighted with `html`:

```html
{{- define "main" }}

{{- if .Title }}
<header class="page-header">
    <h1>{{ .Title }}</h1>
    {{- if .Description }}
    <div class="post-description">
        {{ .Description }}
    </div>
    {{- end }}
</header>
{{- end }}

<ul class="terms-tags">
    {{- $type := .Type }}
    {{- range $key, $value := .Data.Terms.Alphabetical }}
    {{- $name := .Name }}
    {{- $count := .Count }}
    {{- with site.GetPage (printf "/%s/%s" $type $name) }}
    <li>
        <a href="{{ .Permalink }}">{{ .Name }}<sup> {{ $count }}</sup></a>
    </li>
    {{- end }}
    {{- end }}
</ul>

{{- end }}{{/* end main */ -}}
```

Here's the same code block but with `go-html-template` specified:

```go-html-template
{{- define "main" }}

{{- if .Title }}
<header class="page-header">
    <h1>{{ .Title }}</h1>
    {{- if .Description }}
    <div class="post-description">
        {{ .Description }}
    </div>
    {{- end }}
</header>
{{- end }}

<ul class="terms-tags">
    {{- $type := .Type }}
    {{- range $key, $value := .Data.Terms.Alphabetical }}
    {{- $name := .Name }}
    {{- $count := .Count }}
    {{- with site.GetPage (printf "/%s/%s" $type $name) }}
    <li>
        <a href="{{ .Permalink }}">{{ .Name }}<sup> {{ $count }}</sup></a>
    </li>
    {{- end }}
    {{- end }}
</ul>

{{- end }}{{/* end main */ -}}
```

Big difference!

Note that I'm using Chroma for syntax highlighting so your experience may vary, but Chroma comes by default with Hugo.

## References
- https://gohugo.io/content-management/syntax-highlighting/
- https://github.com/alecthomas/chroma
