<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 13.07.15
 * Time: 11:15
 */

namespace View;

abstract class MessageTypes
{
    const Success = 'success';
    const Info = 'info';
    const Warning = 'warning';
    const Error = 'danger';
}

class Message
{



    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->setType(MessageTypes::Success);

    }

    public function setType($type){
        $this->type = $type;
    }

    public function render(\Slim\Slim $app){
        $app->view()->appendData(array('message', $message));
        $app->render('view_message.twig');
    }
}