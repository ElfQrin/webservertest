# webservertest

Web Server Test is a simple yet very configurable and flexible PHP page to check if your webserver is up and running. It shows some basic information about your server's system and checks if PHP and the Database -- MySQL or PostgreSQL -- are running and in case returns their version (if PHP is not working of course no test will be performed). It informs you if images are being shown. It verifies if the system date is correct comparing it with the client's date (which may be incorrect as well, but still it means there's something to fix), it also checks for low disk space and then attempts to write, read, and delete a file on disk.

All warning messages are shown in red so that you can instantly realize there's something that's not working as it should.

You can run it as a test page after the first installation of your webserver or use it as part of your daily routine. It shows nicely on desktop and mobile browsers. It is also scriptable (using wget or lynx) and can generate logs, so that you can include it in your crontab.

http://labs.geody.com/webservertest/
