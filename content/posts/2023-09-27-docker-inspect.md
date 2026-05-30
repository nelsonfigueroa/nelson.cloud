+++
title = "How to Determine the Entrypoint for a Docker Image"
summary = "Using `docker inspect` to determine the entrypoint for a given docker image."
date = "2023-09-27"
lastmod = "2025-12-08T19:34:09-08:00"
categories = ["Docker"]
+++

You can use `docker inspect` to determine the entrypoint of a docker image.

The command looks like this:

```shell
docker inspect --type=image --format='{{json .Config.Entrypoint}}' <image-name>
```

And here's a real-world example (assuming you have the `hashicorp/terraform` image downloaded):

```console
$ docker inspect --type=image --format='{{json .Config.Entrypoint}}' hashicorp/terraform

["/bin/terraform"]
```

There are some examples on the [official docs](https://docs.docker.com/reference/cli/docker/inspect/) but none of the examples covered my use cases, so here are some additional useful `docker inspect` examples using the `busybox` image.

List environment variables:

```console
$ docker inspect --type=image --format='{{json .Config.Env}}' busybox

["PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin"]
```

List the `CMD`:

```console
$ docker inspect --type=image --format='{{json .Config.Cmd}}' busybox

["sh"]
```

Print out the architecture:

```console
$ docker inspect --type=image --format='{{json .Architecture}}' busybox

"amd64"
```


The `docker inspect` command can also be used to inspect other docker resources too, not just images. Check out the documentation for more information.

References:
- https://docs.docker.com/reference/cli/docker/inspect/
