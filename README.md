# Predicty App

This tool in an incomplete proof of concept of a marketing tool, that aggregates campaign results data from multiple ad networks.
It's main purpose is to provide a simple way to compare campaign results from different sources.

At its current state, the functionality is limited, and the codebase is not yet production ready. 
Some of the features were not implemented, and some of the existing features are not fully tested and/or optimized.

## Development

To set up the development environment, you need to have Docker and Docker Compose installed on your machine.
Makefiles were tested on Ubuntu, but should work on any Linux distribution. 

On Windows WSL2 is required, but the setup process is not tested.
It was reported that some of the makefile commands are not working there.

To run the setup:

```shell
make build
make fixtures
```

After this, you can access the app at https://localhost.
For some time, you may see an HTTP 502 error - this is because the app is still being built.

GraphQL endpoint is available at https://localhost/graphql.

You can log in directly from the GraphQL playground, using the following query:

```graphql
mutation {
  login(username:"john.doe@example.com", passcode:"111111") {
    email
  }
}
```

Passcode `111111` is used for all users in dev environment.

## Customizing development environment

You can create your own `.env` file with custom configuration. See `.env.dist` fot a list of available settings.

## Available services

| Name     | Address                 |
|----------|-------------------------|
| Mailhog  | http://localhost:8025/# |
| Caddy    | https://localhost       |
| Postgres | localhost:5432          |