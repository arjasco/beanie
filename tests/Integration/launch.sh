#!/bin/bash
BEANSTALKD_PID=$(pgrep beanstalkd)

# Kill beanstalkd if it is running already.
if [ ! -z $BEANSTALKD_PID ]
then
    sudo kill -9 $BEANSTALKD_PID
fi

# Start beanstalkd and send to the background.
sudo beanstalkd -l localhost -p 11300 > /dev/null 2>&1 &

# Wait for beanstalkd to bind to 11300 so our tests dont
# try to access it before it's ready to accept connections.
while true
    do nc -z localhost 11300 > /dev/null 2>&1 && break
done
