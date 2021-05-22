#!/bin/sh

set -eo pipefail

for i in $(find tests -type f -name "*Test.php" | xargs -I {} basename {} .php)
do
    echo $i
    ./vendor/bin/phpunit --configuration ./phpunit.xml --filter $i
done

#  ./vendor/bin/phpunit --configuration ./phpunit.xml --filter FilterUrlTest
#  ./vendor/bin/phpunit --configuration ./phpunit.xml --filter FiltersTest
