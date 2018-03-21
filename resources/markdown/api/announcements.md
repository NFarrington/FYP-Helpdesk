# Announcements

### Announcements.Index

#### Request

`GET /api/announcements`

#### Parameters

None.

### Announcements.Create

#### Request

`POST /api/announcements`

#### Parameters

Name | Value | Details
--- | --- | ---
title | string | The announcement's title.
content | string | The announcement's content, formatted in markdown.
status | integer | The announcement's status. 0 - unpublished, 1 - published, 2 - active

### Announcements.View

#### Request

`GET /api/announcements/{id}`

#### Parameters

None.

### Announcements.Edit

#### Request

`PUT /api/announcements/{id}`

#### Parameters

Name | Value | Details
--- | --- | ---
title | string | The announcement's title.
content | string | The announcement's content, formatted in markdown.
status | integer | The announcement's status. 0 - unpublished, 1 - published, 2 - active

### Announcements.Delete

#### Request

`DELETE /api/announcements/{id}`

#### Parameters

None.
