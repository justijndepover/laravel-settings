<?php

namespace Justijndepover\Settings\Tests;

use Illuminate\Support\Facades\DB;
use Justijndepover\Settings\Tests\Models\User;

class DatabaseSettingsTest extends TestCase
{
    /** @test */
    public function it_can_fetch_all_settings_from_database()
    {
        DB::table('settings')->insert([
            'key' => 'name',
            'value' => 'value',
        ]);

        DB::table('settings')->insert([
            'key' => 'name2',
            'value' => 'value',
        ]);

        DB::table('settings')->insert([
            'key' => 'name3',
            'value' => 'value',
        ]);

        $this->assertEquals($this->settings->all()->count(), 3);
    }

    /** @test */
    public function it_can_store_a_setting_in_database()
    {
        $this->settings->set('name', 'value');
        $setting = DB::table('settings')->where('key', '=', 'name')->first();

        $this->assertEquals($setting->value, 'value');
    }

    /** @test */
    public function it_can_store_multiple_settings_in_database()
    {
        $this->settings->set([
            'name' => 'value',
            'name2' => 'value2'
        ]);

        $setting = DB::table('settings')->where('key', '=', 'name')->first();
        $this->assertEquals($setting->value, 'value');

        $setting2 = DB::table('settings')->where('key', '=', 'name2')->first();
        $this->assertEquals($setting2->value, 'value2');
    }

    /** @test */
    public function it_can_fetch_a_single_value_from_database()
    {
        DB::table('settings')->insert([
            'key' => 'name',
            'value' => 'value',
        ]);

        $this->assertEquals($this->settings->get('name'), 'value');
    }

    /** @test */
    public function it_can_delete_a_setting_from_the_database()
    {
        $this->settings->set('name', 'value');

        $setting = DB::table('settings')->where('key', '=', 'name')->first();
        $this->assertEquals($setting->value, 'value');

        $this->settings->forget('name');

        $setting = DB::table('settings')->where('key', '=', 'name')->first();
        $this->assertEquals($setting, null);
    }

    /** @test */
    public function it_can_check_if_value_exists()
    {
        DB::table('settings')->insert([
            'key' => 'name',
            'value' => 'value',
        ]);

        $this->assertEquals($this->settings->has('name'), true);
    }

    /** @test */
    public function it_can_flush_data()
    {
        DB::table('settings')->insert([
            'key' => 'name',
            'value' => 'value',
        ]);

        DB::table('settings')->insert([
            'key' => 'name2',
            'value' => 'value2',
        ]);

        $this->assertEquals($this->settings->all()->count(), 2);
        $this->assertEquals(DB::table('settings')->count(), 2);

        $this->settings->flush();

        $this->assertEquals($this->settings->all()->count(), 0);
        $this->assertEquals(DB::table('settings')->count(), 0);
    }

    /** @test */
    public function it_can_delete_entire_database()
    {
        DB::table('settings')->insert([
            'key' => 'name',
            'value' => 'value',
        ]);

        DB::table('settings')->insert([
            'key' => 'name2',
            'value' => 'value2',
        ]);

        $this->settings->delete();

        $this->assertEquals($this->settings->all()->count(), 0);
        $this->assertEquals(DB::table('settings')->count(), 0);
    }

    /** @test */
    public function it_can_delete_a_single_value()
    {
        DB::table('settings')->insert([
            'key' => 'name',
            'value' => 'value',
        ]);

        DB::table('settings')->insert([
            'key' => 'name2',
            'value' => 'value2',
        ]);

        $this->settings->delete('name');

        $this->assertEquals($this->settings->all()->count(), 1);
        $this->assertEquals(DB::table('settings')->count(), 1);
    }

    /** @test */
    public function it_has_a_facade_accessor()
    {
        DB::table('settings')->insert([
            'key' => 'name',
            'value' => 'value',
        ]);

        $setting = \Settings::get('name');

        $this->assertEquals($setting, 'value');
    }

    /** @test */
    public function it_can_store_a_single_user_setting()
    {
        $this->settings->forUser(1)->set('name', 'value');

        $setting = DB::table('settings')->where('key', '=', 'name')->where('user_id', '=', 1)->first();
        $this->assertEquals($setting->value, 'value');
    }

    /** @test */
    public function it_can_store_multiple_user_settings()
    {
        $this->settings->forUser(1)->set([
            'name' => 'value',
            'name2' => 'value2'
        ]);

        $setting = DB::table('settings')->where('key', '=', 'name')->where('user_id', '=', 1)->first();
        $this->assertEquals($setting->value, 'value');

        $setting2 = DB::table('settings')->where('key', '=', 'name2')->where('user_id', '=', 1)->first();
        $this->assertEquals($setting2->value, 'value2');
    }

    /** @test */
    public function it_can_store_a_single_locale_setting()
    {
        $this->settings->forLocale('nl')->set('name', 'value');

        $setting = DB::table('settings')->where('key', '=', 'name')->where('locale', '=', 'nl')->first();
        $this->assertEquals($setting->value, 'value');
    }

    /** @test */
    public function it_can_store_multiple_locale_settings()
    {
        $this->settings->forLocale('nl')->set([
            'name' => 'value',
            'name2' => 'value2'
        ]);

        $setting = DB::table('settings')->where('key', '=', 'name')->where('locale', '=', 'nl')->first();
        $this->assertEquals($setting->value, 'value');

        $setting2 = DB::table('settings')->where('key', '=', 'name2')->where('locale', '=', 'nl')->first();
        $this->assertEquals($setting2->value, 'value2');
    }

    /** @test */
    public function it_can_store_user_settings_and_normal_settings()
    {
        $this->settings->forUser(1)->set('name', 'value');
        $this->settings->set('name', 'value');

        $setting = DB::table('settings')->where('key', '=', 'name')->where('user_id', '=', 1)->first();
        $this->assertEquals($setting->value, 'value');

        $setting = DB::table('settings')->where('key', '=', 'name')->where('user_id', '=', null)->first();
        $this->assertEquals($setting->value, 'value');
    }

    /** @test */
    public function it_can_store_locale_settings_and_normal_settings()
    {
        $this->settings->forLocale('nl')->set('name', 'value');
        $this->settings->set('name', 'value');

        $setting = DB::table('settings')->where('key', '=', 'name')->where('locale', '=', 'nl')->first();
        $this->assertEquals($setting->value, 'value');

        $setting = DB::table('settings')->where('key', '=', 'name')->where('locale', '=', null)->first();
        $this->assertEquals($setting->value, 'value');
    }

    /** @test */
    public function it_can_store_different_locale_settings()
    {
        $this->settings->forLocale('nl')->set('name', 'value');
        $this->settings->forLocale('fr')->set('name', 'value-fr');

        $setting = DB::table('settings')->where('key', '=', 'name')->where('locale', '=', 'nl')->first();
        $this->assertEquals($setting->value, 'value');

        $setting = DB::table('settings')->where('key', '=', 'name')->where('locale', '=', 'fr')->first();
        $this->assertEquals($setting->value, 'value-fr');
    }

    /** @test */
    public function it_has_a_working_user_setting_trait()
    {
        $this->settings->forUser(1)->set('name', 'value');

        $setting = DB::table('settings')->where('key', '=', 'name')->where('user_id', '=', 1)->first();
        $this->assertEquals($setting->value, 'value');

        $user = User::create([
            'name' => 'Foo Bar',
            'email' => 'foo@bar.com',
            'password' => 'foobar',
        ]);

        $this->assertEquals($user->settings->get('name'), 'value');
        $this->assertEquals($user->settings()->get('name'), 'value');
    }
}
