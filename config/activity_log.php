<?php

return [
    /**
     * set default related user
     */
    'table_name' => 'activity_log',

    /**
     * set default table for record the log
     */
    'user_model' => App\Models\User::class,

    /**
     * if you want run log with queue change value to true
     */
    'queueable' => false,
];
