# bahn-search
A cli tool to check the same bahn connection for every next weekend

### Installation
Copy the _.env.dist_ file to _.env_ and if you want change the PROGRAM_ID variable. Then run
```bash
composer install
```

### Usage
Just run this (e.g. in a cronjob). It will generate an output of the prices for each weekend for first and second class.
The run will take 5-10 minutes.
```php
bin/console search:connections "München Hbf" "Hamburg Hbf"
 ```