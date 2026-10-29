+++
title = "Enums Can Be Enforced at the Database Level in PostgreSQL"
summary = "A PostgreSQL column can be restricted to a predefined set of values using an enumerated type (enum)."
date = "2026-10-29"
categories = ["Databases", "PostgreSQL"]
ShowToc = false
TocOpen = false
featured = false
+++

PostgreSQL databases support having [enumerated type](https://en.wikipedia.org/wiki/Enumerated_type) (enum) columns so that the column only accepts one of a predefined set of values. I've worked with [ORMs](https://en.wikipedia.org/wiki/Object%E2%80%93relational_mapping) like [Active Record](https://guides.rubyonrails.org/active_record_basics.html) from [Ruby on Rails](https://rubyonrails.org/). Using Active Record, I could work with database columns that only allowed a certain set of values. I always thought that was only possible due to the ORM layer abstracting away these things, but that's not the case. Enums can be enforced at the database level (at least with PostgreSQL).

I'll demonstrate on a PostgreSQL container with a simple table. I'll be using Podman. You can also use Docker to follow along, just replace `podman` with `docker` in the commands.

Make sure Podman/Docker is running, then run the following to get a running PostgreSQL container locally:

```shell
podman run -d --name enum-demo -e POSTGRES_PASSWORD=postgres docker.io/library/postgres:18
```

Then exec into the running container:

```console
$ podman exec -it enum-demo psql -U postgres
psql (18.6 (Debian 18.6-1.pgdg13+2))
Type "help" for help.

postgres=#
```

Now we can start setting up the table to try out enums. First, we'll create the enum type itself. It's going to be a type just like `INTEGER` or `TEXT` are types in a database. I'm thinking of CI/CD pipelines to use as an example, so the enum will be called `pipeline_status` and it'll only accept the values `pending`, `running`, `succeeded`, and `failed`. Run this SQL query in the container:

```sql
CREATE TYPE pipeline_status AS ENUM ('pending', 'running', 'succeeded', 'failed');
```

PostgreSQL should return:

```text
CREATE TYPE
```

Now let's create a simple table that makes use of the enum. The table will be called `pipeline_runs` and it'll have two columns: `branch` of type `TEXT`, and `status` which uses our enum type `pipeline_status`. Run this in the container:

```sql
CREATE TABLE pipeline_runs (
  branch TEXT,
  status pipeline_status
);
```

PostgreSQL should return:

```text
CREATE TABLE
```

Now let's try inserting a row. This SQL query inserts a row where `branch` is `main` and `status` is one of the four predefined enum values (`running`).

```sql
INSERT INTO pipeline_runs (branch, status) VALUES ('main', 'running');
```

PostgreSQL replies:

```text
INSERT 0 1
```

We can check for the newly created row:

```sql
SELECT * FROM pipeline_runs;
```

You should see:

```text
 branch | status
--------+---------
 main   | running
(1 row)
```

Now let's try inserting a row with an enum value that is not a part of the values we defined. We'll try inserting `cancelled` for the `status` column. We should get an error.

```sql
INSERT INTO pipeline_runs (branch, status) VALUES ('main', 'cancelled');
```

PostgreSQL should say:

```text
ERROR:  invalid input value for enum pipeline_status: "cancelled"
LINE 1: ...TO pipeline_runs (branch, status) VALUES ('main', 'cancelled...
                                                             ^
```

The `cancelled` value isn't one of `('pending', 'running', 'succeeded', 'failed')`, so PostgreSQL gives us an error. PostgreSQL itself is enforcing enums! No ORMs here.

Note that the values in the enum are case sensitive. So inserting `Pending` instead of `pending` will fail:

```sql
INSERT INTO pipeline_runs (branch, status) VALUES ('main', 'Pending');
```

PostgreSQL returns the error:

```text
ERROR:  invalid input value for enum pipeline_status: "Pending"
LINE 1: ...TO pipeline_runs (branch, status) VALUES ('main', 'Pending')...
                                                             ^
```

Another caveat is that inserting `NULL` as the value for `status` is still allowed unless the column is also created with `NOT NULL`.

Let's say we actually want `cancelled` to be a part of the enum. We can modify the enum like so:

```sql
ALTER TYPE pipeline_status ADD VALUE 'cancelled';
```

PostgreSQL responds with:

```text
ALTER TYPE
```

Now that `cancelled` is a valid value, we can try inserting again and we won't get an error:

```sql
INSERT INTO pipeline_runs (branch, status) VALUES ('main', 'cancelled');
```

PostgreSQL responds with:

```text
INSERT 0 1
```

We should have two rows total now. We can double check:

```sql
SELECT * FROM pipeline_runs;
```

You should see:

```text
 branch |  status
--------+-----------
 main   | running
 main   | cancelled
(2 rows)
```

We successfully added a value to the `pipeline_status` enum and used it in a new row.

One final note: there isn't a clean way to remove a value from an enum. You can add values, but you would need to rebuild the enum if you want to remove any values. Plan ahead!

Now that the demo is over, exit the PostgreSQL prompt by running:

```text
\q
```

Then clean up the container and, optionally, the image:

```console
$ podman rm -f enum-demo
enum-demo
```

```console
$ podman rmi docker.io/library/postgres:18
Untagged: docker.io/library/postgres:18
Deleted: 07ee628d2e71f8956c85fc5466906a6d4038cf00f6db07f067d810ef8fec8161
```

We created an enum type with predefined values, tried inserting both valid and invalid values, and it was all enforced at the database level with no abstractions from an ORM!
