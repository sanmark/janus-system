# Sanmark Janus

*Sanmark Janus* is a *User Authentication Management System*. It provides the following critical features:

 - Store user accounts.
 - Store metadata about users.
 - Create authentication sessions.
 - Create forgotten user secret (password) reset requests.

Following supporting features are a part of *Sanmark Janus* as well:

 - CRUD `User`s.
 - CRUD `Metakey`s (Eg: "name", "email", and "birt-place".).
 - CRUD `Meta`s (Eg: "Jon Snow", "king@north.westeros", and "Tower of Joy".). `Meta`s encapsulate `User`s' values for `Metakey`s.).
 - CRUD `AuthSession`s. When a `User` logs in, it creates a `AuthSession`, which has a unique `token`. Client applicatins can use this `token` to identify the logged in user.
 - Logging in with Google and Facebook. You will have to provide required credentials for accessing their APIs.

## Installing

The recommended way of installing *Sanmark Janus* is through the official Docker image, which can be found in [https://hub.docker.com/r/sanmark/janus/](https://hub.docker.com/r/sanmark/janus/).

You will need a separate MySQL server hosted somewhere, and credentials to it. Use [sanmark/janus-db](https://github.com/sanmark/janus-db) to migrate the DB.

### Required Environment Variables

You will have to pass the following environment variables into the Docker Container you create.

 - `APP_NAME`
 - `APP_ENV` (`local` or `production`.)
 - `APP_KEY` ([Laravel](https://github.com/laravel/laravel) compatible app key.)
 - `APP_DEBUG` (`true` or `false`.)
 - `DB_CONNECTION` (Set to `mysql`.)
 - `DB_HOST`
 - `DB_PORT`
 - `DB_DATABASE`
 - `DB_USERNAME`
 - `DB_PASSWORD`
 - `SYSTEM_AUTH_SESSION_ACTIVE_MINUTES` (Number of minutes to keep the `AuthSessions` valid.)
 - `THIRD_PARTY_GOOGLE_API_CLIENT_ID` (Optional.)
 - `THIRD_PARTY_GUZZLEHTTP_VERIFY` (Set to `false` if hosted in a HTTP server; `true` if HTTPS.)
 - `THIRD_PARTY_FACEBOOK_APP_ID` (Optional.)
 - `THIRD_PARTY_FACEBOOK_APP_SECRET` (Optional.)

## API Documentation

Once you have a working installation of *Sanmark Janus*, visit the path `/api/doc` on the server to get a fully interactive API Documentation.
