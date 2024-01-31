# Firebase for Hyperf

A Hyperf package for the [Firebase PHP Admin SDK](https://github.com/kreait/firebase-php).


## The future of the Firebase Admin PHP SDK

Please read about the future of the Firebase Admin PHP SDK on the
[SDK's GitHub Repository](https://github.com/kreait/firebase-php).

## Installation

```bash
composer require fcorz/hyperf-firebase
```

## Publish
```bash
# Hyperf
php bin/hyperf.php vendor:publish fcorz/hyperf-firebase
```

## Configuration
### Credentials with JSON files
```.env
FIREBASE_CREDENTIALS=config/certificates/service-account-file.json
```

## Usage

```php
use Fcorz\Hyperf\Firebase\Facades\FireBase;

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