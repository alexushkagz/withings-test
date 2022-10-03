This is a test task which has a goal to show an implementation of a simple OAuth2-based authentification to an external API of [Withings](https://developer.withings.com/) in order to retireve some data.

You may run this project using Docker (port 5000):
```
docker run -d -p 5000:80 --name withings-test-php-app -v "$PWD/src":/var/www/html php:apache
```

## Key Moments

- It's a simple example with native PHP and native JS, with no frameworks
- One of the reason I did not use ReactJS is that I made all development inside a container and I didn't want to complicate the structure with docker compose and multiple containers 
- It's not a ready to use production solution, it's just a test application
- For the measurements.php test you may see the full response in the console.log()

## Possible improvements

- Authorization class and API Class for fetching user data should be 2 different classes if an application is larger
- Authorization and API class may be static, cause there is no need of initialization of multiple instances of these classes
- ClientId and Secret should normally be saved separately (e.g. in DB) and injected into Authorization class constructor for better security
- Tokens should also be saved in a DB, I used $_SESSION as a storage for this test
- There is no error handling in case if access_token is outdated. Also there is no option for token refreshing.
- It would be a good idea to make it a SPA (e.g. with React) and make all requests in AJAX
- There is a lot of hard coding (url, port). This should be either a $_SERVER or environment variables.