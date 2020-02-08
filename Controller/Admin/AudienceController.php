<?php

namespace Ekyna\Bundle\MailchimpBundle\Controller\Admin;

use Ekyna\Bundle\AdminBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;

/**
 * Class AudienceController
 * @package Ekyna\Bundle\MailchimpBundle\Controller\Admin
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AudienceController extends ResourceController
{
    /**
     * Synchronize action.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function synchronizeAction()
    {
        $process = new Process(
            'php bin/console ekyna:mailchimp:synchronize',
            $this->getParameter('kernel.project_dir')
        );

        $process->start();

        $this->addFlash("Audiences synchronization started...");

        return $this->redirect($this->generateResourcePath('ekyna_mailchimp.audience', 'list'));
    }

    /**
     * @inheritDoc
     */
    public function newAction(Request $request)
    {
        $this->addFlash(
            "You can't create audience from this administration. Please use Mailchimp interface.",
            'warning'
        );

        return $this->redirect($this->generateResourcePath('ekyna_mailchimp.audience', 'list'));
    }

    /**
     * @inheritDoc
     */
    public function removeAction(Request $request)
    {
        $this->addFlash(
            "You can't remove audience from this administration. Please use Mailchimp interface.",
            'warning'
        );

        return $this->redirect($this->generateResourcePath('ekyna_mailchimp.audience', 'list'));
    }
}
