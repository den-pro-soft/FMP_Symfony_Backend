<?php
// ------------------------------------------------------------------------------------------
// Security.
// ------------------------------------------------------------------------------------------
/**
 * @apiDefine user User access rights needed.
 * The user must have access token in the header.
 */
/**
 * @apiDefine token Token access rights needed.
 * The user must have access token in the query.
 */
/**
 * @api {post} /api/v1/signin SignIn user
 * @apiVersion 1.0.0
 * @apiName SignIn user
 * @apiDescription Sign in user.
 *
 * @apiGroup SignIn
 *
 * @apiParam {String} email User email.
 * @apiParam {String} password User password.
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *          "email": "test@test.com",
 *          "password": "qwerty123!"
 *     }
 *
 * @apiSuccess {String} username Username
 * @apiSuccess {String} token  User authentication token.
 * @apiSuccess {String} email  User email.
 * @apiSuccess {String} full_name  User full name.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *          "username": "test@test.com",
 *          "email": "test@test.com",
 *          "full_name": "Vasya Pupkin",
 *          "token": "nei9cx7olb4k0cg8w4oc80wsg8s408o"
 *     }
 *
 * @apiError {String} status Fail
 * @apiError {String} message Incorrect password
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "status": "Fail",
 *       "message": "Incorrect password"
 *     }
 */

/**
 * @api {post} /api/v1/signup?t=:token SignUp user
 * @apiVersion 1.0.0
 * @apiPermission token
 * @apiName SignUp user
 * @apiDescription Sign up user
 *
 * @apiGroup SignUp
 *
 * @apiParam {String} email User email.
 * @apiParam {String} full_name User full name.
 * @apiParam {String} password User password.
 * @apiParam {String} confirm_password User password again.
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *          "email": "test@test.com",
 *          "full_name": "Vasya Pupkin",
 *          "password": "qwerty123!",
 *          "confirm_password": "qwerty123!"
 *     }
 *
 * @apiSuccess {String} username Username
 * @apiSuccess {String} token  User authentication token.
 * @apiSuccess {String} email  User email.
 * @apiSuccess {String} full_name  User full name.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *          "username": "test@test.com",
 *          "email": "test@test.com",
 *          "full_name": "Vasya Pupkin",
 *          "token": "nei9cx7olb4k0cg8w4oc80wsg8s408o"
 *     }
 *
 * @apiError {String} status Fail
 * @apiError {String} message Passwords does not match.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "status": "Fail",
 *       "message": "Passwords does not match."
 *     }
 */
/**
 * @api {post} /api/v1/forgot-password Forgot password
 * @apiVersion 1.0.0
 * @apiName Forgot password
 *
 * @apiGroup Password
 *
 * @apiParam {String} email User email.
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *          "email": "test@test.com"
 *     }
 *
 * @apiSuccess {String} subject Ok
 * @apiSuccess {String} body Check your email
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "status": "Ok",
 *       "message": "Check your email"
 *     }
 *
 * @apiError {String} status Fail
 * @apiError {String} message We could not find an account with that email address. Please review and try again.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "status": "Fail",
 *       "message": "We could not find an account with that email address. Please review and try again."
 *     }
 */
/**
 * @api {post} /api/v1/change-password?t=:token Change password
 * @apiPermission token
 * @apiVersion 1.0.0
 * @apiName Change password
 *
 * @apiGroup Password
 *
 * @apiParam {String} password New password.
 * @apiParam {String} confirm_password Confirm new password.
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *          "password": "qwerty123!",
 *          "confirm_password": "qwerty123!"
 *     }
 *
 * @apiSuccess {String} status Ok
 * @apiSuccess {String} message Password has been changed
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "status": "Ok",
 *       "message": "Password has been changed"
 *     }
 *
 * @apiError {String} status Fail
 * @apiError {String} message Passwords does not match.
 *
 * @apiErrorExample Error-Response:
 *
 *     HTTP/1.1 401 Bad Request
 *     {
 *       "status": "Fail",
 *       "message": "Passwords does not match."
 *     }
 */
