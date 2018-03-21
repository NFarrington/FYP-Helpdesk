# Roles

### Roles.Index

#### Request

`GET /api/roles`

#### Parameters

None.

### Roles.View

#### Request

`GET /api/roles/{id}`

#### Parameters

None.

### Roles.Edit

#### Request

`PUT /api/roles/{id}`

#### Parameters

Name | Value | Details
--- | --- | ---
name | string | The role's title.
description | string | The role's description.
permissions | array | An array of permission IDs that this role should have.
