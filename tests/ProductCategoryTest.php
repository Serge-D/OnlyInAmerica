<?php

namespace App\Tests;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductCategoryTest extends WebTestCase
{
    public function testProductCategory(): void
    {
        $client = static::createClient();

        // Ajouter une catégorie pour le test
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $category = new Category();
        $category->setName('Test Category');
        $category->setSlug('test-category');
        $category->setDescription('Description de la catégorie de test');
        $category->setCreatedAt(new \DateTimeImmutable()); // Ajoutez une valeur pour 'created_at'
        $entityManager->persist($category);
        $entityManager->flush();

        $client->request('GET', '/productcategory/test-category');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Test Category');
        // Afficher un message dans le terminal en fonction du résultat du test
        if ($this->getStatus() === \PHPUnit\Framework\AssertionFailedError::class) {
            echo "Le test a échoué : La catégorie n'a pas été trouvée ou le nom de la catégorie ne correspond pas.\n";
        } else {
            echo "Le test a réussi : La catégorie a été trouvée avec succès.\n";
        }
    }
    public function testProductCategoryNotFound()
    {
        $client = static::createClient();
        $client->request('GET', '/productcategory/non-existent-category');

        $this->assertResponseRedirects('/');
        $client->followRedirect();
        $this->assertSelectorExists('.container h2:contains("Produit à la une")');
        // Afficher un message dans le terminal en fonction du résultat du test
        if ($this->getStatus() === \PHPUnit\Framework\AssertionFailedError::class) {
            echo "Le test a échoué : La redirection vers la page d'accueil n'a pas été effectuée ou le sélecteur pour le produit à la une n'a pas été trouvé.\n";
        } else {
            echo "Le test a réussi : La redirection vers la page d'accueil a été effectuée avec succès et le produit à la une a été trouvé.\n";
        }
    }
}
