<?php

declare(strict_types=1);

namespace Tests\SitemapPlugin\Controller;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

trait TearDownTrait
{
    public function tearDown(): void
    {
        if (null !== $this->client && null !== $this->client->getContainer()) {
            if (\method_exists($this->client->getContainer(), 'getMockedServices')) {
                foreach ($this->client->getContainer()->getMockedServices() as $id => $service) {
                    $this->client->getContainer()->unmock($id);
                }
            }
        }

        \Mockery::close();
        $this->client = null;
        $this->entityManager = null;
        $this->fixtureLoader = null;

        $it = new RecursiveDirectoryIterator($this->client->getParameter('sylius.sitemap.path'), FilesystemIterator::SKIP_DOTS);
        $it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach($it as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            }
            else {
                unlink($file->getPathname());
            }
        }
        rmdir($this->client->getParameter('sylius.sitemap.path'));
    }
}
