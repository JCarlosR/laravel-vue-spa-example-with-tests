<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Throwable;

class WelcomeTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     * @throws Throwable
     */
    public function testWelcomePageDisplaysAppName()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitFor('.title', 100)
                ->assertSee(config('app.name'));
        });
    }
}
