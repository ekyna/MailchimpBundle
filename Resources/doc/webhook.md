### Example data from mailchimp webhook

Subscribe

```json
{
  "id": "1750771048",
  "email": "contact@ekyna.com",
  "email_type": "html",
  "ip_opt": "2.10.89.80",
  "web_id": "327617911",
  "merges": {
    "EMAIL": "contact@ekyna.com",
    "FNAME": "Etienne",
    "LNAME": "Dauvergne",
    "ADDRESS": "",
    "PHONE": "",
    "BIRTHDAY": ""
  },
  "list_id": "6897813fd8"
}

```

Unsubscribe data

```json
{
  "action": "unsub",
  "reason": "manual",
  "id": "1750771048",
  "email": "contact@ekyna.com",
  "email_type": "html",
  "ip_opt": "2.10.89.80",
  "web_id": "327617911",
  "merges": {
    "EMAIL": "contact@ekyna.com",
    "FNAME": "Etienne",
    "LNAME": "Dauvergne",
    "ADDRESS": "",
    "PHONE": "",
    "BIRTHDAY": ""
  },
  "list_id": "6897813fd8"
}
```

Profile update

```json
{
  "id": "0a981efa2a",
  "email": "test@ekyna.com",
  "email_type": "html",
  "ip_opt": "2.10.89.80",
  "web_id": "334127699",
  "merges": {
    "EMAIL": "test@ekyna.com",
    "FNAME": "Learn",
    "LNAME": "Ekyna",
    "ADDRESS": "",
    "PHONE": "",
    "BIRTHDAY": ""
  },
  "list_id": "6897813fd8"
}
```

Email update

```json
{
  "new_id": "9027f12402",
  "new_email": "learn@ekyna.com",
  "old_email": "test@ekyna.com",
  "list_id": "6897813fd8"
}
```
