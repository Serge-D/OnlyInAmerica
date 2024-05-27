<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUserRegister()
    {
        /*
         * Etapes :
         * 1. créer un faux client (navigateur) et pointer vers une url
         * 2. remplir les champs de mon formulaire d'inscription
         * 3. Voir si dans ma page j'ai le message success
         */

        $client = static::createClient(); // création du client
        $client->request('GET', '/inscription'); // pointer vers l'url

        // Remplir les champs du formulaire avec les données de l'utilisateur
        $client->submitForm('Envoyer',[
            'user[lastName]' => 'nala',
            'user[firstName]' => 'didier',
            'user[email]' => 'test4@test4.com',
            'user[plainPassword][first]' => 'Motdepasse1234567!',
            'user[plainPassword][second]' => 'Motdepasse1234567!'
        ]);

        // Vérifie la redirection après soumission du formulaire
        $this->assertResponseRedirects('/connexion.html');
        $client->followRedirect();

        // Vérifie la présence du message de succès après redirection => 3ème étape
        $this->assertSelectorExists('div:contains("Félicitations, vous pouvez vous connecter.")');

    }
}
