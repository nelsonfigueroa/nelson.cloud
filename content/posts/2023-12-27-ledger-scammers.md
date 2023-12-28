+++
title = "Hitting Back at Ledger Scammers With Python"
summary = "Using a Python script to send fake data to a Ledger phishing site."
date = "2023-12-27"
lastmod = "2023-12-27"
categories = ["Cybersecurity", "Python"]
toc = false
+++

## Fake Ledger Email

I recently received this email claiming to be from [Ledger](https://www.ledger.com/). I immediately knew it was a scam.

{{< figure src="/ledger-scammers/scam-email.webp" alt="Email pretending to be from Ledger" >}}

I decided to take a peek at `vaultscanner.com` just for fun. The site looked like a genuine Ledger site. This site was created with more effort than other scam sites I've seen.

{{< figure src="/ledger-scammers/fake-ledger-site.webp" alt="The fake Ledger website." >}}

I clicked on a random Ledger device and it played a "connecting" animation.

{{< figure src="/ledger-scammers/connecting-device.webp" alt="Fake Ledger site showing a Ledger device connecting." >}}

After that I got an error. It's obviously fake. How can my Ledger data be damaged if I didn't connect a Ledger device to begin with? I don't even own one.

{{< figure src="/ledger-scammers/fake-error.webp" alt="Fake error" >}}

And of course, the site then prompts for the recovery phrase.

{{< figure src="/ledger-scammers/recovery-phrase-entry.webp" alt="Fake Ledger site asking for recovery phrase" >}}

I checked the browser dev tools to see where this phrase was going to. It was getting sent as a query string to `/data1.php` as a `POST` request.

{{< figure src="/ledger-scammers/dev-tools.webp" alt="Developer tools showing where the recovery phrase was sent" >}}

## Writing a Python Script

I had an idea to write a quick Python script to send fake data to the scammers. This is something I've done in the past to MetaMask scammers: [Retaliating Against MetaMask Scammers With Python]({{< relref "/posts/2022-04-28-retaliating-against-metamask-scammers-with-python.md" >}}).

With some quick research, I found that the recovery phrases for Ledger devices are created using the same wordlist that MetaMask uses. I also learned that Ledger recovery phrases are 24 words long. With this information I was ready to start writing a script.

Here's the script I came up with:

```python
import requests
import random
import time

# List of words from https://github.com/bitcoin/bips/blob/master/bip-0039/english.txt
# (Trimmed due to long length)
WORDLIST = [
    "abandon",
    "ability",
    "able",
    "about",
    "above",
    .
    .
    .
]

requests_sent = 0

while True:
    # getting list of 24 random words
    recovery_phrase = random.sample(WORDLIST, 24)

    # joining the list of 24 words into a single string
    recovery_phrase = " ".join(recovery_phrase)

    # query string for request
    params = {
        "privateKey": recovery_phrase,
        "token": "Ledger"
    }

    # sending POST request
    response = requests.post(
        "https://vaultscanner.com/data1.php", params=params, headers={}
    )

    requests_sent = requests_sent + 1

    # Output
    print(f"Requests sent: {requests_sent}, Status code: {response.status_code}, Phrase sent: {recovery_phrase}\n")

```

Then I just let it run for a while to give the scammers a ton of fake data.

{{< figure src="/ledger-scammers/terminal.webp" alt="Terminal output when running Python script." >}}

I hope I made scamming/phishing more difficult for them :)

