<?php

namespace Tests\App\GraphQl;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductTest extends WebTestCase
{
    /**
     * New product list
     *
     * @var array
     */
    private $products = [
        'CocaCola' => [
            'description' => 'Refreshing Drink',
            'volume' => 1.0,
            'expirationDate' => '01.06.2018',
            'section' => 'Main',
        ],
        'Pepsi' => [
            'description' => 'Refreshing Drink',
            'volume' => 1.0,
            'expirationDate' => '01.05.2018',
            'section' => 'Door',
        ],
        'IceCream' => [
            'description' => 'Cold',
            'volume' => 0.2,
            'expirationDate' => '01.03.2018',
            'section' => 'Freezer',
        ],
    ];

    /**
     * Product list after update
     *
     * @var array
     */
    private $updateProducts = [
        'CocaCola' => [
            'description' => 'Refreshing Drink',
            'volume' => 1.0,
            'expirationDate' => '15.05.2018',
            'section' => 'Door',
        ],
        'Pepsi' => [
            'description' => 'Refreshing Drink',
            'volume' => 1.0,
            'expirationDate' => '15.04.2018',
            'section' => 'Freezer',
        ],
        'IceCream' => [
            'description' => 'Cold',
            'volume' => 0.1,
            'expirationDate' => '01.03.2017',
            'section' => 'Main',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        self::bootKernel();
    }

    /**
     * Try to add products
     */
    public function testCreate()
    {
        $client = static::createClient();

        foreach ($this->products as $product => $params) {
            $request = sprintf('
                mutation {
                  create(input: {name: "%s", description: "%s", volume: %.1f, expirationDate: "%s", section: %s}) {
                    id
                  }
                }
            ', $product, $params['description'], $params['volume'], $params['expirationDate'], $params['section']);

            $client->request('GET', '/api/graphql/fridge', ['query' => $request]);
            $json = $client->getResponse()->getContent();
            $result = json_decode($json);

            $this->assertGreaterThan(0, $result->data->create->id, sprintf('Product Name: %s', $product));
        }
    }

    /**
     * Try to change products
     */
    public function testUpdate()
    {
        $client = static::createClient();

        $products = $this->viewAll();
        foreach ($products as $product) {
            if (!isset($this->updateProducts[$product->name])) {
                continue;
            }

            $request = sprintf('
                mutation {
                  update(id: %d  input: {name: "%s", description: "%s", volume: %.1f, expirationDate: "%s", section: %s}) {
                    id
                  }
                }
            ', $product->id,
                'Product',
                $this->updateProducts[$product->name]['description'],
                $this->updateProducts[$product->name]['volume'],
                $this->updateProducts[$product->name]['expirationDate'],
                $this->updateProducts[$product->name]['section']
            );

            $client->request('GET', '/api/graphql/fridge', ['query' => $request]);
            $json = $client->getResponse()->getContent();
            $result = json_decode($json);

            $this->assertEquals($product->id, $result->data->update->id, sprintf('Product Name: %s', $product->name));
        }
    }

    /**
     * Try to view All products
     */
    public function testView()
    {
        $client = static::createClient();

        $products = $this->viewAll();

        $this->assertGreaterThan(0, count($products));

        $productId = $products[0]->id;
        $request = sprintf('
            {
              view(id: %d) {
                id
                name
                description
                volume
                expirationDate
                createdAt
                section {
                  id
                  name
                  description
                  volume
                }
              }
            }
        ', $productId);

        $client->request('GET', '/api/graphql/fridge', ['query' => $request]);
        $json = $client->getResponse()->getContent();
        $result = json_decode($json);

        $this->assertEquals($productId, $result->data->view->id);
        $this->assertGreaterThan(0, $result->data->view->section->id);
    }

    /**
     * Try to view some products
     */
    public function testViewBy()
    {
        $client = static::createClient();

        $request = '
            {
              viewBy(criteria: {name: "Product"}, orderBy: {expirationDate: ASC}) {
                view {
                  id
                  name
                  description
                  volume
                  createdAt
                  expirationDate
                  section {
                    id
                    name
                    description
                    volume
                  }
                }
              }
            }
        ';

        $client->request('GET', '/api/graphql/fridge', ['query' => $request]);
        $json = $client->getResponse()->getContent();
        $result = json_decode($json);

        $this->assertGreaterThan(1, count($result->data->viewBy->view));

        $firstItemExpirationDate = $result->data->viewBy->view[0]->expirationDate;

        $request = '
            {
              viewBy(criteria: {name: "Product"}, orderBy: {expirationDate: DESC}) {
                view {
                  id
                  name
                  description
                  volume
                  createdAt
                  expirationDate
                  section {
                    id
                    name
                    description
                    volume
                  }
                }
              }
            }
        ';

        $client->request('GET', '/api/graphql/fridge', ['query' => $request]);
        $json = $client->getResponse()->getContent();
        $result = json_decode($json);

        $lastItem = array_pop($result->data->viewBy->view);
        $this->assertEquals($firstItemExpirationDate, $lastItem->expirationDate);
    }

    /**
     * Try to view All Expired products
     */
    public function testViewAllExpired()
    {
        $client = static::createClient();

        $request = '
            {
              viewAllExpired {
                view {
                  id
                  name
                  description
                  volume
                  createdAt
                  expirationDate
                  section {
                    id
                    name
                    description
                    volume
                  }
                }
              }
            }
        ';

        $client->request('GET', '/api/graphql/fridge', ['query' => $request]);
        $json = $client->getResponse()->getContent();
        $result = json_decode($json);

        $this->assertGreaterThan(0, count($result->data->viewAllExpired->view));
    }

    /**
     * Try to view remove All products
     */
    public function testDelete()
    {
        $client = static::createClient();

        $products = $this->viewAll();
        foreach ($products as $product) {
            $request = sprintf('
                mutation {
                  delete(id: %d )
                }
            ', $product->id
            );

            $client->request('GET', '/api/graphql/fridge', ['query' => $request]);
            $json = $client->getResponse()->getContent();
            $result = json_decode($json);

            $this->assertEquals('Success', $result->data->delete, $product->name);
        }
    }

    /**
     * Get all product list
     *
     * @return array
     */
    private function viewAll(): array
    {
        $client = static::createClient();

        $request = '
            {
              viewAll {
                view {
                  id
                  name
                  description
                  volume
                  createdAt
                  expirationDate
                  section {
                    id
                    name
                    description
                    volume
                  }
                }
              }
            }
        ';

        $client->request('GET', '/api/graphql/fridge', ['query' => $request]);
        $json = $client->getResponse()->getContent();
        $result = json_decode($json);

        $products = [];
        foreach ($result->data->viewAll->view as $item) {
            $products[] = $item;
        }

        return $products;
    }
}
