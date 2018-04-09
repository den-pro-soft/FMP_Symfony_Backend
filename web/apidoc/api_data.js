define({ "api": [
  {
    "type": "get",
    "url": "/api/v1/blog/:category?page=:page",
    "title": "Career Advice",
    "version": "1.0.0",
    "name": "Career_Advice",
    "description": "<p>Blog.</p>",
    "group": "Blog",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "category",
            "description": "<p>career-advice|linkedin|resume|interviewing|job-search</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "page",
            "description": "<p>Page number.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "count",
            "description": "<p>Number of pages.</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "blogs",
            "description": "<p>Array of blogs object.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     \"count\": 13,\n     \"blogs\": [\n         {\n             \"title\": \"Blog title\",\n             \"seo_title\": \"Blog seo title\",\n             \"description\": \"Blog description\",\n             \"image\": \"Url to the blog image\",\n             \"post_date\": \"2017-04-05T04:30:31+0300\",\n             \"url\": \"Blog url\",\n             \"author\": {\n                 \"email\": \"User email\",\n                 \"full_name\": \"User full name\",\n                 \"avatar\": \"Url to the user image\"\n             }\n         }\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "../../src/RestBundle/Controller/Documentation.php",
    "groupTitle": "Blog"
  },
  {
    "type": "get",
    "url": "/api/v1/career-advice/:title",
    "title": "Career Advice view",
    "version": "1.0.0",
    "name": "Career_Advice_view",
    "description": "<p>Blog.</p>",
    "group": "Blog",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>Blog title.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>Blog title</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "seo_title",
            "description": "<p>Blog seo title</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>Blog description</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "image",
            "description": "<p>Url to the blog image</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "post_date",
            "description": "<p>Blog datetime</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "url",
            "description": "<p>Blog url</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "author",
            "description": "<p>Blog author</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     {\n         \"title\": \"Blog title\",\n         \"seo_title\": \"Blog seo title\",\n         \"description\": \"Blog description\",\n         \"image\": \"Url to the blog image\",\n         \"post_date\": \"2017-04-05T04:30:31+0300\",\n         \"url\": \"Blog url\",\n         \"author\": {\n             \"email\": \"User email\",\n             \"full_name\": \"User full name\",\n             \"avatar\": \"Url to the user image\",\n             \"link\": \"User link\"\n             }\n     }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "../../src/RestBundle/Controller/Documentation.php",
    "groupTitle": "Blog"
  },
  {
    "type": "post",
    "url": "/api/v1/checkout",
    "title": "Checkout",
    "version": "1.0.0",
    "permission": [
      {
        "name": "token",
        "title": "Token access rights needed.",
        "description": "<p>The user must have access token in the query.</p>"
      }
    ],
    "name": "Checkout",
    "description": "<p>Checkout page.</p>",
    "group": "Checkout",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "services",
            "description": "<p>Services collection.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n [\n     {\n         \"id\": 1,\n         \"name\": \"Career Finder\",\n         \"price_senior\": 1,\n         \"price_executive\": null,\n         \"icon\": \"card-career-finder\",\n         \"link\": \"career-finder\"\n     },\n ]",
          "type": "json"
        }
      ]
    },
    "filename": "../../src/RestBundle/Controller/Documentation.php",
    "groupTitle": "Checkout"
  },
  {
    "type": "get",
    "url": "/api/v1/congratulation",
    "title": "Congratulation",
    "version": "1.0.0",
    "permission": [
      {
        "name": "token",
        "title": "Token access rights needed.",
        "description": "<p>The user must have access token in the query.</p>"
      }
    ],
    "name": "Congratulation",
    "description": "<p>Congratulation page.</p>",
    "group": "Checkout",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "services",
            "description": "<p>Services collection.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n [\n     {\n         \"id\": 1,\n         \"name\": \"Career Finder\",\n         \"price_senior\": 1,\n         \"price_executive\": null,\n         \"icon\": \"card-career-finder\",\n         \"link\": \"career-finder\"\n     },\n ]",
          "type": "json"
        }
      ]
    },
    "filename": "../../src/RestBundle/Controller/Documentation.php",
    "groupTitle": "Checkout"
  },
  {
    "type": "post",
    "url": "/api/v1/change-password?t=:token",
    "title": "Change password",
    "permission": [
      {
        "name": "token",
        "title": "Token access rights needed.",
        "description": "<p>The user must have access token in the query.</p>"
      }
    ],
    "version": "1.0.0",
    "name": "Change_password",
    "group": "Password",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>New password.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "confirm_password",
            "description": "<p>Confirm new password.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n     \"password\": \"qwerty123!\",\n     \"confirm_password\": \"qwerty123!\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>Ok</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Password has been changed</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": \"Ok\",\n  \"message\": \"Password has been changed\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>Fail</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Passwords does not match.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "\nHTTP/1.1 401 Bad Request\n{\n  \"status\": \"Fail\",\n  \"message\": \"Passwords does not match.\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "../../src/RestBundle/Controller/Documentation.php",
    "groupTitle": "Password"
  },
  {
    "type": "post",
    "url": "/api/v1/forgot-password",
    "title": "Forgot password",
    "version": "1.0.0",
    "name": "Forgot_password",
    "group": "Password",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>User email.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n     \"email\": \"test@test.com\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subject",
            "description": "<p>Ok</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "body",
            "description": "<p>Check your email</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": \"Ok\",\n  \"message\": \"Check your email\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>Fail</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>We could not find an account with that email address. Please review and try again.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"status\": \"Fail\",\n  \"message\": \"We could not find an account with that email address. Please review and try again.\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "../../src/RestBundle/Controller/Documentation.php",
    "groupTitle": "Password"
  },
  {
    "type": "get",
    "url": "/api/v1/profile",
    "title": "Get profile",
    "version": "1.0.0",
    "permission": [
      {
        "name": "token",
        "title": "Token access rights needed.",
        "description": "<p>The user must have access token in the query.</p>"
      }
    ],
    "name": "Account",
    "description": "<p>Profile.</p>",
    "group": "Profile",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "profile",
            "description": "<p>User profile.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "   HTTP/1.1 200 OK\n   {\n    \"profile\": {\n        \"full_name\": \"Vasya Pupkin\",\n        \"email\": \"nikolay.savchuk@jelvix.com\",\n        \"phone_number\": \"12345456708\",\n        \"street_address\": \"Fake street\",\n        \"city\": \"Fake City\",\n        \"state\": \"Fake State\",\n        \"postal_code\": 12345,\n        \"birth_date\": \"2017-01-31T00:00:00+0200\",\n        \"linkedin_url\": \"http://fmp.app/app_dev.php/api/v1/profile/save\"\n   },\n    \"career_preferences\": {\n        \"industry\": \"Test\",\n        \"job_titles\": [\n            \"test1\",\n            \"test2\",\n            \"test3\"\n        ],\n        \"job_types\": [\n            \"test3\",\n            \"test4\"\n        ],\n        \"relocation_value\": true,\n        \"relocation_type\": \"null\",\n        \"relocation_location\": [\n            \"dfasdasd\",\n            \"dasdasdasd\"\n        ],\n        \"experience\": \"djaksdjask\",\n        \"education\": \"djaksdjask\",\n        \"desire_salary_value\": 10000,\n        \"desire_salary_type\": \"hours\"\n   },\n    \"education\": [],\n    \"work_experience\": [\n        {\n            \"id\": 1,\n            \"employer\": \"San Francisco1\",\n            \"job_title\": \"dsads1\",\n            \"start_date\": \"2015-05\",\n            \"end_date\": \"2016-05\",\n            \"salary_earned\": \"1212\",\n            \"reason_for_leaving\": \"dasdas fasdf dsfsd fs dfds\"\n        }\n    ],\n    \"reference\": [],\n    \"documents\": [\n        {\n            \"id\": 1,\n            \"name\": \"tech.docx\",\n            \"type\": \"Resume\",\n            \"added_by\": \"Test Admin\",\n            \"date_added\": \"2017-06-21T14:47:14+0300\",\n            \"document\": \"http://fmp.app/app_dev.php/api/v1/download/document/1\"\n        },\n        {\n            \"id\": 2,\n            \"name\": \"tech.docx\",\n            \"type\": \"Resume\",\n            \"added_by\": \"Vasya Pupkin\",\n            \"date_added\": \"2017-06-21T14:47:14+0300\",\n            \"document\": \"http://fmp.app/app_dev.php/api/v1/download/document/2\"\n        }\n    ],\n    \"templates\": [\n        {\n            \"id\": 1,\n            \"name\": \"tech.docx\",\n            \"template\": \"http://fmp.app/app_dev.php/api/v1/download/template/1\"\n        }\n    ],\n    \"questions\": {\n        \"work_authorization\": \"asdasdasd\",\n        \"gender\": \"dasdasd\",\n        \"veteran_status\": \"dscfsdfs\",\n        \"disability_status\": \"dsfdfs\",\n        \"race_ethnicity\": \"sdffds\"\n    },\n    \"progress\": {\n        \"value\": 0,\n        \"values\": {\n            \"profile\": \"Update Profile\",\n            \"career_preferences\": \"Update Career Preferences\",\n            \"work_experience_education\": \"Update Education & Work Experience\",\n            \"reference\": \"Update Reference\",\n            \"documents\": \"Update Documents\",\n            \"questions\": \"Update Questions\"\n        }\n    }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "../../src/RestBundle/Controller/Documentation.php",
    "groupTitle": "Profile"
  },
  {
    "type": "delete",
    "url": "/api/v1/profile/:section/:id",
    "title": "Delete",
    "version": "1.0.0",
    "permission": [
      {
        "name": "token",
        "title": "Token access rights needed.",
        "description": "<p>The user must have access token in the query.</p>"
      }
    ],
    "name": "Delete_section",
    "description": "<p>Delete section in the profile.</p>",
    "group": "Profile",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "section",
            "description": "<p>profile|career_preferences|education|work_experience|reference|document|questions</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>section unique id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "profile",
            "description": "<p>User profile.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "   HTTP/1.1 200 OK\n   {\n    \"profile\": {\n        \"full_name\": \"Vasya Pupkin\",\n        \"email\": \"nikolay.savchuk@jelvix.com\",\n        \"phone_number\": \"12345456708\",\n        \"street_address\": \"Fake street\",\n        \"city\": \"Fake City\",\n        \"state\": \"Fake State\",\n        \"postal_code\": 12345,\n        \"birth_date\": \"2017-01-31T00:00:00+0200\",\n        \"linkedin_url\": \"http://fmp.app/app_dev.php/api/v1/profile/save\"\n   },\n    \"career_preferences\": {\n        \"industry\": \"Test\",\n        \"job_titles\": [\n            \"test1\",\n            \"test2\",\n            \"test3\"\n        ],\n        \"job_types\": [\n            \"test3\",\n            \"test4\"\n        ],\n        \"relocation_value\": true,\n        \"relocation_type\": \"null\",\n        \"relocation_location\": [\n            \"dfasdasd\",\n            \"dasdasdasd\"\n        ],\n        \"experience\": \"djaksdjask\",\n        \"education\": \"djaksdjask\",\n        \"desire_salary_value\": 10000,\n        \"desire_salary_type\": \"hours\"\n   },\n    \"education\": [],\n    \"work_experience\": [\n        {\n            \"id\": 1,\n            \"employer\": \"San Francisco1\",\n            \"job_title\": \"dsads1\",\n            \"start_date\": \"2015-05\",\n            \"end_date\": \"2016-05\",\n            \"salary_earned\": \"1212\",\n            \"reason_for_leaving\": \"dasdas fasdf dsfsd fs dfds\"\n        }\n    ],\n    \"reference\": [],\n    \"documents\": [\n        {\n            \"id\": 1,\n            \"name\": \"tech.docx\",\n            \"type\": \"Resume\",\n            \"added_by\": \"Test Admin\",\n            \"date_added\": \"2017-06-21T14:47:14+0300\",\n            \"document\": \"http://fmp.app/app_dev.php/api/v1/download/document/1\"\n        },\n        {\n            \"id\": 2,\n            \"name\": \"tech.docx\",\n            \"type\": \"Resume\",\n            \"added_by\": \"Vasya Pupkin\",\n            \"date_added\": \"2017-06-21T14:47:14+0300\",\n            \"document\": \"http://fmp.app/app_dev.php/api/v1/download/document/2\"\n        }\n    ],\n    \"templates\": [\n        {\n            \"id\": 1,\n            \"name\": \"tech.docx\",\n            \"template\": \"http://fmp.app/app_dev.php/api/v1/download/template/1\"\n        }\n    ],\n    \"questions\": {\n        \"work_authorization\": \"asdasdasd\",\n        \"gender\": \"dasdasd\",\n        \"veteran_status\": \"dscfsdfs\",\n        \"disability_status\": \"dsfdfs\",\n        \"race_ethnicity\": \"sdffds\"\n    },\n    \"progress\": {\n        \"value\": 0,\n        \"values\": {\n            \"profile\": \"Update Profile\",\n            \"career_preferences\": \"Update Career Preferences\",\n            \"work_experience_education\": \"Update Education & Work Experience\",\n            \"reference\": \"Update Reference\",\n            \"documents\": \"Update Documents\",\n            \"questions\": \"Update Questions\"\n        }\n    }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "../../src/RestBundle/Controller/Documentation.php",
    "groupTitle": "Profile"
  },
  {
    "type": "put",
    "url": "/api/v1/profile/:section",
    "title": "Update",
    "version": "1.0.0",
    "permission": [
      {
        "name": "token",
        "title": "Token access rights needed.",
        "description": "<p>The user must have access token in the query.</p>"
      }
    ],
    "name": "Update_account",
    "description": "<p>Update profile.</p>",
    "group": "Profile",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "section",
            "description": "<p>profile|career_preferences|education|work_experience|reference|document|questions</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "profile",
            "description": "<p>User profile.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "   HTTP/1.1 200 OK\n   {\n    \"profile\": {\n        \"full_name\": \"Vasya Pupkin\",\n        \"email\": \"nikolay.savchuk@jelvix.com\",\n        \"phone_number\": \"12345456708\",\n        \"street_address\": \"Fake street\",\n        \"city\": \"Fake City\",\n        \"state\": \"Fake State\",\n        \"postal_code\": 12345,\n        \"birth_date\": \"2017-01-31T00:00:00+0200\",\n        \"linkedin_url\": \"http://fmp.app/app_dev.php/api/v1/profile/save\"\n   },\n    \"career_preferences\": {\n        \"industry\": \"Test\",\n        \"job_titles\": [\n            \"test1\",\n            \"test2\",\n            \"test3\"\n        ],\n        \"job_types\": [\n            \"test3\",\n            \"test4\"\n        ],\n        \"relocation_value\": true,\n        \"relocation_type\": \"null\",\n        \"relocation_location\": [\n            \"dfasdasd\",\n            \"dasdasdasd\"\n        ],\n        \"experience\": \"djaksdjask\",\n        \"education\": \"djaksdjask\",\n        \"desire_salary_value\": 10000,\n        \"desire_salary_type\": \"hours\"\n   },\n    \"education\": [],\n    \"work_experience\": [\n        {\n            \"id\": 1,\n            \"employer\": \"San Francisco1\",\n            \"job_title\": \"dsads1\",\n            \"start_date\": \"2015-05\",\n            \"end_date\": \"2016-05\",\n            \"salary_earned\": \"1212\",\n            \"reason_for_leaving\": \"dasdas fasdf dsfsd fs dfds\"\n        }\n    ],\n    \"reference\": [],\n    \"documents\": [\n        {\n            \"id\": 1,\n            \"name\": \"tech.docx\",\n            \"type\": \"Resume\",\n            \"added_by\": \"Test Admin\",\n            \"date_added\": \"2017-06-21T14:47:14+0300\",\n            \"document\": \"http://fmp.app/app_dev.php/api/v1/download/document/1\"\n        },\n        {\n            \"id\": 2,\n            \"name\": \"tech.docx\",\n            \"type\": \"Resume\",\n            \"added_by\": \"Vasya Pupkin\",\n            \"date_added\": \"2017-06-21T14:47:14+0300\",\n            \"document\": \"http://fmp.app/app_dev.php/api/v1/download/document/2\"\n        }\n    ],\n    \"templates\": [\n        {\n            \"id\": 1,\n            \"name\": \"tech.docx\",\n            \"template\": \"http://fmp.app/app_dev.php/api/v1/download/template/1\"\n        }\n    ],\n    \"questions\": {\n        \"work_authorization\": \"asdasdasd\",\n        \"gender\": \"dasdasd\",\n        \"veteran_status\": \"dscfsdfs\",\n        \"disability_status\": \"dsfdfs\",\n        \"race_ethnicity\": \"sdffds\"\n    },\n    \"progress\": {\n        \"value\": 0,\n        \"values\": {\n            \"profile\": \"Update Profile\",\n            \"career_preferences\": \"Update Career Preferences\",\n            \"work_experience_education\": \"Update Education & Work Experience\",\n            \"reference\": \"Update Reference\",\n            \"documents\": \"Update Documents\",\n            \"questions\": \"Update Questions\"\n        }\n    }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "../../src/RestBundle/Controller/Documentation.php",
    "groupTitle": "Profile"
  },
  {
    "type": "post",
    "url": "/api/v1/signin",
    "title": "SignIn user",
    "version": "1.0.0",
    "name": "SignIn_user",
    "description": "<p>Sign in user.</p>",
    "group": "SignIn",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>User email.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>User password.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n     \"email\": \"test@test.com\",\n     \"password\": \"qwerty123!\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "username",
            "description": "<p>Username</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>User authentication token.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>User email.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "full_name",
            "description": "<p>User full name.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     \"username\": \"test@test.com\",\n     \"email\": \"test@test.com\",\n     \"full_name\": \"Vasya Pupkin\",\n     \"token\": \"nei9cx7olb4k0cg8w4oc80wsg8s408o\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>Fail</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Incorrect password</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"status\": \"Fail\",\n  \"message\": \"Incorrect password\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "../../src/RestBundle/Controller/Documentation.php",
    "groupTitle": "SignIn"
  },
  {
    "type": "post",
    "url": "/api/v1/signup?t=:token",
    "title": "SignUp user",
    "version": "1.0.0",
    "permission": [
      {
        "name": "token",
        "title": "Token access rights needed.",
        "description": "<p>The user must have access token in the query.</p>"
      }
    ],
    "name": "SignUp_user",
    "description": "<p>Sign up user</p>",
    "group": "SignUp",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>User email.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "full_name",
            "description": "<p>User full name.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>User password.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "confirm_password",
            "description": "<p>User password again.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n     \"email\": \"test@test.com\",\n     \"full_name\": \"Vasya Pupkin\",\n     \"password\": \"qwerty123!\",\n     \"confirm_password\": \"qwerty123!\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "username",
            "description": "<p>Username</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>User authentication token.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>User email.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "full_name",
            "description": "<p>User full name.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     \"username\": \"test@test.com\",\n     \"email\": \"test@test.com\",\n     \"full_name\": \"Vasya Pupkin\",\n     \"token\": \"nei9cx7olb4k0cg8w4oc80wsg8s408o\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>Fail</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Passwords does not match.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"status\": \"Fail\",\n  \"message\": \"Passwords does not match.\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "../../src/RestBundle/Controller/Documentation.php",
    "groupTitle": "SignUp"
  }
] });
