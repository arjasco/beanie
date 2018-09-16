# Beanie
A beanstalkd library for PHP.

---

## Installation

We reccomend using composer to install Beanie.
```
composer require arjasco/beanie
```
Alternatively you can download a zip / clone a release if you want to use this library without composer.

## Usage

The best way to demostrate how to use Beanie is to get straight into code examples, there are other commands not in this example and will be documented underneath.
```php
<?php

use Arjasco\Beanie\Factory as BeanieFactory;
use Arjasco\Beanie\Connection;
use Arjasco\Beanie\Job;

// Create a Beanie instance
$beanie = BeanieFactory::make('localhost', 11300);

// Create a job to put into a tube.
$job = (new Job)->setData(json_encode(['github_repo' => 'arjasco/beanie']));

// Or simply create a payload of data without the Job class
// the put method expects an instance of Job, a string
// or an object implementing __toString()
$job = json_encode(['github_repo' => 'arjasco/beanie']);

// Returns instance of Job
$job = $beanie->put($job);

$priority = 10; // Very high priority. lower = higher priority
$delay = 10; // Delay for 10 seconds before the job can be consumed.
$ttr = 60; // 60 second time to run.

// Put a job into the 'github' tube
$job = $beanie->use('github')->put($job, $priority, $delay, $ttr);

// Reserve a job from the `github` tube.
$job = $beanie->use('github')->reserve();

// Reserve another job from the default queue.
$job2 = $beanie->reserve();

// Release the job back into the tube.
$beanie->release($job2);

// Do some work with the first job...
doSomeWork($job);

// Job is taking too long to complete and we are approaching our TTR,
// so lets touch the job to request more time.
$beanie->touch($job);

// and then delete from the tube once we are finished
$beanie->delete($job);
```
