+++
title = "Making Life More Difficult for Bank Scammers"
summary = "Using Python to flood scammers with fake information."
date = "2024-02-22"
lastmod = "2024-02-22"
categories = ["Python", "Cybersecurity"]
toc = false
+++

If you've been around you know I enjoy spamming phishing/scamming sites. I recently received this fake Chase email and decided to go down another phishing/scamming attempt.

{{< figure src="/chase-scam/email.webp" alt="Phishing Email" >}}

The email linked to a Google Drive link of a PDF.
{{< figure src="/chase-scam/pdf.webp" alt="Google Drive PDF" >}}

The PDF itself links to somewhere else. I clicked on the PDF and after some redirects I ultimately landed on a fake Chase login site. Look at the URL lol.

{{< figure src="/chase-scam/login.webp" alt="Fake Chase login form" >}}

Next, I opened my browser dev tools, I filled in the form and pressed "Sign In".

A `POST` request goes out to `https://secure005.access.chaise.com.secure-accessaccount.com/submit.php` with this payload:

```json
{"uid":"1ef25781cb3fd42f981b2bbb183d9887","ip":"138.199.35.102","uagent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:122.0) Gecko/20100101 Firefox/122.0","stp":"0","j_username":"anon","j_password":"password","securityToken":""}
```

Then I was shown this page:

{{< figure src="/chase-scam/wrong-credentials.webp" alt="Error message" >}}

And of course on the frontend there's a message about incorrect credentials. It's one of the most common tricks these people use.

Attempting to submit new credentials does nothing. I noticed that the "reset your password" link is the only way forward so I clicked that and it took me to this page:

{{< figure src="/chase-scam/otp.webp" alt="One-time code form" >}}

It's asking for a one-time code. I'm not really sure how they expect people to enter a code that they normally receive after submitting *correct* credentials. Not only that but they don't even know the phone number of the user. Either way, I filled in `000000` and hit "Next".

Another `POST` request goes out to the same URL as before: `https://secure005.access.chaise.com.secure-accessaccount.com/submit.php` with this JSON:

```json
{"uid":"1ef25781cb3fd42f981b2bbb183d9887","stp":"1","otp":"000000"}
```

