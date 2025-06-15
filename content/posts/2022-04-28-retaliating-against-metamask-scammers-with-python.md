+++
title = "Retaliating Against MetaMask Scammers With Python"
summary = "Using Python to send fake seed phrases to a MetaMask scam site."
date = "2022-04-28"
lastmod = "2022-04-28"
categories = ["Cybersecurity", "Python"]
+++

## Reconnaissance

Recently, I recieved this email:

<img src="/retaliating-against-metamask-scammers/fake-metamask-email.webp" alt="fake metamask email" width="720" height="482" style="max-width: 100%; height: auto; aspect-ratio: 1190 / 798;" loading="lazy" decoding="async">

I have never used MetaMask. It was pretty obvious this was a scam. I decided to check it out anyway out of curiosity.
It led me to this site which looked legit but had a major flaw: there is no domain and I'm accessing an insecure IP address.

<img src="/retaliating-against-metamask-scammers/fake-metamask-site.webp" alt="fake metamask site" width="720" height="473" style="max-width: 100%; height: auto; aspect-ratio: 3446 / 2266;" loading="lazy" decoding="async">

Still curious, I followed along and clicked on "Start verification process". In the next page, there was a text field prompting me to input my seed phrase:

<img src="/retaliating-against-metamask-scammers/fake-metamask-input-field.webp" alt="fake metamask input field for seed phrases" width="720" height="473" style="max-width: 100%; height: auto; aspect-ratio: 3446 / 2266;" loading="lazy" decoding="async">

So I submitted 12 random words and used the browser dev tools to figure out where my seed phrase was being sent to.

<img src="/retaliating-against-metamask-scammers/fake-metamask-submission.webp" alt="fake metamask post-submission page" width="720" height="473" style="max-width: 100%; height: auto; aspect-ratio: 3446 / 2266;" loading="lazy" decoding="async">

It looks like my fake seed phrase is being sent to `/log.php` as a query string through a `GET` request. I wanted to make these scammers pay somehow.
I figured I could come up with a quick script to slam this endpoint with random seed phrases to waste their time.

To make my made up seed phrases look more legitimate, I needed to find out if the words in seed phrases have certain limits or if they come from a pool of words. I want the scammers to try every single phrase I submit only to find out that they don't work.

After doing some reading from the official [MetaMask site](https://metamask.zendesk.com/hc/en-us/articles/4404722782107-User-Guide-Secret-Recovery-Phrase-password-and-private-keys#h_01FYVAXJQT914HCHEYFPNMEJEA), I saw this:

<img src="/retaliating-against-metamask-scammers/metamask-documentation.webp" alt="metamask documentation" width="720" height="305" style="max-width: 100%; height: auto; aspect-ratio: 1724 / 732;" loading="lazy" decoding="async">

So there's a specific list of words that seed phrases are generated from and I have a direct link to them. This was pefect. I could use this list of words in my script:

- https://github.com/bitcoin/bips/blob/master/bip-0039/english.txt

## Scripting Time

I came up with a Python script that takes the list of words from the GitHub link, generates a 12 word seed phrase, and sends a `GET` request to the scammer URL along with the seed phrase as a query string:

```python
import requests
import random

# List of words from https://github.com/bitcoin/bips/blob/master/bip-0039/english.txt
# (Trimmed due to long length)
SEED_PHRASE_WORDS = [
    "abandon",
    "ability",
    "able",
    "about",
    "above",
    .
    .
    .
]

# getting list of 12 random words
seed_phrase = random.sample(SEED_PHRASE_WORDS, 12)

# joining the list of 12 words into a single string
seed_phrase = " ".join(seed_phrase)

# query string for request
params = {"send_words": seed_phrase}

# sending GET request
response = requests.get("http://176.113.115.238/38sy2a0egs6zhxv/log.php", params=params)

# Output
print(f'Sent phrase: {seed_phrase}')
print(f'{response.status_code} {response.text}')
```

I ran this and it worked:

<img src="/retaliating-against-metamask-scammers/script-output.webp" alt="script output" width="720" height="65" style="max-width: 100%; height: auto; aspect-ratio: 1468 / 134;" loading="lazy" decoding="async">

The final step was to put this on a loop and leave it running for a very long time. I added a `while` loop. This is what the updated code looks like:

```python
import requests
import random

# List of words from https://github.com/bitcoin/bips/blob/master/bip-0039/english.txt
# (Trimmed due to long length)
SEED_PHRASE_WORDS = [
    "abandon",
    "ability",
    "able",
    "about",
    "above",
    .
    .
    .
]

while True:
    # getting list of 12 random words
    seed_phrase = random.sample(SEED_PHRASE_WORDS, 12)

    # joining the list of 12 words into a single string
    seed_phrase = " ".join(seed_phrase)

    # query string for request
    params = {"send_words": seed_phrase}

    # sending GET request
    response = requests.get("http://176.113.115.238/38sy2a0egs6zhxv/log.php", params=params)

    # Output
    print(f'Sent phrase: {seed_phrase}')
    print(f'{response.status_code} {response.text}')
```

After that, I left my script running overnight:

<img src="/retaliating-against-metamask-scammers/script-output-2.webp" alt="script output with loop" width="720" height="518" style="max-width: 100%; height: auto; aspect-ratio: 1844 / 1328;" loading="lazy" decoding="async">

The morning after, I noticed that my script output was stuck along with a new response message...

<img src="/retaliating-against-metamask-scammers/scammer-response.webp" alt="scammer response" width="720" height="518" style="max-width: 100%; height: auto; aspect-ratio: 1844 / 1328;" loading="lazy" decoding="async">

I guess they caught on LOL. They blocked my IP address from sending requests, so I simply changed changed my IP address and carried on.

---
2022-07-25 update: I should have included headers in my requests to make them look as legitimate as possible. The `requests` Python package sends the version of the package itself in the `User-Agent` header, giving away the fact that Python is being used to send requests.

The following code shows an example of the `User-Agent` being sent:

```python
import requests

r = requests.get('https://google.com')

print(r.request.headers)

# => {'User-Agent': 'python-requests/2.27.1', 'Accept-Encoding': 'gzip, deflate', 'Accept': '*/*', 'Connection': 'keep-alive'}
```

---

Using [Little Snitch](https://www.obdev.at/products/littlesnitch/index.html) I was able to see that this IP address originates from Russia, which I thought was interesting.

<img src="/retaliating-against-metamask-scammers/little-snitch.webp" alt="Little Snitch connection to Russia" width="720" height="316" style="max-width: 100%; height: auto; aspect-ratio: 3514 / 1546;" loading="lazy" decoding="async">

## Final Thoughts

I hate scammers. I hope I made their lives difficult.
If they're smart, they'll filter out seed phrases coming in from my IP address in their database.
But considering that their fake MetaMask site doesn't use a domain or SSL, they probably aren't very bright.
Even if my original IP address was blocked, I'm sure the seed phrases I provided polluted their database. I doubt they're keeping track of IP addresses at the database level.
