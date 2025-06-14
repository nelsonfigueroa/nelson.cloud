+++
title = "Hacking into Hack The Box"
summary = "Getting the invite code to Hack The Box"
date = "2020-01-06"
lastmod = "2020-01-06"
categories = ["Cybersecurity"]
+++


<img src="/hacking-hack-the-box/hackthebox.webp" alt="Hack The Box Logo" width="720" height="270" style="max-width: 100%; height: auto; aspect-ratio: 800 / 300;" loading="lazy" decoding="async">

Hack The Box is an online penetration testing platform where users can practice their hacking abilities and test their cybersecurity knowledge. What's interesting is that in order to sign up to the site in the first place, you need to hack your way in. In this post, I'll be showing how I managed to get in and what my thought process was along the way.

<br>

>2022-12-10 Update: I noticed that hackthebox no longer requires users to solve a puzzle to register, so this post no longer applies :(

<br>

To sign up to the site, I was redirected to [https://www.hackthebox.eu/invite](https://www.hackthebox.eu/invite). There is a single field that prompts for an invite code. Other than that, there are no clues on the surface.

<img src="/hacking-hack-the-box/invitecode.webp" alt="Invite code field" width="610" height="260" style="max-width: 100%; height: auto; aspect-ratio: 610 / 260;" loading="lazy" decoding="async">

The first thing I did was to inspect the code by simply right-clicking on the page and selecting 'View Page Source'. Once I had the raw code in front of me, I tried to look for any clues as to how to get in. I ended up finding a JavaScript file that looked like exactly what I needed due to its name: [inviteapi.min.js](https://www.hackthebox.eu/js/inviteapi.min.js). However, it was minified and hard to decipher just by looking at it:

```javascript
eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--){d[c.toString(a)]=k[c]||c.toString(a)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('1 i(4){h 8={"4":4};$.9({a:"7",5:"6",g:8,b:\'/d/e/n\',c:1(0){3.2(0)},f:1(0){3.2(0)}})}1 j(){$.9({a:"7",5:"6",b:\'/d/e/k/l/m\',c:1(0){3.2(0)},f:1(0){3.2(0)}})}',24,24,'response|function|log|console|code|dataType|json|POST|formData|ajax|type|url|success|api|invite|error|data|var|verifyInviteCode|makeInviteCode|how|to|generate|verify'.split('|'),0,{}))
```

So I figured the next step would be to beautify the code. I opened up a new browser tab and searched for "javascript beautify" on google. I opened the first result, [https://beautifier.io/](https://beautifier.io/), and copied and pasted the minified code to get some readable code. This was the result:

```javascript
function verifyInviteCode(code) {
    var formData = {
        "code": code
    };
    $.ajax({
        type: "POST",
        dataType: "json",
        data: formData,
        url: '/api/invite/verify',
        success: function(response) {
            console.log(response)
        },
        error: function(response) {
            console.log(response)
        }
    })
}

function makeInviteCode() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: '/api/invite/how/to/generate',
        success: function(response) {
            console.log(response)
        },
        error: function(response) {
            console.log(response)
        }
    })
}
```

Just looking at the names of the functions confirmed to me that I was on the right path. Since I needed an invite code, I decided to run `makeInviteCode()` first in my browser's console and got this JSON in return:

```json
{
  "0": 200,
  "success": 1,
  "data": {
    "data": "Va beqre gb trarengr gur vaivgr pbqr, znxr n CBFG erdhrfg gb /ncv/vaivgr/trarengr",
    "enctype": "ROT13"
  }
}
```

The `data` clearly looks like encrypted text. And we even get the `enctype` telling us what form of encryption was used: ROT 13. Once again, I opened up a new browser tab and searched for "ROT 13 decrypt". I chose [this website](https://cryptii.com/pipes/rot13-decoder)

Using the tool, I decrypted the string and got the result: "In order to generate the invite code, make a POST request to /api/invite/"

<img src="/hacking-hack-the-box/rot13site.webp" alt="Site used to decrypt ROT13 cipher" width="720" height="158" style="max-width: 100%; height: auto; aspect-ratio: 1820 / 400;" loading="lazy" decoding="async">

Now, to make a `POST` request to `https://hackthebox.eu/api/invite/` I used [HTTPie](https://httpie.org/). It was as easy as running the following:

```
$ http post https://www.hackthebox.eu/api/invite/generate
```

And I got the following JSON response:

```json
{
    "0": 200,
    "data": {
        "code": "QUpXRlAtR01QUlgtSVhSUFQtQ0dBVUEtUklNWE4=",
        "format": "encoded"
    },
    "success": 1
}
```
The code appears to be encoded. Based on previous experience, encoded strings that end with a `=` are generally encoded in base64. I could be wrong, but I decided to try decoding it as a base64 string anyway:

```
$ echo QUpXRlAtR01QUlgtSVhSUFQtQ0dBVUEtUklNWE4= | base64 --decode

AJWFP-GMPRX-IXRPT-CGAUA-RIMXN
```

That looked like a code to me. I tried it on the form, and sure enough it worked! I was in.

<img src="/hacking-hack-the-box/congrats.webp" alt="Congratulations screen" width="700" height="300" style="max-width: 100%; height: auto; aspect-ratio: 700 / 300;" loading="lazy" decoding="async">

It was a lot of fun trying to figure this out. That was the easiest part though. Next, I'll try to root some actual machines in Hack The Box's pentesting labs.
