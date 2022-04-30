+++
title = "Retaliating Against MetaMask Scammers"
summary = "Providing fake seed phrases to a scammer site."
date = "2022-04-28"
tags = ["cybersecurity"]
toc = false
+++

## Reconnaissance

Recently, I recieved this email:

{{< figure src="/retaliating_against_metamask_scammers/fake_metamask_email.png" alt="fake metamask email" >}}

I have never used MetaMask. It was pretty obvious this was a scam. I decided to check it out anyway out of curiosity. 
It led me to this site which looked legit but had a major flaw: there is no domain and I'm accessing an insecure IP address.

{{< figure src="/retaliating_against_metamask_scammers/fake_metamask_site.png" alt="fake metamask site" >}}

Still curious, I followed along and clicked on "Start verification process". In the next page, there was a text field prompting me to input my seed phrase:

{{< figure src="/retaliating_against_metamask_scammers/fake_metamask_input_field.png" alt="fake metamask input field for seed phrases" >}}

So I submitted 12 random words and used the browser dev tools to figure out where my seed phrase was being sent to.

{{< figure src="/retaliating_against_metamask_scammers/fake_metamask_submission.png" alt="fake metamask post-submission page" >}}

It looks like my fake seed phrase is being sent to `/log.php` as a query string through a `GET` request. I wanted to make these scammers pay somehow.
I figured I could come up with a quick script to slam this endpoint with random seed phrases to waste their time.

To make my made up seed phrases look more legitimate, I needed to find out if the words in seed phrases have certain limits or if they come from a pool of words. I want the scammers to try every single phrase I submit only to find out that they don't work.

After doing some reading from the official [MetaMask site](https://metamask.zendesk.com/hc/en-us/articles/4404722782107-User-Guide-Secret-Recovery-Phrase-password-and-private-keys#h_01FYVAXJQT914HCHEYFPNMEJEA), I saw this:

{{< figure src="/retaliating_against_metamask_scammers/metamask_documentation.png" alt="metamask documentation" >}}

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
{{< figure src="/retaliating_against_metamask_scammers/script_output.png" alt="script output" >}}

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
{{< figure src="/retaliating_against_metamask_scammers/script_output_2.png" alt="script output with loop" >}}

The morning after, I noticed that my script output was stuck along with a new response message...
{{< figure src="/retaliating_against_metamask_scammers/scammer_response.png" alt="scammer response" >}}

I guess they caught on LOL. They blocked my IP address from sending requests, so I simply changed changed my IP address and carried on.

Using [Little Snitch](https://www.obdev.at/products/littlesnitch/index.html) I was able to see that this IP address originates from Russia, which I thought was interesting.

{{< figure src="/retaliating_against_metamask_scammers/little_snitch.png" alt="Little Snitch connection to Russia" >}}

## Final Thoughts

I hate scammers. I hope I made their lives difficult. 
If they're smart, they'll filter out seed phrases coming in from my IP address in their database.
But considering that their fake MetaMask site doesn't use a domain or SSL, they probably aren't very bright.
Even if my original IP address was blocked, I'm sure the seed phrases I provided polluted their database. I doubt they're keeping track of IP addresses at the database level.
