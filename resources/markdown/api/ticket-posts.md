# Ticket.Posts

### Ticket.Posts.Create

#### Request

`POST /api/tickets/{id}/posts`

#### Parameters

Name | Value | Details
--- | --- | ---
reply | string | The content of the post.

### Ticket.Posts.Edit

#### Request

`PUT /api/tickets/{id}/posts/{id}`

#### Parameters

Name | Value | Details
--- | --- | ---
content | string | The ticket post's new content.

### Ticket.Posts.Delete

#### Request

`DELETE /api/tickets/{id}/posts/{id}`

#### Parameters

None.
