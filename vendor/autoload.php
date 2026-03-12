<?php

fwrite(STDERR, "This repository ships without full Composer dependencies in this environment.\n");
fwrite(STDERR, "Run 'composer install --no-dev --optimize-autoloader' on an online Linux machine and copy the complete vendor/ directory here.\n");
exit(1);
