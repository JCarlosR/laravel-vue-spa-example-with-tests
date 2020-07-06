<?php

namespace Tests\Browser\Pages;

use Facebook\WebDriver\Exception\TimeoutException;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

abstract class Page extends BasePage
{

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
     * Get the global element shortcuts for the site.
     *
     * @return array
     */
    public static function siteElements()
    {
        return [
            // '@element' => '#selector',
        ];
    }
}
