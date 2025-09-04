
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate

npm install
npm run dev

### Code sniffer
vendor/bin/phpcs --standard=phpcs.xml -v app/

### Mess Detector
vendor/bin/phpmd app/ text phpmd.xml