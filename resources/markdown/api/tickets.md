# Tickets

### Tickets.Index

#### Request

`GET /api/tickets`

#### Parameters

None.

### Tickets.Create

#### Request

`POST /api/tickets`

#### Parameters

Name | Value | Details
--- | --- | ---
summary | string | The ticket's title/summary.
content | string | The content of the ticket.
department_id | integer | The department ID to submit the ticket to.

### Tickets.View

#### Request

`GET /api/tickets/{id}`

#### Parameters

None.

### Tickets.Edit

#### Request

`PUT /api/tickets/{id}`

#### Parameters

Name | Value | Details
--- | --- | ---
close | boolean | True - the ticket should be closed
