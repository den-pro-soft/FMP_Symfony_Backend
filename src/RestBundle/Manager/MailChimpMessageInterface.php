<?php

namespace RestBundle\Manager;

interface MailChimpMessageInterface
{
    public function getBody();

    public function getSubject();

    public function getTitle();

    public function getReplyTo();

    public function getFromName();
}