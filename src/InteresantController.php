<?php

namespace App;

use Silex\App;
use Symfony\Component\HttpFoundation\Request;
use Itav\Component\Serializer\Serializer;
use Itav\Component\Form;
use Itav\Component\Table;


class InteresantController
{
    public function listAction(App $app, Request $request)
    {

        $serializer = new Serializer();
        $repo = new InteresantRepo();
        $interesants = $repo->findAll();
        $table = new Table\Table();
        $headRow = new Table\Tr();
        $headRow
            ->addElement(new Table\Th('1'))
            ->addElement(new Table\Th('2'))
            ->addElement(new Table\Th('3'))
            ->addElement(new Table\Th('4'));
        $thead = new Table\Thead();
        $thead->addElement($headRow);
        $tbody = new Table\Tbody();
        foreach ($interesants as $interesant) {
            $id = $interesant->getId();
//            $actions = "<a href='/info/$id'>info</a>&nbsp"
//                . "<a href='/edit/$id'>edit</a>&nbsp"
//                . "<a href='/del/$id'>del</a>";

            $row = new Table\Tr();
            $row->setElements([
                new Table\Td($interesant->getName() . ' ' . $interesant->getLastName() . ' ' . $interesant->getFirstName()),
                new Table\Td($interesant->getAddresses()[0]->getCity()),
                new Table\Td($interesant->getTen()),
                new Table\Td('action_place'),
            ]);
            $tbody->addElement($row);
        }
        $table
            ->addElement($thead)
            ->addElement($tbody)
            ->setId('FBinteresantTable')
            ->setClass(ViewHelp::CSS_TABLE_LIST);

        $table = $serializer->normalize($table);
        return $app['twig']->render('list.twig', [
            'table' => $table,
            'title' => $app->trans('interesants'),
        ]);
    }

    public function formAction()
    {
        $serializer = new Serializer();
        $interesant = new Interesant();
        $select = $this->prepareSelectInteresant($interesant);
        return json_encode($serializer->normalize($select));
    }

    public function addAction(App $app, $id = null)
    {
        $interesant = new Interesant();
        $address = new Address();
        $interesant->addAddress($address);
        if ($id) {
            $repo = new InteresantRepo();
            $interesant = $repo->find($id);
        }

        $form = $this->prepareAddForm($app, $interesant);

        $serializer = new Serializer();
        $formNorm = $serializer->normalize($form);
        return $app['templating']->render('page.php', array('form' => $formNorm));
    }

    public function saveAction(App $app, Request $request)
    {
        $interesantData = $request->get('interesant');
        $submit = $request->get('submit');

        $serializer = new Serializer();
        $interesant = new Interesant();
        $interesant = $serializer->unserialize($interesantData, Interesant::class, $interesant);
        if (isset($submit['add_position'])) {
            $address = new Address();
            $interesant->addAddress($address);
            $form = $this->prepareAddForm($app, $interesant);
            $formNorm = $serializer->normalize($form);
            return $app['templating']->render('page.php', array('form' => $formNorm));
        }
        if (isset($submit['remove_item'])) {
            $index = $submit['remove_item'];
            if (count($interesant->getAddresses()) > 1) {
                $interesant->delAddress($index);
                $interesant->reindexAddresses();
            }
            $form = $this->prepareAddForm($app, $interesant);
            $formNorm = $serializer->normalize($form);
            return $app['templating']->render('page.php', array('form' => $formNorm));
        }
        $valid = $this->validateInteresant($interesant);
        if ($valid) {
            $repo = new InteresantRepo();
            $savedId = $repo->save($interesant);
            if ($request->headers->get('Accept') == 'application/json') {
                if (!$savedId) {
                    return $app->json(['msg' => 'fail'], 404);
                }
                return $app->json(['msg' => 'ok']);
            }
            var_dump($savedId);
            return '';
        }
        $form = $this->prepareAddForm($app, $interesant);
        $formNorm = $serializer->normalize($form);
        return $app['templating']->render('page.php', array('form' => $formNorm));
    }

    public function infoAction(App $app, Request $request, $id)
    {
        $serializer = new Serializer();
        $repo = new InteresantRepo();
        $interesant = $repo->find($id);
        if ($request->headers->get('Accept') == 'application/json') {
            return $app->json($serializer->normalize($interesant));
        }
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
    public function prepareAddForm(App $app, Interesant $interesant)
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
                new Form\Option('Private', Interesant::TYPE_PRIVATE, ($interesant->getType() == Interesant::TYPE_PRIVATE)),
                new Form\Option('Company', Interesant::TYPE_COMPANY, ($interesant->getType() == Interesant::TYPE_COMPANY))
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

        $addButton = new Form\Button();
        $addButton
            ->setLabel('Add position')
            ->setType(Form\Button::TYPE_SUBMIT)
            ->setName('submit[add_position]');

        $fs2 = $this->prepareAddressForm($interesant);
        $fs2->addElement($addButton);

        $bank = new Form\Input();
        $bank
            ->setLabel('Bank Account:')
            ->setName('interesant[bank_account]')
            ->setValue($interesant->getBankAccount());

        $fs3 = new Form\FieldSet();
        $fs3->addElement($bank);

        $form = new Form\Form();
        $form
            ->setName('interesantAdd')
            ->setAction($app['url_generator']->generate('interesant_add'))
            ->setMethod('POST');

        $form
            ->addElement($fs1)
            ->addElement($fs2)
            ->addElement($fs3)
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
                ->setName("interesant[addresses][$i][type]")
                ->setOptions([
                    new Form\Option('Main', Address::TYPE_MAIN, $address->getType() == Address::TYPE_MAIN),
                    new Form\Option('Post', Address::TYPE_POST, $address->getType() == Address::TYPE_POST),
                    new Form\Option('Other', Address::TYPE_OTHER, $address->getType() == Address::TYPE_OTHER)
                ]);

            $street = new Form\Input();
            $street
                ->setLabel('Street:')
                ->setName("interesant[addresses][$i][street]")
                ->setValue($address->getStreet());

            $zip = new Form\Input();
            $zip
                ->setLabel('Zip:')
                ->setName("interesant[addresses][$i][zip]")
                ->setValue($address->getZip());

            $city = new Form\Input();
            $city
                ->setLabel('City:')
                ->setName("interesant[addresses][$i][city]")
                ->setValue($address->getCity());

            $removeButton = new Form\Button();
            $removeButton
                ->setLabel('Remove')
                ->setType(Form\Button::TYPE_SUBMIT)
                ->setName("submit[remove_item]")
                ->setValue($i);

            $fsItem = new Form\FieldSet();
            $fsItem->setElements([$type, $street, $zip, $city, $removeButton]);
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
                ->setLabel($item->getName() ? $item->getName() : $item->getFirstName() . $item->getLastName())
                ->setValue($item->getId())
                ->setSelected($item->getId() === $interesant->getId());
            $options[] = $option;
        }
        $select->setOptions($options);
        return $select;
    }

}
