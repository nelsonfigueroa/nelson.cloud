+++
title = "NGINX: Difference Between ~ and ~* Tildes"
summary = "Comparing the differences between ~ and ~* tildes in NGINX configuration."
date = "2022-06-06"
categories = ["NGINX"]
keywords = ["NGINX", "nginx configuration", "location directive", "regex modifiers", "case sensitive", "case insensitive", "nginx tildes", "nginx location", "web server", "nginx tutorial"]
+++

The difference is that `~` is case-sensitive while `~*` is not case-sensitive.

In the example below, the path `/admin/` would match while `/Admin/` would not:

```nginx
location ~ ^/admin/$ {
    return 301 https://$host/;
}
```

In this example, both `/admin` and `/Admin/` would match and be redirected:

```nginx
location ~* ^/admin/$ {
    return 301 https://$host/;
}
```
