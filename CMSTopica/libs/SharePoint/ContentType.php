<?php

namespace Office365\PHP\Client\SharePoint;


use Office365\PHP\Client\Runtime\ClientActionDeleteEntity;
use Office365\PHP\Client\Runtime\ClientObject;

class ContentType extends ClientObject
{

    /**
     * Deletes Content Type resource
     */
    public function deleteObject()
    {
        $qry = new ClientActionDeleteEntity($this);
        $this->getContext()->addQuery($qry);
    }



    function setProperty($name, $value, $persistChanges = true)
    {
        parent::setProperty($name, $value, $persistChanges);
        if ($name == "StringId") {
            $this->setResourceUrl($this->resourcePath->toUrl() . "('{$value}')");
        }
    }


}