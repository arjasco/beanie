# Sprout [![Build Status](https://travis-ci.org/arjasco/sprout.svg?branch=master)](https://travis-ci.org/arjasco/sprout)
A beanstalkd library for PHP.

---

## Installation

We reccomend using composer to install Sprout.
```
composer require arjasco/sprout
```
Alternatively you can download a zip / clone a release if you want to use this library without composer.

## Usage

The best way to demostrate how to use Sprout is to get straight into code examples, there are other commands not in this example which can be found in the Sprout class.
```php
<?php

use Arjasco\Sprout\Factory as SproutFactory;
use Arjasco\Sprout\Connection;
use Arjasco\Sprout\Job;

// Create a Sprout instance
$sprout = SproutFactory::make('localhost', 11300);

// Create a job to put into a tube.
$job = (new Job)->setData(json_encode(['github_repo' => 'arjasco/sprout']));

// Or simply create a payload of data without the Job class
// the put method expects an instance of Job, a string
// or an object implementing __toString()
$job = json_encode(['github_repo' => 'arjasco/sprout']);

// Returns instance of Job
$job = $sprout->put($job);

$priority = 10; // Very high priority. lower = higher priority
$delay = 10; // Delay for 10 seconds before the job can be consumed.
$ttr = 60; // 60 second time to run.

// Put a job into the 'github' tube
$job = $sprout->use('github')->put($job, $priority, $delay, $ttr);

// Reserve a job from the `github` tube.
$job = $sprout->use('github')->reserve();

// Reserve another job from the default queue.
$job2 = $sprout->reserve();

// Watch tubes.
$job3 = $sprout->watch(['user-emails', 'newsletters'])->reserve();

// Release the job back into the ready queue.
$sprout->release($job2);

// Do some work with the first job...
doSomeWork($job);

// Job is taking too long to complete and we are approaching our TTR,
// so lets touch the job to request more time.
$sprout->touch($job);

// and then delete from the tube once we are finished
$sprout->delete($job);

// Peek into the different queues
$nextJob = $sprout->peek()->job($jobId);
$nextReadyJob = $sprout->peek()->ready();
$nextDelayedJob = $sprout->peek()->delayed();
$nextBuriedJob = $sprout->peek()->buried();
```

## Contributing

There is always room for improvement.

Step 1: Run the test suite `vendor/bin/phpunit`

Step 2: Write your feature / fix / improvement, adding tests wherever possible (Unit and/or integration)

Step 3: Run the test suite again `vendor/bin/phpunit`

Step 4: Assuming you get all 'green', submit your PR.
