<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

use Illuminate\Mail\Mailable;

class MailMakeTest extends ATestCase
{
    protected string $myCommand = 'make:mail';
    protected string $myComponent = 'Mail';

    public function provider(): array
    {
        return [
            ['MyMail', null, null, [], []],
            ['MyMarkdownMail', null, ['--markdown' => 'mail'], [], []],

            ['MyMail', self::MODULE, null, [], []],
            ['MyMarkdownMail', self::MODULE, ['--markdown' => 'mail'], [], []],

            ['MyMail', self::SAMPLE_MODULE, null, [], []],
            ['MyMarkdownMail', self::SAMPLE_MODULE, ['--markdown' => 'mail'], [], []],
        ];
    }

    protected function assertions(string $name, ?string $module, array $myFile, array $myClass): void
    {
        $args = $this->myArgs;
        parent::assertions($name, $module, $myFile, $myClass);

        $class = $this->getMyClass($name, $module);
        $this->assertTrue(is_subclass_of($class, Mailable::class));
        $this->assertIsArray($args);

        if (isset($args['--markdown'])) {
            ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

            $path = self::SANDBOX . '/';
            if ($module) {
                $path .= $meta['path'] . '/' . $module . '/' . $meta['assets'] . '/' . $meta['views'] . '/';
            } else {
                $path .= 'resources/views/';
            }
            $path .= $args['--markdown'] . '.blade.php';

            $this->assertFileExists($path);
        }
    }
}
