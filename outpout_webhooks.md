### Event Delivery
{
  "RecordType": "Delivery",
  "ServerID": 23,
  "MessageStream": "outbound",
  "MessageID": "00000000-0000-0000-0000-000000000000",
  "Recipient": "john@example.com",
  "Tag": "welcome-email",
  "DeliveredAt": "2026-02-13T18:19:43Z",
  "Details": "Test delivery webhook details",
  "Metadata": {
    "example": "value",
    "example_2": "value"
  }
}

### Event Bounce
{
  "ID": 42,
  "Type": "HardBounce",
  "RecordType": "Bounce",
  "TypeCode": 1,
  "Tag": "Test",
  "MessageID": "00000000-0000-0000-0000-000000000000",
  "Details": "Test bounce details",
  "Email": "john@example.com",
  "From": "sender@example.com",
  "BouncedAt": "2026-02-13T18:19:43Z",
  "Inactive": true,
  "DumpAvailable": true,
  "CanActivate": true,
  "Subject": "Test subject",
  "ServerID": 1234,
  "MessageStream": "outbound",
  "Content": "Test content",
  "Name": "Hard bounce",
  "Description": "The server was unable to deliver your message (ex: unknown user, mailbox not found).",
  "Metadata": {
    "example": "value",
    "example_2": "value"
  }
}

### Event Spam Complaint
{
  "RecordType": "SpamComplaint",
  "ID": 42,
  "Type": "SpamComplaint",
  "TypeCode": 100001,
  "Tag": "Test",
  "MessageID": "00000000-0000-0000-0000-000000000000",
  "Details": "Test spam complaint details",
  "Email": "john@example.com",
  "From": "sender@example.com",
  "BouncedAt": "2026-02-13T18:19:43Z",
  "Inactive": true,
  "DumpAvailable": true,
  "CanActivate": true,
  "Subject": "Test subject",
  "ServerID": 1234,
  "MessageStream": "outbound",
  "Content": "Test content",
  "Name": "Spam complaint",
  "Description": "The subscriber explicitly marked this message as spam.",
  "Metadata": {
    "example": "value",
    "example_2": "value"
  }
}

### Open
{
  "RecordType": "Open",
  "MessageStream": "outbound",
  "Metadata": {
    "example": "value",
    "example_2": "value"
  },
  "FirstOpen": true,
  "Recipient": "john@example.com",
  "MessageID": "00000000-0000-0000-0000-000000000000",
  "ReceivedAt": "2026-02-13T18:19:43Z",
  "Platform": "WebMail",
  "ReadSeconds": 5,
  "Tag": "welcome-email",
  "UserAgent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36",
  "OS": {
    "Name": "OS X 10.7 Lion",
    "Family": "OS X 10",
    "Company": "Apple Computer, Inc."
  },
  "Client": {
    "Name": "Chrome 35.0.1916.153",
    "Family": "Chrome",
    "Company": "Google"
  },
  "Geo": {
    "IP": "188.2.95.4",
    "City": "Novi Sad",
    "Country": "Serbia",
    "CountryISOCode": "RS",
    "Region": "Autonomna Pokrajina Vojvodina",
    "RegionISOCode": "VO",
    "Zip": "21000",
    "Coords": "45.2517,19.8369"
  }
}


### Link Click
{
  "RecordType": "Click",
  "MessageStream": "outbound",
  "Metadata": {
    "example": "value",
    "example_2": "value"
  },
  "Recipient": "john@example.com",
  "MessageID": "00000000-0000-0000-0000-000000000000",
  "ReceivedAt": "2026-02-13T18:19:43Z",
  "Platform": "Desktop",
  "ClickLocation": "HTML",
  "OriginalLink": "https://example.com",
  "Tag": "welcome-email",
  "UserAgent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36",
  "OS": {
    "Name": "OS X 10.7 Lion",
    "Family": "OS X 10",
    "Company": "Apple Computer, Inc."
  },
  "Client": {
    "Name": "Chrome 35.0.1916.153",
    "Family": "Chrome",
    "Company": "Google"
  },
  "Geo": {
    "IP": "188.2.95.4",
    "City": "Novi Sad",
    "Country": "Serbia",
    "CountryISOCode": "RS",
    "Region": "Autonomna Pokrajina Vojvodina",
    "RegionISOCode": "VO",
    "Zip": "21000",
    "Coords": "45.2517,19.8369"
  }
}