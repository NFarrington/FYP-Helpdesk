# Permissions

### Permissions.Index

#### Request

`GET /api/permissions`

#### Parameters

None.

### Permissions.View

#### Request

`GET /api/permissions/{id}`

#### Parameters

None.

### Permissions.Edit

#### Request

`PUT /api/permissions/{id}`

#### Parameters

Name | Value | Details
--- | --- | ---
name | string | The permission's name.
description | string | The permission's description.
roles | array | An array of role IDs that should have this permission.
