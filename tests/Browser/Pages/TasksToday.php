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
