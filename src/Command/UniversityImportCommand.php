<?php

namespace App\Command;

use GuzzleHttp\Client;
use Pimcore\Console\AbstractCommand;
use Pimcore\Model\DataObject\Data\BlockElement;
use Pimcore\Model\DataObject\University;
use Pimcore\Model\DataObject\Service;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UniversityImportCommand extends AbstractCommand

{

    public function __construct(protected Client $client)
    {
        parent::__construct();
    }

    protected function configure()
    {

        $this
            ->setName('app:university:import')
            ->setDescription('Import universities from an api');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $response = $this->client->get('http://universities.hipolabs.com/search?country=United+Kingdom');
        $universities = json_decode($response->getBody()->getContents(), true);
        $parent = Service::createFolderByPath('universities');
        foreach ($universities as $university) {
            $universityObject = University::getByName($university['name'], 1);
            if (!$universityObject instanceof University) {
                $universityObject = new University();
                $universityObject->setName($university['name']);
                $universityObject->setParent($parent);
                $universityObject->setKey(Service::getValidKey($university['name'], 'object'));
                $universityObject->setPublished(true);
            }
            $universityObject->setCountry($university['alpha_two_code']);
            $data = [];
            foreach ($university['web_pages'] as $value) {
                $data[] = ['url' => new BlockElement('url', 'input', $value)];
            }
            $universityObject->setUrl($data);
            $universityObject->save();
        }

        return 0;
    }
}
