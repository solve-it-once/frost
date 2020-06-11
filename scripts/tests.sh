#!/bin/bash

# Running tests on our local
# Before running this script make sure docker is up and running, and the site
# is doing its site thing.

# Get ready to test
chromedriver --port=4444 &

# Lint our code
# Check against coder
# Requires drupal/coder and phpcodesniffer to be global installed.
phpcs --standard=Drupal,DrupalPractice --extensions=php,module,inc,install,test,profile,theme,css,info,txt,md ../web/modules/custom/ > ../tests/output/coder.txt

# Run behat tests
../vendor/bin/behat -c ../tests/behat-docker.yml > ../tests/output/behat.txt

# Run our phpunit tests
../vendor/bin/phpunit -v --testsuite=unit -c `pwd`../tests/phpunit.xml > ../tests/output/phpunit-unit.txt
../vendor/bin/phpunit -v --testsuite=kernel -c `pwd`../tests/phpunit.xml > ../tests/output/phpunit-kernel.txt
../vendor/bin/phpunit -v --testsuite=functional -c `pwd`../tests/phpunit.xml > ../tests/output/phpunit-functional.txt
../vendor/bin/phpunit -v --testsuite=functional-javascript -c `pwd`../tests/phpunit.xml > ../tests/output/phpunit-functional-javascript.txt

# Additional checks
# Axe-crawler requires a tld, so set localhost.tld to 127.0.0.1 in your /etc/hosts
# axe-crawler --configFile ../tests/.axe-crawler.json "localhost.tld:8000/"

# kill chromedriver
killall -9 chromedriver
