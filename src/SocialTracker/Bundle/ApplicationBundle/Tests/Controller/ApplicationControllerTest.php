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

    public function testAddSocial()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'John',
            'PHP_AUTH_PW'   => 'qq'
        ));

        $crawler = $client->request('POST', '/social/add', array('social' => 'facebook'));

        $socials = $client->getContainer()->get('security.context')->getToken()->getUser()->getSocial();

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue(in_array('facebook', $socials));
    }

    public function testAddSocialFail()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'John',
            'PHP_AUTH_PW'   => 'qq'
        ));

        $crawler = $client->request('POST', '/social/add', array('social' => 'BadSocial'));

        $socials = $client->getContainer()->get('security.context')->getToken()->getUser()->getSocial();

        $this->assertFalse($client->getResponse()->isSuccessful());
    }

    /**
     * @depends testAddSocial
     */
    public function testRemoveSocial()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'John',
            'PHP_AUTH_PW'   => 'qq'
        ));

        $crawler = $client->request('POST', '/social/remove', array('social' => 'facebook'));

        $socials = $client->getContainer()->get('security.context')->getToken()->getUser()->getSocial();

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue(!in_array('facebook', $socials));
    }

    /**
     * @depends testRemoveSocial
     */
    public function testRemoveSocialFail()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'John',
            'PHP_AUTH_PW'   => 'qq'
        ));

        $crawler = $client->request('POST', '/social/remove', array('social' => 'facebook'));

        $socials = $client->getContainer()->get('security.context')->getToken()->getUser()->getSocial();

        $this->assertFalse($client->getResponse()->isSuccessful());
    }
}