// ------------------------------------------------------------------------------------------
// Career Advice.
// ------------------------------------------------------------------------------------------
/**
 * @api {get} /api/v1/blog/:category?page=:page Career Advice
 * @apiVersion 1.0.0
 * @apiName Career Advice
 * @apiDescription Blog.
 *
 * @apiGroup Blog
 *
 * @apiParam {String} category career-advice|linkedin|resume|interviewing|job-search
 * @apiParam {Integer} page Page number.
 *
 * @apiSuccess {Integer} count Number of pages.
 * @apiSuccess {Array} blogs Array of blogs object.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *          "count": 13,
 *          "blogs": [
 *              {
 *                  "title": "Blog title",
 *                  "seo_title": "Blog seo title",
 *                  "description": "Blog description",
 *                  "image": "Url to the blog image",
 *                  "post_date": "2017-04-05T04:30:31+0300",
 *                  "url": "Blog url",
 *                  "author": {
 *                      "email": "User email",
 *                      "full_name": "User full name",
 *                      "avatar": "Url to the user image"
 *                  }
 *              }
 *          ]
 *     }
 */
/**
 * @api {get} /api/v1/career-advice/:title Career Advice view
 * @apiVersion 1.0.0
 * @apiName Career Advice view
 * @apiDescription Blog.
 *
 * @apiGroup Blog
 *
 * @apiParam {String} title Blog title.
 *
 * @apiSuccess {String} title Blog title
 * @apiSuccess {String} seo_title Blog seo title
 * @apiSuccess {String} description Blog description
 * @apiSuccess {String} image Url to the blog image
 * @apiSuccess {String} post_date Blog datetime
 * @apiSuccess {String} url Blog url
 * @apiSuccess {Object} author Blog author
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *          {
 *              "title": "Blog title",
 *              "seo_title": "Blog seo title",
 *              "description": "Blog description",
 *              "image": "Url to the blog image",
 *              "post_date": "2017-04-05T04:30:31+0300",
 *              "url": "Blog url",
 *              "author": {
 *                  "email": "User email",
 *                  "full_name": "User full name",
 *                  "avatar": "Url to the user image",
 *                  "link": "User link"
 *                  }
 *          }
 *     }
 */
