+++
title = "Publishing Fake AWS API Keys on My Site"
summary = "Using canarytokens.org to generate fake AWS API keys and then publishing them on my site."
date = "2023-06-16"
lastmod = "2023-06-16"
categories = ["Cybersecurity"]
toc = false
+++

I recently discovered [canarytokens.org](https://canarytokens.org), which helps detect if your infrastructure has been breached through various methods. For example, [canarytokens.org](https://canarytokens.org) lets me generate fake AWS API keys and receive an email notification when they are used. So I did exactly that just to see what would happen.

I published the fake AWS API keys to my site under [https://nelson.cloud/.env](https://nelson.cloud/.env). I chose `.env` because this is a file that is commonly scanned for by scripts/bots due to accidental uploads. I knew someone would find the credentials eventually and I was curious how quickly and frequently they would be accessed.

At the time of writing this blog post, this is what the `.env` file contains:

```shell
$ curl https://nelson.cloud/.env

AWS_ACCESS_KEY_ID=AKIA2OGYBAH6TDQ3GH4E
AWS_SECRET_ACCESS_KEY=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
REGION=us-east-2
```

Around 2 minutes after deploying out this file, I received my first email notification from [canarytokens.org](https://canarytokens.org). That was fast! It just goes to show that people are constantly scanning for credentials on the internet. Within an an hour or so I had ~10 notifications.

The notification emails from canarytokens.org provide useful details about the attackers using the AWS API keys. Here's an example notification I received:

{{< figure src="/putting-up-fake-aws-keys/notification.png" alt="canarytokens.org notification showing python usage" >}}

We can see based on the user agent that whoever used these AWS keys was doing this automatically using Python.

I also got another notification that shows that the attacker was using Powershell:

{{< figure src="/putting-up-fake-aws-keys/notification2.png" alt="canarytokens.org notification showing powershell usage" >}}

It's worth noting that **User Agent strings can be spoofed**, so take these screenshots with a grain of salt.

There is a guide here if you're interested in trying this out for yourself:
- https://docs.canarytokens.org/guide/