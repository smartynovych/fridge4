<?php

namespace Tests\App\GraphQl;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResolverTest extends WebTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        self::bootKernel();
    }

    public function testView()
    {
        $client = static::createClient();

        $request = '
            {
              view(id: 1) {
                id
                name
                description
                volume
                expirationDate
                createdAt
                section{
                  id
                  name
                  description
                  volume
                }
              }
            }
        ';

        $client->request('GET', '/graphql/fridge', ['query' => $request]);
        $json = $client->getResponse()->getContent();
        $result = json_decode($json);

        $this->assertEquals('Bear', $result->data->view->name);
    }
}