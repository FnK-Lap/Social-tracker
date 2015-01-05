<?php

namespace SocialTracker\Bundle\ApplicationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InstagramControllerTest extends WebTestCase
{
    public function testC()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'FnK',
            'PHP_AUTH_PW'   => 'qq'
        ));

        $crawler = $client->request('GET', '/instagram');


    }
}