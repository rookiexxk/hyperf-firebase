# Firebase for Hyperf

A Hyperf package for the [Firebase PHP Admin SDK](https://github.com/kreait/firebase-php).

## Installation

```shell
composer require rookiexxk/hyperf-firebase
```

## Publish

```shell
php bin/hyperf.php vendor:publish fcorz/hyperf-firebase
```

## Configuration

### Credentials with JSON files

```env
FIREBASE_CREDENTIALS=config/certificates/service-account-file.json
```

## Usage

### Facades

```php
use Fcorz\Hyperf\Firebase\Facades\Firebase;

// Return an instance of the Messaging component for the default Firebase project
$defaultMessaging = Firebase::messaging();

// Return an instance of the Auth component for a specific Firebase project
$appMessaging = Firebase::project('app')->messaging();

$anotherAppMessaging = Firebase::project('another-app')->messaging();

// send message
$message = [
    'token' => $deviceToken,
    'notification' => [/* Notification data as array */], // optional
    'data' => [/* data array */], // optional
];

Firebase::messaging()->send($message);
```

### Dependency Injection

```php
use Fcorz\Hyperf\Firebase\ApplicationProxy;

class yourProjectFirebase extends ApplicationProxy
{
    protected string $name = 'project_name';

}

// send message
class yourClass
{
    public function __construct(yourProjectFirebase $firebase)
    {
        $firebase->messaging()->send($message);
    }
}
```

## The future of the Firebase Admin PHP SDK

Please read about the future of the Firebase Admin PHP SDK on the
[SDK's GitHub Repository](https://github.com/kreait/firebase-php).
