<?php

namespace Tests\Browser\Pages;

use Facebook\WebDriver\Exception\TimeOutException;
use Laravel\Dusk\Browser;

class TasksToday extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/tasks/today';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     * @return void
     * @throws TimeOutException
     */
    public function assert(Browser $browser)
    {
        $browser
            ->waitForLocation($this->url())
            ->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@navbar-dropdown-toggle' => '.navbar-nav.ml-auto .dropdown-toggle',
        ];
    }

    /**
     * Click on the log out link.
     *
     * @param Browser $browser
     * @return void
     * @throws TimeOutException
     */
    public function clickLogout($browser)
    {
        $browser
            ->click('@navbar-dropdown-toggle') // expand dropdown
            ->waitForText('Logout')
            ->clickLink('Logout')
            ->pause(100);
    }
}
