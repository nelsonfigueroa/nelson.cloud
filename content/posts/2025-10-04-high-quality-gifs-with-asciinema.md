+++
title = "Creating High Quality GIFs from Asciinema Recordings"
summary = "Use `agg` with a huge font size to get high quality GIFs."
date = "2025-10-04"
categories = ["Shell", "Asciinema"]
ShowToc = false
TocOpen = false
+++

Say you have a recording created with [Asciinema](https://asciinema.org/) named `recording.cast` and now you want to convert that into a GIF. You can use the `agg` command (which is a [separate installation](https://docs.asciinema.org/manual/agg/installation/)) to convert it like so:
```
agg recording.cast recording.gif
```

However, if you run `agg` with the default settings the resulting GIF won't be high quality and it'll look a little fuzzy.

To get a high quality GIF you need to specify a big font size with the `--font-size` option:

```
agg --font-size 64 recording.cast recording.gif
```

Here are two GIFs you can compare. The top one was created with the default `agg` settings. The bottom one was created with `--font-size 64`. Depending on your display, you may need to zoom in a bit to see the difference:

<img src="/asciinema-high-quality-gifs/pulumi-up.gif" alt="fuzzy GIF created with default agg settings" width="720" height="474" style="max-width: 100%; height: auto; aspect-ratio: 772 / 509;" loading="lazy" decoding="async">

<img src="/asciinema-high-quality-gifs/pulumi-up-in-hd.gif" alt="higher quality GIF created with agg --font-size 64" width="720" height="475" style="max-width: 100%; height: auto; aspect-ratio: 3532 / 2329;" loading="lazy" decoding="async">

It's a noticeable difference. The trade-off here is that the higher quality GIF will be a bigger filesize, so be mindful of that. Try experimenting with different font sizes.

I was expecting there to be some sort of `--size` or `--quality` option, but you just need to increase the font to get higher quality GIFs with `agg`.

## References
- https://docs.asciinema.org/manual/agg/
- https://docs.asciinema.org/manual/agg/installation/
- https://docs.asciinema.org/manual/agg/usage/
