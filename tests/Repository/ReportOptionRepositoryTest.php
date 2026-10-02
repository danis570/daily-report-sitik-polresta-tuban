<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Repository;

use PHPUnit\Framework\TestCase;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\Domain\ReportOption;

class ReportOptionRepositoryTest extends TestCase
{
    private ReportOptionRepository $reportOptionRepository;

    private \PDO $pdo;

protected function setUp(): void
{
    Database::clearConnection();

    $this->pdo = Database::getConnection('dev');

    $this->reportOptionRepository = new ReportOptionRepository($this->pdo);

    // 🔥 Hapus anak dulu baru parent
    $this->truncateAllRelatedTables();
}

private function truncateAllRelatedTables(): void
{
    $this->pdo->exec('SET FOREIGN_KEY_CHECKS=0');

    $this->pdo->exec('TRUNCATE TABLE report_items');
    $this->pdo->exec('TRUNCATE TABLE reports');
    $this->pdo->exec('TRUNCATE TABLE report_options');

    $this->pdo->exec('SET FOREIGN_KEY_CHECKS=1');
}

    public function testSaveSuccess(): void
    {
        $reportOption = new ReportOption();

        $reportOption->category = 'activity';
        $reportOption->name = 'Apel pagi';
        $reportOption->description = 'Kegiatan apel pagi';

        $result = $this->reportOptionRepository->save($reportOption);

        self::assertNotNull($result->id);
        self::assertEquals('activity', $result->category);
        self::assertEquals('Apel pagi', $result->name);
        self::assertEquals(
            'Kegiatan apel pagi',
            $result->description
        );
    }

    public function testFindById(): void
    {
        $reportOption = new ReportOption();

        $reportOption->category = 'activity';
        $reportOption->name = 'Apel pagi';
        $reportOption->description = 'Kegiatan apel pagi';

        $saved = $this->reportOptionRepository->save($reportOption);

        $found = $this->reportOptionRepository->findById($saved->id);

        self::assertNotNull($found);
        self::assertEquals($saved->id, $found->id);
        self::assertEquals(
            $saved->category,
            $found->category
        );
        self::assertEquals(
            $saved->name,
            $found->name
        );
        self::assertEquals(
            $saved->description,
            $found->description
        );
    }

    public function testFindByIdNotFound(): void
    {
        $result = $this->reportOptionRepository->findById(999999);

        self::assertNull($result);
    }

    public function testFindByCategory(): void
    {
        $option1 = new ReportOption();
        $option1->category = 'activity';
        $option1->name = 'Apel pagi';

        $option2 = new ReportOption();
        $option2->category = 'activity';
        $option2->name = 'Vidcon';

        $option3 = new ReportOption();
        $option3->category = 'location';
        $option3->name = 'Lapangan Polres Tuban';

        $this->reportOptionRepository->save($option1);
        $this->reportOptionRepository->save($option2);
        $this->reportOptionRepository->save($option3);

        $results = $this->reportOptionRepository
            ->findByCategory('activity');

        self::assertCount(2, $results);

        self::assertEquals('Apel pagi', $results[0]->name);
        self::assertEquals('Vidcon', $results[1]->name);
    }

    public function testFindByCategoryWhenNotFound(): void
    {
        $results = $this->reportOptionRepository
            ->findByCategory('activity');

        self::assertIsArray($results);
        self::assertCount(0, $results);
    }

    public function testFindByCategoryAndName(): void
    {
        $reportOption = new ReportOption();

        $reportOption->category = 'activity';
        $reportOption->name = 'Apel pagi';
        $reportOption->description = 'Kegiatan apel pagi';

        $saved = $this->reportOptionRepository->save($reportOption);

        $found = $this->reportOptionRepository
            ->findByCategoryAndName(
                $saved->category,
                $saved->name
            );

        self::assertNotNull($found);
        self::assertEquals($saved->id, $found->id);
        self::assertEquals(
            $saved->category,
            $found->category
        );
        self::assertEquals(
            $saved->name,
            $found->name
        );
    }

