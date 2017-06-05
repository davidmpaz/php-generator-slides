# To Use slides locally
```
$ npm install
$ npm run serve
```

# To run examples
```
docker-compose up -d --build --force-recreate
```
Adjust the scripts global variables for database host. Something like this 
would do the job:
```
// instead:
$db = new PDO('mysql:host=127.0.0.1', $user, $pass);
// use this
$db = new PDO('mysql:host=slides-mysql', $user, $pass);
```
After that:
```
$ docker exec -it slides-php5 php /tmp/db-init.php
$ docker exec -it slides-php5 php /tmp/db.php // or any other example
$ docker exec -it slides-php5 php /tmp/db-clean.php // not needed actually on containers
```
## To run & profile
To run php with profiling tools enabled, pass options with `-d` flag

```
$ docker exec -it slides-php5 php -d xdebug.profiler_enable=1 -dxdebug.trace_format=1 -dxdebug.auto_trace=1 /tmp/db.php
```
After this there should be files ready to analyse:
* trace.*
* cachegrind.out.*

Use script: `tracefile-analyser.php` to extract information, something like this:
```
$ php src/snippets/tracefile-analyser.php src/snippets/trace.1552429182.xt memory-own 20
```

