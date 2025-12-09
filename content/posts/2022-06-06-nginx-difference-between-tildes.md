+++
title = "NGINX: Difference Between ~ and ~* Tildes"
summary = "~ for case-sensitive matching vs ~* for case-insensitive pattern matching in location blocks."
date = "2022-06-06"
categories = ["NGINX"]
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
