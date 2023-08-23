# Onboarding

Welcome to the Glance Onboarding Repository! This repository is designed to help newcomers to the project understand the architecture, technologies, and practices used within our project ecosystem. Through a series of challenges and exercises, you will gain hands-on experience that will empower you to contribute effectively to the project.

## Introduction

Congratulations on joining our team! As you start your journey with us, it would be nice for you to have an understanding of our project's architecture and development practices. This repository aims to provide you with a guided path to understanding these aspects while allowing you to actively participate and learn.

## Getting Started

Before you begin, make sure you have the following prerequisites set up:

- **Git**: You need to have Git installed on your machine. If you don't, please follow [these instructions](https://github.com/git-guides/install-git).
- **Docker**: You also need to have Docker installed. If you don't, follow [these instructions](https://docs.docker.com/get-docker/).
- **VS Code**: You will need VS Code to write your software. If you don't have it, you can download it [here](https://code.visualstudio.com/download).
- **DataGrip**: You will need an IDE (Integrated Development Environment) to query, create and manage your local database. You can apply for a DataGrip student license [here](https://www.jetbrains.com/community/education/#students).
- **Insomnia**: You will need Insomnia to test your API endpoints. You can download it [here](https://insomnia.rest/download).

## Setting up the environment
1. Clone the repository.

2. We use `.env` files to handle configuration values and sensitive information. To be able to use the repository, copy the two existing `.env.example` files into `.env` files within the same directory. You should then have the `onboarding/.env` and `onboarding/backend/api/.env` files. The latter one does not require any modification, however in the former one you should set the credentials for the database that is going to be created in your machine. For example, you could set the credentials as follows:

```bash
DB_NAME=onboarding
DB_USERNAME=newcomer_example_username
DB_PASSWORD=newcomer_example_password
DB_ROOT_PASSWORD=newcomer_example_root_password
DB_HOST=database
```

3. Run `docker-compose up`. Both the backend and database containers will be constructed. 

4. If everything goes fine in the last step, you should be able to connect to the created database using your IDE. Open your IDE When asked for username and password, enter the values you set in the `.env` file. Use 'localhost' as the host and 'jdbc:mysql://localhost:3306' as the URL. Now, you should be able to access the tables that were created and check the existing data.

5. To check that everything is fine software-wise, open Insomnia and do a GET HTTP request for the following URL: 'localhost:8181/api/hello-world'. You should be able to see a hello world message.

## Challenge

Now that everything is prepared, you are ready to begin the challenges!

If you go to `onboarding/backend/api/src`, you will see the Collaboration folder. This folder contais three other folders: Application, Domain and Infrastructure. Inside Application you will find all the use cases that you should create, one for each CRUD operation. By the end of your work, you should have endpoints to create, read, update and delete a member and an experiment. Use [RFC-010]{https://readthedocs.web.cern.ch/display/FPS/RFC+010+-+Back-end+architecture+proposal} as a guide regarding the architecture!

Good luck! Don't forget that you can always contact more senior members of the Glance team if you feel lost!