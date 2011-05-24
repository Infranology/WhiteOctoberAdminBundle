<?php

/*
 * This file is part of the WhiteOctoberAdminBundle package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WhiteOctober\AdminBundle\DataManager\Base\Action;

use WhiteOctober\AdminBundle\Action\Action;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class CreateAction extends Action
{
    protected function configure()
    {
        $this
            ->setRoute('create', '/', array(), array('_method' => 'POST'))
            ->setDependences(array(
                'new' => array(),
            ))
            ->addOptions(array(
                'template' => 'WhiteOctoberAdminBundle::default/new.html.twig',
            ))
        ;
    }

    public function executeController()
    {
        $createDataClosure = $this->getActionsVars()->get('createDataClosure');
        $data = $createDataClosure();

        $form = $this->createFormFromFields($this->getFields());
        $form->setData($data);

        $form->bindRequest($this->container->get('request'));
        if ($form->isValid()) {
            $saveDataClosure = $this->getActionsVars()->get('saveDataClosure');
            $saveDataClosure($data);

            return new RedirectResponse($this->generateUrl('list'));
        }

        return $this->render($this->getOption('template'), array('form' => $form->createView()));
    }
}
