<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ExampleSessionTest extends CIUnitTestCase
{
    public function testSessionSimple(): void
    {
        $session = service('session');

        $session->set('isLoggedIn', 123);
        $this->assertSame(123, $session->get('isLoggedIn'));
    }
}
