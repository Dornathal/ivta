<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 13.07.15
 * Time: 18:48
 */

namespace Control;


use Exception;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Slim\Slim;

class EntityCreator
{



    /**
     * EntityCreator constructor.
     */
    public function __construct(ActiveRecordInterface $cleanEntity)
    {
        $this->entity = $cleanEntity;
        $this->requirements = array();
    }

    public function setRequirement($field, $name, $length, $isrequired){
        $this->requirements[$field] = array(
                'name'=>$name,
                'required'=>$isrequired,
                'max_length'=>$length
        );
    }

    public function hasErrors($value, $options){
        if(!$value && $options['required']){
            return array(true, $options['name'].' is required!');
        }
        if($value and strlen($value) > $options['max_length']){
            return array(true, $options['name'].' must be less than '.$options['max_length'].' characters long!');
        }
        return array(false, null);
    }

    public function build(Slim $app){
        $errors = array();
        $req = $app->request();

        foreach($this->requirements as $param=>$options){
            $value = $req->post($param);
            list($flag,$error) = $this->hasErrors($value,$options);
            if($flag) array_push($errors, $error);
            $this->entity->setByName($param, $value);
        }
        if($errors){
            print_r($errors);
            $app->flash('errors',$errors);
        }
        else{
            try{
                $this->entity->save();
                return true;
            }catch (Exception $e){
                echo $e;
                $app->flash('message',$e);
            }
        }
        return false;
    }
}