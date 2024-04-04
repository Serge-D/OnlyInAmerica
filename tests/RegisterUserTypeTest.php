<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTypeTest extends WebTestCase
{
    public function testSomething(): void
    {
        /*
         * 1. Créer un faux client (navigateur) qui va pointer vers une URL
         * 2. Remplir les champs de mon formulaire d'inscription
         * 3. Regarder dans ma page si j'ai le message (alert) suivant : Félicitations, vous pouvez vous connecter.
         */

        // etape 1
        $client = static::createClient();
        $client->request('GET','/user/inscription');

        //etape 2 (user[lastName],user[firstName],user[email],user[plainPassword][first],user[plainPassword][second],
        //user[address],user[zipCode],user[country],user[city],user[phone]
        $client->submitForm('Envoyer',[
            'user[lastName]'=>'didier',
            'user[firstName]'=>'doe',
            'user[email]'=>'didier@exemple.fr',
            'user[plainPassword][first]'=>'test',
            'user[plainPassword][second]'=>'test',
            'user[address]'=>'1 rue de paris',
            'user[zipCode]'=>'75001',
            'user[country]'=>'FR',
            'user[city]'=>'Paris',
            'user[phone]'=>'0601020304'
        ]);

        //Suivre la redirection du formulaire
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        //etape 3
        $this->assertSelectorExists('div:contains("Félicitations, vous pouvez vous connecter.")');

    }
}
