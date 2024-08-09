<?php

return [

    /*
    |---------------------------------------------------------------------------
    | AWS SNS Configuration
    |---------------------------------------------------------------------------
    |
    | This section defines the configuration settings for handling AWS SNS
    | notifications in your Laravel application. You can set the route for
    | receiving SNS notifications and specify the list of expected SNS topics.
    | These settings can be customized via environment variables.
    |
    */

    'sns' => [

        /*
        |-----------------------------------------------------------------------
        | SNS Route
        |-----------------------------------------------------------------------
        |
        | This option defines the route that will handle incoming AWS SNS
        | notifications. The default route is '/hooks/sns', but you can
        | customize it by setting the AWS_SNS_ROUTE environment variable.
        |
        */
        'route'  => env('AWS_SNS_ROUTE', '/hooks/sns'),

        /*
        |-----------------------------------------------------------------------
        | SNS Topics
        |-----------------------------------------------------------------------
        |
        | This option defines the list of expected AWS SNS topics that your
        | application should accept notifications from. The topics should be
        | specified as a comma-separated list in the AWS_SNS_TOPICS environment
        | variable. This helps in ensuring that only notifications from the
        | specified topics are processed.
        |
        */
        'topics' => explode(',', env('AWS_SNS_TOPICS', ''))

    ]

];
