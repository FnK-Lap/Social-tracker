<?php

namespace SocialTracker\Bundle\ApplicationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationControllerTest extends WebTestCase
{
    public function testLoginSuccess()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('_submit')->form();

        $this->assertTrue($form->has('_username'));
        $this->assertTrue($form->has('_password'));

        $client->submit($form, array(
            '_username' => 'John',
            '_password' => 'qq'
        ));

        $crawler = $client->followRedirect();

        $this->assertEquals('John', $client->getContainer()->get('security.context')->getToken()->getUser()->getUsername());
        $this->assertEquals(1, $crawler->filter('div.quick-publish')->count());
    }

    public function testLoginFail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('_submit')->form();

        $this->assertTrue($form->has('_username'));
        $this->assertTrue($form->has('_password'));

        $client->submit($form, array(
            '_username' => 'BadUsername',
            '_password' => 'qq'
        ));

        $crawler = $client->followRedirect();

        $this->assertEquals('anon.', $client->getContainer()->get('security.context')->getToken()->getUser());
        $this->assertEquals(1, $crawler->filter('#login-box')->count());
    }

    public function testLoggedFail()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'BadUser',
            'PHP_AUTH_PW'   => 'BadPass'
        ));

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testLoggedSuccess()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'John',
            'PHP_AUTH_PW'   => 'qq'
        ));

        $crawler = $client->request('GET', '/');

        $this->assertFalse($client->getResponse()->isRedirect());
    }

    public function testDisableSocial()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'John',
            'PHP_AUTH_PW'   => 'qq'
        ));

        $crawler = $client->request('GET', '/social/facebook/disable');
        $user = $client->getContainer()->get('security.context')->getToken()->getUser();

        $this->assertEquals(null, $user->getFacebookAccessToken());
        $this->assertEquals(null, $user->getFacebookUsername());
    }
}
