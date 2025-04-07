<?php

while (true) {
  echo "Running schedule:run at " . date('Y-m-d H:i:s') . PHP_EOL;
  exec('php backend/artisan schedule:run');
  sleep(60);
}
