FORMAT: 1A
HOST: http://yoursite.com/cms-api/acl

# ACL

ACL Module API documentation.

# Users [/users]

## Retrieve all users [GET]

+ Response 200 (application/json)

        {  
           "data":[  
              {  
                 "id":4,
                 "email":"coen@pixelindustries.com",
                 "first_name":"Coen",
                 "last_name":"Zimmerman",
                 "roles":[  
                    "admin"
                 ],
                 "permissions":[]
              },
              {  
                 "id":6,
                 "email":"test@testing.nl",
                 "first_name":"Tester T.",
                 "last_name":"Testington",
                 "roles":[  
                    "editor"
                 ],
                 "permissions":[]
              }
           ]
        }
       
## Retrieve a user [GET]

+ Response 200 (application/json)

        {  
           "data":[  
              {  
                 "id":4,
                 "email":"coen@pixelindustries.com",
                 "first_name":"Coen",
                 "last_name":"Zimmerman",
                 "roles":[  
                    "admin"
                 ],
                 "permissions":[]
              },
              {  
                 "id":6,
                 "email":"test@testing.nl",
                 "first_name":"Tester T.",
                 "last_name":"Testington",
                 "roles":[  
                    "editor"
                 ],
                 "permissions":[]
              }
           ]
        }
        
## Single User [/users/{userId}]

+ Parameters
    + userId: 1 (required, number) - ID of the user

### Retrieve a user [GET]

+ Response 200 (application/json)

        {  
           "data": {  
             "id":4,
             "email":"coen@pixelindustries.com",
             "first_name":"Coen",
             "last_name":"Zimmerman",
             "roles":[  
                "admin"
             ],
             "permissions":[]
           }
        }


## Create a user [GET]

+ email (required, string) - A valid email for the user
+ password (required, string) - A password
+ first_name (optional, string)
+ last_name (optional, string)
+ roles (optional, array[string]) - A collection of roles to assign

+ Request (application/json)

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
        
+ Response 201 (application/json)

        {  
           "data": {  
             "id":9,
             "email":"someuser@somedomain.com",
             "first_name":"Some",
             "last_name":"User",
             "roles":[  
                "admin",
                "editor"
             ],
             "permissions":[]
           }
        }
