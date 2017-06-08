Simple application which detect age and gender

Installation
========
```bash
docker-compose up -d
docker exec -it deep-detect-php php composer install
docker exec -it deep-detect-php php console.php app:dd:init
```
Run
========
```bash
docker exec -it deep-detect-php php console.php app:dd:run *url_to_you_photo* 
```

Other commands
========
```bash
docker exec -it deep-detect-php php console.php app:dd:status 
```
Models
======
Models are downloaded from:
[deepdetect.com](https://deepdetect.com/applications/model/#lic)