    public function testFindByCategoryAndNameNotFound(): void
    {
        $result = $this->reportOptionRepository
            ->findByCategoryAndName(
                'activity',
                'Apel pagi'
            );

        self::assertNull($result);
    }

    public function testFindAll(): void
    {
        $option1 = new ReportOption();
        $option1->category = 'activity';
        $option1->name = 'Apel pagi';

        $option2 = new ReportOption();
        $option2->category = 'activity';
        $option2->name = 'Vidcon';

        $option3 = new ReportOption();
        $option3->category = 'location';
        $option3->name = 'Lapangan Polres Tuban';

        $this->reportOptionRepository->save($option1);
        $this->reportOptionRepository->save($option2);
        $this->reportOptionRepository->save($option3);

        $results = $this->reportOptionRepository->findAll();

        self::assertCount(3, $results);

        self::assertEquals('activity', $results[0]->category);
        self::assertEquals('Apel pagi', $results[0]->name);

        self::assertEquals('activity', $results[1]->category);
        self::assertEquals('Vidcon', $results[1]->name);

        self::assertEquals('location', $results[2]->category);
        self::assertEquals(
            'Lapangan Polres Tuban',
            $results[2]->name
        );
    }

    public function testFindAllWhenEmpty(): void
    {
        $results = $this->reportOptionRepository->findAll();

        self::assertIsArray($results);
        self::assertCount(0, $results);
    }

    public function testCountAll(): void
    {
        $option1 = new ReportOption();
        $option1->category = 'activity';
        $option1->name = 'Apel pagi';

        $option2 = new ReportOption();
        $option2->category = 'activity';
        $option2->name = 'Vidcon';

        $this->reportOptionRepository->save($option1);
        $this->reportOptionRepository->save($option2);

        $result = $this->reportOptionRepository->countAll();

        self::assertEquals(2, $result);
    }

    public function testUpdate(): void
    {
        $reportOption = new ReportOption();

        $reportOption->category = 'activity';
        $reportOption->name = 'Apel pagi';
        $reportOption->description = 'Kegiatan apel pagi';

        $saved = $this->reportOptionRepository->save($reportOption);

        $saved->category = 'activity';
        $saved->name = 'Apel Fungsi TIK';
        $saved->description = 'Kegiatan apel fungsi TIK';

        $result = $this->reportOptionRepository->update($saved);

        self::assertTrue($result);

        $found = $this->reportOptionRepository
            ->findById($saved->id);

        self::assertNotNull($found);
        self::assertEquals(
            'Apel Fungsi TIK',
            $found->name
        );
        self::assertEquals(
            'Kegiatan apel fungsi TIK',
            $found->description
        );
    }

    public function testDeleteById(): void
    {
        $reportOption = new ReportOption();

        $reportOption->category = 'activity';
        $reportOption->name = 'Apel pagi';

        $saved = $this->reportOptionRepository->save($reportOption);

        $result = $this->reportOptionRepository
            ->deleteById($saved->id);

        self::assertTrue($result);

        $found = $this->reportOptionRepository
            ->findById($saved->id);

        self::assertNull($found);
    }

    public function testDeleteByIdNotFound(): void
    {
        $result = $this->reportOptionRepository
            ->deleteById(999999);

        self::assertFalse($result);
    }

    public function testDeleteAll(): void
    {
        $option1 = new ReportOption();
        $option1->category = 'activity';
        $option1->name = 'Apel pagi';

        $option2 = new ReportOption();
        $option2->category = 'location';
        $option2->name = 'Lapangan Polres Tuban';

        $this->reportOptionRepository->save($option1);
        $this->reportOptionRepository->save($option2);

        self::assertEquals(
            2,
            $this->reportOptionRepository->countAll()
        );

        $this->reportOptionRepository->deleteAll();

        self::assertEquals(
            0,
            $this->reportOptionRepository->countAll()
        );
    }
}