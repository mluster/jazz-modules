<?php
 declare(strict_types=1);

 namespace JazzTest\Modules;

 use Illuminate\Support\Str;

 class BasicTest extends ATestCase
 {
     public function testRun(): void
     {
         $txt = 'sampleString';
         $tmp = Str::kebab($txt);

         $tmp = Str::slug($txt);

         $tmp = Str::snake($txt);
         $this->assertTrue(true);
     }
 }