(I think it's obvious now that the `stp` parameter that keeps showing up represents the step of the fake password recovery process. And based on the value of step, the logic of `submit.php` parses the JSON accordingly.)

Then I landed on this page that asks me for my card information:

{{< figure src="/chase-scam/credit-card.webp" alt="credit card form" >}}

I once again filled the form with fake information and hit "Next". Another `POST` request went out to the same URL as before. The main thing that changes here are the headers and the JSON payload.

```json
{"uid":"1ef25781cb3fd42f981b2bbb183d9887","stp":"2","cnum":"6504 8764 7593 8248","expd":"03/24","cvv":"333"}
```

(Also, I haven't been showing the headers because they're not that interesting, but I am keeping track of them for scripting purposes later.)

Next, I was shown this form. Notice how the first field says "Full Number". I'm pretty sure they meant "Full Name" lol.

{{< figure src="/chase-scam/personal-details.webp" alt="personal details form" >}}

Once again, I filled out and submitted the form.

Once again, a `POST` request goes out to the same URL. This time the JSON payload looks like this:

```json
{"uid":"1ef25781cb3fd42f981b2bbb183d9887","stp":"3","fullname":"Fuck You","bdate":"01/01/1900","ssn":"123-74-6772","phone":"(827) 373-8139","address":"some st","city":"some city","state":"FU","zip":"82920","email":"nah@fu.com"}
```

The `fullname` key in the JSON payload confirms that they meant to write "Full Name" in the field as opposed to "Full Number" lol.

After submitting the previous form I was shown yet another one, this time asking for email address and email password. They are really thorough. They also asked for my email in the previous step so that was a bit redundant.

{{< figure src="/chase-scam/email-form.webp" alt="email form" >}}

I typed in some fake credentials and submitted the form. A `POST` request goes out to the same URL as before with the following JSON payload:
```json
{"uid":"1ef25781cb3fd42f981b2bbb183d9887","stp":"4","cemail":"nah@fu.com","bdate":"ajlksdasdjl"}
```

Then I was redirected to the offical Chase site:

{{< figure src="/chase-scam/chase-site.webp" alt="Official Chase site" >}}

## Spamming Fake Information with Python

If you've read my previous articles about scams and phishing, you know what's next. It's time to write up a Python script to spam these people with fake information and hopefully make their lives harder.

These guys were thorough so I'll need to dynamically create 5 different payloads and send them to the url. Thankfully it's the same endpoint for all of these payloads.

I want to dynamically generate headers and payloads that seem as realstic as possible. These scammers were thorough so I want to be thorough in making their lives more difficult (also I'm unemployed right now so I have a lot of free time. Someone please hire me 🥺)

This is the Python script I came up with:

```python
from faker import Faker
import requests
import uuid
import random
import string

fake = Faker()
url = "https://secure005.access.chaise.com.secure-accessaccount.com/submit.php"

step_0_headers = None
step_1_headers = None
step_2_headers = None
step_3_headers = None
step_4_headers = None

step_0_payload = None
step_1_payload = None
step_2_payload = None
step_3_payload = None
step_4_payload = None


def generate_headers_and_payloads():
    # telling Python to modify the variables on a global scope
    global step_0_headers, step_1_headers, step_2_headers, step_3_headers, step_4_headers, step_0_payload, step_1_payload, step_2_payload, step_3_payload, step_4_payload
    fake_phpsessid = "".join(random.choices(string.ascii_letters + string.digits, k=26))

    step_0_headers = {
        "Host": "secure005.access.chaise.com.secure-accessaccount.com",
        "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:122.0) Gecko/20100101 Firefox/122.0",
        "Accept": "*/*",
        "Accept-Language": "en-US,en;q=0.5",
        "Accept-Encoding": "gzip, deflate, br",
        "Content-Type": "application/x-www-form-urlencoded",
        "Content-Length": str(fake.random_int(min=80, max=215)),
        "Origin": "https://secure005.access.chaise.com.secure-accessaccount.com",
        "DNT": "1",
        "Sec-GPC": "1",
        "Connection": "keep-alive",
        "Referer": "https://secure005.access.chaise.com.secure-accessaccount.com/web/auth/dashboard",
        "Cookie": f"PHPSESSID={fake_phpsessid}; stp=0; ppath=web%2Fauth%2Fdashboard%23%2Fdashboard%2Findex%2Findex",
        "Sec-Fetch-Dest": "empty",
        "Sec-Fetch-Mode": "cors",
        "Sec-Fetch-Site": "same-origin",
        "Pragma": "no-cache",
        "Cache-Control": "no-cache",
    }

    step_1_headers = {
        "Host": "secure005.access.chaise.com.secure-accessaccount.com",
        "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:122.0) Gecko/20100101 Firefox/122.0",
        "Accept": "*/*",
        "Accept-Language": "en-US,en;q=0.5",
        "Accept-Encoding": "gzip, deflate, br",
        "Content-Type": "application/x-www-form-urlencoded",
        "Content-Length": str(fake.random_int(min=80, max=215)),
        "Origin": "https://secure005.access.chaise.com.secure-accessaccount.com",
        "DNT": "1",
        "Sec-GPC": "1",
        "Connection": "keep-alive",
        "Referer": "https://secure005.access.chaise.com.secure-accessaccount.com/",
        "Cookie": f"PHPSESSID={fake_phpsessid}; stp=1; ppath=oamo/identity/help/passwordhelp/",
        "Sec-Fetch-Dest": "empty",
        "Sec-Fetch-Mode": "cors",
        "Sec-Fetch-Site": "same-origin",
        "Pragma": "no-cache",
        "Cache-Control": "no-cache",
    }

    step_2_headers = {
        "Host": "secure005.access.chaise.com.secure-accessaccount.com",
        "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:122.0) Gecko/20100101 Firefox/122.0",
        "Accept": "*/*",
        "Accept-Language": "en-US,en;q=0.5",
        "Accept-Encoding": "gzip, deflate, br",
        "Content-Type": "application/x-www-form-urlencoded",
        "Content-Length": str(fake.random_int(min=80, max=215)),
        "Origin": "https://secure005.access.chaise.com.secure-accessaccount.com",
        "DNT": "1",
        "Sec-GPC": "1",
        "Connection": "keep-alive",
        "Referer": "https://secure005.access.chaise.com.secure-accessaccount.com/",
        "Cookie": f"PHPSESSID={fake_phpsessid}; stp=2; ppath=oamo/identity/help/passwordhelp/",
        "Sec-Fetch-Dest": "empty",
        "Sec-Fetch-Mode": "cors",
        "Sec-Fetch-Site": "same-origin",
        "Pragma": "no-cache",
        "Cache-Control": "no-cache",
    }

    step_3_headers = {
        "Host": "secure005.access.chaise.com.secure-accessaccount.com",
        "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:122.0) Gecko/20100101 Firefox/122.0",
        "Accept": "*/*",
        "Accept-Language": "en-US,en;q=0.5",
        "Accept-Encoding": "gzip, deflate, br",
        "Content-Type": "application/x-www-form-urlencoded",
        "Content-Length": str(fake.random_int(min=80, max=215)),
        "Origin": "https://secure005.access.chaise.com.secure-accessaccount.com",
        "DNT": "1",
        "Sec-GPC": "1",
        "Connection": "keep-alive",
        "Referer": "https://secure005.access.chaise.com.secure-accessaccount.com/",
        "Cookie": f"PHPSESSID={fake_phpsessid}; stp=3; ppath=oamo/identity/help/passwordhelp/",
        "Sec-Fetch-Dest": "empty",
        "Sec-Fetch-Mode": "cors",
        "Sec-Fetch-Site": "same-origin",
        "Pragma": "no-cache",
        "Cache-Control": "no-cache",
    }

    step_4_headers = {
        "Host": "secure005.access.chaise.com.secure-accessaccount.com",
        "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:122.0) Gecko/20100101 Firefox/122.0",
        "Accept": "*/*",
        "Accept-Language": "en-US,en;q=0.5",
        "Accept-Encoding": "gzip, deflate, br",
        "Content-Type": "application/x-www-form-urlencoded",
        "Content-Length": str(fake.random_int(min=80, max=215)),
        "Origin": "https://secure005.access.chaise.com.secure-accessaccount.com",
        "DNT": "1",
        "Sec-GPC": "1",
        "Connection": "keep-alive",
        "Referer": "https://secure005.access.chaise.com.secure-accessaccount.com/",
        "Cookie": f"PHPSESSID={fake_phpsessid}; stp=4; ppath=oamo/identity/help/passwordhelp/",
        "Sec-Fetch-Dest": "empty",
        "Sec-Fetch-Mode": "cors",
        "Sec-Fetch-Site": "same-origin",
        "Pragma": "no-cache",
        "Cache-Control": "no-cache",
    }

    uid = uuid.uuid4().hex

    step_0_payload = {
        "uid": uid,
        "ip": fake.ipv4(),
        "uagent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:122.0) Gecko/20100101 Firefox/122.0",
        "stp": "0",
        "j_username": fake.simple_profile()["username"],
        "j_password": fake.password(length=12),
        "securityToken": "",
    }

    step_1_payload = {
        "uid": uid,
        "stp": "1",
        "otp": fake.random_int(min=100000, max=999999),
    }

    # the credit card in the payload has spaces in between every number, so I am replicating this
    credit_card_number = fake.credit_card_number("mastercard")
    # adding spaces in between every 4 digits
    credit_card_number = " ".join(
        [credit_card_number[i : i + 4] for i in range(0, len(credit_card_number), 4)]
    )

    step_2_payload = {
        "uid": uid,
        "stp": "2",
        "cnum": credit_card_number,
        "expd": fake.credit_card_expire(),
        "cvv": fake.credit_card_security_code(),
    }

    step_3_payload = {
        "uid": uid,
        "stp": "3",
        "fullname": fake.name(),
        "bdate": fake.date_of_birth(minimum_age=18).strftime("%m/%d/%Y"),
        "ssn": fake.ssn(),
        "phone": fake.phone_number(),
        "address": fake.street_address(),
        "city": fake.city(),
        "state": fake.state_abbr(),
        "zip": fake.postcode(),
        "email": fake.simple_profile()["mail"],
    }

    step_4_payload = {
        "uid": uid,
        "stp": "4",
        "cemail": fake.simple_profile()["mail"],
        "bdate": fake.password(
            length=12
        ),  # the parameter is bdate but in the frontend it asked for password
    }


requests_sent = 0
while True:
    # dynamically generate fake headers and JSON payloads
    generate_headers_and_payloads()

    # handle exceptions so that the script continues even if there are connection issues
    try:
        # send request for each step using the fake generated data
        response = requests.post(url, headers=step_0_headers, json=step_0_payload)
        print(f"Step 0 status code: {response.status_code}")
        response = requests.post(url, headers=step_1_headers, json=step_1_payload)
        print(f"Step 1 status code: {response.status_code}")
        response = requests.post(url, headers=step_2_headers, json=step_2_payload)
        print(f"Step 2 status code: {response.status_code}")
        response = requests.post(url, headers=step_3_headers, json=step_3_payload)
        print(f"Step 3 status code: {response.status_code}")
        response = requests.post(url, headers=step_4_headers, json=step_4_payload)
        print(f"Step 4 status code: {response.status_code}")

        requests_sent = requests_sent + 5
        print(f"Requests sent: {requests_sent}")
    except requests.exceptions.RequestException as e:
        print({e})
```

It's quite lengthy but it works. I ran the script in the background and went about my day.

{{< figure src="/chase-scam/terminal.webp" alt="Terminal output after running the Python script" >}}

## More Retaliation Against Scammers

If you enjoyed this, I've done a few other posts similar to this one:
- [Hitting Back at Ledger Scammers With Python](/hitting-back-at-ledger-scammers-with-python/)
- [Using Python to Flood Scammers with Fake Passwords](/using-python-to-flood-scammers-with-fake-passwords/)
- [Retaliating Against MetaMask Scammers With Python](/retaliating-against-metamask-scammers-with-python/)
