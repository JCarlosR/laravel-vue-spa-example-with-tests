# TopTal Technical Screening Project 

## Test project description

**Write an application for time management**.

User must be able to create an account and log in.

User can add / edit / delete a row, describing what they have worked on, what date, and for how long.

User can add a setting (Preferred working hours per day).

If on a particular date a user has worked under the PreferredWorkingHourPerDay, these rows are red, otherwise green.

Implement at least three roles with different permission levels:

- a regular user would only be able to CRUD on their owned records,
- a user manager would be able to CRUD users,
- and an admin would be able to CRUD all records and users.

Filter entries by date from-to.

Export the filtered times to a sheet in HTML:
  * Date: 2020.06.25
  * Total time: 9h
  * Notes:
    * Note1
    * Note2
    * Note3

**REST API**.

Make it possible to perform all user actions via the API, including authentication.

In any case, you should be able to explain how a REST API works 
and demonstrate that by creating functional tests that use the REST Layer directly.

Please be prepared to use REST clients like Postman, cURL, etc. for this purpose.

It must be a single-page application.

All actions need to be done client-side using AJAX (refreshing the page is not acceptable).

Functional UI/UX design is needed.
You are not required to create a unique design, however, 
do follow best practices to make the project as functional as possible.

New users need to verify their account by email.
Users should not be able to log in until this verification is complete.

Write unit and e2e tests.

## Features

- Laravel 7
- Vue + VueRouter + Vuex + VueI18n
- Pages with dynamic import and layouts
- Login, register, email verification and password reset
- Authentication with JWT
- Bootstrap 4 + Font Awesome 5

## Installation

- Clone this repository
- Edit `.env` and set your database and other credentials
- Run `php artisan key:generate` and `php artisan jwt:secret`
- `php artisan migrate`
- `npm install`

## Usage

#### Development

```bash
# build and watch
npm run watch
```

#### Production

```bash
npm run production
```

## License

This project is open-sourced software licensed under the MIT license.