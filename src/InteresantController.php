<?php

namespace App;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Itav\Component\Serializer\Serializer;
use Itav\Component\Form;

class InteresantController
{

    public function listAction(Request $request)
    {
        $serializer = new Serializer();
        $repo = new InteresantRepo();
        $interesants = $repo->findAll();
        $vars = [];
        foreach ($interesants as $interesant){
            $vars[] = $serializer->normalize($interesant);
        }
        return json_encode($vars);
    }
    
    public function formAction()
    {
        $serializer = new Serializer();
        $interesant = new Interesant();
        $select = $this->prepareSelectInteresant($interesant);
        return json_encode($serializer->normalize($select));
    }    

    public function addAction(Application $app, $id = null)
    {
        $interesant = new Interesant();
        $address1 = new Address();
        $address2 = new Address();
        $address2->setType(Address::TYPE_POST);
        $interesant->setAddresses([$address1, $address2]);
        if ($id) {
            $repo = new InteresantRepo();
            $interesant = $repo->find($id);
        }

        $form = $this->prepareAddForm($app, $interesant);

        $serializer = new Serializer();
        $formNorm = $serializer->normalize($form);
        return $app['templating']->render('page.php', array('form' => $formNorm));
    }

    public function saveAction(Application $app, Request $request, $id = null)
    {
        $interesantData = $request->get('interesant');
        $addressesData = $request->get('addresses');
        $serializer = new Serializer();
        $interesant = new Interesant();
        $addresses = new Addresses();
        $interesant = $serializer->unserialize($interesantData, Interesant::class, $interesant);
        $addresses = $serializer->unserialize($addressesData, Address::class . '[]');
        $interesant->setAddresses($addresses);
        $valid = $this->validateInteresant($interesant);
        if ($valid) {
            $repo = new InteresantRepo();
            $savedId = $repo->save($interesant);
            var_dump($savedId);
            return '';
        }
        $form = $this->prepareAddForm($app, $interesant);
        $formNorm = $serializer->normalize($form);
        return $app['templating']->render('page.php', array('form' => $formNorm));
    }

    public function infoAction(Application $app, $id)
    {
        $serializer = new Serializer();
        $repo = new InteresantRepo();
        $interesant = $repo->find($id);

        $form = $this->prepareAddForm($app, $interesant);
        $form->removeSubmits();
        $formNorm = $serializer->normalize($form);
        return $app['templating']->render('page.php', array('form' => $formNorm));
    }

    public function deleteAction($id)
    {
        
    }

    /**
     * 
     * @param Application $app
     * @param Interesant $interesant
     * @return \Itav\Component\Form\Form
     */
    public function prepareAddForm(Application $app, Interesant $interesant)
    {

        $id = new Form\Input();
        $id
                ->setType(Form\Input::TYPE_HIDDEN)
                ->setName('interesant[id]')
                ->setValue($interesant->getId());

        $type = new Form\Select();
        $type
                ->setLabel('Type:')
                ->setName('interesant[type]')
                ->setOptions([
                    new Form\Option('Private', Interesant::TYPE_PRIVATE, ($interesant->getType() === Interesant::TYPE_PRIVATE)),
                    new Form\Option('Company', Interesant::TYPE_COMPANY, ($interesant->getType() === Interesant::TYPE_COMPANY))
        ]);

        $name = new Form\Input();
        $name
                ->setLabel('Name:')
                ->setName('interesant[name]')
                ->setValue($interesant->getName());

        $firstName = new Form\Input();
        $firstName
                ->setLabel('First Name:')
                ->setName('interesant[first_name]')
                ->setValue($interesant->getFirstName());

        $lastName = new Form\Input();
        $lastName
                ->setLabel('Last Name:')
                ->setName('interesant[last_name]')
                ->setValue($interesant->getLastName());

        $ten = new Form\Input();
        $ten
                ->setLabel('Ten:')
                ->setName('interesant[ten]')
                ->setValue($interesant->getTen());

        $submit = new Form\Button();
        $submit
                ->setLabel('Zapisz')
                ->setType(Form\Button::TYPE_SUBMIT);

        $fs1 = new Form\FieldSet();
        $fs1->setElements([$id, $type, $name, $firstName, $lastName, $ten]);
        $fs2 = $this->prepareAddressForm($interesant);

        $form = new Form\Form();
        $form
                ->setName('interesantAdd')
                ->setAction($app['url_generator']->generate('interesant_add'))
                ->setMethod('POST');

        $form
                ->addElement($fs1)
                ->addElement($fs2)
                ->addElement($submit);
        return $form;
    }

    public function prepareAddressForm(Interesant $interesant)
    {
        $fs = new Form\FieldSet();
        $i = 0;
        foreach ($interesant->getAddresses() as $address) {
            $type = new Form\Select();
            $type
                    ->setLabel('Type:')
                    ->setName("addresses[$i][type]")
                    ->setOptions([
                        new Form\Option('Main', Address::TYPE_MAIN, $address->getType() === Address::TYPE_MAIN),
                        new Form\Option('Post', Address::TYPE_POST, $address->getType() === Address::TYPE_POST)
            ]);

            $street = new Form\Input();
            $street
                    ->setLabel('Street:')
                    ->setName("addresses[$i][street]")
                    ->setValue($address->getStreet());

            $zip = new Form\Input();
            $zip
                    ->setLabel('Zip:')
                    ->setName("addresses[$i][zip]")
                    ->setValue($address->getZip());

            $city = new Form\Input();
            $city
                    ->setLabel('City:')
                    ->setName("addresses[$i][city]")
                    ->setValue($address->getCity());
            
            $fsItem = new Form\FieldSet();
            $fsItem->setElements([$type, $street, $zip, $city]);
            $fs->addElement($fsItem);
            $i++;
        }
        return $fs;
    }

    public function validateInteresant($interesant)
    {
        return true;
    }
    
    /**
     * 
     * @param Interesant $interesant
     * @return Form\Select
     */
    public function prepareSelectInteresant($interesant)
    {
        $repo = new InteresantRepo();
        $interesants = $repo->findAll();
        $select = new Form\Select();
        $select
                ->setLabel('Select Interesant:')
                ->setName('interesant[id]');
        $options = [];
        foreach ($interesants as $item) {
            $option = new Form\Option();
            $option
                    ->setLabel($item->getName() ? $item->getName() :  $item->getFirstName() . $item->getLastName())
                    ->setValue($item->getId())
                    ->setSelected($item->getId() === $interesant->getId());
            $options[] = $option;
        }
        $select->setOptions($options);
        return $select;
    }

}
