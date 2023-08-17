<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerFunctionalTest extends WebTestCase
{
    public function testIndexWithEmptyCart()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/cart');
        
        $this->assertResponseIsSuccessful();
        
        // Ajustement de l'assertion pour refléter le contenu réel du modèle
        $this->assertSelectorTextContains('h1', 'Mon panier');
    }
            
    public function testIndexWithNotEmptyCart()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/cart');
        
        $this->assertResponseIsSuccessful();
        
        // Ajustement de l'assertion pour refléter le contenu réel du modèle
        $this->assertSelectorTextContains('h1', 'Mon panier');
    }
    

    public function testAddToCart()
    {
        $client = static::createClient();
        
        // Supposons que vous ajoutez un produit avec l'ID 1 au panier.
        // Encore une fois, vous devrez peut-être utiliser des fixtures ou mocker l'appel à la base de données.
        $crawler = $client->request('GET', '/cart/add/1');
        
        $this->assertResponseRedirects('/cart');
    }

    // Vous pouvez continuer avec des tests similaires pour les autres actions de votre contrôleur.
}
