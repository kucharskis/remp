<?php

namespace Remp\MailerModule\Forms;

use Nette\Application\UI\Form;
use Nette\Object;
use Remp\MailerModule\Form\Rendering\MaterialRenderer;
use Remp\MailerModule\Repository\BatchesRepository;
use Remp\MailerModule\Repository\JobsRepository;
use Remp\MailerModule\Segment\Aggregator;
use Remp\MailerModule\Segment\SegmentException;

class JobFormFactory extends Object
{
    private $jobsRepository;

    private $batchesRepository;

    private $segmentAggregator;

    public $onSuccess;
    public $onError;

    public function __construct(
        JobsRepository $jobsRepository,
        BatchesRepository $batchesRepository,
        Aggregator $segmentAggregator
    ) {
        $this->jobsRepository = $jobsRepository;
        $this->batchesRepository = $batchesRepository;
        $this->segmentAggregator = $segmentAggregator;
    }

    public function create($jobId)
    {

        $form = new Form();
        $form->addProtection();
        $form->setRenderer(new MaterialRenderer());
        $form->addHidden('job_id', $jobId);

        $job = $this->jobsRepository->find($jobId);
        if (!$job) {
            $form->addError('Unable to load Mail Job.');
        }

        if ($this->batchesRepository->notEditableBatches($jobId)->count() > 0) {
            $form->addError("Job can't be updated. One or more Mail Job Batches were already started.");
        }

        $segments = [];
        try {
            $segmentList = $this->segmentAggregator->list();
            array_walk($segmentList, function ($segment) use (&$segments) {
                $segments[$segment['provider']][$segment['provider'] . '::' . $segment['code']] = $segment['name'];
            });
        } catch (SegmentException $e) {
            $form->addError('Unable to fetch list of segments, please check the application configuration.');
        }

        $form->addSelect('segment_code', 'Segment', $segments)
            ->setPrompt('Select segment')
            ->setRequired("Field 'Segment' is required.")
            ->setHtmlAttribute('class', 'selectpicker')
            ->setHtmlAttribute('data-live-search', 'true')
            ->setHtmlAttribute('data-live-search-normalize', 'true')
            ->setDefaultValue($job->segment_provider . '::' . $job->segment_code);

        $form->addSubmit('save', 'Save')
            ->getControlPrototype()
            ->setName('button')
            ->setHtml('<i class="zmdi zmdi-mail-send"></i> Save');

        $form->onSuccess[] = [$this, 'formSucceeded'];
        return $form;
    }

    public function formSucceeded($form, $values)
    {
        $job = $this->jobsRepository->find($values['job_id']);

        $segment = explode('::', $values['segment_code']);

        $jobNewData = [
            'segment_provider' => $segment[0],
            'segment_code' => $segment[1],
        ];

        try {
            $this->jobsRepository->update($job, $jobNewData);
        } catch (\Exception $e) {
            ($this->onError)($job, $e->getMessage());
        }

        ($this->onSuccess)($job);
    }
}