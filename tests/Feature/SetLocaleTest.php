<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class SetLocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_set_lang_changes_locale_and_returns_messages()
    {
        $locale = 'es';

        $response = $this->json('GET', route('setLang', ['locale' => $locale]));

        $response->assertStatus(200)
            ->assertJson([
                'locale' => $locale,
                'messages' => Lang::get('common', [], $locale),
            ]);

        $this->assertEquals($locale, app()->getLocale());
    }
}
