<?php

namespace Remp\MailerModule\Forms;

use Nette\Application\UI\Form;
use Nette\SmartObject;
use Remp\MailerModule\Repository\BatchesRepository;
use Remp\MailerModule\Repository\BatchTemplatesRepository;
use Remp\MailerModule\Repository\ListsRepository;
use Remp\MailerModule\Repository\TemplatesRepository;

class NewTemplateFormFactory
{
    use SmartObject;

    private $templatesRepository;

    private $batchesRepository;

    private $batchTemplatesRepository;

    private $listsRepository;

    public $onSuccess;

    public function __construct(
        TemplatesRepository $templatesRepository,
        BatchesRepository $batchesRepository,
        BatchTemplatesRepository $batchTemplatesRepository,
        ListsRepository $listsRepository
    ) {
        $this->templatesRepository = $templatesRepository;
        $this->batchesRepository = $batchesRepository;
        $this->batchTemplatesRepository = $batchTemplatesRepository;
        $this->listsRepository = $listsRepository;
    }

    public function create($batchId)
    {
        $form = new Form;
        $form->addProtection();

        $listPairs = $this->listsRepository->all()->fetchPairs('id', 'title');
        $form->addSelect('mail_type_id', 'Newsletter list', $listPairs)
            ->setPrompt('Select newsletter list');

        if (isset($_POST['mail_type_id'])) {
            $templateList = $this->templatesRepository->pairs($_POST['mail_type_id']);
        } else {
            $templateList = null;
        }
        $form->addSelect('template_id', 'Email', $templateList);
        $form->addHidden('batch_id', $batchId);

        $form->addSubmit('save', 'Save')
            ->getControlPrototype()
            ->setName('button')
            ->setHtml('<i class="zmdi zmdi-mail-send"></i> Save');

        $form->onSuccess[] = [$this, 'formSucceeded'];
        return $form;
    }

    public function formSucceeded($form, $values)
    {
        $batch = $this->batchesRepository->find((int)$values['batch_id']);
        $job = $batch->ref('job');

        $batchTemplate = $this->batchTemplatesRepository->add(
            $job->id,
            $batch->id,
            $values['template_id']
        );

        ($this->onSuccess)($job);
    }
}
