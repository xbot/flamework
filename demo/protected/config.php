<?php
return array(
    'name' => 'Demo App',
    'debug' => true,
    'logLevel' => 'log',
    'exceptionHandler' => function($exception) {
        var_dump($exception);
        return true;
    },
    'namespaces' => array('org\\x3f\\flamedemo' => '/srv/http/flamework/demo/protected'),
    'filters' => array(
        'org\\x3f\\flamedemo\\filter\\GlobalFilterA',
        'org\\x3f\\flamedemo\\filter\\GlobalFilterB',
    ),
);
?>
