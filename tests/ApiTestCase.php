<?php
namespace Czim\CmsAclModule\Test;

use Carbon\Carbon;

abstract class ApiTestCase extends WebTestCase
{
    const OAUTH_CLIENT_ID     = '0123456789a0123456789b0123456789';
    const OAUTH_CLIENT_SECRET = 'abcdefghij0abcdefghij1abcdefghij';


    protected function seedDatabase()
    {
        parent::seedDatabase();

        // Create a default OAuth client
        \DB::table('cms_oauth_clients')
            ->insert([
                'id'         => static::OAUTH_CLIENT_ID,
                'secret'     => static::OAUTH_CLIENT_SECRET,
                'name'       => 'Testing',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    }


    /**
     * @return string
     */
    protected function getTestBootCheckerBinding()
    {
        return \Czim\CmsCore\Test\Helpers\Core\MockApiBootChecker::class;
    }

}
