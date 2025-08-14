+++
title = "The prefers-reduced-motion CSS Media Query"
summary = "Apply CSS styles when a user enables reduced motion on their device."
date = "2024-11-27"
categories = ["CSS"]
keywords = ["CSS media query", "prefers-reduced-motion", "CSS accessibility", "motion sensitivity", "CSS animations", "reduced motion", "accessibility", "CSS tutorial", "responsive design"]
+++

I recently learned about the `prefers-reduced-motion` [CSS media query](https://developer.mozilla.org/en-US/docs/Web/CSS/@media/prefers-reduced-motion). In a nutshell, if a user enables reduced motion on their device/browser, the CSS styles within this media query will be applied.

For example, if buttons have animations when they're clicked like this:

```css
button:active {
  transform: translateY(4px);
}
```

We can disable those animations if a user has enabled reduced motion on their device by using the `prefers-reduced-motion` media query like this:

```css
@media (prefers-reduced-motion) {
    /* anything in here will apply on devices with reduced motion enabled */
    button:active {
        transform: none;
    }
}
```

Technically, the CSS being applied under `prefers-reduced-motion` doesn't need to be related to motions or animations. It's possible to do other things, like changing the background color, if a user prefers reduced motion. So something like this is possible too:

```css
body {
  background-color: red;
}

@media (prefers-reduced-motion) {
    body {
        background-color: blue;
    }
}
```

The Mozilla docs cover how to enable reduced motion on several browsers:
- https://developer.mozilla.org/en-US/docs/Web/CSS/@media/prefers-reduced-motion

## References
- https://developer.mozilla.org/en-US/docs/Web/CSS/@media
- https://developer.mozilla.org/en-US/docs/Web/CSS/@media/prefers-reduced-motion
- https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_media_queries/Using_media_queries
