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
class UserTest extends PHPUnit_Extensions_SeleniumTestCase
{
    /**
     * setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://localhost/erp');
    }

    /**
     * for login because all test need to login
     *
     * @return void;
     */
    protected function _login() {
        $this->open('http://localhost/erp/index.php/users');
        $this->type("id=identity", "egon");
        $this->type("id=password", "nakal23baik");
        $this->clickAndWait("name=submit");
    }

    /**
     * tutorial test Title
     *
     * @return void
     */
    public function testAddUser()
    {
        $this->_login();
        $this->open('http://localhost/erp/index.php/users');
        $this->clickAndWait('link=Add User');
        $this->type('id=username',"kasir5");
        $this->type('id=first_name',"kasir");
        $this->type('id=last_name',"kasir");
        $this->type('id=company',"PTENFYS");
        $this->type('id=phone',"1234567890");
        $this->type('id=email',"a@a.com");
        $this->type('id=password',"nakal23baik");
        $this->type('id=password_confirm',"nakal23baik");
        $this->select('name=groups', 'label=cashier');
        $this->clickAndWait('name=submit');
        $this->pause(10000);
        $this->assertText('css=p', 'Account Successfully Created');
        $this->type('//input[@type="text"]', 'kasir5');
        $this->assertElementPresent('//tr/td[text()="kasir5"]');
    }

    public function testLoginNewUser() 
    {
        $this->open('http://localhost/erp');
        $this->type("id=identity", "kasir5");
        $this->type("id=password", "nakal23baik");
        $this->clickAndWait("name=submit");
        $this->pause(10000);
    }

    public function testDeleteUser()
    {
        $this->_login();
        $this->open('http://localhost/erp/index.php/users');
        $this->pause(5000);
        $this->click('//tr/td[text()="kasir5"]/../td[contains(@class,tableActs)]/a[2]');
        $this->assertConfirmation('Are you sure you want to delete kasir5');
        $this->assertText('css=p', 'User Deleted');
        $this->pause(10000);
    }
}
