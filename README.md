# Whoops for Shopware
cool php errors for shopware

1. Install the plugin
2. Disable the standard shopware error handler in config.php
```php
	'front' => array(
		'noErrorHandler' => true,
		'throwExceptions' => true,
	),
```

![Whoops! in Shopware](http://i.imgur.com/1iCjJCY.png)
