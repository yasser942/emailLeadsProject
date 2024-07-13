# Email Leads Project

## About Email Leads Project

This project is designed to manage and send email leads efficiently using Laravel. It leverages Laravel's robust features to handle email jobs and ensure reliable delivery.

## Installation

Follow these steps to set up the project:

1.  **Clone the repository:**

```bash

git  clone  https://github.com/yasser942/emailLeadsProject.git

cd  emailLeadsProject

```

2.  **Install dependencies:**

```bash

composer  install

npm  install

```

3.  **Copy the `.env` file and configure your environment variables:**

```bash

cp  .env.example  .env

```

Update the `.env` file with your database and mail server configurations.

4.  **Generate an application key:**

```bash

php  artisan  key:generate

```

5.  **Run database migrations:**

```bash

php  artisan  migrate

```

6.  **Seed the database:**

```bash

php  artisan  db:seed

```

After seeding the database, you can log in with the admin account:

-   **Email:** admin@gmail.com

-   **Password:** 987654321

7.  **Build front-end assets:**

```bash

npm  run  dev

```

## Running Email Jobs

To send emails, you need to set up and run the Laravel queue worker. Follow these steps:

1.  **Configure the queue driver:**

In your `.env` file, set the `QUEUE_CONNECTION` to your desired driver (e.g., `database`, `redis`).

2.  **Run the queue worker:**

```bash

php  artisan  queue:work

```

This command will start processing jobs in the queue. Ensure your queue driver is properly configured and running.
