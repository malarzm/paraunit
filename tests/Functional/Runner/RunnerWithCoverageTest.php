<?php
declare(strict_types=1);

namespace Tests\Functional\Runner;

use Paraunit\Configuration\CoverageConfiguration;
use Paraunit\Runner\Runner;
use Tests\BaseIntegrationTestCase;

/**
 * Class RunnerWithCoverageTest
 * @package Tests\Functional\Runner
 * @small
 */
class RunnerWithCoverageTest extends BaseIntegrationTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->configuration = new CoverageConfiguration();
    }

    public function testAllGreen()
    {
        $this->setTextFilter('ThreeGreenTestStub.php');
        $this->loadContainer();

        /** @var Runner $runner */
        $runner = $this->container->get('paraunit.runner.runner');

        $this->assertEquals(0, $runner->run());
        $this->assertOutputOrder($this->getConsoleOutput(), [
            'PARAUNIT',
            'Coverage driver in use',
            '...',
        ]);
    }
}
