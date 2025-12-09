+++
title = "Finding Private Information Through Resumes on Google Search"
summary = "Reconsider uploading your resume on the open web."
date = "2024-09-16"
categories = ["Cybersecurity", "Privacy", "Google"]
ShowToc = false
TocOpen = false
+++

I noticed a lot of people are willing to upload their full unredacted resume to their personal sites.
I recently had an idea of using Google search filters to see how easy it would be to find these.
I wanted to see how easily a malicious person could harvest things like phone numbers, email addresses, and even physical addresses.
Turns out it's not that difficult.

I started by searching domains that host static sites like `github.io` and `pages.dev`.

I found a lot of resumes with this Google search. [Try this search yourself](https://www.google.com/search?q=resume.pdf+filetype:pdf+site:github.io).

```
resume.pdf filetype:pdf site:github.io
```

Searching for `resume` instead of `resume.pdf` also works, but the results are polluted by "Resume Guide" PDFs and etc.

<br>

Searching for the standard `github.com` domain also yields lots of results. [Try this search yourself](https://www.google.com/search?q=resume.pdf+filetype:pdf+site:github.com).

```
resume.pdf filetype:pdf site:github.com
```

<br>

After GitHub I decided to try the `pages.dev` domain used by [Cloudflare Pages](https://pages.cloudflare.com/). Here is a simple search query that gives good results. [Try this search yourself](https://www.google.com/search?q=resume+filetype:pdf+site:pages.dev).

```
resume filetype:pdf site:pages.dev
```

<br>

The Netlify domain `netlify.app` is used for static pages and also hosts a lot of resumes that users upload. [Try this search yourself](https://www.google.com/search?q=resume+filetype:pdf+site:netlify.app).

```
resume filetype:pdf site:netlify.app
```

<br>

There are top level domains (TLDs) commonly used for personal sites, such as `.me` and `.dev`. These domains are also great for finding resumes. Here's a Google search for the `.me` TLD. [Try it yourself](https://www.google.com/search?q=resume+filetype:pdf+site:*.me).

```
resume filetype:pdf site:*.me
```

<br>

And here is a Google search for the `.dev` TLD which is commonly used by developers. [Try this out yourself](https://www.google.com/search?q=resume+filetype:pdf+site:*.dev).

```
resume.pdf filetype:pdf site:*.dev
```

<br>

I didn't try this with other TLDs but I know that other TLDs like `.id`, `.blog`, and `.codes` are usually used for personal sites. Feel free to search by these TLDs and see what you can find!

We can take this further. What if we want to find people based on something more specific than a domain or TLD? I'll keep using the `.dev` TLD for simplicity but there are many possibilities.

Maybe we want to find someone in a specific city? [Try it yourself](https://www.google.com/search?q=boston+resume+filetype:pdf+site:*.dev).

```
boston resume filetype:pdf site:*.dev
```

<br>

Or someone that went to a particular college? [Try it yourself](https://www.google.com/search?q=University+of+California+Los+Angeles+resume+filetype:pdf+site:*.dev).

```
University of California Los Angeles resume filetype:pdf site:*.dev
```

<br>

Someone that works or worked at a certain company? [Try it yourself](https://www.google.com/search?q="Apple%2C+Inc"+resume+filetype%3Apdf+site%3A*.dev).

```
"Apple, Inc" resume filetype:pdf site:*.dev
```

<br>

Maybe we want the name, number, and address of someone who has worked at a defense company and may know sensitive information important to U.S. national security? Note that I didn't filter based on domain or TLD here. [Try it yourself](https://www.google.com/search?q="resume.pdf"+"Raytheon"+filetype%3Apdf).

```
"resume.pdf" "Raytheon" filetype:pdf
```

<small><i>(I'm just joking. Please don't come after me, feds.)</i></small>

<br>

I also considered writing a script to run these searches for me, gather all PDF links, download the PDFs, and then parse through them for names, numbers, emails, and addresses.
But I stopped because I have no interest in collecting this information. I just wanted to see and show others how easy it is to find information on the open web. It's definitely possible to write a script to do this though.

## Conclusion

In conclusion, it's really easy to find private information on resumes posted online. Please reconsider if you are thinking of making your resume available online.

## Further Reading

I am not the first to have this idea. Here's a similar post that you should also check out:
- https://www.trickster.dev/post/simple-ways-to-find-exposed-sensitive-information/
