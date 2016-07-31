FORMAT: 1A
HOST: acl

# ACL

ACL Module API documentation.

# Group Users

# Users [/users]

## Retrieve all users [GET]

Retrieves a full unpaginated list of users.

+ Response 200 (application/json)

        {
            "data": [
                {
                    "id": 4,
                    "email": "coen@pixelindustries.com",
                    "first_name": "Coen",
                    "last_name": "Zimmerman",
                    "roles": [
                        "admin"
                    ]
                },
                {
                    "id": 6,
                    "email": "test@testing.nl",
                    "first_name": "Tester T.",
                    "last_name": "Testington",
                    "roles": [
                        "editor"
                    ]
                }
            ]
        }


## Create a user [POST]

Create a new user, which may log in immediately after.

+ Attributes (object)
    + email (required, string) - A valid email for the user
    + password (required, string)
    + first_name (optional, string)
    + last_name (optional, string)
    + roles: admin,editor (optional, array[string]) - The roles to assign
        
+ Request Full data (application/json)
        
        {
            "email": "someuser@somedomain.com",
            "password" "secret",
            "first_name": "Some",
            "last_name": "User",
            "roles": [
                "admin",
                "editor"
            ]
        }
        
+ Request Minimal data (application/json)
        
        {
            "email": "someuser@somedomain.com",
            "password" "secret"
        }
                
+ Response 201 (application/json)
   
        {
            "data": {
                "id": 9,
                "email": "someuser@somedomain.com",
                "first_name": "Some",
                "last_name": "User",
                "roles": [
                    "admin",
                    "editor"
                ]
            }
        }
       
       
## Single User [/users/{userId}]

+ Parameters
    + userId: 1 (required, number) - ID of the user

### Retrieve a user [GET]

Retrieves a single user.

+ Response 200 (application/json)

    + Attributes (object)
        + id: 9 (number, required)
        + email: someuser@somedomain.com (string) - Valid e-mail address
        + first_name: Some (string)
        + last_name: User (string)
        + roles: admin,editor (array[string]) - A list of the roles currently assigned to the user

    + Body
    
            {
                "data": {
                    "id": 4,
                    "email": "coen@pixelindustries.com",
                    "first_name": "Coen",
                    "last_name": "Zimmerman",
                    "roles": [
                        "admin"
                    ]
                }
            }

### Update a user [PUT]

Updates a user.

Note that a user's e-mail address may not be changed. 
If new roles are set, any roles omitted will be unassigned.

+ Attributes (object)
    + password (string) - A new password
    + first_name (optional, string)
    + last_name (optional, string)
    + roles: admin,editor (optional, array[string]) - New roles to assign

+ Request Full data (application/json)

        {
            "password": "newsecret",
            "first_name": "New",
            "last_name": "Name",
            "roles": [
                "editor"
            ]
        }
        
+ Request Update roles only (application/json)

        {
            "roles": [
                "editor"
            ]
        }
        
+ Request New password only (application/json)

        {
            "password" "newsecret"
        }
        
+ Response 200 (application/json)

        {
            "data": {
                "id": 4,
                "email": "coen@pixelindustries.com",
                "first_name": "Coen",
                "last_name": "Zimmerman",
                "roles": [
                    "admin"
                ]
            }
        }

### Delete a user [DELETE]

Deletes a user. 
This instantly voids any open sessions and denies all access this user may have had.
 
+ Response 204


# Group Roles

## Roles [/roles]

### Retrieve all roles [GET]

Retrieves a list of all roles.

+ Response 200 (application/json)

        {
          "data":[
             {
                "key":"admin",
                "permissions":[
                   "acl.roles.show",
                   "acl.roles.edit",
                   "acl.roles.create",
                   "acl.roles.delete",
                   "acl.users.show"
                ]
             },
             {
                "key":"editor",
                "permissions":[
                    "acl.roles.show",
                    "acl.users.show"
                ]
             }
          ]
        }

## Create a role [POST]

Creates a new role.

+ Attributes (object)
    + key: admin(required, string) - A key that uniquely identifies the role
    + name: Administrator (string) - An optional display name
    + permissions: acl.users.show,acl.users.edit (optional, array[string]) - The permissions that users with this role get
        
+ Request (application/json)
        
                {
                    "key": "admin",
                    "name": "Administrator",
                    "permissions": [
                        "acl.users.show",
                        "acl.roles.show"
                    ]
                }
                
+ Response 201 (application/json)
        
                {  
                   "data": {  
                     "key": "admin",
                     "permissions":[  
                        "acl.users.show",
                        "acl.roles.show"
                     ]
                   }
                }
        

## Single Role [/roles/{roleKey}]

+ Parameters
    + roleKey: admin (required, string) - Key of the role

### Retrieve a role [GET]

Retrieves a single role by its key.

+ Response 200 (application/json)

    + Attributes (object)
        + key: admin (string, required)
        + permissions: acl.roles.show,acl.users.show (array[string]) - A list of the permissions that the role offers

    + Body

            {
                "data": {
                    "key":"admin",
                    "permissions":[
                        "acl.users.show",
                        "acl.roles.show"
                    ]
                }
            }

### Update a role [PUT]

Updates a role.

+ Response 200

        {
          "data": {
            "key":"editor",
            "permissions":[
                "acl.roles.show",
                "acl.users.show"
            ]
         }
        }

### Delete a role [DELETE]

Deletes a role.
Roles which are still in use (assigned to any user) are NOT deleted.

+ Response 204

+ Response 412
    
        {
            "message": "Role is still in use"
        }



# Group Permissions

## Permissions [/permissions]

### Retrieve all available permissions [GET]

Retrieves all permissions that are defined by loaded CMS modules.
This will exclude any permissions set for users or roles that are not currently defined by modules.

+ Response 200 (application/json)

        {
           "data":[
              {
                 "key":"do.something"
              },
              {
                 "key":"acl.roles.show"
              },
              {
                 "key":"acl.roles.create"
              },
              {
                 "key":"acl.roles.edit"
              },
              {
                 "key":"acl.roles.delete"
              },
              {
                 "key":"acl.users.show"
              },
              {
                 "key":"acl.users.create"
              },
              {
                 "key":"acl.users.edit"
              },
              {
                 "key":"acl.users.delete"
              }
           ]
        }

## Permissions per module [/permissions/module/{moduleKey}]

### Retrieve available permissions for a single module [GET]

Returns all permissions defined for a single module.

Module permissions are defined by their ACL presence data. 
This endpoint will return a flattened list of all permissions related to a single module.

+ Response 200 (application/json)

        {
           "data":[
              {
                 "key":"acl.roles.show"
              },
              {
                 "key":"acl.roles.create"
              },
              {
                 "key":"acl.roles.edit"
              },
              {
                 "key":"acl.roles.delete"
              }
           ]
        }

## Permissions in use [/permissions/used]

### Retrieve all permissions in use [GET]

Retrieves permissions in use, regardless of whether they are considered available or not.
This will only return the permissions that are currently assigned to any roles (or users).

+ Response 200 (application/json)

        {
           "data":[
              {
                 "key":"acl.roles.show"
              },
              {
                 "key":"acl.roles.edit"
              }
           ]
        }
