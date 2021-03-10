# Native PHP CRUD API

**Requirements**
- Docker 3.1.0 (Windows 10)
- PHP 7.3
- Redis 6-alpine
- Postgres
- nginx alpine
- Composer
## Docker Compose
Docker Compose is already set in order to run ``docker-compose up --build``.

## How to use.

First run ``composer update``, next run ``docker-compose up --build`` you may see postgres credential inside docker compose file. Once you run you may access it in [localhost:8081](http://localhost:8081/) this api use [firebase JWT](https://github.com/firebase/php-jwt), first request ``user/store`` in order to have authentication token, and will regenerate once the data is change and you must add variable in header ``ActiveUser`` you may put any integer as long as it exist in database. Authentication token always validate in the middleware before reach the the controller. CORS are set into htaccess

**End Point**
- (*POST*) http://localhost:8081/user/store 
- (*GET*) http://localhost:8081/user/show
- (*DELETE*) http://localhost:8081/user/remove
- (*PATCH*) http://localhost:8081/user/update/:id

**Sample Payload**

*POST & PATCH*

	{
	  "item": "Juan Dela Cruzs",
	  "desc": "cartracks",
	  "qty": "pass1s2342"
	}
*DELETE*

    {
      "id": ""
    }

**Sample Response**

	{
	  "code": 200,
	  "message": "Data has been created",
	  "payload": {
	    "id": "",
	    "item": "",
	    "desc": "",
	    "qty": "",
	  },
	  "token": ""
	}

