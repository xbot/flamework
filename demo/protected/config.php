<?php
return array(
    'name' => 'Demo App',
    'debug' => true,
    'exceptionHandler' => function($exception) {
        var_dump($exception);
        return true;
    },
    'namespaces' => array('org\\x3f\\flamedemo' => '/srv/http/flamework/demo/protected'),
    'filters' => array(
        'org\\x3f\\flamedemo\\filter\\GlobalFilterA',
        'org\\x3f\\flamedemo\\filter\\GlobalFilterB',
    ),
    'db' => array(
        'connection_string' => 'sqlite:/srv/http/flamework/demo/protected/demo.db',
    ),
);
?>
