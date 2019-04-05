define({ "api": [
  {
    "type": "post",
    "url": "/login",
    "title": "",
    "version": "1.0.1",
    "group": "Authentication",
    "name": "Login",
    "description": "<p>Login API after successful login will give the user details</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>Mandatory.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>Mandatory.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n    \"name\": \"Hayley Dibbert\",\n    \"email\": \"cremin.elza@reichert.com\",\n    \"no_of_post\": 0\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\n    \"message\": \"Unauthenticated.\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/AuthController.php",
    "groupTitle": "Authentication"
  }
] });
