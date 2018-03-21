# Articles

### Articles.Index

#### Request

`GET /api/articles`

#### Parameters

None.

### Articles.Create

#### Request

`POST /api/articles`

#### Parameters

Name | Value | Details
--- | --- | ---
title | string | The article's title.
content | string | The article's content, formatted in markdown.
visible-from-date | date (yyyy-mm-dd) | The date to publish the article at.
visible-from-time | time (hh:mm) | The time to publish the article at.
visible-to-date | date (yyyy-mm-dd) | The date to stop publishing the article at.
visible-to-time | time (hh:mm) | The time to stop publishing the article at.

### Articles.View

#### Request

`GET /api/articles/{id}`

#### Parameters

None.

### Articles.Edit

#### Request

`PUT /api/articles/{id}`

#### Parameters

Name | Value | Details
--- | --- | ---
title | string | The article's title.
content | string | The article's content, formatted in markdown.
visible-from-date | date (yyyy-mm-dd) | The date to publish the article at.
visible-from-time | time (hh:mm) | The time to publish the article at.
visible-to-date | date (yyyy-mm-dd) | The date to stop publishing the article at.
visible-to-time | time (hh:mm) | The time to stop publishing the article at.

### Articles.Delete

#### Request

`DELETE /api/articles/{id}`

#### Parameters

None.
