<?php

namespace App\Tests\Classe;

use App\Classe\Search;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase {
    public function testDefaultValues() {
        $search = new Search();

        $this->assertEquals('', $search->string);
        $this->assertEmpty($search->categories);
    }

    public function testSettingValues() {
        $search = new Search();

        $search->string = 'testString';
        $search->categories = [new Category(), new Category()];

        $this->assertEquals('testString', $search->string);
        $this->assertCount(2, $search->categories);
    }
}