// ------------------------------------------------------------------------------------------
// Account.
// ------------------------------------------------------------------------------------------
/**
 * @apiDefine UserProfile
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *      "profile": {
 *          "full_name": "Vasya Pupkin",
 *          "email": "nikolay.savchuk@jelvix.com",
 *          "phone_number": "12345456708",
 *          "street_address": "Fake street",
 *          "city": "Fake City",
 *          "state": "Fake State",
 *          "postal_code": 12345,
 *          "birth_date": "2017-01-31T00:00:00+0200",
 *          "linkedin_url": "http://fmp.app/app_dev.php/api/v1/profile/save"
 *     },
 *      "career_preferences": {
 *          "industry": "Test",
 *          "job_titles": [
 *              "test1",
 *              "test2",
 *              "test3"
 *          ],
 *          "job_types": [
 *              "test3",
 *              "test4"
 *          ],
 *          "relocation_value": true,
 *          "relocation_type": "null",
 *          "relocation_location": [
 *              "dfasdasd",
 *              "dasdasdasd"
 *          ],
 *          "experience": "djaksdjask",
 *          "education": "djaksdjask",
 *          "desire_salary_value": 10000,
 *          "desire_salary_type": "hours"
 *     },
 *      "education": [],
 *      "work_experience": [
 *          {
 *              "id": 1,
 *              "employer": "San Francisco1",
 *              "job_title": "dsads1",
 *              "start_date": "2015-05",
 *              "end_date": "2016-05",
 *              "salary_earned": "1212",
 *              "reason_for_leaving": "dasdas fasdf dsfsd fs dfds"
 *          }
 *      ],
 *      "reference": [],
 *      "documents": [
 *          {
 *              "id": 1,
 *              "name": "tech.docx",
 *              "type": "Resume",
 *              "added_by": "Test Admin",
 *              "date_added": "2017-06-21T14:47:14+0300",
 *              "document": "http://fmp.app/app_dev.php/api/v1/download/document/1"
 *          },
 *          {
 *              "id": 2,
 *              "name": "tech.docx",
 *              "type": "Resume",
 *              "added_by": "Vasya Pupkin",
 *              "date_added": "2017-06-21T14:47:14+0300",
 *              "document": "http://fmp.app/app_dev.php/api/v1/download/document/2"
 *          }
 *      ],
 *      "templates": [
 *          {
 *              "id": 1,
 *              "name": "tech.docx",
 *              "template": "http://fmp.app/app_dev.php/api/v1/download/template/1"
 *          }
 *      ],
 *      "questions": {
 *          "work_authorization": "asdasdasd",
 *          "gender": "dasdasd",
 *          "veteran_status": "dscfsdfs",
 *          "disability_status": "dsfdfs",
 *          "race_ethnicity": "sdffds"
 *      },
 *      "progress": {
 *          "value": 0,
 *          "values": {
 *              "profile": "Update Profile",
 *              "career_preferences": "Update Career Preferences",
 *              "work_experience_education": "Update Education & Work Experience",
 *              "reference": "Update Reference",
 *              "documents": "Update Documents",
 *              "questions": "Update Questions"
 *          }
 *      }
 *  }
 */
/**
 * @api {get} /api/v1/profile Get profile
 * @apiVersion 1.0.0
 * @apiPermission token
 * @apiName Account
 * @apiDescription Profile.
 *
 * @apiGroup Profile
 *
 * @apiSuccess {Object} profile User profile.
 *
 * @apiUse UserProfile
 */
/**
 * @api {put} /api/v1/profile/:section Update
 * @apiVersion 1.0.0
 * @apiPermission token
 * @apiName Update account
 * @apiDescription Update profile.
 *
 * @apiGroup Profile
 *
 * @apiParam {String} section profile|career_preferences|education|work_experience|reference|document|questions
 * @apiSuccess {Object} profile User profile.
 *
 * @apiUse UserProfile
 */
/**
 * @api {delete} /api/v1/profile/:section/:id Delete
 * @apiVersion 1.0.0
 * @apiPermission token
 * @apiName Delete section
 * @apiDescription Delete section in the profile.
 *
 * @apiGroup Profile
 *
 * @apiParam {String} section profile|career_preferences|education|work_experience|reference|document|questions
 * @apiParam {Integer} id section unique id
 * @apiSuccess {Object} profile User profile.
 *
 * @apiUse UserProfile
 */
/**
 * @api {get} /api/v1/congratulation Congratulation
 * @apiVersion 1.0.0
 * @apiPermission token
 * @apiName Congratulation
 * @apiDescription Congratulation page.
 *
 * @apiGroup Checkout
 *
 * @apiSuccess {Array} services Services collection.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *      [
 *          {
 *              "id": 1,
 *              "name": "Career Finder",
 *              "price_senior": 1,
 *              "price_executive": null,
 *              "icon": "card-career-finder",
 *              "link": "career-finder"
 *          },
 *      ]
 */
/**
 * @api {post} /api/v1/checkout Checkout
 * @apiVersion 1.0.0
 * @apiPermission token
 * @apiName Checkout
 * @apiDescription Checkout page.
 *
 * @apiGroup Checkout
 *
 * @apiSuccess {Array} services Services collection.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *      [
 *          {
 *              "id": 1,
 *              "name": "Career Finder",
 *              "price_senior": 1,
 *              "price_executive": null,
 *              "icon": "card-career-finder",
 *              "link": "career-finder"
 *          },
 *      ]
 */
