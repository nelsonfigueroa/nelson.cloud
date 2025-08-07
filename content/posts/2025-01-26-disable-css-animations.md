+++
title = "How to Disable CSS Animations and Transitions"
summary = "Disable CSS animations and transitions with a few lines of CSS."
date = "2025-01-26"
lastmod = "2025-03-01"
categories = ["CSS", "HTML"]
keywords = ["disable CSS animations", "CSS transitions", "animation none", "CSS performance", "reduce motion", "CSS optimization", "disable transitions", "CSS accessibility", "animation control"]
ShowToc = false
TocOpen = false
+++

To disable CSS animations and transitions you can try adding the following to your CSS:

```css
* {
    animation: none;
    transition: none;
}
```

If that doesn't work, try using `!important` in your CSS:

```css
* {
    animation: none !important;
    transition: none !important;
}
```

You can also put these into a CSS class and apply to HTML elements as needed if you still want animations in some places:

```css
/* style.css */

.no-animations {
    animation: none;
    transition: none;
}
```

```html
<!-- index.html -->

<html>
  <head>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
     <!-- add a class to whatever elements you want -->
     <button class="no-animations" type="button">No Animations on this Button</button>

     <button type="button">Animations are ok here</button> 
  </body>
</html>
```

If you're using a CSS library that comes with animations by default it may be a pain to maintain a modified version of it without animations. It's easier to write your own CSS to disable animations and transitions.

My motivation behind this post was disabling button animations when using [daisyUI](https://daisyui.com/) on a side project.

Note that I am not a CSS expert and there may be other ways of animating elements via CSS that I did not cover here. Also, this doesn't help if elements are being moved around by JavaScript. But you can easily disable JavaScript for any site through your browser :)

## References
- https://stackoverflow.com/questions/11131875/what-is-the-cleanest-way-to-disable-css-transition-effects-temporarily
- https://stackoverflow.com/questions/31576156/what-does-animationnone-do-exactly
- https://developer.mozilla.org/en-US/docs/Web/CSS/animation
- https://developer.mozilla.org/en-US/docs/Web/CSS/transition