<?php
/**
 * Testing For BrowserTest
 *
 * PHP version 5.5
 *
 * @category Test
 * @package  Erp
 * @author   Egon Firman <egon.firman@gmail.com>
 * @license  none <www.bvap.me>
 * @link     www.bvap.me
 */

use PHPUnit\Extensions\SeleniumTestCase;

/**
 * Browser Test
 *
 * @category Test
 * @package  Erp
 * @author   Egon Firman <egon.firman@gmail.com>
 * @license  none <www.bvap.me>
 * @link     bvap.me
 */
class LoginTest extends PHPUnit_Extensions_SeleniumTestCase
{
    /**
     * setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://localhost/erp/');
    }

    /**
     * tutorial test Title
     *
     * @return void
     */
    public function testTitle()
    {
        $this->open('http://localhost/erp');
        $this->assertTitle('Medika | Login');

        // check without username
        $this->clickAndWait('name=submit');
        $this->assertText(
            '//div[@class="pt5"]/p', 
            'The Identity field is required.'
        );

        // check without password
        $this->type('id=identity', 'egon');
        $this->clickAndWait('name=submit');
        $this->assertText(
            '//div[@class="pt5"]/p', 
            'The Password field is required.'
        );

        // check false password
        $this->type('id=identity', 'egon');
        $this->type('id=password', 'kucing');
        $this->clickAndWait('name=submit');
        $this->assertText(
            '//div[@class="pt5"]/p', 
            'Incorrect Login'
        );

        // check login true
        $this->type('id=identity', 'egon');
        $this->type('id=password', 'nakal23baik');
        $this->clickAndWait('name=submit');
        $this->pause(5000);
        $this->assertTitle(
            'Medika | Dashboard'
        );
    }

    /**
     * for login and logut
     *
     * @return void
     */
    private function _login() 
    {

    }
}
